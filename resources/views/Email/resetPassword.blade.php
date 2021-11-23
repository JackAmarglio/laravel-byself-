@component('mail::message')
# Reset password

Do you want to reset password? 
If so click button.

@component('mail::button', ['url' => 'https://hungry-kilby-3e4c68.netlify.app/forgotpassword/'.$token])
Reset password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
