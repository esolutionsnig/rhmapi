@component('mail::message')
# Dear {{$applicant->surname}} {{$applicant->firstname}} {{$applicant->othernames}},

{{$ai->interview_message}}

Instructions:
{{$ai->interview_instructions}}

@component('mail::panel')
Date: **{{$ai->interview_date}}**<br>
Time: **{{$ai->interview_time}}**<br>
Venue: **{{$ai->interview_venue}}**
@endcomponent

Call this number: +234 (0) 815 514 0367 to confirm availability.

Kind regards,<br>
Human Resource Manager.<br>
{{ config('app.name') }}
@endcomponent
