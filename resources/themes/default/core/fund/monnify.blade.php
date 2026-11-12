<x-app-layout :user="$user">
     <div class="col-span-full md:col-span-3">
        <x-app.back-header title="Pay With USSD / Bank" fallback="{{ route('core.deposit') }}" />
        <div id="amount-screen" class="">
            <div class="mb-4">
                
                <x-input.default type="number" id="amount-input" label="Amount" />
            </div>
            <div class="flex justify-center mt-4">
                
                <x-btn.default class="mt-4 mb-10" id="submit-amount">Fund Now</x-btn.default>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>

    <script>
        document.getElementById('submit-amount').addEventListener('click', function() {
            var amount = document.getElementById('amount-input').value;

            if (!amount || amount <= 0) {
                Toastify({
                    text: "Please enter a valid amount.",
                    className: "toastify-error",
                    duration: 3000,
                    close: false,
                    gravity: "top",
                    position: "center"
                }).showToast();
                return;
            }

            payWithMonnify(amount);
        });

        function payWithMonnify(amount) {
            MonnifySDK.initialize({
                amount: amount,
                currency: "NGN",
                reference: new String((new Date()).getTime()), 
                customerFullName: "{{ $user->first_name .' '. $user->last_name }}",
                customerEmail: "{{ $user->email }}",
                apiKey: "{{ $gateway->api_token }}", 
                contractCode: "{{ $gateway->business_id }}", 
                paymentDescription: "Wallet Deposit",
                metadata: {
                    "name": "{{ $user->first_name }}",
                    "age": 25
                },
                onLoadStart: () => {
                    console.log("loading has started");
                },
                onLoadComplete: () => {
                    console.log("SDK is UP");
                },
                onComplete: function(response) {
                    console.log(response);
                    if (response.status === "SUCCESS") {
                        Toastify({
                            text: "Payment successful!",
                            className: "toastify-success",
                            duration: 3000,
                            close: false,
                            gravity: "top",
                            position: "center"
                        }).showToast();
                        
                        setTimeout(function() {
                            window.location.href = '/';
                        }, 3000); 
                    } else {
                        Toastify({
                            text: "Payment failed. Please try again.",
                            className: "toastify-error",
                            duration: 3000,
                            close: false,
                            gravity: "top",
                            position: "center"
                        }).showToast();
                    }
                },
                onClose: function(data) {
                   
                }
            });
        }
    </script>
</x-app-layout>
