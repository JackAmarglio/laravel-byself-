@component('mail::message')
# Introduction

Thanks for registering my homepage.

@component('mail::button', ['url' =>'https://wizardly-easley-48f918.netlify.app/verify/'.$token])
Verify
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@component('mail::panel')
This is the panel content.
@endcomponent
@endcomponent