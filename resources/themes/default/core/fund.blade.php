<x-app-layout :user="$user">
   
        @php
            $primary = jannah_opt('primary_color');
            $showKYC = true;
        @endphp
        <div class="col-span-full md:col-span-3">
        <x-app.back-header title="Fund Wallet" />
       <div class="space-y-4">
           @foreach ($banks as $bank)
            @php
                $userAccountNumber = $user->{$bank['slug']} ?? null;
                $isPaymentGatewayEnabled = isset($payments[$bank['identifier']]) && $payments[$bank['identifier']]['status'] === 'enabled';
            @endphp

            @if ($isPaymentGatewayEnabled && !empty($userAccountNumber))
                @php
                    $showKYC = false;
                @endphp
             
                <div class="relative bg-primary rounded-[50px] text-white p-4 shadow-xl transition-all duration-500 hover:scale-105">
                        <div class="flex flex-col items-center justify-center space-y-1">
                            <p class="text-sm flex items-center gap-1">
                                {{ $bank['name'] }}
                            </p>
                            <p class="text-xl font-bold flex items-center gap-2 v">
                                {{ $userAccountNumber }}
                            </p>
                            <button class="bg-white/20 text-white px-12 py-3 rounded-full text-sm font-semibold transition-all duration-300 hover:bg-white/30" id="copy-acct">
                                Copy Account
                            </button>
                        </div>
                    </div>
            @endif
       
        
        @endforeach
</div>
        @if ($showKYC)
           
            <div class="relative bg-purple-600 rounded-[50px] text-white p-4 shadow-xl transition-all duration-500 hover:scale-105">
                        <div class="flex flex-col items-center justify-center space-y-1">
                            <p class="text-xs flex items-center gap-1">
                                Based on CBN Regulations, you are expected to complete KYC before getting a virtual bank account number. Kindly click on the button below to finish KYC or use other payment methods.
                            </p>
                            
                            <a href="{{ route('core.kyc') }}" class="bg-white/20 text-white px-12 py-3 rounded-full text-sm font-semibold transition-all duration-300 hover:bg-white/30">
                               Complete Now
                            </a>
                        </div>
                    </div>
        @endif
        </div>
        <div class="md:col-span-2">
        <div class="flex items-center my-4 text-center text-muted-foreground md:hidden">
            <div class="flex-grow border-t border-muted"></div>
            <span class="mx-4 text-xs">OR</span>
            <div class="flex-grow border-t border-muted"></div>
        </div>
         <div class="space-y-4 mt-4 md:mt-16">
        @if (isset($payments['mf']) && $payments['mf']['status'] === 'enabled')
            <div onclick="load('{{ route('core.deposit.monnify') }}')" class="bg-white rounded-[30px] p-4 shadow-lg transition-all duration-500 hover:scale-105 ">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                            <i class="jannah-crown-1 text-primary"></i>
                        </div>
                        <div class="ml-2">
                            <h3 class="text-xs font-medium">Pay with Card/USSD/Bank</h3>
                            <p class="text-muted-foreground text-2xs">Fund your wallet using online services</p>
                        </div>
                    </div>
                    <button class="text-primary hover:text-primary/80">
                        <span><i class="jannah-arrow-right-3"></i></span>
                    </button>
                </div>
            </div>
        @endif

       
            <div onclick="load('{{ route('core.deposit.manual') }}')" class="bg-white rounded-[30px] p-4 shadow-lg transition-all duration-500 hover:scale-105">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                            <i class="jannah-brifecase-tick text-primary"></i>
                        </div>
                        <div class="ml-2">
                            <h3 class="text-xs font-medium">Manual Deposit</h3>
                            <p class="text-muted-foreground text-2xs">Fund your wallet directly from Us</p>
                        </div>
                    </div>
                    <button class="text-primary hover:text-primary/80">
                        <span><i class="jannah-arrow-right-3"></i></span>
                    </button>
                </div>
            </div>

            @if (isset($payments['pv']) && $payments['pv']['status'] === 'enabled')
                <div onclick="load('{{ route('core.deposit.onetime') }}')" class="bg-white rounded-[30px] p-4 shadow-lg transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                                <i class="jannah-money-2 text-primary"></i>
                            </div>
                            <div class="ml-2">
                                <h3 class="text-xs font-medium">Onetime Account</h3>
                                <p class="text-muted-foreground text-2xs">Fund your wallet with an automated account</p>
                            </div>
                        </div>
                        <button class="text-primary hover:text-primary/80">
                            <span><i class="jannah-arrow-right-3"></i></span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
        </div>
</x-app-layout>

<script>
    document.getElementById('copy-acct').addEventListener('click', function() {
        var accountNumber = document.getElementById('acct').innerText;
        navigator.clipboard.writeText(accountNumber).then(function() {
            Toastify({
                text: "Account number copied!",
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                duration: 3000
            }).showToast();
        });
    });
</script>
