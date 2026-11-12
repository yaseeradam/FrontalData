<x-app-layout :user="$user">
    
@php
$acct = $bank[0]['account'];
$primary = jannah_opt('primary_color');
@endphp

<div class="col-span-full md:col-span-3">
        <x-app.back-header title="Bank Transfer / Dynamic Account" fallback="{{ route('core.deposit') }}" />
        <div id="amount-screen" class="bg-white rounded-xl">
            <div class="mb-4">
               
                <x-input.default type="number" id="amount-input" label="Amount" />
            </div>
            <div class="">
                
                <x-btn.default class="mt-2" id="submit-amount">Pay Now</x-btn.default>
            </div>
        </div>

        <div id="payment-screen" class="hidden">
    <div class="bg-white rounded-xl shadow shadow-lg rounded-[50px]">
            <div class="flex items-center justify-between m-2 transition-all duration-500 hover:scale-105 p-2">
                <div class="flex items-center">
                    <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                        <i class="jannah-barcode text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-2xs text-gray-500">Account Number</h3>
                        <p class="font-medium text-xl" id="acct">@format($acct, 334)</p>
                    </div>
                </div>
            </div>

            <hr class="border-t-2 border-dotted border-muted my-4">

            <div class="flex items-center justify-between m-2 transition-all duration-500 hover:scale-105 p-2">
                <div class="flex items-center">
                    <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                        <i class="jannah-bank text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-2xs text-gray-500">Bank Name</h3>
                        <p class="font-medium text-sm">9PSB</p>
                    </div>
                </div>
            </div>

            <hr class="border-t-2 border-dotted border-muted my-4">

            <div class="flex items-center justify-between m-2 transition-all duration-500 hover:scale-105 p-2">
                <div class="flex items-center">
                    <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                        <i class="jannah-happyemoji text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-2xs text-gray-500">Account Name</h3>
                        <p class="font-medium text-sm">{{ $bank[0]['name'] }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-t-2 border-dotted border-muted my-4">
            
                  <div class="flex items-center justify-between m-2 transition-all duration-500 hover:scale-105 p-2">
                <div class="flex items-center">
                    <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                        <i class="jannah-money text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-2xs text-gray-500">Amount</h3>
                        <p class="font-medium text-sm"><span id="pay-amount"></span></p>
                    </div>
                </div>
            </div>

            <hr class="border-t-2 border-dotted border-muted my-4">
            
                  <div class="flex items-center justify-between m-2 transition-all duration-500 hover:scale-105 p-2">
                <div class="flex items-center">
                    <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                        <i class="jannah-clock text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-2xs text-red-500">Expires In</h3>
                        <p class="font-medium text-sm text-red-500"><span id="expire-timer"></span></p>
                    </div>
                </div>
            </div>

            </div>
            
            <div class="flex space-x-4 mt-4 justify-center">
                <button class="text-2xs bg-alt text-primary p-3 px-8 rounded-full" id="copy-acct">Copy Number</button>
                <button class="bg-primary text-white p-3 px-8 rounded-full text-2xs">Share Details</button>
            </div>
       </div>
        <div class="md:col-span-2">
        <div class="bg-white rounded-xl p-4 mt-4">
            <div class="text-xs font-semibold mb-4">Add money via bank transfer in 3 steps</div>

            <div class="space-y-2">
                <div class="flex items-start space-x-2">
                    <span class="text-primary font-bold text-sm">1.</span>
                    <p class="text-2xs">Copy the 9PSB Account Number: <span class="font-bold">@format($acct, 334)</span></p>
                </div>

                <div class="flex items-start space-x-2">
                    <span class="text-primary font-bold text-sm">2.</span>
                    <p class="text-2xs">Use your bank app or USSD to make the transfer</p>
                </div>

                <div class="flex items-start space-x-2">
                    <span class="text-primary font-bold text-sm">3.</span>
                    <p class="text-2xs">Click on the button below After payment</p>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-center">
            <button id="payment-made" class="bg-primary text-white p-3 px-8 rounded-full text-2xs">I have made Payments</button>
        </div>
</div>
</div>
        <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="text-white text-center">
        <div class="spinner"></div>
        <p class="mt-2" id="loading-text">Processing Payment...</p>
        <p id="overlay-timer" class="text-sm mt-2"></p>
    </div>
</div>

<style>
    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #F59E0B;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 0.3s linear infinite;
        margin: auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<script>
    let amountInput = document.getElementById('amount-input');
let submitAmountBtn = document.getElementById('submit-amount');
let paymentScreen = document.getElementById('payment-screen');
let amountScreen = document.getElementById('amount-screen');
let payAmount = document.getElementById('pay-amount');
let expireTimer = document.getElementById('expire-timer');
let paymentMadeBtn = document.getElementById('payment-made');
let loadingOverlay = document.getElementById('loading-overlay');
let loadingText = document.getElementById('loading-text');
let overlayTimer = document.getElementById('overlay-timer');

let expireTime = 30 * 60 * 1000;
let countdownInterval;

function formatAmount(amount) {
    return parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

submitAmountBtn.addEventListener('click', function() {
    let amount = amountInput.value;
    if (amount) {
        amountScreen.classList.add('hidden');
        paymentScreen.classList.remove('hidden');
        payAmount.textContent = formatAmount(amount);
        startCountdown(expireTime);
    }
});


paymentMadeBtn.addEventListener('click', function() {
    loadingOverlay.classList.remove('hidden');
    let startTime = new Date().getTime();
    let hasRedirected = false;
    let hasToastShown = false; 
    let checkInterval = 1000; 

    countdownInterval = setInterval(function() {
        let currentTime = new Date().getTime();
        let elapsed = currentTime - startTime;

        if (elapsed >= 2 * 60 * 1000) {
            clearInterval(countdownInterval);
            if (!hasToastShown) {
                Toastify({
                    text: "Transaction pending... check back later",
                    duration: 3000,
                    backgroundColor: "#F59E0B"
                }).showToast();
                hasToastShown = true; 
            }
            loadingOverlay.classList.add('hidden');
            return;
        }

        overlayTimer.textContent = `Completed in ${(2 * 60 - Math.floor(elapsed / 1000))} seconds`;

        if (!hasRedirected) {
            fetch('/deposit/onetime/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ amount: payAmount.textContent.replace(/,/g, '') })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && !hasRedirected) {
                    clearInterval(countdownInterval);
                    if (!hasToastShown) {
                        Toastify({
                            text: "Transaction successful!",
                            duration: 3000,
                            backgroundColor: "#16A34A"
                        }).showToast();
                        hasToastShown = true; 
                    }
                    hasRedirected = true;
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = '/';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }, checkInterval);
});



function startCountdown(duration) {
    let start = Date.now(),
        diff,
        minutes,
        seconds;

    countdownInterval = setInterval(function() {
        diff = duration - ((Date.now() - start));

        minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        seconds = Math.floor((diff % (1000 * 60)) / 1000);

        expireTimer.textContent = minutes + ":" + (seconds < 10 ? "0" + seconds : seconds);

        if (diff <= 0) {
            clearInterval(countdownInterval);
            expireTimer.textContent = "Expired";
        }
    }, 1000);
}
</script>
</x-app-layout>

