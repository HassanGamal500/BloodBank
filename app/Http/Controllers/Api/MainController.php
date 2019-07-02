<?php

namespace App\Http\Controllers\Api;

use App\Model\BloodType;
use App\Model\Category;
use App\Model\City;
use App\Model\Client;
use App\Model\Contact;
use App\Model\Governorate;
use App\Model\Notification;
use App\Model\Order;
use App\Model\Post;
use App\Model\Setting;
use App\Model\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
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
	public function governorates() {

		$governorates = Governorate::all();

		return responseJson(1, 'success', $governorates);

	}

	public function cities(Request $request) {

		$cities = City::where(function ($query) use ($request){
		    if ($request->has('governorate_id')) {
		        $query->where('governorate_id', $request->governorate_id);
            }
        })->get();

		return responseJson(1, 'success', $cities);

	}

    public function posts() {

        $posts = Post::with('categories')->paginate(10);

        return responseJson(1, 'success', $posts);

    }

    public function postsByCategory($id) {

        $posts = Post::with('categories')->where('category_id', $id)->paginate(10);

        return responseJson(1, 'success', $posts);

    }

    public function settings() {

        $settings = Setting::all();

        return responseJson(1, 'success', $settings);

    }

    public function contacts(Request $request) {

        $validator = validator()->make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, 'validator error', $validator->errors());
        }

        $contacts = Contact::create($request->all());
        $contacts->save();
        return responseJson(1, 'success', $contacts);

    }

    public function categories() {

        $categories = Category::all();

        return responseJson(1, 'success', $categories);

    }

    public function bloodTypes() {

        $bloodTypes = BloodType::all();

        return responseJson(1, 'success', $bloodTypes);

    }

    public function notifications() {

	    $notifications = Notification::all();

	    return responseJson(1, 'success', $notifications);

    }

    public function makeFavorite(Request $request) {

        $validator= validator()->make($request->all(),[
            'client_id'=>'required',
            'post_id'=>'required|exists:posts,id'
        ]);

        if ($validator->fails()) {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }

        $client_post = Client::where('id',$request->input('client_id'))->first();

        if ($client_post) {

            $post=$request->input('post_id');
            $client_post->posts()->toggle($post);
            return responseJson(1,'success',$client_post);

        } else {

            return responseJson(0,'fail');

        }

    }

    public function favorites(Request $request) {

        $favorites = $request->user()->posts()->with('categories')->latest()->paginate(20);
        return responseJson(1, 'success', $favorites);

    }

    public function allOrder() {

	    $orders = Order::all();

	    return responseJson(1, 'success', $orders);

    }

    public function createOrder(Request $request) {

        $validator =validator()->make($request->all(),[
            'patient_name' => 'required',
            'age' => 'required|numeric',
            'phone' => 'required|digits:11',
            'blood_type_id' => 'required|exists:blood_types,id',
            'number_of_bags' => 'required|numeric',
            'city_id' => 'required|exists:cities,id',
        ]);

        if ($validator->fails()){
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $orderRequest = $request->user()->requests()->create($request->all());

        $clientIds = $orderRequest->city->governorate->clients()
            ->whereHas('bloodTypes', function ($query) use ($request, $orderRequest){
                $query->where('blood_types.id', $orderRequest->blood_type_id);
            })->pluck('clients.id')->toArray();
        //dd($clientIds);
        $send = "";

        if (count($clientIds)) {

            $notification = $orderRequest->notifications()->create([
                'title' => 'احلى مسا عليك يا اشرف  ',
                'content' => $orderRequest->blood_type . 'محتاج متبرع لفصيلة ',
            ]);

            $notification->clients()->attach($clientIds);

//            $tokens = $request->ids;
//            $title = $request->title;
//            $body = $request->body;
//            $data = Order::first();
//            $send = notifyByFirebase($title, $body, $tokens, $data, true);
//            info("firebase result: " . $send);

            $tokens = Token::whereIn('client_id', $clientIds)->where('token', '!=', null)->pluck('token')->toArray();

            if (count($tokens)) {

    //                $audience = ['include_players_id' => $tokens];
    //                $content = [
    //                    'ar' => 'يوجد اشعار من ل' . $request->user()->name(),
    //                    'en' => 'You have a New Notification' . $request->user()->name()
    //                ];

                $title = $notification->title;
                $content = $notification->content;
                $data = [
//                    'action' => 'new notification',
//                    'data' => null,
//                    'client' => 'client',
//                    'title' => $notification->title,
//                    'content' => $notification->conntent,
//                    'order_id' => $orderRequest->id

                    'order_request_id' => $orderRequest->id

                ];

//                info(json_encode($data));

                $send = notifyByFirebase($title, $content, $tokens, $data);
//                info($send);
                info("firebase result" . $send);
//                $send = json_decode($send);

            }

        }

        return responseJson(1, 'تم الاضافه بنجاح', compact('orderRequest'));

    }

}
