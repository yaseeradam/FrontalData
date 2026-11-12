@php $primary = jannah_opt('primary_color'); @endphp

<div class="relative flex flex-col w-full p-2 md:col-span-3"> 
    <div class="relative bg-primary rounded-[50px] text-white p-4 shadow-xl transition-all duration-500 hover:scale-105">
        <div class="flex flex-col items-center justify-center space-y-1">
            <p class="text-sm flex items-center gap-1">
                <span class="mr-1"></span> Available Balance
                <span class="text-sm cursor-pointer" id="eye" onclick="toggleBalance()">
                    <i class="jannah-eye"></i>
                </span>
            </p>
            <p class="text-2xl font-bold flex items-center gap-2">
                ₦<span id="balance">@numberformat($balance)</span>
            </p>
            <button onclick="load('{{ route('core.deposit') }}')" class="bg-white/20 text-white px-12 py-3 rounded-full text-sm font-semibold transition-all duration-300 hover:bg-white/30">
                + Add Money
            </button>
        </div>
    </div><div class="relative bottom-8 -z-20">
    <div class="absolute w-[50px] h-[50px] mn-blob rounded-full -left-16 -top-10 -z-30 absolute top-20 hover:scale-105"></div>
    <div class="-z-20 relative bg-white/10 text-black p-4 rounded-b-[50px] pt-12 transition-all duration-500 hover:scale-105">
        @foreach ($transactions as $transaction)
            <x-block.transaction :transaction="$transaction" />
        @endforeach
    </div>
</div>

</div><script>
    function toggleBalance() {
        let balance = document.getElementById("balance");
        let eye = document.getElementById("eye").querySelector("i");
        
        if (balance.style.filter === "blur(5px)") {
            balance.style.filter = "none";
            eye.classList.remove("jannah-eye-slash");
            eye.classList.add("jannah-eye");
        } else {
            balance.style.filter = "blur(5px)";
            eye.classList.remove("jannah-eye");
            eye.classList.add("jannah-eye-slash");
        }
    }
</script>