@component('mail::message')
# Welcome {{$user->surname}} {{$user->firstname}} {{$user->othernames}} to iCMS Career Portal.

Your account was successfully created. To explore and find out all the amazing  things you can do with this portal take the following steps:

How to get started
1. Visit: https://crossandchurchillgroup.com
2. Sign in using the credentials below.
3. Hover over the top right menu with your name on it, then click on the profile menu to change your password.
4. Carry out your transaction as need be.
5. Do not forget to SIGN OUT before leaving your system.

@component('mail::panel')
Find below your credentials to access the application:<br>
Email address: **{{$user->email}}** <br>
Default Password: **Your Chosen Password**
@endcomponent

Thank you and do let us know if we can do more to please you!

@component('mail::button', ['url' => 'https://crossandchurchillgroup.com'])
GET STARTED
@endcomponent

Kind regards,<br>
Support Team.<br>
{{ config('app.name') }}
@endcomponent
