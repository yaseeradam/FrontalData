<x-dev-layout :user="$user" title="IUC Verification API">
    <div class="my-4 font-bold text-xl">IUC Verification Integration</div>
    <div class="md:col-span-5 border border-lg shadow shadow-md rounded-[40px] w-full p-12 mx-4">
        
        <div class="mb-4 font-semibold text-lg">Overview</div>
        <div>
            This section outlines the recommended flow for integrating IUC Verification services on the @jannah_opt('site_name') RESTful API.
        </div>
        
        <x-block.hr />
        <x-block.title>Available Endpoints</x-block.title>
        <div>
            To integrate the @jannah_opt('site_name') IUC Verification Payment RESTful API, the following parameters apply:
        </div>
        
        <ul class="mt-4 list-disc list-inside">
            <li><strong>iuc</strong> - The decoder IUC number</li>
            <li><strong>cable</strong> - The cable provider ID</li>
        </ul>
        
        <x-block.hr />
        <x-block.title>Verify IUC Number</x-block.title>
        <div>
            To verify an IUC number, use a <strong>GET</strong> request with the endpoint below:
        </div>
        
        <x-block.link>
            @jannah_opt('site_url')/api/cable/cable-validation?iuc=12345555&cable=1
        </x-block.link>
        
        <x-block.hr />
        <x-block.title>Code Sample</x-block.title>
        <x-block.code>
        $url = '@jannah_opt('site_url')/api/cable/cable-validation?iuc=12345555&cable=1';
        
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
