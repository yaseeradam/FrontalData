<x-dev-layout :user="$user" title="Developer API">
    <div class="my-4 font-bold text-xl">Introduction</div>
    <div class="md:col-span-5 w-full md:p-4 overflow-x-auto">
        <div class="border border-lg shadow shadow-md rounded-[40px] w-full p-4 md:p-12 overflow-x-auto">
        
        <div class="mb-4 font-semibold text-lg">Introduction</div>

        <div>
            Welcome to @jannah_opt('site_name')! Our API allows you to interact with @jannah_opt('site_name') services seamlessly. You can use it to purchase services and access information from our database.
        </div>

        <div class="mt-4">
            We provide language bindings in PHP, Ruby, Python, React.js, and JavaScript. However, you can use any programming language of your choice to connect to our API endpoints.
        </div>
        
        <div class="mt-4">
            This API documentation is designed to assist you through the integration process efficiently.
        </div>
        
        <div class="mt-4 font-semibold">Thank you for choosing @jannah_opt('site_name')!</div>
        
        <x-block.hr />
        <x-block.title>Authentication</x-block.title>

        <div>
            Our API uses Bearer Authentication to generate an access token. You can find the authentication token in the sample response below.
        </div>
        
        <x-block.hr />
        <x-block.title>Endpoint URL</x-block.title>
        <x-block.link>
            @jannah_opt('site_url')/api/user
        </x-block.link>
        
        <x-block.hr />
        <x-block.title>Code Sample</x-block.title>

        <x-block.code>
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "@jannah_opt('site_url')/api/user");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {{ $user->api_token }}",
            ]
        );
        $json = curl_exec($ch);
        curl_close($ch);
        </x-block.code>
        
        <x-block.hr />
        <x-block.title>Expected Response</x-block.title>

        <x-block.code>
        {
            "status": "success",
            "balance": "{{ $user->wallet_balance }}",
            "username": "{{ $user->username }}"
        }
        </x-block.code>
    </div>
    </div>
    
</x-dev-layout>

