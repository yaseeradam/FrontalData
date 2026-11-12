@props(['transaction'])
@php
    $attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag;
    $sign = $transaction->type === 'debit' ? '-' : ($transaction->type === 'credit' ? '+' : '');
    $statusColor = $transaction->status == 'successful' ? 'text-green-500' : 'text-red-500';

    $billerLower = strtolower(trim($transaction->biller));
    $isBonus = in_array($billerLower, ['registeration bonus', 'registration bonus', 'signup bonus', 'bonus']);

    if ($isBonus) {
        $title = 'Registration Bonus';
        $iconFile = 'bonus.png';
    } elseif ($billerLower == 'received') {
        $title = 'Incoming Transfer';
        $iconFile = 'received.png';
    } elseif ($billerLower == 'transfer') {
        $title = 'Outgoing Transfer';
        $iconFile = 'transfer.png';
    } else {
        $pk = $transaction->quantity ?? $transaction->product;
        $title = strtoupper($transaction->biller) . ' ' . $pk;
        $iconFile = $billerLower . '.png';
    }
@endphp

<a href="{{ route('core.transaction', $transaction->session_id) }}" {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <img src="@basecdn('images/' . $iconFile)" onerror="this.onerror=null;this.src='{{ asset('images/bonus.png') }}';" alt="icon" class="mr-2 size-[50px] rounded-full object-cover">
    <div class="flex-grow min-w-0 pr-2">
        <span class="font-semibold text-gray-900 text-xs md:text-sm block truncate">{{ $title }}</span>
        <span class="text-2xs md:text-xs text-gray-500 block">@date($transaction->created_at)</span>
    </div>
    <div class="text-right shrink-0">
        <span class="block font-bold text-xs md:text-sm text-slate-900">{{ $sign }}₦@numberformat($transaction->amount)</span>
        <span class="{{ $statusColor }} text-2xs md:text-xs font-semibold">@ucwords($transaction->status)</span>
    </div>
</a>