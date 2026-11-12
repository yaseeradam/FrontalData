<x-app-layout title="Cable TV" :user="$user">
    @php
        $primaryColor = jannah_opt('primary_color');
        $color = ltrim($primaryColor, '#');
    @endphp

      
    <div class="col-span-3"> 
         <form id="buy-tv" method="POST" class="z-[99]"> 
         @csrf
            <x-app.back-header title="Cable TV" />
            <input id="service_type" value="CableTV" hidden="" />
            <input name="network" id="network" value="{{ $serviceF }}" hidden />
            <input name="plan" id="plan" hidden />
            <input name="amount" id="amount" hidden />
            <input name="pin" id="pin" hidden="" />
            <div class="flex justify-between gap-x-2" id="network-icons">
              @foreach($services as $service)
                <div class="p-2 rounded-2xl transition-all hover:scale-110 category-btn" data-service="{{ $service }}">
                  <img class="rounded-full size-10 cursor-pointer" src="@basecdn('img/' . $service . '.png')" data-service="{{ $service }}">
                </div>
              @endforeach
            </div>
             <div>
                <x-input.default id="number" name="number" type="number" label="IUC Number" />
            </div>
              <div id="ver" class="text-xs text-red-500">
                
            </div> 
                   
                   
                   
                  
           
            
      
                
                <div class="grid grid-cols-3 gap-x-8 md:grid-cols-4 lg:grid-cols-6 md:gap-x-3 gap-y-4 mt-6 text-sm products"></div> 
            <x-btn.default id="buy-tv-b" class="hidden"  type="button" onclick="toggleCanvas(event)">
            Continue
            </x-btn.default>
            <x-btn.default id="verify-iuc" type="button">
            Verify IUC
            </x-btn.default>
            <button class="hidden" id="pay-now"></button>
        </div>
        </form>
  </div>        
        
        
        
        
        
        
        <script>
            validate('network', 'number', 'verify-iuc', 'buy-tv-b', '{{ route('core.verify.iuc') }}');
        </script>
       <script>
            submitForm('buy-tv', 'pay-now', '{{ route('core.purchase.tv') }}');
        </script>
        <script>
    document.addEventListener('DOMContentLoaded', function () {
        const products = @json($products);
        const productContainer = document.querySelector('.products');
        const categoryButtons = document.querySelectorAll('.category-btn');
        const iconContainers = document.querySelectorAll('#network-icons > div');
        const networkImages = document.querySelectorAll('#network-icons img[data-service]');
        let selectedNetwork = networkImages[0].getAttribute('data-service');
        let selectedPlan = null;
        
        function updateProductGrid(category) {
            console.log('In progress');
            console.log(category);
            productContainer.innerHTML = '';
            const categoryProducts = products[category] || [];

            categoryProducts.forEach(product => {
                const productElement = document.createElement('div');
                productElement.className = 'transition-all hover:scale-110 grid grid-rows-2 flex justify-center items-center p-2 bg-gray-100 rounded-2xl shadow-sm product-item';
                productElement.setAttribute('data-plan', product.name);
                productElement.innerHTML = `
                  
                        <div class="font-bold text-primary text-center"><span class="text-2xs">${product.name}</div>
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
                item.classList.remove('border-primary', 'border-2');
            });
            productElement.classList.add('border-primary', 'border-2');
            selectedPlan = productElement.getAttribute('data-plan');
            document.getElementById('plan').value = selectedPlan;
        }

        updateProductGrid(selectedNetwork);
        iconContainers[0].classList.add('border', 'border-sm', 'border-primary');
       
  networkImages.forEach(image => {
    image.addEventListener('click', function() {
      selectedNetwork = this.getAttribute('data-service');
      updateProductGrid(selectedNetwork);
      document.getElementById('network').value = selectedNetwork;
      iconContainers.forEach(container => {
        container.classList.remove('border', 'border-sm', 'border-primary');
      });
      this.parentElement.classList.add('border', 'border-sm', 'border-primary');
    });
  });
    });
</script>

       
    
</x-app-layout>