@php
    $ref = request()->query('ref') ?? '';
    $sitename = jannah_opt('site_name');
@endphp

<x-auth-layout :drk="false" :txt="'Get Started Now ' . $sitename" txt2="Get started absolutely free." txt3="">
            <x-form.auth id="reg-form">
                @csrf
                <div class="space-4 md:grid md:grid-cols-2">
                    <div class="md:mr-4">
                        <x-input.default name="username" type="text" autocomplete="username" required label="Username" />
                    </div>
                    
                    <div>
                        <x-input.default name="ref" type="text" label="Referral Code" class="optional" value="{{ $ref }}" />
                    </div>
                    
                    <div  class="col-span-2">
                        <x-input.default name="email" type="email" autocomplete="email" required label="Email" />
                    </div>
                    
                    <div class="md:mr-4">
                        <x-input.password name="password" type="password" autocomplete="new-password" required placeholder="Enter Your New Password" />
                    </div>
                    
                    
                    
                    <div>
                        <x-input.password id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required label="Confirm Password" />
                    </div>
                    
                    </div>
                   
               
                    <x-btn.default type="button" id="reg-button">
                        Register
                    </x-btn.default>
               <p class="text-center mt-6 text-sm text-gray-500">By registering, I agree to {{ $sitename }} <x-link.default href="">
                          Terms of Service
                        </x-link.default> and <x-link.default href="">
                          Privacy Policy
                        </x-link.default>.</p>
              
                <p class="text-center mt-6 text-sm text-slate-600">
                    Already have an account? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-800 ml-1">Sign In</a>
                </p>
            </x-form.auth>

    <script>
        submitForm('reg-form', 'reg-button', '{{ route('register') }}');
    </script>
</x-auth-layout>