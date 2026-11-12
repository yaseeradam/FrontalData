<x-app-layout :drk="false" title="Data" :user="$user">
    @php
        $primaryColor = jannah_opt('primary_color');
        $color = ltrim($primaryColor, '#');
    @endphp
    <div class="col-span-full md:col-span-3"> 
        <form id="buy-data" method="POST" class="z-[99]">
            @csrf
            <x-app.back-header title="Buy Data" />
            <input id="service_type" value="Data" hidden />
            <input name="network" id="network" value="{{ $serviceF }}" hidden />
            <input name="pin" id="pin" required hidden />
            <input name="subcategory" id="subcategory" value="" hidden />
            <input name="amount" id="amount" value="" hidden />
           
            <input name="plan" id="plan" value="" hidden />
            <div class="flex justify-between gap-x-2" id="network-icons">
              @foreach($services as $service)
                <div class="p-2 rounded-2xl transition-all hover:scale-110 category-btn" data-service="{{ $service }}">
                  <img class="rounded-full size-10 cursor-pointer" src="@basecdn('img/' . $service . '.png')" data-service="{{ $service }}">
                </div>
              @endforeach
            </div>

            <div>
                <x-input.default id="number" name="number" type="number" label="Phone" />
            </div>
            
            <div class="flex justify-between mt-4 text-sm text-gray-700 subcategories w-full">
               
            </div>
            
            <div class="grid grid-cols-3 gap-x-8 md:grid-cols-4 lg:grid-cols-6 md:gap-x-3 gap-y-4 mt-6 text-sm products"></div>
            
            <x-btn.default type="button" onclick="toggleCanvas(event)">
                Continue
            </x-btn.default>
            
            <button class="hidden" id="pay-now"></button>
            <div class="mb-20 md:hidden"></div>
        </form>
    </div>

    <script>
        submitForm('buy-data', 'pay-now', '{{ route('core.purchase.data') }}');
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        
        const products = @json($products);
        const productContainer = document.querySelector('.products');
        const subcategoryContainer = document.querySelector('.subcategories');
        const categoryButtons = document.querySelectorAll('.category-btn');
        let selectedCategory = categoryButtons[0].getAttribute('data-service');
        let selectedPlan = null;
        document.getElementById('network').value = selectedCategory;

        function updateSubcategoryButtons(category) {
            subcategoryContainer.innerHTML = '';
            const categoryProducts = products[category] || {};

            for (const subcategory in categoryProducts) {
                const subcategoryButton = document.createElement('button');
                subcategoryButton.className = 'text-primary text-sm font-semibold subcategory-btn';
                subcategoryButton.setAttribute('data-subcategory', subcategory);
                subcategoryButton.innerText = subcategory;
                subcategoryButton.addEventListener('click', function () {
                    updateProductGrid(subcategory);
                    setActiveSubcategoryButton(this);
                });
                subcategoryContainer.appendChild(subcategoryButton);
            }
            const firstSubcategoryButton = subcategoryContainer.querySelector('.subcategory-btn');
            if (firstSubcategoryButton) {
                document.getElementById('subcategory').value = firstSubcategoryButton.getAttribute('data-subcategory');
                firstSubcategoryButton.click();
            }
        }

        function setActiveSubcategoryButton(button) {
            document.querySelectorAll('.subcategory-btn').forEach(btn => {
                btn.classList.remove("border-[@jannah_opt('primary_color')]", 'border-b-2', 'text-white');
            });
            button.classList.add("border-[@jannah_opt('primary_color')]", 'border-b-2', 'text-white');
        }

        function updateProductGrid(subcategory) {
            document.getElementById('subcategory').value = subcategory;
            productContainer.innerHTML = '';
            const categoryProducts = products[selectedCategory] || {};
            const subcategoryProducts = categoryProducts[subcategory] || [];

            subcategoryProducts.forEach(product => {
                const productElement = document.createElement('div');
                productElement.className = 'transition-all hover:scale-110 grid grid-rows-2 flex justify-center items-center p-2 bg-gray-100 rounded-2xl shadow-sm product-item';
                productElement.setAttribute('data-plan', product.name);
                productElement.innerHTML = `
                   
                  
                        <div class="font-bold text-primary text-center"><span class="text-2xs">${product.name}</span></div>
                        <div class="text-xs text-gray-400 text-nowrap">Pay ₦${product.role_price}</div>
                   
                `;
                productElement.addEventListener('click', () => {
                    document.getElementById('plan').value = product.name;
                    document.getElementById('amount').value = product.role_price;
                    setSelectedPlan(productElement);
                });
                productContainer.appendChild(productElement);
            });
        }

        function setSelectedPlan(productElement) {
            document.querySelectorAll('.product-item').forEach(item => {
                item.classList.remove('border-primary', 'border-[1px]');
            });
            productElement.classList.add("border-[@jannah_opt('primary_color')]", 'border-[1px]');
            selectedPlan = productElement.getAttribute('data-plan');
            document.getElementById('plan').value = selectedPlan;
        }
        updateSubcategoryButtons(selectedCategory);
        categoryButtons[0].classList.add('border', 'border-sm', 'border-[{{ $primaryColor }}]');
        
        categoryButtons.forEach(button => {
            button.addEventListener('click', function () {
                selectedCategory = this.getAttribute('data-service');
                document.getElementById('network').value = selectedCategory;
                document.getElementById('subcategory').value = '';
                updateSubcategoryButtons(selectedCategory);
                categoryButtons.forEach(btn => {
                    btn.classList.remove("border-[@jannah_opt('primary_color')]", 'border-[1px]', 'rounded-2xl', 'text-white', 'border', 'border-sm', );
                });
                this.classList.add("border-[@jannah_opt('primary_color')]", 'border-[1px]', 'rounded-2xl', 'text-white', 'border', 'border-sm', );
            });
        });
    });
    </script>
</x-app-layout>
