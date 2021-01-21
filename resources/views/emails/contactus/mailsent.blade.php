@component('mail::message')
# Dear Support,

A client has filled out the contact us form from your website. Find below the details of the form filled for your information:

@component('mail::panel')
Name: **{{$contactus->name}}** <br>
Phone Number: **{{$contactus->phone_number}}** <br>
Email Address: **{{$contactus->email}}** <br>
Address: **{{$contactus->address}}** <br>
Subject: **{{$contactus->subject}}** <br>
Message: **{{$contactus->message}}**
@endcomponent

Thank you for responding to this client in shortest time possible.

Kind regards,<br>
Software Development Team.<br>
Cross & Churchill Estates Ltd.
@endcomponent
