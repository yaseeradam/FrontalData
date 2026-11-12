@component('mail::message')
# {{ $subject }}

{!! nl2br(e($content)) !!}

Thanks,<br>
@jannah_opt('site_name')
@endcomponent