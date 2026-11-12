@props(['name', 'amt', 'tp' => false, 'status' => null])

@php
    $colorClass = match($status) {
        'success' => 'text-green-500',
        'pending', 'processing' => 'text-yellow-500',
        'failed' => 'text-red-500',
        default => 'text-gray-800'
    };
@endphp

<li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border {{ $colorClass }} -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
  <div class="flex items-center justify-between w-full">
    <span>{{ $name }}</span>
    <span>
      @php
        echo $tp == true ? $amt : '₦' . number_format($amt);
      @endphp
    </span>
  </div>
</li>