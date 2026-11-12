<x-app-layout :user="$user">
    @php
        $billerLower = strtolower(trim($transaction->biller));
        $isBonus = in_array($billerLower, ['registeration bonus', 'registration bonus', 'signup bonus', 'bonus']);
        $iconFile = $isBonus ? 'bonus.png' : $billerLower . '.png';
        $displayTitle = $isBonus ? 'Registration Bonus - Received' : strtoupper($transaction->biller) . ' - ' . $transaction->product;
    @endphp
    <div class="col-span-full md:col-span-3">
        
        <x-app.back-header title="Transaction Details" fallback="{{ route('core.transactions') }}" />
        <div id="capture">
        <div class="flex justify-center relative z-10">
            <img src="@basecdn('images/' . $iconFile)" onerror="this.onerror=null;this.src='{{ asset('images/bonus.png') }}';" class="rounded-full w-12 h-12 object-cover shadow-sm" />
        </div>
        
        <div class="bg-white rounded-xl">
            <h5 class="text-center text-sm font-bold text-slate-800">{{ $displayTitle }}</h5>
            <p class="text-center text-xl font-bold my-2">
                @if (preg_match('/\d+(\.\d+)?[MBGT]{2}/', $transaction->description, $matches))
                    {{ $matches[0] }}
                @elseif ($transaction->quantity)
                   {{ $transaction->quantity }}
                @else
                    ₦{{ number_format($transaction->amount, 2) }}
                @endif

            </p>

            @if ($transaction->status == "successful")
                <p class="text-center text-green-500 text-xs">Successful</p>
                <div class="flex justify-around items-center my-4 relative">
                    <div class="flex-1 flex flex-col items-center">
                        <div class="relative w-full flex justify-center">
                            <i class="ri-checkbox-circle-fill text-green-500 text-2xl" style="position:relative; left: 28px"></i>
                            <hr class="w-full border-t-2 border-green-500 mt-4" style="position:relative; left: 30px">
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-xs">Initiated</p>
                            <p class="text-2xs text-muted">@time($transaction->created_at)</p>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="relative w-full flex justify-center">
                            <i class="ri-checkbox-circle-fill text-green-500 text-2xl" style="position:relative; left: 36px"></i>
                            <hr class="w-20 border-t-2 border-green-500 mt-4" style="position:relative; left: 38px">
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-xs">Processing</p>
                            <p class="text-2xs text-muted-foreground">@time($transaction->created_at)</p>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="relative w-full flex justify-center">
                            <i class="ri-checkbox-circle-fill text-green-500 text-2xl" style="position:relative; left: 2px"></i>
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-xs">Completed</p>
                            <p class="text-2xs text-muted-foreground">@time($transaction->updated_at)</p>
                        </div>
                    </div>
                </div>
                <p class="flex text-2xs text-muted-foreground bg-gray-100 p-2 rounded-xl mb-4 justify-center shadow-sm transition-all duration-500 hover:scale-105">The recipient number is expected to be credited within 3 minutes, subject to notification by the service provider.</p>
            @elseif ($transaction->status == "processing")
                <p class="text-center text-yellow-500 text-xs">Processing...</p>
                <div class="flex justify-around items-center my-4 relative">
                    <div class="flex-1 flex flex-col items-center">
                        <div class="relative w-full flex justify-center">
                            <i class="ri-checkbox-circle-fill text-green-500 text-2xl" style="position:relative; left: 28px"></i>
                            <hr class="w-full border-t-2 border-green-500 mt-4" style="position:relative; left: 30px">
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-xs">Initiated</p>
                            <p class="text-2xs text-muted">@time($transaction->created_at)</p>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="relative w-full flex justify-center">
                            <i class="ri-alarm-fill text-yellow-500 text-2xl" style="position:relative; left: 2px"></i>
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-xs text-yellow-500">Processing</p>
                            <p class="text-2xs text-muted-foreground">@time($transaction->created_at)</p>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col items-center"></div>
                </div>
                <p class="flex text-2xs text-muted-foreground bg-gray-100 p-2 rounded-xl mb-4 justify-center shadow-sm transition-all duration-500 hover:scale-105">Due to network congestion, your transaction is being delayed. Kindly contact customer support for further assistance.</p>
            @elseif ($transaction->status == "failed")
                <p class="text-center text-red-500 text-xs"><i class="ri-close-circle-fill text-lg mt-2"></i>Failed</p>
                <p class="flex text-2xs text-muted-foreground bg-red-100 p-2 rounded-xl mb-4 justify-center shadow-sm transition-all duration-500 hover:scale-105">We couldn't complete your transaction at the moment. Kindly try again shortly.</p>
            @endif

            <div class="border-t border-muted py-2">
                <div class="flex justify-between py-1">
                    <span class="text-xs">Biller</span>
                    <span class="text-xs">@uppercase($transaction->biller)</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-xs">Product</span>
                    <span class="text-xs">{{ $transaction->product }}</span>
                </div>
                @if(!empty($transaction->quantity))
                <div class="flex justify-between py-1 gap-2">
                    <span class="text-xs">{{ strtolower((string) $transaction->product) === 'data' ? 'Data Quantity' : 'Plan' }}</span>
                    <span class="text-xs text-right">{{ $transaction->quantity }}</span>
                </div>
                @endif
                <div class="flex justify-between py-1">
                    <span class="text-xs">Amount</span>
                    <span class="text-xs">₦{{ number_format($transaction->amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-xs">Number ID</span>
                    <span class="text-xs">{{ $transaction->number }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl mt-4 relative bottom-5 z-1">
            <h3 class="text-sm font-semibold mb-2">Transaction Details</h3>
            <div class="border-t border-muted py-2">
                <div class="flex justify-between py-1">
                    <span class="text-xs">Transaction Date</span>
                    <span class="text-xs">@date($transaction->created_at)</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-xs">Session ID</span>
                    <span class="text-xs copy">{{ $transaction->session_id }}</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-4 mb-4 relative bottom-5">
            <button onclick="reportIssue()" id="mm" class="border-t text-sm text-primary border-primary py-3 px-4 rounded-full shadow-sm transition-all duration-500 hover:scale-105">Report an Issue</button>
            <button id="downloadButton" class="bg-primary text-sm text-white py-3 px-4 rounded-full shadow-sm transition-all duration-500 hover:scale-105">Share Receipt</button>
        </div>
        <br>
        </div>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    
    <script>
        const downloadButton = document.getElementById('downloadButton');
        const captureSection = document.getElementById('capture');
        const idsToExclude = ['mm', 'downloadButton'];

        downloadButton.addEventListener('click', function() {
            downloadButton.textContent = 'Downloading...';
            html2canvas(captureSection, {
                onclone: function(clonedDoc) {
                    idsToExclude.forEach(id => {
                        const elementToExclude = clonedDoc.getElementById(id);
                        if (elementToExclude) {
                            elementToExclude.style.display = 'none'; 
                        }
                    });
                },
                useCORS: true 
            }).then(canvas => {
                if (canvas.width > 0 && canvas.height > 0) {
                    const link = document.createElement('a');
                    link.href = canvas.toDataURL("image/png");
                    link.download = 'receipt-{{ $transaction->session_id }}.png';
                    link.click();
                    downloadButton.textContent = 'Downloaded';
                    downloadButton.disabled = true;
                } else {
                    console.error('Canvas is empty.');
                    downloadButton.textContent = 'Download Failed';
                }
            }).catch(error => {
                console.error('Error capturing screenshot:', error);
                downloadButton.textContent = 'Download Failed';
            });
        });

        const clipboard = new ClipboardJS('.copy', {
            target: function(trigger) {
                return trigger.previousElementSibling;
            }
        });

        clipboard.on('success', function(e) {
            e.clearSelection();
            Toastify({
                text: "Reference copied to clipboard!",
                background: "linear-gradient(to right, #FFC107, #FFEB3B)",
                className: "info",
                duration: 3000
            }).showToast();
        });

        clipboard.on('error', function(e) {
            Toastify({
                text: "Failed to copy reference.",
                background: "linear-gradient(to right, #FF5722, #F44336)",
                className: "error",
                duration: 3000
            }).showToast();
        });

        function reportIssue() {
            Toastify({
                text: "Service Temporary Unavailable.",
                background: "linear-gradient(to right, #FF5722, #F44336)",
                className: "error",
                duration: 3000
            }).showToast();
        }
    </script>
</x-app-layout>
