<x-app-layout :user="$user" :drk="true" title="Developer API">

    
        <x-app.frame title="Developer API"> 
            
            <image class="rounded-xl" src="@basecdn('images/programming.png')"></image>
            
   
  <div class="bg-white rounded-xl p-4">
  

  <div class="mt-4">
    <p class="text-2xs">Api Bearer Token</p>
    <p class="text-2xl font-semilight" id="acct">UNAVAILABLE</p>
  </div> 
  <hr class="border-t-2 border-dotted border-muted my-4">
  
  <div class="flex space-x-4 mt-4">
    <button class="text-2xs bg-alt text-primary p-3 px-8 rounded-full" id="copy-acct">Copy API Token</button>
    <button class="bg-primary text-white p-3 px-8 rounded-full text-2xs" id="share-acct">View Docs</button>
  </div>
</div> 
            
 <div class="bg-white rounded-xl p-4 mt-4">
  <div class="text-xs font-semibold mb-4">Sell Data to your customers in 3 steps</div>
  
  <div class="space-y-2">
    <div class="flex items-start space-x-2">
      <span class="text-primary font-bold text-sm">1.</span>
      <p class="text-2xs">Copy your dedicated API Token above</span></p>
    </div>

    <div class="flex items-start space-x-2">
      <span class="text-primary font-bold text-sm">2.</span>
      <p class="text-2xs">View Our documentactions && API endpoints</p>
    </div>

    <div class="flex items-start space-x-2">
      <span class="text-primary font-bold text-sm">3.</span>
      <p class="text-2xs">Connect and begin to vend data from your website</p>
    </div>
  </div>
</div>
 
        </x-app.frame>
  
</x-app-layout>