 function back(){
    if (window.history.length > 1) {
        history.back();
    } else {
        window.location.href = '/dashboard';
    }
}
 document.addEventListener("DOMContentLoaded", function() {
    const balanceElement = document.getElementById("balance");
    const ngn = document.getElementById("ngn");
    const eyeIcon = document.getElementById("eye");
    const initialBalance = balanceElement.textContent;
    const ngnS = ngn.textContent;
    function toggleBalance() {
        if (balanceElement.textContent.includes("****")) {
            balanceElement.innerHTML = initialBalance;
            ngn.innerHTML = ngnS;
            eyeIcon.classList.remove("max-eye-slash");
            eyeIcon.classList.add("max-eye");
            setCookie("balanceVisibility", "visible", 7);
        } else {
            balanceElement.innerHTML = '****';
            ngn.innerHTML = '';
            eyeIcon.classList.remove("max-eye");
            eyeIcon.classList.add("max-eye-slash");
            setCookie("balanceVisibility", "hidden", 7);
        }
    }

    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        const name = cname + "=";
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    const balanceVisibility = getCookie("balanceVisibility");
    if (balanceVisibility === "hidden") {
        balanceElement.innerHTML = '****';
        ngn.innerHTML = '';
        eyeIcon.classList.remove("max-eye");
        eyeIcon.classList.add("max-eye-slash");
    } else {
        balanceElement.innerHTML = initialBalance;
        ngn.innerHTML = ngnS;
        eyeIcon.classList.remove("max-eye-slash");
        eyeIcon.classList.add("max-eye");
    }

    window.toggleBalance = toggleBalance;
});
function load(url) {
           window.location.href = url;
       } 
function showResultModal(options) {
    var type = options.type || 'success';
    var title = options.title || (type === 'success' ? 'Success!' : 'Action Failed');
    var message = options.message || '';
    var redirect = options.redirect || null;

    var existing = document.getElementById('global-status-modal');
    if (existing) {
        existing.remove();
    }

    var isSuccess = type === 'success';

    if (!document.getElementById('status-modal-styles')) {
        var styleEl = document.createElement('style');
        styleEl.id = 'status-modal-styles';
        styleEl.innerHTML = `
            @keyframes checkmarkStroke {
                0% { stroke-dashoffset: 30; }
                100% { stroke-dashoffset: 0; }
            }
            .checkmark-svg-path {
                stroke-dasharray: 30;
                stroke-dashoffset: 30;
                animation: checkmarkStroke 0.4s ease-out 0.1s forwards;
            }
            @keyframes modalPulseDot {
                0%, 100% { transform: scale(1); opacity: 1; }
                50% { transform: scale(1.4); opacity: 0.5; }
            }
        `;
        document.head.appendChild(styleEl);
    }

    var modalHtml = `
    <div id="global-status-modal" style="position: fixed; inset: 0; z-index: 999999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); opacity: 0; transition: opacity 0.25s ease;">
        <div style="background: #ffffff; border-radius: 28px; padding: 32px 28px; max-width: 400px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); text-align: center; border: 1px solid rgba(226, 232, 240, 0.9); transform: scale(0.92); transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="width: 80px; height: 80px; margin: 0 auto 20px auto; border-radius: 50%; background: ${isSuccess ? '#ecfdf5' : '#fff1f2'}; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px -5px ${isSuccess ? 'rgba(16, 185, 129, 0.35)' : 'rgba(244, 63, 94, 0.35)'}; border: 4px solid ${isSuccess ? '#d1fae5' : '#ffe4e6'};">
                ${isSuccess ? `
                <svg style="width: 42px; height: 42px; color: #10b981;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" class="checkmark-svg-path"></path>
                </svg>
                ` : `
                <svg style="width: 40px; height: 40px; color: #f43f5e;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                `}
            </div>

            <h3 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0; font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                ${title}
            </h3>

            <p style="font-size: 14px; color: #64748b; line-height: 1.5; margin: 0 0 20px 0; font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                ${message}
            </p>

            ${isSuccess ? (redirect ? `
            <div style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 9999px; background: #f0fdf4; color: #15803d; font-size: 12px; font-weight: 700; border: 1px solid #bbf7d0;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e; display: inline-block; animation: modalPulseDot 1.5s infinite;"></span>
                <span>Redirecting to your dashboard...</span>
            </div>
            ` : '') : `
            <button type="button" onclick="closeResultModal()" style="width: 100%; padding: 13px 24px; border-radius: 16px; background: linear-gradient(135deg, #0f172a, #1e293b); color: #ffffff; font-size: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25); transition: transform 0.15s, opacity 0.15s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                Try Again
            </button>
            `}
        </div>
    </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);

    var modal = document.getElementById('global-status-modal');
    requestAnimationFrame(function() {
        modal.style.opacity = '1';
        var modalContent = modal.querySelector('div');
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
        }
    });

    if (isSuccess && redirect) {
        setTimeout(function() {
            window.location.href = redirect;
        }, 1800);
    }
}

