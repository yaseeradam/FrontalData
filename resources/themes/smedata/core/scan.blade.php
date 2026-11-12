<x-app-layout :drk="true" title="Scan To Pay">
    <x-app.frame title="Scan To Pay">
        <div class="flex flex-col items-center justify-center h-full">
            <div class="relative w-full max-w-sm h-full border-2 border-dashed border-[@jannah_opt('primary_color')] rounded-xl overflow-hidden">
                <canvas id="qr-canvas" class="w-full h-full"></canvas>
            </div>
            <x-btn.default class="mt-8">
                Pay Now
            </x-btn.default>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
        <script>
            const video = document.createElement('video');
            const canvasElement = document.getElementById('qr-canvas');
            const canvas = canvasElement.getContext('2d');

            function startCamera() {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(function(stream) {
                        video.srcObject = stream;
                        video.setAttribute('playsinline', true);
                        video.play();
                        requestAnimationFrame(tick);
                    });
            }

            function tick() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvasElement.height = video.videoHeight;
                    canvasElement.width = video.videoWidth;
                    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                    
                    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: 'dontInvert',
                    });

                    if (code) {
                        window.location.href = code.data;
                    } else {
                        requestAnimationFrame(tick);
                    }
                } else {
                    requestAnimationFrame(tick);
                }
            }

            startCamera();
        </script>
    </x-app.frame>
</x-app-layout>