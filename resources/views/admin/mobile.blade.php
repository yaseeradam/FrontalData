<x-admin-layout :drk="true" title="Manage VTU App">
   <x-admin.header />
   <x-admin.side />   <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
       <h2 class="text-2xl font-bold mb-6">App Management</h2><div class="grid grid-cols-1 md:grid-cols-2 gap-6">
       <div class="bg-white rounded shadow p-6">
           <h3 class="text-lg font-semibold mb-4">App Configuration</h3>
           <label class="block text-sm font-medium mb-2">App Name</label>
           <input type="text" class="w-full p-2 border rounded mb-4" placeholder="Enter App Name">
           
           <label class="block text-sm font-medium mb-2">Base Site URL</label>
           <input type="text" class="w-full p-2 border rounded mb-4" placeholder="Enter API URL">
           
           <label class="block text-sm font-medium mb-2">Primary Color</label>
           <input type="color" class="w-full h-10 mb-4">
           <label class="block text-sm font-medium text-gray-700 mb-2">UIKing API Token</label>
                <input type="text" class="w-full p-2 border rounded mb-4" placeholder="Enter your API token">
                <p class="text-xs text-gray-500">Don't have one? <a href="#" class="text-blue-500 underline">Register your business</a></p>
           <button class="bg-blue-500 text-white px-4 py-2 rounded w-full mt-4">Save Changes</button>
       </div>
       
       <!-- Feature Toggles -->
       <div class="bg-white rounded shadow p-6">
           <h3 class="text-lg font-semibold mb-4">Feature Toggles</h3>
           <div class="flex justify-between items-center mb-3">
               <span>Enable Airtime Purchase</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
           </div>
           <div class="flex justify-between items-center mb-3">
               <span>Enable Data Purchase</span>
                                       <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
           </div>
           <div class="flex justify-between items-center mb-3">
               <span>Enable Bill Payment</span>
                                       <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
           </div>
           <div class="flex justify-between items-center">
               <span>Enable Gifting Users</span>
                                       <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
           </div>
       </div>
   </div>

   </div>
</x-admin-layout>