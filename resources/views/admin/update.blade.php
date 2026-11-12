<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">

            <div class="mb-8">
                @if(!empty($updateError))
                    <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 p-3 text-sm text-yellow-800">
                        {{ $updateError }}
                    </div>
                @endif
                @if($updateAvailable)
                    <p class="text-green-600 pt-1 pb-1 text-sm">New Version Available: <strong>{{ $latestVersion }}</strong></p>
                    <p class=" pt-1 pb-1 text-sm text-red-600">Current Version: <strong>{{ $currentVersion }}</strong></p>
                    <x-admin.btn.default id="update">Update Now</x-admin.btn-default>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
    const ub = document.getElementById('update');
    const pc = document.getElementById('update-progress');
    const fsText = document.getElementById('fileSize');
    let iv;

    ub.addEventListener('click', function () {
        ub.textContent = 'Initializing...';
        ub.disabled = true;

        fetch('{{ route("admin.update.init") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(rs => rs.json())
        .then(dt => {
            if (dt.status === 'success') {
                fsText.textContent = dt.fileSize;
                pc.style.display = 'block';

                let tt = Math.max(Number(dt.estimatedTime || 1000), 1000);
                let st = Date.now();
                let pr = 0;

                iv = setInterval(() => {
                    let et = Date.now() - st;
                    pr = Math.min((et / tt) * 95, 95);
                    up(Math.round(pr));
                }, 1000);

                ps();
            } else {
                ub.textContent = dt.message || 'Error';
                ub.disabled = false;
            }
        })
        .catch(() => {
            ub.textContent = 'Error';
            ub.disabled = false;
        });
    });

    function up(pr) {
        const pb = document.querySelector('.progress-bar');
        const pt = document.querySelector('.progress-text');
        pb.style.width = pr + '%';
        pt.textContent = pr + '%';
    }

    function ps() {
        const ci = setInterval(() => {
            fetch('{{ route("admin.update.status") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                }
            })
            .then(rs => rs.json())
            .then(dt => {
                if (dt.status === 'success') {
                    clearInterval(ci);
                    if (iv) {
                        clearInterval(iv);
                    }
                    ub.textContent = 'Update Complete';
                    up(100);
                    setTimeout(() => {
                        document.querySelector('.progress-bar').style.backgroundColor = 'green';
                        document.querySelector('.progress-text').style.color = 'green';
                        setTimeout(() => location.reload(), 3000);
                    }, 1000);
                } else if (dt.status === 'error') {
                    clearInterval(ci);
                    if (iv) {
                        clearInterval(iv);
                    }
                    ub.textContent = dt.message || 'Error';
                    ub.disabled = false;
                } else if (dt.message) {
                    ub.textContent = dt.message;
                }
            });
        }, 5000);
    }
});
                    </script>
                    <div id="update-progress" style="display: none;" class="p-1 pt-2 pb-2">
    <div class="mb-2 flex justify-between items-center">
        <div class="flex items-center gap-x-3">
            <span class="size-8 flex justify-center items-center border border-gray-200 text-gray-500 rounded-lg dark:border-neutral-700 dark:text-neutral-500">
                <!-- SVG Icon -->
            </span>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">Version {{ $latestVersion }}</p>
                <p class="text-xs text-gray-500 dark:text-neutral-500">Update Size: <span id="fileSize"></span></p>
            </div>
        </div>
         <div class="inline-flex items-center gap-x-2">
      <button type="button" class="relative text-gray-500 hover:text-gray-800 focus:outline-none focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-500 dark:hover:text-neutral-200 dark:focus:text-neutral-200">
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect width="4" height="16" x="6" y="4"></rect>
          <rect width="4" height="16" x="14" y="4"></rect>
        </svg>
        <span class="sr-only">Pause</span>
      </button>
      <button type="button" class="relative text-gray-500 hover:text-gray-800 focus:outline-none focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-500 dark:hover:text stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 6h18"></path>
          <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
          <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
          <line x1="10" x2="10" y1="11" y2="17"></line>
          <line x1="14" x2="14" y1="11" y2="17"></line>
        </svg>
        <span class="sr-only">Delete</span>
      </button>
    </div>
    </div>

    <!-- Progress Bar -->
    <div class="flex items-center gap-x-3 whitespace-nowrap">
        <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar">
            <div class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap progress-bar" style="width: 0%;"></div>
        </div>
        <div class="w-6 text-end">
            <span class="text-sm text-gray-800 dark:text-white progress-text">0%</span>
        </div>
    </div>
</div>
                @else
                    <p class="text-green-600 text-center font-bold pt-1 pb-1 text-lg">Your software is up to date.</p>
                    <p class=" pt-1 pb-1 text-center text-sm text-green-600">Current Version: <strong>{{ $currentVersion }}</strong></p>
                @endif
            </div>
            
            
            </div>
            
             <div class="bg-white rounded shadow p-6 overflow-x-auto">
           <div class="timeline">
                @foreach($versions as $version => $details)
                    <div>
                        <div class="ps-2 my-2 first:mt-0">
                            <h3 class="text-xs font-medium uppercase text-gray-500 dark:text-neutral-400">
                                {{ $details['released'] }}
                            </h3>
                        </div>

                        <div class="flex gap-x-3">
                            <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-neutral-700">
                                <div class="relative size-7 flex justify-center items-center">
                                    <div class="size-2 rounded-full bg-gray-400 dark:bg-neutral-600"></div>
                                </div>
                            </div>

                            <div class="grow pt-0.5 pb-8">
                                <h3 class="font-bold text-gray-950 dark:text-white text-sm leading-snug">
                                    Version {{ $version }} - {{ $details['description'] }}
                                </h3>
                                <ul class="mt-2 text-sm text-gray-800 dark:text-neutral-200 leading-relaxed">
                                    @foreach($details['updates'] as $update)
                                        <li>{{ $update }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
   
      </div>
  
    </div>
        </div>
</x-admin-layout> 
