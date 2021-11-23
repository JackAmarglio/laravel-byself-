@component('mail::message')
# Introduction

Thanks for registering my homepage.

@component('mail::button', ['url' =>'https://hungry-kilby-3e4c68.netlify.app/verify/'.$token])
Verify
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@component('mail::panel')
This is the panel content.
@endcomponent
@endcomponent