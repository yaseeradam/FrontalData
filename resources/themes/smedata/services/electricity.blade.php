<x-app-layout title="Electricity" :user="$user">
@php
    $primaryColor = jannah_opt('primary_color');
    $color = ltrim($primaryColor, '#');
    
@endphp

        <div class="col-span-3">
            <x-app.back-header title="Pay for Electricity" />
            <form id="buy-electricity" method="POST" class="z-[99]">   
            @csrf
                <input id="service_type" value="Electricity" hidden="" />
                <input name="network" id="network" value="{{ $serviceF }}" hidden="" />
                <input name="pin" id="pin" hidden="" />
                
                <div class="flex justify-between items-center space-x-2 z-[99]  bg-white p-[10px] border border-lg rounded-xl">
                   <div class="text-sm text-gray-400 ">
                            Select Biller
                        </div>
                <div class="relative flex">
                    <div class="flex justify-between">
                        
                        <div>
                            <img id="network-image" src="@basecdn('img/'. $serviceF .'.png')" name="{{ $serviceF }}" data-service="{{ $serviceF }}" class="rounded-full w-9 cursor-pointer">
                        </div>
                    </div>
                
                <i id="select-image-icon" class="jannah-arrow-bottom text-xl cursor-pointer ml-2"></i>

                
                </div>
                </div>
                <div>
                    <x-input.default id="number" name="number" type="number" label="Meter No" />
                </div>
                <div id="ver" class="text-xs text-red-500">
                    
                </div> 
                <div>
                    <x-input.default id="amount" name="amount" type="number" label="Amount" />
                </div>
                <x-btn.default id="buy-tv-b" class="hidden"  type="button" onclick="toggleCanvas(event)">
                Continue
                </x-btn.default>
                <x-btn.default id="verify-iuc" type="button">
                Verify Meter
                </x-btn.default>
                 <button class="hidden" id="pay-now"></button>
                 <div id="image-dropdown" class="fixed bottom-0 right-0 hidden h-full w-full bg-black bg-opacity-10 backdrop-blur-sm slide-uup grid md:grid-cols-3" style="z-index: 9999999 !important">
                <div class="md:col-span-2 "> 
                    
                </div> 
                  <div class="fixed bottom-0 bg-white rounded-t-xl w-full p-2 md:col-span-1">
    <div class="pt-2 px-2 max-h-[50vh] overflow-y-auto">
        @foreach ($services as $service)
            <div class="flex items-center space-x-4 mb-2 cursor-pointer" data-service="{{ $service }}">
                <img src="@basecdn('images/' . $service .'.png')" 
                     name="{{ $service }}" 
                     id="{{ $service }}" 
                     class="w-8"
                     data-src="@basecdn('images/'. $service .'.png')">
                <span class="capitalize text-sm">{{ Str::title($service) }}</span>
            </div>
        @endforeach
    </div>
</div>
                </div>


                </div>
            </form>

        <script>
            validate('network', 'number', 'verify-iuc', 'buy-tv-b', '{{ route('core.verify.meter') }}');
        </script>
       <script>
            submitForm('buy-electricity', 'pay-now', '{{ route('core.purchase.electricity') }}');
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const products = @json($products);
                const productContainer = document.querySelector('.products');
                const networkImages = document.querySelectorAll('#image-dropdown img');
                let selectedNetwork = document.querySelector('#network-image').getAttribute('data-service');

                function roundUpToNearest(amount, multiple) {
                    return Math.ceil(amount / multiple) * multiple;
                }

                function updateProductGrid(network) {
                    if (!productContainer) return;
                    productContainer.innerHTML = '';
                    const networkProducts = products[network] || [];

                    networkProducts.forEach(product => {
                        const roundedPrice = roundUpToNearest(product.role_price, 1000); 
                        const productElement = document.createElement('div');
                        productElement.className = 'bg-gray-100 rounded-lg text-center';
                        productElement.innerHTML = `
                            <div>
                                <p class="text-2xs text-primary bg-alt rounded-lg">₦0.0 Cashback</p>
                            </div>
                            <div class="p-2">
                                <p class="text-sm font-semibold"><span class="text-2xs">₦ </span>${roundedPrice}</p>
                                <p class="text-xs text-gray-400">Pay ₦${product.role_price}</p>
                            </div>
                        `;
                        productElement.addEventListener('click', () => {
                            document.getElementById('amount').value = roundedPrice;
                        });
                        productContainer.appendChild(productElement);
                    });
                }

                updateProductGrid(selectedNetwork);

                networkImages.forEach(image => {
                    image.addEventListener('click', function () {
                        selectedNetwork = this.getAttribute('data-service');
                        document.querySelector('#network-image').src = this.src;
                        updateProductGrid(selectedNetwork);
                    });
                });

                document.addEventListener('click', function (event) {
                    if (!event.target.closest('.relative')) {
                        document.getElementById('image-dropdown').classList.add('hidden');
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectImageIcon = document.getElementById('select-image-icon');
                const imageDropdown = document.getElementById('image-dropdown');
                const networkImage = document.getElementById('network-image');
                const networkInput = document.getElementById('network');

                function toggleDropdown() {
                    imageDropdown.classList.toggle('hidden');
                }

                function selectImage(serviceId, src) {
                    networkImage.src = src;
                    networkInput.value = serviceId;
                    imageDropdown.classList.add('hidden');
                }

                selectImageIcon.addEventListener('click', toggleDropdown);
                networkImage.addEventListener('click', toggleDropdown);

                imageDropdown.addEventListener('click', function (event) {
                    const row = event.target.closest('[data-service]');
                    if (row && imageDropdown.contains(row)) {
                        const img = row.querySelector('img');
                        const serviceId = row.getAttribute('data-service');
                        const src = img ? img.getAttribute('src') : '';
                        selectImage(serviceId, src);
                    }
                });

                document.addEventListener('click', function (event) {
                    if (!event.target.closest('.relative')) {
                        imageDropdown.classList.add('hidden');
                    }
                });
            });
        </script>
</x-app-layout>