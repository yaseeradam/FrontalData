<x-app-layout :user="$user">
        @php
        $acct = @jannah_opt('account_number');
        $primary = jannah_opt('primary_color');
        @endphp
        
         <div class="col-span-full md:col-span-3">
                <div class="my-4 font-bold text-lg text-center">Fund Wallet</div>
                <div class="my-4 font-bold text-lg text-center">N50 Deposit charge applies</div>

         <div class="bg-white rounded-xl shadow shadow-lg rounded-[50px]">
          <div class="flex items-center justify-between m-2 transition-all duration-500 hover:scale-105 p-2">
            <div class="flex items-center">
              <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                <i class="jannah-bank text-primary"></i>
              </div>
              <div class="ml-2">
                <h3 class="text-2xs text-gray-500">Account Number</h3>
                <p class="font-medium text-xl" id="acct">@format($acct, 334)</p>
              </div>
            </div>
          </div>
        
          <hr class="border-t-2 border-dotted border-muted my-2">
        
          <div class="lex items-center justify-between m-2 transition-all duration-500 hover:scale-105 p-2">
            <div class="flex items-center">
              <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                <i class="jannah-bank text-primary"></i>
              </div>
              <div class="ml-2">
                <h3 class="text-2xs text-gray-500">Bank Name</h3>
                <p class="font-medium text-sm">{{ jannah_opt('bank_name') }}</p>
              </div>
            </div>
          </div>
        
          <hr class="border-t-2 border-dotted border-muted my-2">
    
          <div class="lex items-center justify-between m-2 transition-all duration-500 hover:scale-105 p-2">
            <div class="flex items-center">
              <div class="bg-[{{ $primary }}]/10 size-10 rounded-full flex items-center justify-center p-2 text-sm">
                <i class="jannah-bank text-primary"></i>
              </div>
              <div class="ml-2">
                <h3 class="text-2xs text-gray-500">Account Name</h3>
                <p class="font-medium text-sm">{{ jannah_opt('account_name') ?? '--- --- ---' }}</p>
              </div>
            </div>
          </div>
        </div>
         
        
          <div class="flex space-x-4 mt-4 justify-center">
            <button class="text-2xs bg-alt text-primary p-4 px-10 rounded-full transition-all duration-500 hover:scale-110" id="copy-acct">Copy Number</button>
            <button class="bg-primary text-white p-4 px-10 rounded-full text-2xs transition-all duration-500 hover:scale-110">Share Details</button>
          </div>
        
        </div>
         <div class="md:col-span-2">
         <div class="bg-white rounded-xl p-4 mt-4">
          <div class="text-xs font-semibold mb-4">Add money via bank transfer in 3 steps</div>
          
          <div class="space-y-2">
            <div class="flex items-start space-x-2">
              <span class="text-primary font-bold text-sm">1.</span>
              <p class="text-2xs">Copy the @jannah_opt('bank_name') Account Number: <span class="font-bold">@format($acct, 334)</span></p>
            </div>
        
            <div class="flex items-start space-x-2">
              <span class="text-primary font-bold text-sm">2.</span>
              <p class="text-2xs">Use your bank app or USSD to make the transfer</p>
            </div>
        
            <div class="flex items-start space-x-2">
              <span class="text-primary font-bold text-sm">3.</span>
              <p class="text-2xs">Send payment proof using the button below</p>
            </div>
          </div>
        </div>
        <div class="mt-4 flex justify-center mb-4">
            <button onclick="load('https://wa.me/2348060358004')" class="bg-primary text-white p-4 w-full rounded-full text-2xs transition-all duration-500 hover:scale-105 ">Submit Payment Proof | Support 1</button>
        
        </div>
        <p class="text-2xs text-center">OR</p>
         <div class="mt-4 flex justify-center mb-12">
            <button onclick="load('https://wa.me/@jannah_opt('support_phone')')" class="bg-primary text-white p-4 w-full rounded-full text-2xs transition-all duration-500 hover:scale-105 ">Submit Payment Proof  | Support 2</button>
        
        </div>
        <br><br>
        <div class="mt-6"></div>

        </div>
        </div>


</x-app-layout>