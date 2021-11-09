@component('mail::message')
# Reset password

Do you want to reset password? 
If so click button.

@component('mail::button', ['url' => 'https://wizardly-easley-48f918.netlify.app/resetpassword/'.$token])
Reset password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
