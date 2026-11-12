<x-app-layout :drk="true" title="Transfer Funds" :user="$user">
    @php
        $primaryColor = jannah_opt('primary_color');
        $color = ltrim($primaryColor, '#');
        $prices = [100, 200, 500, 1000, 2000, 5000, 10000, 20000, 50000];
    @endphp

    <x-app.frame2 title="Transfer Funds" des="Easily send money to friends and customers no hassle">
        
            <form id="transfer-form" class="px-4" method="POST">
                @csrf
                <input id="service_type" value="Send Money" hidden="" />
                <input name="network" id="network" value="transfer" hidden="" />
                <input name="pin" id="pin"  hidden />
                <div class="space-y-4 mt-4">
                    <div>
                        <x-label.main>Enter User Tag/Username</x-label.main>
                        <x-input.default id="number" name="number" required placeholder="E.g Tony" />
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-4 products">
                        @foreach($prices as $price)
                            <div class="bg-white rounded-lg text-center cursor-pointer border border-transparent p-2" 
                                 onclick="setAmount({{ $price }}, this)">
                                <p class="text-sm font-semibold"><span class="text-2xs">₦ </span>{{ number_format($price) }}</p>
                            </div>
                        @endforeach
                    </div>
                        <x-btn.default class="mt-8" id="not-lb">Send Now</x-btn.default>
                    <div class="hidden">
                        <x-input.default id="amount" name="amount" type="amount" />
                    </div>
                </div>
                
                <button class="hidden" id="pay-now"></button>
            </form>
    </x-app.frame2>

    <script>
        document.getElementById('not-lb').addEventListener('click', function(event) {
            event.preventDefault();
            toggleCanvas(event);
        });
        function setAmount(amount, element) {
            document.getElementById('amount').value = amount;
            
            document.querySelectorAll('.products > div').forEach(div => div.classList.remove('border-[#{{ $color }}]'));

            element.classList.add('border-[#{{ $color }}]');
        }

        submitForm('transfer-form', 'pay-now', '{{ route('core.transfer.init') }}');
    </script>
</x-app-layout>
