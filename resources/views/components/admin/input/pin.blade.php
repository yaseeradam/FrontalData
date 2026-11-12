<div class="flex gap-x-3 justify-center p-4" data-hs-pin-input="">
    @for ($i = 0; $i < $number; $i++)
        <input type="text"
               id="{{ $name }}{{ $i }}"
               name="{{ $name }}[]"
               class="block w-[{{ $width }}] text-center border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
               data-hs-pin-input-item=""
               maxlength="1"
               autofocus="{{ $i === 0 ? 'autofocus' : '' }}"
               oninput="moveToNext(this, {{ $i }}, {{ $number }})">
    @endfor
    <input type="hidden" id="{{ $name }}_hidden" name="{{ $name }}" value="">
</div>

<script>
    function moveToNext(currentInput, index, total) {
        const value = currentInput.value;
        if (value.length > 1) {
            currentInput.value = value.slice(0, 1);
        }
        if (index < total - 1 && value) {
            document.getElementById('{{ $name }}' + (index + 1)).focus();
        }
        const allInputs = Array.from(document.querySelectorAll('[data-hs-pin-input-item]'));
        const pinValue = allInputs.map(input => input.value).join('');
        document.getElementById('{{ $name }}_hidden').value = pinValue;
    }
</script>