@component('mail::message')
# Introduction

Blood Bank Reset Password.

<p>Hello {{$user->name}}</p>

<p>Your Reset Code is : {{$user->pin_code}}</p>

@component('mail::button', ['url' => 'http://hassan.com', 'color' => 'success'])
    Reset
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
