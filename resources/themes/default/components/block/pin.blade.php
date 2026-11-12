<div id="loading-overlay" class="hidden"><div class="fixed inset-0  bg-black bg-opacity-10 backdrop-blur-sm  flex items-center justify-center" style="z-index: 996 !important">
    <div class="text-center">
        <div class="spinner"></div>
        <p class="mt-2" id="loading-text">Processing Payment...</p>
        <p id="overlay-timer" class="text-sm mt-2"></p>
    </div>
</div></div>

<style>
    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #F59E0B;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 0.3s linear infinite;
        margin: auto;
        z-index: 999999;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<div id="canvasOverlay" class="canvas-overlay" onclick="toggleCanvas(event)"></div>
<div id="canvasBox" class="fixed bottom-0 left-0 right-0 slide-up hidden md:grid md:grid-cols-3" style="z-index: 998 !important">
    <div class="md:col-span-2">
        
    </div>
    <div class="md:col-span-1 bg-white rounded-t-2xl p-4 shadow-lg">
      <div class="flex justify-left items-center text-2xl relative">
    <button onclick="toggleCanvas(event)" class="text-gray-500">×</button>
  </div>
      <div class="text-2xl font-semibold text-center relative m-1 bottom-2 p-2">
        <span class="text-sm" id="nairaSign">₦</span><span id="plan_d"></span><span id="decimal"></span>
      </div>
      <div class="space-y-1">
        <div class="flex justify-between mt-1">
          <p class="text-gray-500 text-xs">Product Name</p>
          <div class="text-xs flex justify-between">
            <img id="networkImage" src="" class="w-5 mr-2">
            <span id="serviceType"></span>
          </div>
        </div>
        <div class="flex justify-between mt-1">
          <p class="text-gray-500 text-xs">Amount</p>
          <div class="text-xs">₦<span class="amt"></span></div>
        </div>
        <div class="flex justify-between mt-1">
          <p class="text-gray-500 text-xs">Mobile/ID</p>
          <div class="text-xs"><span id="mobile"></span></div>
        </div>
        <div class="flex justify-between mt-1 text-green-500">
          <p class="text-gray-500 text-xs">Bonus To Earn</p>
          <div class="text-xs">+₦0.00 Cashback</div>
        </div>
      </div>
      <div class="pt-2 pb-1"><hr></div>
      <div class="mt-4">
        <div class="flex justify-between items-center p-2 rounded-xl mt-2 bg-gray-50 selectable">
          <div class="flex items-center">
            <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
            <p class="ml-2 text-xs">Referral (₦<span id="cashbackBalance">{{ number_format($user->referral_balance ?? 0, 2)}}</span>)</p>
          </div>
          <a href="{{ route('core.earn') }}" class="text-primary text-xs" id="addCashbackMoney" style="display: none;">Earn More</a>
        </div>
        <div class="flex justify-between items-center p-2 rounded-xl mt-2 bg-gray-50 selectable">
          <div class="flex items-center">
            <div class="w-8 h-8 bg-green-200 rounded-full"></div>
            <p class="ml-2 text-xs">Wallet (₦<span id="walletBalance">{{ number_format($user->wallet_balance ?? 0, 2)}}</span>)</p>
          </div>
          <a href="{{ route('core.deposit') }}" class="text-primary text-xs" id="addWalletMoney" style="display: none;">Add Money</a>
        </div>
      </div>
      <div class="pt-2 pb-2">
        <x-btn.default onclick="showPinEntry()">Pay</x-btn.default>
      </div>
    </div>
  
</div>

<div id="pinOverlay" class="canvas-overlay" onclick="hidePinEntry()"></div>
<div id="pinCanvas" class="fixed bottom-0 left-0 right-0 slide-up hidden md:grid md:grid-cols-3" style="z-index: 999 !important">
    <div class="md:col-span-2">
        
    </div>
    <div class="md:col-span-1 bg-white rounded-t-2xl shadow-lg ">
        <div class="flex flex-col items-center justify-center p-1 bg-card rounded-lg shadow-lg">
    <div class="pt-4 pb-4">
      <h2 class="text-xs font-semibold text-foreground">Enter Payment PIN</h2>
    </div>
    <div class="flex space-x-4 mb-3">
      @for ($i = 0; $i < 4; $i++)
        <input type="password" class="pin-input w-10 h-10 text-center border-none rounded-md bg-gray-100" maxlength="1" id="pin-box-{{ $i }}" />
      @endfor
    </div>
    <a href="{{ route('core.user.pin') }}" class="mb-2 text-xs text-primary">Forgot Payment PIN?</a>
    
    <div class="bg-gray-50 w-full rounded-t-lg p-1">
      <div class="grid grid-cols-3 gap-2 mt-4">
        @foreach (range(1, 9) as $num)
          <button class="bg-secondary text-secondary-foreground p-3 rounded-lg bg-white" onclick="enterNumber({{ $num }})">{{ $num }}</button>
        @endforeach
        <button class="bg-secondary text-secondary-foreground p-3 rounded-lg col-span-2 bg-white" onclick="enterNumber(0)">0</button>
        <button class="bg-secondary text-secondary-foreground p-3 rounded-lg text-2xl bg-white" onclick="clearPin()">×</button>
      </div>
    </div>
  </div>
    </div>
  
