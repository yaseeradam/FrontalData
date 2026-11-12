<x-dev-layout :user="$user" title="Developer API">
    <div class="my-4 font-bold text-xl">Airtime Subscription Integration</div>
    <div class="md:col-span-5 border border-lg shadow shadow-md rounded-[40px] w-full p-12 mx-4">
        
        <div class="mb-4 font-semibold text-lg">Overview</div>

        <div>
            This section outlines the recommended flow for integrating Airtime Subscription services on the @jannah_opt('site_name') RESTful API.
        </div>

        <x-block.hr />
        <x-block.title>Available Endpoints</x-block.title>

        <div>
            To integrate the @jannah_opt('site_name') Airtime Subscription Payment RESTful API, the following parameters apply:
        </div>
        
        <ul class="mt-4 list-disc list-inside">
            <li><strong>network</strong> - The network provider</li>
            <li><strong>phone</strong> - The recipient's phone number</li>
            <li><strong>plan_type</strong> - The type of airtime plan (e.g., VTU)</li>
            <li><strong>amount</strong> - The amount of airtime</li>
            
        </ul>

        <x-block.hr />
        <x-block.title>Purchase Product</x-block.title>

        <div>
            To purchase airtime, use a <strong>POST</strong> request with the endpoint below:
        </div>

        <x-block.link>
            @jannah_opt('site_url')/api/topup
        </x-block.link>

        <x-block.hr />
        <x-block.title>Code Sample</x-block.title>

        <x-block.code>
        $payload = array(
            'network' => 1,
            'phone' => '0801*****11',
            'plan_type' => 'VTU',
            'amount' => 100,
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, '@jannah_opt('site_url')/api/topup');
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
            "message": "Successfully purchased MTN VTU for 0701*****88, ₦100",
        }
        </x-block.code>
    </div>
</x-dev-layout>

