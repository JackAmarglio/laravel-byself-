@component('mail::message')
# Reset password

Do you want to reset password? 
If so click button.

@component('mail::button', ['url' => 'https://hopeful-chandrasekhar-517d3b.netlify.app/forgotpassword/'.$token])
Reset password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
