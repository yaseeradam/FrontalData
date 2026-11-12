<x-dev-layout :user="$user" title="Electricity Bill Subscription API">
    <div class="my-4 font-bold text-xl">Electricity Bill Subscription Integration</div>
    <div class="md:col-span-5 border border-lg shadow shadow-md rounded-[40px] w-full p-12 mx-4">
        
        <div class="mb-4 font-semibold text-lg">Overview</div>

        <div>
            This section outlines the recommended flow for integrating Electricity Bill Subscription services on the @jannah_opt('site_name') RESTful API.
        </div>

        <x-block.hr />
        <x-block.title>Available Endpoints</x-block.title>

        <div>
            To integrate the @jannah_opt('site_name') Electricity Bill Subscription Payment RESTful API, the following parameters apply:
        </div>
        
        <ul class="mt-4 list-disc list-inside">
            <li><strong>disco</strong> - The electricity provider ID</li>
            <li><strong>meter_type</strong> - The type of meter (prepaid/postpaid)</li>
            <li><strong>meter_number</strong> - The meter number</li>
            <li><strong>amount</strong> - The amount to be paid</li>
            
        </ul>

        <x-block.hr />
        <x-block.title>Purchase Product</x-block.title>
        
        <div>
            To purchase an electricity bill bundle, use a <strong>POST</strong> request with the endpoint below:
        </div>

        <x-block.link>
            @jannah_opt('site_url')/api/bill
        </x-block.link>

        <x-block.hr />
        <x-block.title>Code Sample</x-block.title>

        <x-block.code>
        $payload = array(
            'disco' => 1,
            'meter_type' => 'prepaid',
            'meter_number' => '080*****112',
            'amount' => 300
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, '@jannah_opt('site_url')/api/bill');
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
            "message": "Transaction successful for electricity bill payment of ₦300 to meter number 070*******66"
        }
        </x-block.code>
    </div>
</x-dev-layout>
