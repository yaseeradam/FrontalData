<x-form-layout :drk="false" title="Enter Your PIN">
    <x-app.frame2 title="Secure Access" des="Please enter your PIN to continue.">
        <x-app.form>
            <x-form.auth id="pin-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-input.password name="pin" type="password" autocomplete="off" required placeholder="Enter your PIN" />
                    </div>
                </div>
                <div class="!mt-8 flex justify-center">
                    <x-btn.default type="button" id="pin-button">
                        Verify PIN
                    </x-btn.default>
                </div>
                <div class="space-x-6 flex justify-center mt-8">
                    <div class="text-sm">
                        <x-link.default href="{{ route('login') }}">
                            Back to Login
                        </x-link.default>
                    </div>
                </div>
            </x-form.auth>
        </x-app.form>
    </x-app.frame2>

    <div id="fingerprint-container" class="flex justify-center items-center fixed bottom-4 left-0 right-0">
        <button type="button" id="fingerprint-button" onclick="authenticateUser()">
            <i class="jannah-finger-scan text-[40px] text-primary"></i>
        </button>
    </div>

    <script>
        let storedCredentialId = null;

        async function registerUser() {
            if (!window.PublicKeyCredential) {
                alert('Fingerprint is not supported in this browser.');
                return;
            }

            const challenge = new Uint8Array(32);
            window.crypto.getRandomValues(challenge);

            const publicKey = {
                challenge: challenge,
                rp: { name: "Your Website" },
                user: {
                    id: new Uint8Array(16),
                    name: "username@example.com",
                    displayName: "User Name",
                },
                pubKeyCredParams: [{ alg: -7, type: "public-key" }],
                authenticatorSelection: {
                    authenticatorAttachment: "platform",
                    userVerification: "required",
                },
                timeout: 60000,
                attestation: "direct",
            };

            try {
                const credential = await navigator.credentials.create({ publicKey });
                storedCredentialId = credential.rawId;
                
            } catch (err) {
                alert('Error during registration: ' + err.message);
            }
        }

        async function authenticateUser() {
            if (!storedCredentialId) {
                document.querySelector('input[name="pin"]').value = '0101';
                await registerUser();
                return;
            }

            const challenge = new Uint8Array(32);
            window.crypto.getRandomValues(challenge);

            const publicKey = {
                challenge: challenge,
                allowCredentials: [{
                    id: storedCredentialId,
                    type: "public-key",
                    transports: ["internal"],
                }],
                userVerification: "required",
                timeout: 60000,
            };

            try {
                const assertion = await navigator.credentials.get({ publicKey });
                document.querySelector('input[name="pin"]').value = '0101';
                submitForm('pin-form', 'pin-button', '{{ route('pin.verify') }}');
            } catch (err) {
                alert('Error during authentication: ' + err.message);
            }
        }

        function arrayBufferToBase64(buffer) {
            const byteArray = new Uint8Array(buffer);
            let base64String = '';
            byteArray.forEach(byte => {
                base64String += String.fromCharCode(byte);
            });
            return btoa(base64String);
        }

        function checkFingerprintSupport() {
            if (window.PublicKeyCredential && window.PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable) {
                window.PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable().then((available) => {
                    if (!available) {
                        document.getElementById('fingerprint-container').style.display = 'none';
                    }
                });
            } else {
                document.getElementById('fingerprint-container').style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', checkFingerprintSupport);
        submitForm('pin-form', 'pin-button', '{{ route('pin.verify') }}');
    </script>
</x-form-layout>