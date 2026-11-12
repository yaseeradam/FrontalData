@component('mail::message')
# Transaction Successful

Dear {{ $user->first_name . ' '. $user->last_name }},

We are pleased to inform you that your recent transaction of **₦{{ number_format($amount, 2) }}** has been successfully processed. The recipient is expected to receive the {{ $service }} purchased shortly.

### Transaction Summary:
- **Service:** {{ $service }}
- **Transaction Amount:** ₦{{ number_format($amount, 2) }}
- **Transaction Date:** {{ \Carbon\Carbon::now()->toFormattedDateString() }}
- **Session ID:** {{ $transaction->session_id }}
- **Updated Balance:** ₦{{ number_format($newBalance, 2) }}
@if(!empty($token))
- **Token:** {{ $token }}
@endif

You can view the full transaction details or track the status directly in your account.

If you have any questions or need further assistance, please don't hesitate to reach out to our dedicated support team via any of the channels below:

- **Email:** [{{ jannah_opt('support_email') }}](mailto:{{ jannah_opt('support_email') }})
- **Phone:** {{ jannah_opt('support_phone') }}
- **WhatsApp Support:** {{ jannah_opt('support_whatsapp') }}

Thank you for trusting **@jannah_opt('site_name')** with your transaction needs. We’re here to provide you with a reliable and secure experience every step of the way.

Warm Regards,  
**@jannah_opt('site_name') Team**
@endcomponent
