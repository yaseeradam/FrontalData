<x-dev-layout :user="$user" title="Developer API">
    <div class="my-4 font-bold text-xl">Data Subscription Integration</div>
    <div class="md:col-span-5 border border-lg shadow shadow-md rounded-[40px] w-full p-12 mx-4">
        
        <div class="mb-4 font-semibold text-lg">Overview</div>

        <div>
            This section outlines the recommended flow for integrating Data Subscription services on the @jannah_opt('site_name') RESTful API.
        </div>

        <x-block.hr />
        <x-block.title>Available Endpoints</x-block.title>

        <div>
            To integrate the @jannah_opt('site_name') Data Subscription Payment RESTful API, the following parameters apply:
        </div>
        
        <ul class="mt-4 list-disc list-inside">
            <li><strong>network</strong> - The network provider</li>
            <li><strong>phone</strong> - The recipient's phone number</li>
            <li><strong>data_plan</strong> - The selected data plan</li>
            <li><strong>bypass</strong> (boolean) - Whether to bypass verification</li>
           
        </ul>

        <x-block.hr />
        <x-block.title>Purchase Product</x-block.title>

        <div class="my-4">
            To purchase a data bundle, use a <strong>POST</strong> request with the endpoint below:
        </div>

        <x-block.link>
            @jannah_opt('site_url')/api/data
        </x-block.link>

        <x-block.hr />
        <x-block.title>Code Sample</x-block.title>

        <x-block.code>
        $payload = array(
            'network' => 1,
            'phone' => '080*****111',
            'data_plan' => 1,
            'bypass' => false,
            
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, '@jannah_opt('site_url')/api/data');
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
            "message": "Yello! You have gifted 500MB to 23470*****088. Share link https://mtnapp.page.link/myMTNNGApp with 23470*****088 to download the new MyMTN app for exciting offers.",
        }
        </x-block.code>
    </div>
</x-dev-layout>