function closeResultModal() {
    var modal = document.getElementById('global-status-modal');
    if (modal) {
        modal.style.opacity = '0';
        var modalContent = modal.querySelector('div');
        if (modalContent) {
            modalContent.style.transform = 'scale(0.92)';
        }
        setTimeout(function() {
            modal.remove();
        }, 250);
    }
}

window.showResultModal = showResultModal;
window.closeResultModal = closeResultModal;

function submitForm(form_id, button_id, route) {
    var triggerBtn = document.getElementById(button_id);
    if (!triggerBtn) return;

    var isAuthForm = (
        form_id === 'login-form' ||
        form_id === 'reg-form' ||
        form_id === 'admin-form' ||
        form_id.indexOf('login') !== -1 ||
        form_id.indexOf('reg') !== -1 ||
        form_id.indexOf('auth') !== -1
    );

    triggerBtn.addEventListener('click', function(e) {
        e.preventDefault();

        var form = document.getElementById(form_id);
        if (!form) return;

        if (!form.dataset.requestId) {
            form.dataset.requestId = (window.crypto && crypto.randomUUID)
                ? crypto.randomUUID()
                : Date.now().toString(36) + Math.random().toString(36).substring(2);
        }

        var formData = new FormData(form);
        if (!formData.has('request_id')) {
            formData.append('request_id', form.dataset.requestId);
        }
        var loadingOverlay = document.getElementById('loading-overlay');
        var isValid = true;
        var missingFields = [];
        var formFields = form.querySelectorAll('input, select, textarea');

        formFields.forEach(function(field) {
            if (!field.classList.contains('optional') && !field.value.trim()) {
                isValid = false;
                missingFields.push(field.name.charAt(0).toUpperCase() + field.name.slice(1));
            }
        });

        if (!isValid) {
            var errorMessage = missingFields.length === formFields.length 
                ? "All inputs are required" 
                : "Kindly enter a valid " + missingFields[0];
            if (loadingOverlay) {
                loadingOverlay.classList.add("hidden");
            }

            if (isAuthForm) {
                showResultModal({
                    type: 'error',
                    title: 'Missing Details',
                    message: errorMessage
                });
            } else {
                Toastify({
                    text: errorMessage,
                    className: "toastify-error",
                    duration: 3000,
                    close: false,
                    gravity: "top",
                    position: "center"
                }).showToast();
            }
            return;
        }

        var button = document.getElementById(button_id);
        var originalText = button.innerHTML;
        button.innerHTML = '<span class="loader"></span>';
        button.disabled = true;

        axios.post(route, formData)
            .then(function(response) {
                var serverMessage = response.data.message || "Action successful!";
                if (loadingOverlay) {
                    loadingOverlay.classList.add("hidden");
                }

                if (isAuthForm) {
                    var title = 'Success!';
                    if (form_id.indexOf('login') !== -1) {
                        title = 'Welcome Back!';
                    } else if (form_id.indexOf('reg') !== -1) {
                        title = 'Account Created!';
                    }
                    showResultModal({
                        type: 'success',
                        title: title,
                        message: serverMessage,
                        redirect: response.data.redirect
                    });
                } else {
                    Toastify({
                        text: serverMessage,
                        className: "toastify-success",
                        duration: 3000,
                        close: false,
                        gravity: "top",
                        position: "center"
                    }).showToast();

                    if (response.data.redirect) {
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 3000);
                    }
                }
            })
            .catch(function(error) {
                var errorMessage = "An error occurred";
                if (error.response && error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                }
                if (error.response && error.response.data && error.response.data.errors) {
                    for (const [key, messages] of Object.entries(error.response.data.errors)) {
                        errorMessage = messages[0];
                        break;
                    }
                }
                if (loadingOverlay) {
                    loadingOverlay.classList.add("hidden");
                }

                if (isAuthForm) {
                    var title = form_id.indexOf('reg') !== -1 ? 'Registration Failed' : 'Login Failed';
                    showResultModal({
                        type: 'error',
                        title: title,
                        message: errorMessage
                    });
                } else {
                    Toastify({
                        text: errorMessage,
                        className: "toastify-error",
                        duration: 3000,
                        close: false,
                        gravity: "top",
                        position: "center"
                    }).showToast();
                }
            })
            .finally(function() {
                button.innerHTML = originalText;
                button.disabled = false;
                if (loadingOverlay) {
                    loadingOverlay.classList.add("hidden");
                }
            });
    });
}

