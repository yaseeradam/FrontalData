<x-admin-layout :drk="true" title="Monetization">
    <x-admin.header />
    <x-admin.side />
    <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded shadow p-6">
                <h2 class="text-xl font-bold mb-4">API Integration</h2>
                <label class="block text-sm font-medium text-gray-700 mb-2">UIKing API Token</label>
                <input type="text" class="w-full p-2 border rounded mb-4" placeholder="Enter your API token">
                <p class="text-xs text-gray-500">Don't have one? <a href="#" class="text-blue-500 underline">Register your business</a></p>
            </div>
            <div class="bg-white rounded shadow p-6">
                <h2 class="text-xl font-bold mb-4">Monetization Options</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span>In-App Advertisements</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Crypto Block Rewards</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Affiliate Marketing</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Data API Bonus</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded shadow p-6 md:col-span-2">
                <h2 class="text-xl font-bold mb-4">How You Earn</h2>
                <p class="text-gray-600">Maximize your revenue by enabling multiple monetization methods. Earn through ad impressions, crypto block rewards, affiliate referrals, and lots more. Optimize your earnings with our flexible monetization features.</p>
            </div>
        </div>
    </div>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #4CAF50;
        }
        input:checked + .slider:before {
            transform: translateX(20px);
        }
    </style>
</x-admin-layout>