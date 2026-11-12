<x-auth-layout>
    <x-auth.box>
      <x-form.auth id="admin-form">
        @csrf
        <h3 class="text-gray-800 text-2xl font-extrabold mb-5">
          Welcome back
        </h3>
        <p class="text-gray-800 text-sm font-light mb-5">
          Hello there, sign in to continue
        </p>
        <div class="space-y-4">
          <div>
            <x-input.default name="email" type="email" autocomplete="email" required placeholder="Email address" />
          </div> 
          <div>
            <x-input.password name="password" type="password" autocomplete="current-password" required placeholder="Password" />
          </div>
        </div>
        <div class="!mt-8">
          <x-btn.default type="button" id="admin-button">
            Log in
          </x-btn.default>
        </div>
      </x-form.auth>
</x-auth.box>
  <script>
    submitForm('admin-form', 'admin-button', '{{ route('admin.login') }}');
  </script>
</x-auth-layout>
