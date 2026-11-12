<x-dev-layout :user="$user" title="Cable Subscription API">
    <div class="my-4 font-bold text-xl">Cable Subscription Integration</div>
    <div class=" md:p-4 overflow-x-auto">
        <div class="md:col-span-5 border border-lg shadow shadow-md rounded-[40px] w-full p-4 md:p-12 overflow-x-auto">
      
        <div class="mb-4 font-semibold text-lg">Overview</div>

        <div>
            This section outlines the recommended flow for integrating Cable Subscription services on the @jannah_opt('site_name') RESTful API.
        </div>

        <x-block.hr />
        <x-block.title>Available Endpoints</x-block.title>

        <div>
            To integrate the @jannah_opt('site_name') Cable Subscription Payment RESTful API, the following parameters apply:
        </div>
        
        <ul class="mt-4 list-disc list-inside">
            <li><strong>cable</strong> - The cable provider ID</li>
            <li><strong>iuc</strong> - The decoder IUC number</li>
            <li><strong>cable_plan</strong> - The selected plan ID</li>
           
        </ul>

        <x-block.hr />
        <x-block.title>Purchase Product</x-block.title>
        
        <div>
            To purchase a cable bundle, use a <strong>POST</strong> request with the endpoint below:
        </div>

        <x-block.link>
            @jannah_opt('site_url')/api/cable
        </x-block.link>

        <x-block.hr />
        <x-block.title>Code Sample</x-block.title>

        <x-block.code>
        $payload = array(
            'cable' => 1,
            'iuc' => '080*****112',
            'cable_plan' => 1,
            '
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, '@jannah_opt('site_url')/api/cable');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $headers = [
            "Authorization: Bearer {{ $user->api_token }}",
            'Content-Type: application/json'
        ];
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        </x-block.code>

        <x-block.hr />
        <x-block.title>Expected Response</x-block.title>

        <x-block.code>
        {
            "status": "success",
            "message": "Successfully purchased DSTV joli joli ₦12000 to 070*******66"
        }
        </x-block.code>
    </div>
    
    </div>
</x-dev-layout>