</div>
<script>
    function toggleCanvas(event) {
  const canvas = document.getElementById('canvasBox');
  const overlay = document.getElementById('canvasOverlay');
  const contentWrapper = document.getElementById('contentWrapper');
  const amountValue = parseFloat(document.getElementById('amount').value) || 0;
  const phone = document.getElementById('number').value;
  const planElement = document.getElementById('plan');
  const planX = planElement ? planElement.value : null;
  const planD = document.getElementById('plan_d');
  const nG = document.getElementById('nairaSign');
  document.getElementById('mobile').innerHTML = phone;
  const productType = document.getElementById('service_type').value;
  document.getElementById('serviceType').innerHTML = productType;
  const networkImg = document.getElementById('network').value;
  const cUrl = `/images/${networkImg}.png`;
  document.getElementById('networkImage').src = cUrl;
  const cashbackBalance = parseFloat(document.getElementById('cashbackBalance').innerText.replace(/[^0-9.-]+/g, ""));
  const walletBalance = parseFloat(document.getElementById('walletBalance').innerText.replace(/[^0-9.-]+/g, ""));
  if(planX) {
       planD.innerHTML = planX;
       nG.classList.add('hidden');
  } else {
       planD.innerHTML = amountValue.toFixed(2);
  }
  
  document.querySelectorAll('.amt').forEach(el => {
    el.innerHTML = amountValue.toFixed(2);
  });

  if (cashbackBalance < amountValue) {
    document.getElementById('addCashbackMoney').style.display = 'block';
  } else {
    document.getElementById('addCashbackMoney').style.display = 'none';
  }

  if (walletBalance < amountValue) {
    document.getElementById('addWalletMoney').style.display = 'block';
  } else {
    document.getElementById('addWalletMoney').style.display = 'none';
  }

  canvas.classList.toggle('hidden');
  overlay.classList.toggle('canvas-overlay-active');
  contentWrapper.classList.toggle('blur');

  const pinCanvas = document.getElementById('pinCanvas');
  const pinOverlay = document.getElementById('pinOverlay');

  if (pinOverlay.classList.contains('canvas-overlay-active') && !pinCanvas.contains(event.target)) {
    pinCanvas.classList.add('hidden');
    pinCanvas.classList.remove('slide-up-active');
    clearPin();
    pinOverlay.classList.remove('canvas-overlay-active');
  }

  setTimeout(() => {
    canvas.classList.toggle('slide-up-active');
  }, 3);
}

function showPinEntry() {
  clearPin();
  const pinCanvas = document.getElementById('pinCanvas');
  const pinOverlay = document.getElementById('pinOverlay');

  pinCanvas.classList.remove('hidden');
  pinOverlay.classList.add('canvas-overlay-active');

  setTimeout(() => {
    pinCanvas.classList.add('slide-up-active');
  }, 3);
}

function hidePinEntry() {
  const pinCanvas = document.getElementById('pinCanvas');
  const pinOverlay = document.getElementById('pinOverlay');
  clearPin();
  pinCanvas.classList.remove('slide-up-active');

  setTimeout(() => {
    pinCanvas.classList.add('hidden');
    pinOverlay.classList.remove('canvas-overlay-active');
  }, 3);
}

document.querySelectorAll('.pin-input').forEach((input, index, inputs) => {
  input.setAttribute('inputmode', 'none');

  input.addEventListener('input', function () {
    if (input.value.length === input.maxLength && index < inputs.length - 1) {
      inputs[index + 1].focus();
    }
  });

  input.addEventListener('keydown', function (e) {
    if (e.key === 'Backspace' && input.value === '' && index > 0) {
      inputs[index - 1].focus();
    }
  });
});
 
function enterNumber(num) {
  const inputs = document.querySelectorAll('.pin-input');
  for (let i = 0; i < inputs.length; i++) {
    if (inputs[i].value === '') {
      inputs[i].value = num;
      if (i < inputs.length - 1) {
        inputs[i + 1].focus();
      }
      break;
    }
  }

  let pin = '';
  let hiddenPinInput = document.getElementById('pin');
  let loadingOverlay = document.getElementById('loading-overlay');
  inputs.forEach(el => pin += el.value);
  if (pin.length === inputs.length) {
    hiddenPinInput.value = pin;
    hidePinEntry();
    toggleCanvas(event);  
    loadingOverlay.classList.remove("hidden");
    document.getElementById('pay-now').click();
  }
}

function clearPin() {
  const inputs = document.querySelectorAll('.pin-input');
  inputs.forEach(input => input.value = '');
  inputs[0].focus();
}

</script>