<x-app-layout :drk="true" title="Add Money">

    
        <x-app.frame title="Add Money">
         @if (empty($user->{$slug})) 
         <div class="bg-yellow-500 rounded-xl p-4 text-white ">
            <div class="flex justify-between pb-2">
                <div class="text-2xs">
                  Based on CBN Regulations you are expected to complete KYC before getting a virtual bank account number kindly click on the button below to finich KYC or use other payment methods  
                </div>
            </div>
            <div class="flex justify-between">
                <div>
                    
                </div>
            <a href="{{ route('core.kyc') }}" class="p-3 rounded-full bg-green-500 text-2xs text-white px-8">
                    Complete Now
                </a>
            </div>
        </div>
         @endif

 
            <div class="bg-white rounded-xl p-4">
  <div class="flex items-center justify-between mb-2">
    <div class="flex items-center">
      <div class="bg-muted p-2 text-sm rounded-lg">
        <i class="jannah-bank text-primary"></i>
      </div>
      <div class="ml-2">
        <h3 class="text-xs font-medium">Bank Transfer</h3>
        <p class="text-muted-foreground text-2xs">Add money via mobile or internet banking</p>
      </div>
    </div>
    <button class="text-primary hover:text-primary/80">
      <span><i class="jannah-arrow-right-3"></i></span>
    </button>
  </div>

  <hr class="border-t-2 border-dotted border-muted my-4">
  <div class="mt-4">
    <p class="text-2xs">{{ $bank }}</p>
    <p class="text-2xl font-semilight" id="acct">@format($user->{$slug}, 334)</p>
  </div> 
  <div class="flex space-x-4 mt-4">
    <button class="text-2xs bg-alt text-primary p-3 px-8 rounded-full" id="copy-acct">Copy Number</button>
    <button class="bg-primary text-white p-3 px-8 rounded-full text-2xs" id="share-acct">Share Details</button>
  </div>
</div>
 
<div class="flex items-center my-4 text-center text-muted-foreground">
  <div class="flex-grow border-t border-muted"></div>
  <span class="mx-4 text-xs">OR</span>
  <div class="flex-grow border-t border-muted"></div>
</div>
  <div class="space-y-4"> 
      
  
      <div onclick="load('{{ route('core.deposit.manual') }}')"  class="bg-white rounded-xl p-4">
        <div class="flex items-center justify-between mb-2">
    <div class="flex items-center">
      <div class="bg-muted p-2 text-sm rounded-lg">
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
      <div onclick="load('{{ route('core.deposit.onetime') }}')" class="bg-white rounded-xl p-4">
        <div class="flex items-center justify-between mb-2">
    <div class="flex items-center">
      <div class="bg-muted p-2 text-sm rounded-lg">
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
 
       <div onclick="load('{{ route('core.deposit.monnify') }}')" class="bg-white rounded-xl p-4">
        <div class="flex items-center justify-between mb-2">
    <div class="flex items-center">
      <div class="bg-muted p-2 text-sm rounded-lg">
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
 
   
  
 </div>
  
        </x-app.frame>
  
</x-app-layout>