function validate(provider, input_id, button_id, pay_id, route) {
    document.getElementById(button_id).addEventListener('click', function(e) {
        e.preventDefault();
        var button = document.getElementById(button_id);
        var originalText = button.innerHTML;
        button.innerHTML = '<span class="loader"></span>';
        button.disabled = true;

        var inputValue = document.getElementById(input_id).value.trim();
        var providerValue = document.getElementById(provider).value || '';
        var formData = new FormData();
        formData.append('number', inputValue);
        formData.append('provider', providerValue);
        
        axios.post(route, formData)
            .then(function(response) {
                var serverMessage = response.data.message || "Action successful!";
                var verElement = document.getElementById('ver');
                verElement.innerHTML = serverMessage;
                verElement.classList.remove('text-red-500');
                verElement.classList.add('text-green-500');
                document.getElementById(pay_id).classList.remove('hidden');
                button.classList.add('hidden');
            })
            .catch(function(error) {
                var errorMessage = error.response?.data?.message || "An error occurred";
                var verElement = document.getElementById('ver');
                verElement.innerHTML = errorMessage;
                verElement.classList.remove('text-green-500');
                verElement.classList.add('text-red-500');
            })
            .finally(function() {
                button.innerHTML = originalText;
                button.disabled = false;
            });
    });
}


 document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = togglePassword.previousElementSibling;

        togglePassword.addEventListener('click', function() {
            const icon = togglePassword.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('max-eye-slash');
                icon.classList.add('max-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('max-eye');
                icon.classList.add('max-eye-slash');
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
    function copyToClipboard(text) {
    const tempInput = document.createElement('input');
    tempInput.value = text.replace(/\s+/g, '');
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
}

function setupCopyButton(id) {
    const button = document.getElementById(id);
    if (button) {
        button.addEventListener('click', function() {
            const content = document.getElementById('acct').textContent;
            copyToClipboard(content);

            Toastify({
                text: 'Content copied successfully!',
                duration: 3000,
                backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                className: 'toastify-success'
            }).showToast();
        });
    }
}


    setupCopyButton('copy-acct');
    setupCopyButton('acct');
});

  let currentIndex = 0;
  let autoSlideInterval;

  function moveToSlide(index) {
    const slider = document.getElementById('slider');
    const slideWidth = slider.children[0].offsetWidth;
    currentIndex = index;
    slider.style.transform = `translateX(-${slideWidth * index}px)`;
    updateNavDots(index);
    resetAutoSlide();
  }

  function updateNavDots(index) {
    document.querySelectorAll('[id^="nav-dot-"]').forEach(dot => dot.classList.remove('bg-primary'));
    document.getElementById(`nav-dot-${index}`).classList.add('bg-primary');
  }

  function autoSlide() {
    currentIndex = (currentIndex + 1) % 2;
    moveToSlide(currentIndex);
  }

  function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(autoSlide, 5000);
  }

  function initSlider() {
    updateNavDots(0);
    autoSlideInterval = setInterval(autoSlide, 5000);

    const slider = document.getElementById('slider');
    let startX = 0;

    slider.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
    });

    slider.addEventListener('touchend', (e) => {
      const endX = e.changedTouches[0].clientX;
      if (startX > endX + 50) moveToSlide((currentIndex + 1) % 2);
      if (startX < endX - 50) moveToSlide((currentIndex - 1 + 2) % 2);
    });
  }

  window.onload = initSlider;

    
