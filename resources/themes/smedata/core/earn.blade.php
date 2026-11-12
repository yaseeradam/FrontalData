<x-app-layout :user="$user" title="Earn Cash">

    <div class="col-span-full md:col-span-3">
        <x-app.back-header title="Refer & Earn" /> 
       
        <div class="px-2 mb-2">
             <div class="bg-primary rounded-[40px] p-4 flex items-center justify-center  shadow-xl transition-all duration-500 hover:scale-110">
  

  <div class="text-white">
    <p class="text-2xs">Refferal Code</p>
    <p class="text-2xl font-semilight" >{{ $user->username }}</p>
  </div> 
  
</div> 
 <div class="hidden" id="acct">{{route('register') . '?ref=' . $user->username }}</div>
  <div class="flex space-x-4 mt-4">
    <button class="text-2xs bg-alt text-primary p-4 px-8 rounded-full shadow-xl transition-all duration-500 hover:scale-105" id="copy-acct">Copy referral Link</button>
    <button class="bg-primary text-white p-4 px-8 rounded-full text-2xs shadow-xl transition-all duration-500 hover:scale-105" id="share-acct">Transactions</button>
  </div> 
   </div>
   </div>
   <div class="md:col-span-2">
       
 <div class="bg-white rounded-xl">
  <div class="text-xs font-semibold mb-4">Refer and earn up to @jannah_opt('referral_earning_percentage')% on their sales in 3 steps</div>
  
  <div class="space-y-2">
    <div class="flex items-start space-x-2">
      <span class="text-primary font-bold text-sm">1.</span>
      <p class="text-2xs">Copy your unique referral link above</p>
    </div>

    <div class="flex items-start space-x-2">
      <span class="text-primary font-bold text-sm">2.</span>
      <p class="text-2xs">Share the link with your friends or customers</p>
    </div>

    <div class="flex items-start space-x-2">
      <span class="text-primary font-bold text-sm">3.</span>
      <p class="text-2xs">Earn up to @jannah_opt('referral_earning_percentage')% on every sale they make after signing up</p>
    </div>
  </div>


 
  
</x-app-layout>