@component('mail::message')
# Dear {{$user->surname}} {{$user->firstname}} {{$user->othernames}},

Please be informed that your expense profile was accessed at ** {{date('M j, Y H:i')}}**.

If you did not log on to your expense account at the time detailed above, please call our interractive contact center on ** +234 802 068 9069 **.

Thank you for choosing us.

Kind regards,<br>
Support Team.<br>
{{ config('app.name') }}
@endcomponent
