@component('mail::message')
# Transaction Unsuccessful

Dear {{ $user->first_name . ' '. $user->last_name }},

We regret to inform you that your recent transaction of **₦{{ number_format($amount, 2) }}** could not be completed successfully. We understand how important this service is to you, and our team is committed to assisting in any way possible to resolve this issue.

### Transaction Summary:
- **Service:** {{ $service }}
- **Transaction Amount:** ₦{{ number_format($amount, 2) }}
- **Transaction Date:** {{ \Carbon\Carbon::now()->toFormattedDateString() }}
- **Session ID:** {{ $transaction->session_id }}

@if($isAdmin)
### Technical Error Details:
{{ $response_data['error'] ?? 'Insufficient API funds. Please review API logs for further details.' }}
@endif

We encourage you to try again, and if you continue to experience issues, our dedicated support team is here to help. Please feel free to reach out through any of the following contact options:

- **Email Support:** [{{ jannah_opt('support_email') }}](mailto:{{ jannah_opt('support_email') }})
- **Customer Service Hotline:** {{ jannah_opt('support_phone') }}
- **WhatsApp Support:** {{ jannah_opt('support_whatsapp') }}

Our team is available to assist you with any questions or concerns and will ensure you receive the help you need to complete your transaction. Once again, thank you for choosing **@jannah_opt('site_name')**. We value your trust and look forward to providing you with a seamless experience in the future.

Warm Regards,  
**@jannah_opt('site_name') Team**
@endcomponent
