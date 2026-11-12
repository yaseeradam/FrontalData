<x-app-layout :drk="false" title="Data" :user="$user">
    @php
        $primaryColor = jannah_opt('primary_color') ?: '#0f5f49';
        if (! preg_match('/^#[0-9a-fA-F]{6}$/', $primaryColor)) {
            $primaryColor = '#0f5f49';
        }
        $color = ltrim($primaryColor, '#');
    @endphp
    <style>
        .data-plan-category-field {
            display: block;
            margin-top: 1rem;
        }

        .data-plan-category-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #6b7280;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .data-plan-category-select {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 1rem;
            background-color: #fff;
            color: #111827;
            font-size: 0.95rem;
            font-weight: 700;
            padding: 0.85rem 2.5rem 0.85rem 0.9rem;
            outline: none;
        }

        .data-plan-category-select:focus {
            border-color: {{ $primaryColor }};
            box-shadow: 0 0 0 3px rgba({{ hexdec(substr($color, 0, 2)) }}, {{ hexdec(substr($color, 2, 2)) }}, {{ hexdec(substr($color, 4, 2)) }}, 0.16);
        }

        .data-subcategories {
            display: none;
        }

        .data-subcategory-btn {
            max-width: 100%;
            border: 1px solid transparent;
            border-radius: 999px;
            color: {{ $primaryColor }} !important;
            font-size: 0.8rem;
            font-weight: 700;
            line-height: 1.2;
            padding: 0.45rem 0.8rem;
            text-align: center;
            transition: background-color 150ms ease, border-color 150ms ease, color 150ms ease, transform 150ms ease;
            overflow-wrap: anywhere;
        }

        .data-subcategory-btn:hover {
            transform: translateY(-1px);
        }

        .data-subcategory-btn.is-active {
            background-color: {{ $primaryColor }};
            border-color: {{ $primaryColor }};
            color: #fff !important;
        }

        .data-products-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .data-product-card {
            align-items: center;
            background: #fff;
            border: 1px solid transparent;
            border-radius: 1rem;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.08);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            justify-content: center;
            min-height: 5.1rem;
            padding: 0.65rem;
            text-align: center;
            transition: border-color 150ms ease, transform 150ms ease, box-shadow 150ms ease;
        }

        .data-product-card:hover {
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.1);
            transform: translateY(-1px);
        }

        .data-product-card.is-selected {
            border-color: {{ $primaryColor }};
        }

        .data-product-name {
            color: {{ $primaryColor }};
            font-size: 0.72rem;
            font-weight: 800;
            line-height: 1.2;
            max-width: 100%;
            overflow-wrap: anywhere;
        }

        .data-product-price {
            color: #9ca3af;
            font-size: 0.78rem;
            line-height: 1.2;
            white-space: nowrap;
        }

        @media (min-width: 640px) {
            .data-plan-category-field {
                display: none;
            }

            .data-subcategories {
                align-items: center;
                display: flex;
                flex-wrap: wrap;
                gap: 0.65rem;
                justify-content: center;
                margin-top: 1rem;
            }

            .data-products-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 768px) {
            .data-products-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .data-products-grid {
                grid-template-columns: repeat(6, minmax(0, 1fr));
            }
        }
    </style>
    <div class="col-span-3"> 
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

            <div class="data-plan-category-field">
                <label for="subcategory-select" class="data-plan-category-label">Plan category</label>
                <select id="subcategory-select" class="data-plan-category-select" aria-label="Plan category"></select>
            </div>
            
            <div class="text-sm text-gray-700 subcategories data-subcategories w-full">
               
            </div>
            
            <div class="mt-6 text-sm products data-products-grid"></div>
            
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
        const subcategorySelect = document.querySelector('#subcategory-select');
        const categoryButtons = document.querySelectorAll('.category-btn');
        if (!categoryButtons.length) {
            productContainer.innerHTML = '<div class="text-center text-sm text-gray-500">No data networks available.</div>';
            return;
        }

        let selectedCategory = categoryButtons[0].getAttribute('data-service');
        let selectedPlan = null;
        document.getElementById('network').value = selectedCategory;

        function updateSubcategoryButtons(category) {
            subcategoryContainer.innerHTML = '';
            subcategorySelect.innerHTML = '';
            const categoryProducts = products[category] || {};

            for (const subcategory in categoryProducts) {
                const subcategoryButton = document.createElement('button');
                const subcategoryOption = document.createElement('option');

                subcategoryButton.type = 'button';
                subcategoryButton.className = 'data-subcategory-btn subcategory-btn';
                subcategoryButton.setAttribute('data-subcategory', subcategory);
                subcategoryButton.textContent = subcategory;
                subcategoryButton.addEventListener('click', function () {
                    subcategorySelect.value = subcategory;
                    updateProductGrid(subcategory);
                    setActiveSubcategoryButton(this);
                });
                subcategoryContainer.appendChild(subcategoryButton);

                subcategoryOption.value = subcategory;
                subcategoryOption.textContent = subcategory;
                subcategorySelect.appendChild(subcategoryOption);
            }
            const firstSubcategoryButton = subcategoryContainer.querySelector('.subcategory-btn');
            if (firstSubcategoryButton) {
                document.getElementById('subcategory').value = firstSubcategoryButton.getAttribute('data-subcategory');
                firstSubcategoryButton.click();
                return;
            }

            document.getElementById('subcategory').value = '';
            document.getElementById('plan').value = '';
            document.getElementById('amount').value = '';
            productContainer.innerHTML = '<div class="text-center text-sm text-gray-500">No plans available for this network.</div>';
        }

        function setActiveSubcategoryButton(button) {
            document.querySelectorAll('.subcategory-btn').forEach(btn => {
                btn.classList.remove('is-active');
            });
            button.classList.add('is-active');
        }

        function updateProductGrid(subcategory) {
            document.getElementById('subcategory').value = subcategory;
            document.getElementById('plan').value = '';
            document.getElementById('amount').value = '';
            selectedPlan = null;
            productContainer.innerHTML = '';
            const categoryProducts = products[selectedCategory] || {};
            const subcategoryProducts = categoryProducts[subcategory] || [];

            if (!subcategoryProducts.length) {
                productContainer.innerHTML = '<div class="text-center text-sm text-gray-500">No plans available in this category.</div>';
                return;
            }

            subcategoryProducts.forEach(product => {
                const productElement = document.createElement('div');
                const productName = document.createElement('div');
                const productPrice = document.createElement('div');

                productElement.className = 'data-product-card product-item';
                productElement.setAttribute('data-plan', product.name);

                productName.className = 'data-product-name';
                productName.textContent = product.name;

                productPrice.className = 'data-product-price';
                productPrice.textContent = `Pay ₦${product.role_price}`;

                productElement.appendChild(productName);
                productElement.appendChild(productPrice);
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
                item.classList.remove('is-selected');
            });
            productElement.classList.add('is-selected');
            selectedPlan = productElement.getAttribute('data-plan');
            document.getElementById('plan').value = selectedPlan;
        }
        updateSubcategoryButtons(selectedCategory);
        categoryButtons[0].classList.add('border', 'border-sm', 'border-primary');

        subcategorySelect.addEventListener('change', function () {
            const selectedSubcategory = this.value;
            const matchingButton = Array.from(document.querySelectorAll('.subcategory-btn')).find(button => {
                return button.getAttribute('data-subcategory') === selectedSubcategory;
            });

            updateProductGrid(selectedSubcategory);
            if (matchingButton) {
                setActiveSubcategoryButton(matchingButton);
            }
        });
        
        categoryButtons.forEach(button => {
            button.addEventListener('click', function () {
                selectedCategory = this.getAttribute('data-service');
                document.getElementById('network').value = selectedCategory;
                document.getElementById('subcategory').value = '';
                document.getElementById('plan').value = '';
                document.getElementById('amount').value = '';
                updateSubcategoryButtons(selectedCategory);
                categoryButtons.forEach(btn => {
                    btn.classList.remove("border-primary", 'border-[1px]', 'rounded-2xl', 'text-white', 'border', 'border-sm', );
                });
                this.classList.add("border-primary", 'border-[1px]', 'rounded-2xl', 'text-white', 'border', 'border-sm', );
            });
        });
    });
    </script>
</x-app-layout>
