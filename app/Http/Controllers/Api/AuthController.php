<?php

namespace App\Http\Controllers\Api;

use App\Mail\ResetPassword;
use App\Model\Client;
use App\Model\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    //
/*
    private function apiResponse($status, $message, $data=null) {

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response);

    }
*/
    public function register(Request $request)
    {

        $validator = validator()->make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:clients',
            'birth_of_date' => 'required',
            'phone' => 'required',
            'password' => 'required|confirmed',
            'blood_type_id' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, 'validator error', $validator->errors());
        }

        $request->merge(['password' => bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client->api_token = str_random(60);
        $client->save();
        return responseJson(1, 'success', [
            'api_token' => $client->api_token,
            'client' => $client
        ]);

    }

    public function login(Request $request)
    {

        $validator = validator()->make($request->all(),[
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, 'validator error', $validator->errors());
        }

        $client = Client::where('phone', $request->phone)->first();
        if ($client) {

            if (Hash::check($request->password, $client->password)) {

                return responseJson(1, 'your phone and password are correct', [
                    'api_token' => $client->api_token,
                    'client' => $client
                ]);

            } else {

                return responseJson(0, 'your phone and password are not correct, Try Again');

            }

        } else {

            return responseJson(0, 'your phone and password are not correct');

        }

    }

    public function notificationSettings(Request $request) {

        $validator = validator()->make($request->all(),[
            'governorates.*' => 'exists:governorates,id',
            'bloodtypes.*' => 'exists:blood_types,id',
        ]);
        if($validator->fails())
        {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        if($request->has('governorates'))
        {
            $request->user()->governorates()->sync($request->governorates);
        }
        if($request->has('bloodtypes'))
        {
            $request->user()->bloodTypes()->sync($request->bloodtypes);
        }

        $data = [
            'governorates' => $request->user()->governorates()->pluck('governorates.id')->toArray(),
            'bloodtypes' => $request->user()->bloodTypes()->pluck('blood_types.id')->toArray(),
        ];
        return responseJson(1, 'تم التحديث',$data);

    }

    public function profile(Request $request, $id) {

        $validator= validator()->make($request->all(),[

            'name'=>'required',
            'email'=>'required|unique:clients',
            'birth_of_date'=>'required|date',
            'phone'=>'required|unique:clients',
            'password'=>'required',
            'blood_type_id'=>'required',
            'city_id'=>'required',

        ]);

        if ($validator->fails()){
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }

        $client=Client::find($id);
        if ($client){

            $request->merge(['password'=>bcrypt($request->password)]);
            $client->update($request->all());
            $client->api_token = str_random(60);
            $client->save();
            return responseJson('1','Update Successful',['api_token'=>$client->api_token,'client'=>$client]);

        }else{

            return responseJson(0,'update failed','fail');

        }

    }

    public function newPassword(Request $request) {

        $validator = validator()->make($request->all(),[
            'phone' => 'required',
            'password' => 'required|confirmed',
            'pin_code' => 'required'
        ]);

        if ($validator->fails()) {
            $data = $validator->errors();
            return responseJson(0, $validator->errors()->first(), $data);
        }

        $user = Client::where('pin_code', $request->pin_code)
            ->where('pin_code', '!=', 0)
            ->where('phone', $request->phone)
            ->first();

        if ($user) {

            $user->password = bcrypt($request->password);
            $user->pin_code = null;

            if ($user->save()) {

                return responseJson(1, 'تم تغيير كلمه المرور بنجاح');

            } else {

                return responseJson(0, 'حدث خطأ ، حاول مره اخرى');

            }

        } else {

            return responseJson(0, 'هذا الكود غير صالح');

        }

    }

    public function resetPassword(Request $request) {

        $validator = validator()->make($request->all(),[
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, 'validator error', $validator->errors());
        }

        $user = Client::where('phone', $request->phone)->first();
        if ($user) {

            $code = rand(1111, 9999);

            $update = $user->update(['pin_code' => $code]);

            if ($update) {

                // send SMS

                smsMisr($request->phone, "Your Reset Code Is : ".$code);

                // send Email

               /* Mail::to($user->email)
                    ->bcc('hassan.alex26@gmail.com')
                    ->send(new ResetPassword($user));*/

                return responseJson(1, 'برجاء فحص هاتفك', [
                    'pin_code_for_test' => $code,
                    'mail_fails' => Mail::failures(),
                    'email' => $user->email
                ]);

            } else {

                return responseJson(0, 'حدث خطأ، حاول مره اخرى ');

            }

        } else {

            return responseJson(0, 'لا يوجد اى حساب مرتبط بهذا الهاتف');

        }

    }


    public function registerToken(Request $request) {

        $validator = validator()->make($request->all(),[
            'token' => 'required',
            'platform' => 'required|in:android,ios'
            //'api_token' => 'required'
        ]);

        if ($validator->fails()){
            $data = $validator->errors();
            return responseJson(0, $validator->errors()->first(), $data);
        }

        Token::where('token', $request->token)->delete();

        $request->user()->tokens()->create($request->all());

        return responseJson('1', 'تم التسجيل بنجاح');

    }

    public function removeToken(Request $request) {

        $validator = validator()->make($request->all(),[
            'token' => 'required',
        ]);

        if ($validator->fails()){
            $data = $validator->errors();
            return responseJson(0, $validator->errors()->first(), $data);
        }

        Token::where('token', $request->token)->delete();

        return responseJson('1', 'تم الحذف بنجاح');

    }


}
