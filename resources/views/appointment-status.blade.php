@component('mail::message')
# Appointment Status: {{$status}}

Hello {{$appointment->patient->name}},


@if ($status == 'For Approval')
    Your appointment request with {{nova_get_setting('doctor_name', 'Dr. Joyce Litan Iribani')}} at Joyce Dental Spa Clinic is currently FOR APPROVAL.
@endif

@if ($status == 'Approved')
We are happy to inform you that your appointment with  {{nova_get_setting('doctor_name', 'Dr. Joyce Litan Iribani')}}  has been APPROVED.
@endif

@if ($status == 'Finished')
Thank you for visiting Joyce Dental Spa Clinic for your appointment with {{nova_get_setting('doctor_name', 'Dr. Joyce Litan Iribani')}}. We hope your experience was pleasant and met your expectations.

If you have any follow-up questions or need further assistance, please don’t hesitate to visit our website or reach out to us at {{nova_get_setting('contact', '0917-505-3601')}}. 
@endif

@if ($status == 'Rejected')
We regret to inform you that your appointment request with {{nova_get_setting('doctor_name', 'Dr. Joyce Litan Iribani')}}  has been REJECTED due to scheduling conflicts.

We apologize for any inconvenience this may cause. Please feel free to submit a new request for a different date or time, contact us at {{nova_get_setting('contact', '0917-505-3601')}} for assistance in selecting a new appointment time.
@endif

@if($status == 'Cancelled')
We regret to inform you that your appointment with {{nova_get_setting('doctor_name', 'Dr. Joyce Litan Iribani')}} has been CANCELED by the clinic due to unforeseen circumstances.

We sincerely apologize for the inconvenience. Please contact us at {{nova_get_setting('contact', '0917-505-3601')}} or visit our website to reschedule at your earliest convenience.
@endif

Appointment Details:
- Date: {{$appointment->date}}
- Time: {{$appointment->slot}}

@if ($status == 'For Approval')
We will review your request and notify you once your appointment is confirmed or if further adjustments are needed. If you have any questions, feel free to contact us at {{nova_get_setting('contact', '0917-505-3601')}} or visit our website.
@endif

@if($status == 'Approved')
Please arrive 10-15 minutes early to allow time for other important steps needed for check-in. Our system is fully digital, so no paperwork is required upon arrival.

If you need to make any changes or reschedule, kindly visit our website or contact us at {{nova_get_setting('contact', '0917-505-3601')}}.
@endif

@if ($status == 'Finished')
Your oral health is important to us, and we’re here to help with any future needs.

We’d love to see you again! Schedule your next appointment anytime through our online portal for your convenience.
@endif

Please note: This is an automated email, and replies to this message are not monitored.


Thank you!! <br>
{{ config('app.name') }}
@endcomponent
