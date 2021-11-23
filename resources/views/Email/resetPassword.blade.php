@component('mail::message')
# Reset password

Do you want to reset password? 
If so click button.

@component('mail::button', ['url' => 'https://competent-roentgen-c0be06.netlify.app/forgotpassword/'.$token])
Reset password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
