<x-app-layout :user="$user">@php $primaryColor = jannah_opt('primary_color'); $color = ltrim($primaryColor, '#'); @endphp

<div class="col-span-3">
    <form id="buy-epin" method="POST" class="z-[99]">
        @csrf
        <x-app.back-header title="Exam PIN (EPIN)" />
        <input id="service_type" value="EPIN" hidden />
        <input name="network" id="network" value="{{ $serviceF }}" hidden />
        <input name="pin" id="pin" hidden /><div class="flex justify-between gap-x-2" id="network-icons">
        @foreach($services as $service)
            <div class="p-2 rounded-2xl transition-all hover:scale-110" data-service="{{ $service }}">
                <img class="rounded-full size-10 cursor-pointer" src="@basecdn('images/' . $service . '.png')" data-service="{{ $service }}">
            </div>
        @endforeach
    </div>
    
    <div>
        <x-input.default id="number" name="number" type="number" label="Total No" required/>
    </div>
    <div>
        <x-input.default id="amount" name="amount" type="number" label="Amount" required disabled/>
    </div>

    <div class="grid grid-cols-3 gap-x-8 md:grid-cols-4 lg:grid-cols-6 md:gap-x-3 gap-y-4 mt-6 text-sm products">
    </div>

    <x-btn.default id="buy-epin-bb" type="button" onclick="toggleCanvas(event)">
        Continue
    </x-btn.default>

    <button class="hidden" id="pay-now"></button>
</form>

</div><script>
    submitForm('buy-epin', 'pay-now', '{{ route('core.purchase.epin') }}');

    document.addEventListener('DOMContentLoaded', function() {
        const products = @json($products);
        const productContainer = document.querySelector('.products');
        const iconContainers = document.querySelectorAll('#network-icons > div');
        const networkImages = document.querySelectorAll('#network-icons img[data-service]');
        const numberInput = document.getElementById('number');
        const amountInput = document.getElementById('amount');
        let selectedNetwork = networkImages[0].getAttribute('data-service');
        let selectedPrice = 0;

        function updateProductGrid(network) {
            productContainer.innerHTML = '';
            (products[network] || []).forEach(product => {
                const productElement = document.createElement('div');
                productElement.className = 'transition-all hover:scale-110 grid grid-rows-2 flex justify-center items-center p-2 bg-gray-100 rounded-2xl shadow-sm';
                productElement.innerHTML = `<div class="font-bold text-primary text-center"><span class="text-2xs">₦ </span>${product.rounded_price}</div><div class="text-xs text-gray-400 text-nowrap">Pay ₦${product.role_price}</div>`;
                productElement.addEventListener('click', () => {
                    selectedPrice = product.rounded_price;
                    updateTotalAmount();
                });
                productContainer.appendChild(productElement);
            });
        }

        function updateTotalAmount() {
            const totalPins = parseInt(numberInput.value) || 0;
            amountInput.value = totalPins * selectedPrice;
        }

        numberInput.addEventListener('input', updateTotalAmount);

        updateProductGrid(selectedNetwork);
        iconContainers[0].classList.add('border', 'border-[1px]', 'border-primary');

        networkImages.forEach(image => {
            image.addEventListener('click', function() {
                selectedNetwork = this.getAttribute('data-service');
                updateProductGrid(selectedNetwork);
                document.getElementById('network').value = selectedNetwork;
                iconContainers.forEach(container => {
                    container.classList.remove('border', 'border-[1px]', 'border-primary');
                });
                this.parentElement.classList.add('border', 'border-[1px]', 'border-primary');
            });
        });
    });
</script></x-app-layout>