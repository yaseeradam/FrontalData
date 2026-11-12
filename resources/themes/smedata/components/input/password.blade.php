@php
    $primary = jannah_opt('primary_color');
    $label = $label ?? 'Password'; 
@endphp

<div class="relative mt-6">
    <input id="passwordField" type="password" placeholder=" "
        {{ $attributes->merge(['class' => "peer w-full px-4 py-[15px] bg-transparent border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[$primary] focus:border-transparent"]) }}>
    
    <label for="passwordField"
        class="absolute left-3 top-0 -translate-y-1/2 peer-focus:bg-white px-1 text-gray-600 text-xs transition-all 
                            peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:text-gray-400 
                            peer-placeholder-shown:text-base peer-focus:top-0 peer-focus:-translate-y-1/2 peer-focus:text-[{{ $primary }}] 
                            peer-focus:text-xs peer-not-placeholder-shown:top-0 peer-not-placeholder-shown:-translate-y-1/2 
                            peer-not-placeholder-shown:text-[{{ $primary }}] peer-not-placeholder-shown:text-xs">
        {{ $label }}
    </label>

    <i id="togglePassword" class="ri-eye-off-line absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-600 text-xl"></i>
</div>  

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('passwordField');

        togglePassword.addEventListener('click', function () {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                togglePassword.classList.remove('ri-eye-off-line');
                togglePassword.classList.add('ri-eye-line');
            } else {
                passwordField.type = 'password';
                togglePassword.classList.remove('ri-eye-line');
                togglePassword.classList.add('ri-eye-off-line');
            }
        });
    });
</script>