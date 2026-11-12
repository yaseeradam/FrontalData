
                <div class="hidden md:block bg-white p-4 rounded-[40px] flex justify-between items-center animate-fadeInUp transition duration-500 transform hover:scale-110 shadow-sm border border-sm md:col-span-2">
                    <div class="text-xs mt-4">
                         You Are Currently Running On User Package
                    </div>
                    <div class="text-xs">
                         Upgrage Your Account And Enjoy More Discount
                    </div>
                   <div>
                       <a href="{{ route('core.user.upgrade') }}">
                           <x-btn.default class="mt-4 py-[10px] text-xs">Upgrade to Agent</x-btn.default>
                       </a>
                       
                   </div>
                   
                </div> 
                <div class="hidden md:flex flex-col bg-white p-4 rounded-[40px]  justify-center items-center animate-fadeInUp transition duration-500 transform hover:scale-110 shadow-sm border border-sm">
                    <div>
                        <div class="text-xs">
                        Data Purchased Today
                    </div>
                    <div class="text-2xl font-bold">
                        {{ $sales }} GB
                    </div>
                    </div>
                </div>
                <div class="hidden md:block animate-fadeInUp transition duration-500 transform hover:scale-110 md:col-span-2">
                    <img src="/img/app/1741168985_sd2.webp" class="rounded-[40px] animate-fadeInUp transition duration-500 transform hover:scale-110 shadow-sm">
                       
                    </img>
                </div>