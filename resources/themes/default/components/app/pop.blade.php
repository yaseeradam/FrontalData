<div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 backdrop-blur-sm hidden p-4 z-[999999]">
    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-[50px] shadow-lg w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg text-center mx-4  transition-all duration-500 hover:scale-105">
        <div class="p-4">
            
        <h2 class="text-lg font-bold">Notification</h2>
        <p class="mt-2 text-sm">{{ $slot }}</p>
        </div>
        <hr class="divider w-full mt-4">
        <div class="p-4">
             <button onclick="closePopup()" class="bg-transparent text-sm hover:text-red-500">Okay, Got it</button>
        </div>
       
    </div>
</div>

