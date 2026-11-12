<x-dev-layout :user="$user" title="Meter Number Verification API">
    <div class="my-4 font-bold text-xl">Meter Number Verification Integration</div>
    <div class="md:col-span-5 border border-lg shadow shadow-md rounded-[40px] w-full p-12 mx-4">
        
        <div class="mb-4 font-semibold text-lg">Overview</div>
        <div>
            This section outlines the recommended flow for integrating Meter Number Verification services on the @jannah_opt('site_name') RESTful API.
        </div>

        <x-block.hr />
        <x-block.title>Available Endpoints</x-block.title>
        <div>
            To integrate the @jannah_opt('site_name') Meter Number Verification API, the following parameters apply:
        </div>
        
        <ul class="mt-4 list-disc list-inside">
            <li><strong>meter_type</strong> - The meter type (prepaid or postpaid)</li>
            <li><strong>disco</strong> - The electricity provider ID</li>
            <li><strong>meter_number</strong> - The customer's meter number</li>
        </ul>

        <x-block.hr />
        <x-block.title>Verify Meter Number</x-block.title>
        
        <div>
            To verify a meter number, use a <strong>GET</strong> request with the endpoint below:
        </div>

        <x-block.link>
            @jannah_opt('site_url')/api/bill/bill-validation?meter_number=12345555&disco=1&meter_type=postpaid
        </x-block.link>

        <x-block.hr />
        <x-block.title>Code Sample</x-block.title>

        <x-block.code>
        $url = "@jannah_opt('site_url')/api/bill/bill-validation?meter_number=12345555&disco=1&meter_type=postpaid";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
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
            "name": "UIKING CO"
        }
        </x-block.code>
    </div>
</x-dev-layout>
