@component('mail::message')
# Electricity Token Delivery

Dear {{ $user->first_name . ' '. $user->last_name }},

Thank you for your recent purchase of electricity service. Your transaction has been successfully processed, and your token is provided below.

### Transaction Summary:
- **Service:** {{ $service }}
- **Transaction Amount:** ₦{{ number_format($amount, 2) }}
- **Transaction Date:** {{ \Carbon\Carbon::now()->toFormattedDateString() }}
- **Session ID:** {{ $transaction->session_id }}

### Your Token:
{{ $token }}

Please enter the token into your electricity meter to activate the purchased units. Should you encounter any issues with the token or require further assistance, feel free to contact our support team.

**Contact Support:**
- **Email:** [{{ jannah_opt('support_email') }}](mailto:{{ jannah_opt('support_email') }})
- **Phone:** {{ jannah_opt('support_phone') }}
- **WhatsApp Support:** {{ jannah_opt('support_whatsapp') }}

Thank you for trusting **@jannah_opt('site_name')** for your electricity needs. We are committed to providing you with reliable and efficient service.

Warm Regards,  
**@jannah_opt('site_name') Team**
@endcomponent
