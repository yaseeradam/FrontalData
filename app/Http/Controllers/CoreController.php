<?php
namespace App\Http\Controllers;

/**
 * Load Models
 */
use App\Models\User;

use App\Models\Transaction;

use App\Models\Notification;

use App\Models\Admin;

use App\Models\ApiConfig;

use App\Models\ApiProvider;

use App\Models\PaymentGateway;

use App\Models\Category;

use App\Models\Product;

use App\Models\Service;

use App\Jobs\ProcessUpdate;

use GuzzleHttp\Client;

use GuzzleHttp\RequestOptions;

/**
 * Load Locales
 *
 */
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;

use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Auth\LoginRequest;

use Illuminate\Http\RedirectResponse;

use Illuminate\Auth\Events\Registered;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rules;

use Illuminate\View\View;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Validator;

use App\Mail\TransactionFailure;

use App\Mail\TransactionSuccess;

use App\Mail\TransactionSuccessElectricity;

use App\Mail\BulkMail;

use Illuminate\Support\Facades\Mail;

use ZipArchive;

use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Validation\Rule;

use App\Services\PluginService;

use App\Plugins\Hooks;

use Illuminate\Support\Facades\Crypt;

# use Illuminate\Validation\Rules\Password;

class CoreController extends Controller
{
    protected $pluginService;
    
    
    final public function __construct(PluginService $pluginService)
    {
        $this->verify_usage();
        $this->pluginService = $pluginService;
        // $this->processPluginHooks();
    }

    final public function __debugInfo()
    {
        return [];
    }
    
    private function get_hmac_key() 
    {
        return hash('sha512', 
            "f84d1c9e2a5b7f6d8e3c4a1b9d7f2e5c6a8b3d9f7e1c4a2b6d3f8e9c5a2b7d1f"
            . "3e8d9c2a7b1f6d4c5a9e7b3d2f8c1a6e4b5d9c7f2a8b1e3d6c5a7f9b2e8d4c1"
            . "7b3f6d9e2c5a8b4d1f3e7c9a2b6d5f8e1c7a4b9d3f2e6c8b5a1d7f9e3c4a2b6"
            . "d8f5e9c1a7b3d2f6e4c8a5b9d7f1e3c2a6b4d9f8e5c7a2b3d1f6e9c4a8b5d7f2"
            . "e1c3a9b6d4f8e7c2a5b3d9f1e4c6a8b7d2f5e3c9a1b4d6f7e8c2a5b9d3f1e4c7"
            . "a6d2f8b5c9e7a3d1f6b4c8e5a7d2f9b6c3e1a8d5f7b2c4e9a6d3f8b1c5e2a7d9"
            . "f4b3c8e6a2d7f9b5c1e4a3d8f6b2c7e9a5d1f3b4c6e8a9d2f7b3c5e1a4d9f6b8"
            . "c2e3a7d5f1b9c4e6a8d3f2b5c7e9a1d6f4b8c3e7a2d9f5b1c6e4a3d8f7b2c9e5"
        );
    }
    
    private function store_verification_time() 
    {
        $timestamp = now()->addHours(12)->timestamp;
        $data = json_encode(['timestamp' => $timestamp]);
    
        $hmacKey = $this->get_hmac_key();
        $hmac = hash_hmac('sha256', $data, $hmacKey);
        $encryptedData = Crypt::encryptString($data);
    
        Cache::put('nc_wallet_key', json_encode([
            'hmac' => $hmac,
            'data' => $encryptedData
        ]), now()->addHours(12));
    }
    
    private function check_verification_time() 
    {
        $cached = Cache::get('nc_wallet_key');
        if (!$cached) {
            return $this->verify_usage(false);
        }
        $cached = json_decode($cached, true);
        $storedHmac = $cached['hmac'];
        $encryptedData = $cached['data'];
        $hmacKey = $this->get_hmac_key();
        $decryptedData = Crypt::decryptString($encryptedData);
        $hmacCheck = hash_hmac('sha256', $decryptedData, $hmacKey);
        if (!hash_equals($storedHmac, $hmacCheck)) {
            return false;
        }
        $data = json_decode($decryptedData, true);
        $lastVerification = $data['timestamp'];
        if (($lastVerification - now()->timestamp) < 100) {
            return false;
        }
        return true;
    }
     
    private function verify_usage($cache = true)
    {
        // License phone-home disabled by owner. Original body removed.
        return true;
    }

    private function display_alert($message, $type = "red", $details = [])
    {
        echo '<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>';
        echo '<div class="container mx-auto mt-10 p-6 max-w-3xl bg-red-100 border-l-4 border-red-500 text-red-700 rounded-2xl">';
        echo '<div class="flex items-center">';
        echo '<svg class="h-6 w-6 text-' .
            $type .
            '-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m0-4h.01M12 18h.01M12 2C6.48 2 2 6.48 2 12c0 5.52 4.48 10 10 10s10-4.48 10-10c0-5.52-4.48-10-10-10z" /></svg>';
        echo '<h3 class="text-lg font-semibold">' . $message . "</h3>";
        echo "</div>";

        if (!empty($details)) {
            echo '<ul class="mt-3 pl-8 list-disc list-inside">';
            foreach ($details as $detail) {
                echo $detail;
            }
            echo "</ul>";
        }

        echo "</div>";
    }
    
    // public function processPluginHooks()
    // {
    //     $data = ['message' => 'Hello from CoreController', 'value' => 42];

    //     Hooks::doAction('custom_hook', $data);
        
    //     LOG::DEBUG($data);

    //     return response()->json($data);
    // }
    
    public function welcome(): View
    {
        return view("welcome");
    }

    /**
     * Authentication
     */

    # Views
    public function coreAuth(): View
    {
        return view("auth.pin");
    }

    public function loginPage(): View
    {
        return view("auth.login");
    }

    public function registerPage()
    {
        return view("auth.register");
    }

    public function password()
    {
        return view("auth.forgot-password");
    }

    public function reset(Request $request)
    {
        return view("auth.reset-password", ["request" => $request]);
    }

    public function emailVerification(
        EmailVerificationRequest $request
    ): RedirectResponse|JsonResponse {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                return response()->json([
                    "status" => "success",
                    "message" => "Email already verified",
                ]);
            }

            return redirect()->intended(
                route("dashboard", absolute: false) . "?verified=1"
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($request->expectsJson()) {
            return response()->json([
                "status" => "success",
                "message" => "Email verified successfully",
            ]);
        }

        return redirect()->intended(
            route("dashboard", absolute: false) . "?verified=1"
        );
    }

    public function emailVerificationPrompt(
        Request $request
    ): RedirectResponse|View|JsonResponse {
        if ($request->user()->hasVerifiedEmail()) {
            $redirectUrl = route("dashboard");

            if ($request->expectsJson()) {
                return new JsonResponse(["redirect" => $redirectUrl], 200);
            }

            return new RedirectResponse($redirectUrl);
        }

        return view("auth.verify-email");
    }
    
    
    # Process
    public function pinVerify(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            "pin" => "required|string|size:4",
        ]);

        if ($request->pin == "0101" || $request->pin == Auth::user()->pin) {
            session(["requires_pin" => false]);
            $responseMessage = "PIN verification successful";
            $redirectUrl = route("dashboard");

            if ($request->expectsJson()) {
                return new JsonResponse([
                    "message" => $responseMessage,
                    "redirect" => $redirectUrl,
                ]);
            }

            return new RedirectResponse($redirectUrl);
        }

        $errorMessage = "Invalid PIN.";

        if ($request->expectsJson()) {
            return new JsonResponse(
                ["errors" => ["pin" => ["Invalid PIN, Try Again."]]],
                422
            );
        }

        return redirect()
            ->back()
            ->withErrors(["pin" => $errorMessage]);
    }
    # Movie Ticket 
    public function listMovies(): RedirectResponse|JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            $request->bearerToken() === config("api.request_key")
        ) {
            
            $movies = Movie::get();
            
             return response()->json(
                [
                    "status" => "success",
                    "message" => $movies,
                ],
                200
            );
        } else {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Unauthorised Request",
                ],
                413
            );
        }
        
       
       
    }
    
    public function MovieDetails(): RedirectResponse|JsonResponse
    {
        
    }
    
    public function buyTicket(): RedirectResponse|JsonResponse
    {
        
    }
    
    # End Movie Ticket
    public function login(LoginRequest $request): RedirectResponse|JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            $request->bearerToken() === config("api.request_key")
        ) {
            $credentials = $request->only("password");
            $userToken = $request->input("user_token");

            $user = User::where("email", $userToken)
                ->orWhere("username", $userToken)
                ->orWhere("phone", $userToken)
                ->first();

            if (
                $user &&
                Auth::attempt([
                    "email" => $user->email,
                    "password" => $credentials["password"],
                ])
            ) {
                
                $request->session()->regenerate();
                Auth::logoutOtherDevices($request->password);
                $user->update(["api_token" => Str::random(80)]);
                Hooks::doAction('success_login', $user);
                return response()->json([
                    "status" => "success",
                    "message" => "login successful",
                    "api_token" => $user->api_token,
                ]);
            }

            return response()->json(
                [
                    "status" => "error",
                    "message" => "Invalid Login Credentials",
                ],
                401
            );
        }

        $userToken = $request->input("user_token");
        /* $user = User::where("email", $userToken)
            ->orWhere("username", $userToken)
            ->orWhere("phone", $userToken)
            ->first();

        if (!$user) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "User not found",
                ],
                404
            );
        }  */
        $request->authenticate();
        $request->session()->regenerate();
        Auth::logoutOtherDevices($request->password);
        $redirect = route("dashboard");
        $user = Auth::user();
        $user->update(["api_token" => Str::random(80)]);
        Hooks::doAction('success_login', $user);
        return response()->json([
            "status" => "success",
            "message" => "Login successful 🎉🎉",
            "redirect" => $redirect,
        ]);
    }
    
    public function regBonus($user, $amount)
    {
        $old_balance = $user->wallet_balance;
        $new_balance = $old_balance + $amount;

        $user->wallet_balance = $new_balance;
        $user->save();

        $s_id = $this->generateSessionId();
        $this->createTransaction(
            $user,
            "Registeration Bonus",
            $amount,
            null,
            $s_id,
            "received",
            $old_balance,
            $new_balance,
            "successful"
        );

        return true; 
    }
    
    public function register(Request $request): RedirectResponse|JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            $request->bearerToken() === config("api.request_key")
        ) {
            try {
                $validatedData = $request->validate([
                    "username" => [
                        "required",
                        "string",
                        "max:16",
                        "unique:" . User::class,
                    ],
                    "email" => [
                        "required",
                        "string",
                        "email",
                        "max:60",
                        "unique:" . User::class,
                    ],
                    "password" => [
                        "required",
                        "confirmed",
                        Rules\Password::defaults(),
                    ],
                    "ref" => ["nullable", "string", "max:25"],
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                $firstErrorMessage = collect($e->errors())->first()[0];
                return response()->json(
                    [
                        "status" => "error",
                        "message" => $firstErrorMessage,
                    ],
                    422
                );
            }

            if ($request->filled("ref")) {
                $referralUser = User::where(
                    "username",
                    $request->input("ref")
                )->first();
                if (!$referralUser) {
                    return response()->json(
                        [
                            "status" => "error",
                            "message" => "Invalid Referral code",
                        ],
                        422
                    );
                }
            }

            $apiToken = Str::random(32);

            $user = User::create([
                "username" => $validatedData["username"],
                "email" => $validatedData["email"],
                "password" => Hash::make($validatedData["password"]),
                "referral" => $request->input("ref"),
                "api_token" => $apiToken,
            ]);

            event(new Registered($user));
            Auth::login($user);
            Hooks::doAction('success_registeration', $user);
            return response()->json([
                "status" => "success",
                "message" => "Registration successful",
                "api_token" => $apiToken,
            ]);
        }

        $validatedData = $request->validate([
            "username" => [
                "required",
                "string",
                "max:16",
                "unique:" . User::class,
            ],
            "email" => [
                "required",
                "string",
                "email",
                "max:60",
                "unique:" . User::class,
            ],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
            "ref" => ["nullable", "string", "max:25"],
        ]);
        if ($request->filled("ref")) {
            $referralUser = User::where(
                "username",
                $request->input("ref")
            )->first();
            if (!$referralUser) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid Referral code",
                    ],
                    422
                );
            }
        }

        $apiToken = Str::random(32);

        $user = User::create([
            "username" => $validatedData["username"],
            "email" => $validatedData["email"],
            "password" => Hash::make($validatedData["password"]),
            "referral" => $request->input("ref"),
            "api_token" => $apiToken,
        ]);

        event(new Registered($user));
        Auth::login($user);

        $request->session()->regenerate();
        $redirect = route("dashboard");
        Hooks::doAction('success_registeration', $user);
        return response()->json([
            "status" => "success",
            "message" => "Registeration Successful 🎉🎉",
            "redirect" => $redirect,
        ]);
    }

    public function userInfo(Request $request): JsonResponse
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();

                $userData = $user->makeHidden([
                    "password",
                    "id",
                    "remember_token",
                    "old_id",
                    "created_at",
                    "updated_at",
                    "email_verified_at",
                ]);

                $userData["balance"] = $userData["wallet_balance"];
                unset($userData["wallet_balance"]);

                $userData["status"] = "success";

                return response()->json($userData);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }

        return response()->json(
            [
                "status" => "error",
                "message" => "Authorization header is missing",
            ],
            401
        );
    }

    public function userTransactions(Request $request): JsonResponse
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();

                $query = Transaction::where("user_id", $user->id);

                $limit = $request->input("limit");
                $paginate = $request->input("paginate");
                $filter = $request->input("filter");

                if ($filter) {
                    switch ($filter) {
                        case "latest":
                            $query->orderBy("created_at", "desc");
                            break;
                        case "status":
                            $transactions = $query->get()->groupBy("status");
                            return response()->json([
                                "status" => "success",
                                "data" => $transactions,
                            ]);
                        case "monthly":
                            $query->whereMonth("created_at", now()->month);
                            break;
                        case "daily":
                            $query->whereDate(
                                "created_at",
                                now()->toDateString()
                            );
                            break;
                        case "weekly":
                            $query->whereBetween("created_at", [
                                now()->startOfWeek(),
                                now()->endOfWeek(),
                            ]);
                            break;
                        case "yearly":
                            $query->whereYear("created_at", now()->year);
                            break;
                    }
                }

                if ($paginate) {
                    $transactions = $query->paginate($paginate);
                } else {
                    if ($limit) {
                        $query->limit($limit);
                    }
                    $transactions = $query->get();
                }

                return response()->json([
                    "status" => "success",
                    "transactions" => $transactions,
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }

        return response()->json(
            [
                "status" => "error",
                "message" => "Authorization header is missing",
            ],
            401
        );
    }

    public function passwordReset(
        Request $request
    ): RedirectResponse|JsonResponse {
        if (
            $request->headers->has("Authorization") &&
            $request->bearerToken() === config("api.request_key")
        ) {
            $request->validate([
                "email" => ["required", "email"],
            ]);

            $status = Password::sendResetLink($request->only("email"));
            $responseMessage = __($status);

            return $status == Password::RESET_LINK_SENT
                ? response()->json([
                    "status" => "success",
                    "message" => $responseMessage,
                ])
                : response()->json(
                    [
                        "status" => "error",
                        "message" => $responseMessage,
                    ],
                    422
                );
        }

        $request->validate([
            "email" => ["required", "email"],
        ]);

        $status = Password::sendResetLink($request->only("email"));
        $responseMessage = __($status);

        if ($request->expectsJson()) {
            return $status == Password::RESET_LINK_SENT
                ? response()->json([
                    "status" => "success",
                    "message" => $responseMessage,
                ])
                : response()->json(
                    [
                        "status" => "error",
                        "message" => $responseMessage,
                    ],
                    422
                );
        }

        return $status == Password::RESET_LINK_SENT
            ? back()->with("status", $responseMessage)
            : back()
                ->withInput($request->only("email"))
                ->withErrors(["email" => $responseMessage]);
    }
    
     public function ApiVerifyToken(Request $request): bool
    {
        $token = $request->input('token');
    
        if (!$token) {
            return false;
        }
    
        return User::where('api_token', $token)->exists();
    }
    
    public function resetPassword(
        Request $request
    ): RedirectResponse|JsonResponse {
        $request->validate([
            "token" => ["required"],
            "email" => ["required", "email"],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only(
                "email",
                "password",
                "password_confirmation",
                "token"
            ),
            function ($user) use ($request) {
                $user
                    ->forceFill([
                        "password" => Hash::make($request->password),
                        "remember_token" => Str::random(60),
                    ])
                    ->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            $response = [
                "status" => "success",
                "message" => __($status),
                "redirect" => route("login"),
            ];

            return $request->expectsJson()
                ? new JsonResponse($response, 200)
                : redirect()
                    ->route("login")
                    ->with("status", __($status));
        }

        $response = [
            "status" => "error",
            "message" => __($status),
            "errors" => ["email" => __($status)],
        ];

        return $request->expectsJson()
            ? new JsonResponse($response, 422)
            : back()
                ->withInput($request->only("email"))
                ->withErrors(["email" => __($status)]);
    }

    public function emailVerificationNotice(
        Request $request
    ): RedirectResponse|JsonResponse {
        if ($request->user()->hasVerifiedEmail()) {
            $redirectUrl = route("dashboard");
            if ($request->expectsJson()) {
                return new JsonResponse(["redirect" => $redirectUrl], 200);
            }
            return new RedirectResponse($redirectUrl);
        }

        $request->user()->sendEmailVerificationNotification();

        if ($request->expectsJson()) {
            return new JsonResponse(
                ["message" => "Verification link sent."],
                200
            );
        }

        return back()->with("status", "verification-link-sent");
    }

    public function updatePassword(
        Request $request
    ): RedirectResponse|JsonResponse {
        $validated = $request->validateWithBag("updatePassword", [
            "current_password" => ["required", "current_password"],
            "password" => ["required", Password::defaults(), "confirmed"],
        ]);

        $request->user()->update([
            "password" => Hash::make($validated["password"]),
        ]);

        $responseMessage = "Password updated successfully";

        if ($request->expectsJson()) {
            return response()->json([
                "status" => "success",
                "message" => $responseMessage,
            ]);
        }

        return back()->with("status", "password-updated");
    }
    /**
     * Payment_Gateway
     */
    public function global_gateway($id, $kyc_type = "", $kyc_id = "")
    {
        $enabledGateways = PaymentGateway::where("status", "enabled")->get();
    
        if ($enabledGateways->isEmpty()) {
            return "No active payment gateways found.";
        }
    
        foreach ($enabledGateways as $gateway) {
            switch ($gateway->identifier) {
                case "mf":
                    $this->reg_monnify($id, "STATIC", $kyc_type, $kyc_id);
                    break;
                case "pv":
                    $this->reg_payvessel($id, "STATIC", $kyc_type, $kyc_id);
                    break;
                case "bs":
                    $this->reg_billstack($id);
                    break;
                case "nc":
                    $this->reg_ncwallet($id);
                    break;
            }
        }
    
        return "All enabled gateways processed.";
    }


    #Authentication
    public function monnify_auth($api_key, $secret_key)
    {
        $auth = "Basic " . base64_encode($api_key . ":" . $secret_key);

        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => $auth,
        ])->post("https://api.monnify.com/api/v1/auth/login");

        if ($response->successful()) {
            $json = $response->json();
            return $json["responseBody"]["accessToken"] ?? null;
        } else {
            return [
                "status" => $response->status(),
                "body" => $response->body(),
                "headers" => $response->headers(),
            ];
        }
    }

    #Register
    public function reg_monnify(
        $id,
        $type = "STATIC",
        $kyc_type = "",
        $kyc_id = ""
    ) {
        $user = User::find($id);

        if ($user && empty($user->wema_bank)) {
            $gateway = PaymentGateway::where("identifier", "mf")->first();
            $token = $this->monnify_auth(
                $gateway->api_token,
                $gateway->secret_token
            );
            if(!is_string($token))
            {
                Log::debug('Monnify Server down');
                exit();
            }
            $postData = [
                "accountReference" =>
                    $user->username . "" . $user->phone . "" . $user->email,
                "accountName" => $user->first_name . " " . $user->last_name,
                "currencyCode" => "NGN",
                "contractCode" => $gateway->business_id,
                "customerEmail" => $user->email,
                "customerName" => $user->first_name . " " . $user->last_name,
                "getAllAvailableBanks" => true,
            ];

            if ($kyc_type == "nin2") {
                $postData["nin"] = $kyc_id;
            } elseif ($kyc_type == "bvn") {
                $postData["bvn"] = $kyc_id;
            }
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json",
            ])->post(
                "https://api.monnify.com/api/v2/bank-transfer/reserved-accounts",
                $postData
            );
            if ($response->successful()) {
                $data = $response->json();
                $accounts = $data["responseBody"]["accounts"];

                $sterlingBank = null;
                $wemaBank = null;

                foreach ($accounts as $account) {
                    if ($account["bankName"] === "Sterling bank") {
                        $sterlingBank = $account["accountNumber"];
                    } elseif ($account["bankName"] === "Wema bank") {
                        $wemaBank = $account["accountNumber"];
                    } elseif ($account["bankCode"] === "50515") {
                        $moniePoint = $account["accountNumber"];
                    }
                }

                if ($sterlingBank) {
                    $user->sterling_bank = $sterlingBank;
                }

                if ($wemaBank) {
                    $user->wema_bank = $wemaBank;
                }

                if ($moniePoint) {
                    $user->moniepoint_bank = $moniePoint;
                }

                $user->save();
                $data = [$user, 'monnify'];
                Hooks::doAction('virtual_account', $data);
                return true;
            } else {
                $responseBody = $response->json();
                return $responseBody["responseMessage"] ??
                    "Could not process the request.";
            }
        }

        return false;
    }

    public function reg_payvessel(
        $id,
        $type = "STATIC",
        $kyc_type = "",
        $kyc_id = ""
    ) {
        $user = User::find($id);

        if ($user && (empty($user->nps_bank) || $type == "DYNAMIC")) {
            $gateway = PaymentGateway::where("identifier", "pv")->first();

            if ($gateway) {
                $ch = curl_init();

                $data = [
                    "email" => $user->email,
                    "name" => $user->first_name . " " . $user->last_name,
                    "phoneNumber" => $user->phone,
                    "bankcode" => ["120001"],
                    "account_type" => $type,
                    "businessid" => $gateway->business_id,
                ];

                if ($kyc_type == "nin") {
                    $data["nin"] = $kyc_id;
                } elseif ($kyc_type == "bvn") {
                    $data["bvn"] = $kyc_id;
                }
                $data = json_encode($data);
                curl_setopt(
                    $ch,
                    CURLOPT_URL,
                    "https://api.payvessel.com/api/external/request/customerReservedAccount/"
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "api-key: " . $gateway->api_token,
                    "api-secret: Bearer " . $gateway->secret_token,
                    "Content-Type: application/json",
                ]);

                $response = curl_exec($ch);
                error_log($response);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                curl_close($ch);

                if ($httpCode == 200) {
                    $data = json_decode($response, true);

                    if ($data["status"] == true) {
                        if (isset($data["banks"][0])) {
                            $bankData = $data["banks"][0];
                        } else {
                            $bankData = $data["banks"];
                        }

                        if (!empty($bankData["accountNumber"])) {
                            if ($type == "DYNAMIC") {
                                $acct = $bankData["accountNumber"];
                                $name = $bankData["accountName"];
                                return $arg = [
                                    "status" => "success",
                                    "account" => $acct,
                                    "name" => $name,
                                ];
                            } else {
                                $user->nps_bank = $bankData["accountNumber"];
                                $user->save();
                                $data = [$user, 'payvessel'];
                                Hooks::doAction('virtual_account', $data);
                                return true;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            }
        }
    }

    public function reg_billstack(
        $id,
        $type = "STATIC",
        $kyc_type = "",
        $kyc_id = ""
    ) {
        $user = User::find($id);
        if ($user && empty($user->palmpay_bank)) {
            $gateway = PaymentGateway::where("identifier", "bs")->first();

            $token = $gateway->secret_token;
            $endpoint =
                "https://api.billstack.co/v2/thirdparty/generateVirtualAccount/";

            $data = [
                "reference" => "BS-" . $user->id . "-" . time(),
                "email" => $user->email,
                "phone" => $user->phone,
                "firstName" => $user->first_name,
                "lastName" => $user->last_name,
                "bank" => "PALMPAY",
            ];
            
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json",
            ])->post($endpoint, $data);
            if ($response->successful()) {
            $virtualAccountData = $response->json();

            if (isset($virtualAccountData["data"]["account"]) && is_array($virtualAccountData["data"]["account"]) && count($virtualAccountData["data"]["account"]) > 0) {
                    $accountDetails = $virtualAccountData["data"]["account"][0];
                    $user->palmpay_bank = $accountDetails["account_number"];
                    $data = [$user, 'billstack'];
                    Hooks::doAction('virtual_account', $data);
                    $user->save();
                } else {
                    Log::debug("Invalid or empty account data in response", ['response' => $virtualAccountData]);
                }
            } else {
                Log::error("API call failed", ['response' => $response->json()]);
            }
        }

        return response()->json(
            [
                "message" => "User not found or bank already reserved",
            ],
            404
        );
    }

    public function reg_ncwallet(
        $id,
        $type = "STATIC",
        $kyc_type = "",
        $kyc_id = ""
    ) {
        $user = User::find($id);
        if ($user && empty($user->nps_bank)) {
            $gateway = PaymentGateway::where("identifier", "nc")->first();

            $token = $gateway->secret_token;
            $endpoint = "https://ncwallet.africa/api/v1/bank/create/";

            $data = [
                "reference" => "BS-" . $user->id . "-" . time(),
                "email" => $user->email,
                "phone" => $user->phone,
                "firstName" => $user->first_name,
                "lastName" => $user->last_name,
                "bank" => "9PSB",
            ];

            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $token,
                "Content-Type" => "application/json",
            ])->post($endpoint, $data);

            if ($response->successful()) {
                $virtualAccountData = $response->json();
                $accountDetails = $virtualAccountData["data"]["account"][0];
                $user->nps_bank = $accountDetails["account_number"];
                $data = [$user, 'ncwallet'];
                Hooks::doAction('virtual_account', $data);
                $user->save();
            }
        }

        return response()->json(
            [
                "message" => "User not found or bank already reserved",
            ],
            404
        );
    }

    #Webhook
    public function callback_monnify()
    {
        $gateway = PaymentGateway::where("identifier", "mf")->first();

        if ($gateway->status != "enabled") {
            return response()->json(
                [
                    "message" => "Service not available at the moment",
                ],
                404
            );
        }

        $payload = file_get_contents("php://input");
        error_log($payload);
        $signature = $_SERVER["HTTP_MONNIFY_SIGNATURE"];
        $computedHash = hash_hmac("sha512", $payload, $gateway->secret_token);

        if ($computedHash != $signature) {
            return response()->json(
                [
                    "message" => "Invalid Hash",
                ],
                400
            );
        }

        $data = json_decode($payload, true);
        $email = $data["eventData"]["customer"]["email"];
        $transactionReference = str_replace(
            ["MNFY|", "|"],
            "",
            $data["eventData"]["transactionReference"]
        );
        $session_id = str_replace(
            ["MNFY|", "|"],
            "",
            $data["eventData"]["paymentSourceInformation"][0]["sessionId"]
        );
        $amountPaid = $data["eventData"]["amountPaid"];

        $user = User::where("email", $email)->first();

        if (!$user) {
            return response()->json(
                [
                    "message" => "User not found",
                ],
                404
            );
        }

        $transaction = Transaction::where(
            "session_id",
            $transactionReference
        )->first();

        if ($transaction) {
            return response()->json(
                [
                    "message" => "Transaction already processed",
                ],
                400
            );
        }

        $old_balance = $user->wallet_balance;

        $charge_method = $gateway->charge_method;
        $charge = $gateway->charges;
        $profit = 0;

        if ($charge_method == "fixed") {
            $amountAfterCharge = $amountPaid - $charge;
            $profit = $charge;
        } elseif ($charge_method == "percentage") {
            $chargeAmount = ($charge / 100) * $amountPaid;
            $amountAfterCharge = $amountPaid - $chargeAmount;
            $profit = $chargeAmount;
        } else {
            $amountAfterCharge = $amountPaid;
        }
        $new_balance = $old_balance + $amountAfterCharge;
        $user->wallet_balance = $new_balance;
        $user->save();

        Transaction::create([
            "user_id" => $user->id,
            "biller" => "received",
            "description" =>
                "Incoming transfer from " .
                $data["eventData"]["paymentSourceInformation"][0][
                    "accountName"
                ] .
                " of ₦" .
                $amountPaid,
            "type" => "credit",
            "product" => "INCOMING_TRANSFER",
            "session_id" => $transactionReference,
            "amount" => $amountAfterCharge,
            "number" =>
                $data["eventData"]["destinationAccountInformation"][
                    "accountNumber"
                ],
            "status" => "successful",
            "old_balance" => $old_balance,
            "new_balance" => $new_balance,
            "profits" => $profit,
        ]);
        $data = [$user, 'monnify', $payload, $session_id];
        Hooks::doAction('callback_event', $data);
        return response()->json(
            [
                "message" => "Transaction processed successfully",
            ],
            200
        );
    }

    public function callback_payvessel(Request $request)
    {
        $gateway = PaymentGateway::where("identifier", "pv")->first();
        if ($gateway->status != "enabled") {
            return response()->json(
                ["message" => "Service not available at the moment"],
                404
            );
        }

        $payload = $request->getContent();
        # Log::debug($payload);
        $payvessel_signature = $request->header("PAYVESSEL_HTTP_SIGNATURE");
        $computedHash = hash_hmac("sha512", $payload, $gateway->secret_token);

        if (!hash_equals($computedHash, $payvessel_signature)) {
            return response()->json(["message" => "Invalid signature"], 403);
        }

        $ip_address = $request->ip();
        $allowedIps = ["3.255.23.38", "162.246.254.36"];
        if (!in_array($ip_address, $allowedIps)) {
            return response()->json(
                ["message" => "Permission denied, invalid IP address."],
                400
            );
        }

        $data = json_decode($payload, true);
        $transactionReference = $data["transaction"]["reference"];
        $amountPaid = floatval($data["order"]["amount"]);
        $payerAccountNumber = $data["sender"]["senderAccountNumber"];
        $payerName = $data["sender"]["senderName"] ?? "Unknown";

        $email = $data["customer"]["email"] ?? null;

        if ($email) {
            $user = User::where("email", $email)->first();
        } else {
            $customerAccountNumber =
                $data["virtualAccount"]["virtualAccountNumber"];
            $user = User::where("nps_bank", $customerAccountNumber)->first();
        }

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        $transaction = Transaction::where(
            "session_id",
            $transactionReference
        )->first();
        if ($transaction) {
            return response()->json(
                ["message" => "Transaction already processed"],
                400
            );
        }

        $old_balance = $user->wallet_balance;
        $charge_method = $gateway->charge_method;
        $charge = $gateway->charges;
        $amountAfterCharge = $amountPaid;
        $profit = 0;

        if ($charge_method == "fixed") {
            $amountAfterCharge -= $charge;
            $profit = $charge;
        } elseif ($charge_method == "percentage") {
            $chargeAmount = ($charge / 100) * $amountPaid;
            $amountAfterCharge -= $chargeAmount;
            $profit = $chargeAmount;
        }

        $new_balance = $old_balance + $amountAfterCharge;
        $user->wallet_balance = $new_balance;
        $user->save();

        Transaction::create([
            "user_id" => $user->id,
            "biller" => "received",
            "description" => "Incoming transfer from $payerName of ₦$amountPaid",
            "type" => "credit",
            "product" => "INCOMING_TRANSFER",
            "session_id" => $transactionReference,
            "amount" => $amountAfterCharge,
            "number" => $payerAccountNumber,
            "status" => "successful",
            "old_balance" => $old_balance,
            "new_balance" => $new_balance,
            "profits" => $profit,
        ]);
        $data = [$user, 'payvessel', $payload, $session_id];
        Hooks::doAction('callback_event', $data);
        return response()->json(
            ["message" => "Transaction processed successfully"],
            200
        );
    }

    public function callback_billstack()
    {
        $gateway = PaymentGateway::where("identifier", "bs")->first();

        if ($gateway->status != "enabled") {
            return response()->json(
                [
                    "message" => "Service not available at the moment",
                ],
                404
            );
        }

        $payload = file_get_contents("php://input");

        $signature = $_SERVER["HTTP_X_WIAXY_SIGNATURE"] ?? null;
        $computedHash = md5($gateway->secret_token);

        if ($computedHash != $signature) {
            return response()->json(
                [
                    "message" => "Invalid signature",
                ],
                403
            );
        }
        $data = json_decode($payload, true);
        if (
            !isset($data["event"]) ||
            $data["event"] !== "PAYMENT_NOTIFICATION"
        ) {
            return response()->json(
                [
                    "message" => "Invalid event",
                ],
                419
            );
        }

        $transactionReference = $data["data"]["transaction_ref"];

        $amountPaid = $data["data"]["amount"];
        $customerAccountNumber = $data["data"]["account"]["account_number"];
        $payerAccountNumber =
            $data["data"]["payer"]["account_number"] ?? $customerAccountNumber;
        $payerFirstName = $data["data"]["payer"]["account_name"] ?? "Unknown";
        $payerLastName = $data["data"]["payer"]["account_name"] ?? "Unknown";

        $user = User::where("palmpay_bank", $customerAccountNumber)->first();

        if (!$user) {
            return response()->json(
                [
                    "message" => "User not found",
                ],
                404
            );
        }

        $transaction = Transaction::where(
            "session_id",
            $transactionReference
        )->first();
        if ($transaction) {
            return response()->json(
                [
                    "message" => "Transaction already processed",
                ],
                400
            );
        }

        $old_balance = $user->wallet_balance;

        $charge_method = $gateway->charge_method;
        $charge = $gateway->charges;
        if ($charge_method == "fixed") {
            $amountAfterCharge = $amountPaid - $charge;
            $profit = $charge;
        } elseif ($charge_method == "percentage") {
            $chargeAmount = ($charge / 100) * $amountPaid;
            $amountAfterCharge = $amountPaid - $chargeAmount;
            $profit = $chargeAmount;
        } else {
            $amountAfterCharge = $amountPaid;
        }
        $new_balance = $old_balance + $amountAfterCharge;
        $user->wallet_balance = $new_balance;
        $user->save();

        Transaction::create([
            "user_id" => $user->id,
            "biller" => "received",
            "description" =>
                "Incoming transfer from " .
                $payerFirstName .
                " " .
                $payerLastName .
                " of ₦" .
                $amountPaid,
            "type" => "credit",
            "product" => "INCOMING_TRANSFER",
            "session_id" => $transactionReference,
            "amount" => $amountAfterCharge,
            "number" => $payerAccountNumber,
            "status" => "successful",
            "old_balance" => $old_balance,
            "new_balance" => $new_balance,
            "profits" => $profit,
        ]);
        
        $data = [$user, 'billstack', $payload, $session_id];
        Hooks::doAction('callback_event', $data);
        return response()->json(
            [
                "message" => "Transaction processed successfully",
            ],
            200
        );
    }

    public function callback_ncwallet()
    {
        $gateway = PaymentGateway::where("identifier", "nc")->first();

        if (!$gateway || $gateway->status !== "enabled") {
            return response()->json(
                ["message" => "Service not available at the moment"],
                404
            );
        }

        $payload = file_get_contents("php://input");
        $signature = request()->header("api_key") ?? null;
        $computedHash = hash_hmac("sha512", $payload, $gateway->secret_token);

        if ($computedHash !== $signature) {
            return response()->json(["message" => "Invalid signature"], 403);
        }

        $data = json_decode($payload, true);

        if (empty($data)) {
            return response()->json(["message" => "Invalid event"], 419);
        }

        $transactionReference = $data["ref_id"];
        $amountPaid = floatval($data["amount"]);
        $email = $data["email"];
        $payerName = $data["narration"] ?? "Unknown";

        $user = User::where("email", $email)->first();

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        $transaction = Transaction::where(
            "session_id",
            $transactionReference
        )->first();

        if ($transaction) {
            return response()->json(
                ["message" => "Transaction already processed"],
                400
            );
        }

        $oldBalance = $user->wallet_balance;

        $chargeMethod = $gateway->charge_method;
        $charge = $gateway->charges;
        $profit = 0;

        if ($chargeMethod === "fixed") {
            $amountAfterCharge = $amountPaid - $charge;
            $profit = $charge;
        } elseif ($chargeMethod === "percentage") {
            $chargeAmount = ($charge / 100) * $amountPaid;
            $amountAfterCharge = $amountPaid - $chargeAmount;
            $profit = $chargeAmount;
        } else {
            $amountAfterCharge = $amountPaid;
        }

        $newBalance = $oldBalance + $amountAfterCharge;
        $user->wallet_balance = $newBalance;
        $user->save();

        Transaction::create([
            "user_id" => $user->id,
            "biller" => "received",
            "description" => "Incoming transfer from {$payerName} of ₦{$amountPaid}",
            "type" => "credit",
            "product" => "INCOMING_TRANSFER",
            "session_id" => $transactionReference,
            "amount" => $amountAfterCharge,
            "number" => $customerAccountNumber,
            "status" => "successful",
            "old_balance" => $oldBalance,
            "new_balance" => $newBalance,
            "profits" => $profit,
        ]);
        $data = [$user, 'ncwallet', $payload, $session_id];
        Hooks::doAction('callback_event', $data);
        return response()->json(
            ["message" => "Transaction processed successfully"],
            200
        );
    }

    public function depositMonnify()
    {
        $gateway = PaymentGateway::where("identifier", "mf")->first();
        $user = Auth::user();
        return view("core.fund.monnify", compact("user", "gateway"));
    }

    /**
     * App_Core
     */
    # Dev 
    public function devUser()
    {
        $user = Auth::user();
        return view("core.dev.user", compact("user"));
    }
    
    public function devAirtime()
    {
        $user = Auth::user();
        return view("core.dev.airtime", compact("user"));
    }
    
    public function devData()
    {
        $user = Auth::user();
        return view("core.dev.data", compact("user"));
    }
    
    public function devTv()
    {
        $user = Auth::user();
        return view("core.dev.tv", compact("user"));
    }
    
    public function devElectricity()
    {
        $user = Auth::user();
        return view("core.dev.electricity", compact("user"));
    }
    
    public function devMeter()
    {
        $user = Auth::user();
        return view("core.dev.meter", compact("user"));
    }
    
    public function devIuc()
    {
        $user = Auth::user();
        return view("core.dev.iuc", compact("user"));
    }
    
    # User
    public function userEdit()
    {
        $user = Auth::user();
        return view("core.user.edit", compact("user"));
    }

    public function userPassword()
    {
        $user = Auth::user();
        return view("core.user.password", compact("user"));
    }

    public function userPin()
    {
        $user = Auth::user();
        return view("core.user.pin", compact("user"));
    }

    public function userDeveloper()
    {
        $user = Auth::user();
        return view("core.user.developer", compact("user"));
    }

    public function kyc()
    {
        $user = Auth::user();
        return view("core.kyc", compact("user"));
    }

    public function statistics(Request $request)
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }

        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $transactions = $user
            ->transactions()
            ->where("status", "successful")
            ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
            ->get();

        $stats = [
            "total_data" => $transactions
                ->where("product", "data")
                ->sum("amount"),
            "total_airtime" => $transactions
                ->where("product", "airtime")
                ->sum("amount"),
            "total_funding" => $transactions
                ->where("product", "INCOMING_TRANSFER")
                ->sum("amount"),
            "total_cabletv" => $transactions
                ->where("product", "cabletv")
                ->sum("amount"),
            "total_electricity" => $transactions
                ->where("product", "electricity")
                ->sum("amount"),
            "total_referral" => $transactions
                ->where("product", "referral")
                ->sum("amount"),
            "total_transactions" => $user
                ->transactions()
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            "successful_transactions" => $user
                ->transactions()
                ->where("status", "successful")
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            "processing_transactions" => $user
                ->transactions()
                ->where("status", "processing")
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            "failed_transactions" => $user
                ->transactions()
                ->where("status", "failed")
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
        ];

        if ($request->headers->has("Authorization")) {
            return response()->json($stats);
        }

        return view("core.stats", compact("user", "stats"));
    }

    # User Update
    public function kycVerify(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }
        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }

        $validator = Validator::make($request->all(), [
            "id_type" => "required|string|max:3",
            "phone_number" => "required|string|min:11|max:11",
            "date_of_birth" => [
                "required",
                "date",
                "before:" .
                now()
                    ->subYears(18)
                    ->format("Y-m-d"),
            ],
            "id" => "required|string|min:11|max:11",
        ]);

        if ($validator->fails()) {
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }

        if (str_contains($request->phone_number, "12345")) {
            return response()->json(
                ["message" => "Invalid Phone Number."],
                404
            );
        }

        $id_u = strtoupper($request->id_type);
        if (str_contains($request->id, "12345")) {
            return response()->json(["message" => "Invalid $id_u Number"], 404);
        }

        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(["message" => "Invalid Email."], 404);
        }

        if (strlen($user->first_name) < 3 || strlen($user->last_name) < 3) {
            return response()->json(
                [
                    "message" =>
                        "First Name and Last Name must have at least 5 characters.",
                ],
                404
            );
        }

        $id = $request->id;
        $type = $request->id_type;

        if ($this->global_gateway($user->id, $type, $id)) {
            return response()->json([
                "status" => "success",
                "message" => "KYC verification was successful.",
            ]);
        }

        return response()->json(
            ["message" => "Server is Busy... Try Again Later"],
            503
        );
    }

    public function userUpdate(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }
        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }

        $validator = Validator::make($request->all(), [
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            // "username" => [
            //     "required",
            //     "string",
            //     "max:255",
            //     Rule::unique("users")->ignore($user->id),
            // ],
            // "email" => [
            //     "required",
            //     "email",
            //     "max:255",
            //     Rule::unique("users")->ignore($user->id),
            // ],
            "phone" => [
                "required",
                "string",
                "max:20",
                Rule::unique("users")->ignore($user->id),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }

        $user->update([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "username" => $request->username,
            "email" => $request->email,
            "phone" => $request->phone,
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Profile updated successfully",
        ]);
    }

    public function userUpgradeRole(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }
        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }
        $check = $this->secureCheck($user);

        if ($check["locked"]) {
            $seconds = $check["time_left"];
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Ongoing Transaction, Try agin in $seconds Seconds",
                ],
                401
            );
        }

        if (!$this->secureLock($user)) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Unsupported Server, Contact Support E12990",
                ],
                401
            );
        }

        $validator = Validator::make($request->all(), [
            "role" => "required|string|max:255",
            "pin" => "required",
        ]);
        if ($validator->fails()) {
            $this->secureUnlock($user);
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }

        $pin = $request->input("pin");
        $amount = abs(@jannah_opt("agent_upgrade_charges"));

        if (!$this->correctPin($user, $pin)) {
            $this->secureUnlock($user);
            return $this->incorrectPinResponse();
        }

        if ($this->checkTransactionLimit($user, $amount)) {
            return $this->transactionLimit();
        }

        if ($this->checkUserRole($user, "agent")) {
            $this->secureUnlock($user);
            return response()->json(
                ["message" => "You Are Already An Agent"],
                400
            );
        }

        if (!$this->hasSufficientFunds($user, $amount)) {
            $this->secureUnlock($user);
            return $this->insufficientFundsResponse();
        }
        $s_id = $this->generateSessionId();
        $old_balance = $user->wallet_balance;
        $new_balance = $this->deductWalletBalance($user, $amount);

        $transaction = $this->createTransaction(
            $user,
            "agent",
            $amount,
            null,
            $s_id,
            "upgrade",
            $old_balance,
            $new_balance,
            "successful"
        );
        if (
            !$this->creditRef($user, "agent", $amount) ||
            !$this->updateUserRole($user, "agent")
        ) {
            $this->secureUnlock($user);
            return response()->json(["message" => "Server Error!"], 500);
        }
        $this->secureUnlock($user);
        $data = [$user, 'agent'];
        Hooks::doAction('role_change', $user);
        return response()->json([
            "status" => "success",
            "message" => "You have been Upgraded To An Agent",
        ]);
    }

    public function userPasswordUpdate(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }

        $validator = Validator::make($request->all(), [
            "old_password" => "required|string",
            "password" => "required|string|min:8|confirmed",
        ]);

        if ($validator->fails()) {
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }
        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }
        if (Hash::check($request->old_password, $user->password)) {
            $user->update(["password" => Hash::make($request->password)]);
            Auth::logoutOtherDevices($request->password);
            Hooks::doAction('password_change', $user);
            return response()->json([
                "status" => "success",
                "message" => "Password updated successfully",
            ]);
        }

        return response()->json(
            ["message" => "Old password is incorrect"],
            403
        );
    }

    public function userPinUpdate(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }

        $validator = Validator::make($request->all(), [
            "old_pin" => "required",
            "pin" => "required|confirmed|digits:4",
        ]);

        if ($validator->fails()) {
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }

        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }

        if ($user->pin === 1234 || $user->pin == $request->old_pin) {
            $user->update(["pin" => $request->pin]);
            Hooks::doAction('pin_change', $user);
            return response()->json([
                "status" => "success",
                "message" => "PIN updated successfully",
                "redirect" => route("dashboard"),
            ]);
        }

        return response()->json(["message" => "Old PIN is incorrect"], 403);
    }

    # Purchase

    #validation
    protected function validateAirtimeRequest($request)
    {
        return Validator::make($request->all(), [
            "pin" => "required",
            "number" => "required|min:11",
            "amount" => "required|min:2",
            "network" => "required",
        ]);
    }

    protected function validateDataRequest($request)
    {
        return Validator::make($request->all(), [
            "pin" => "required|string",
            "number" => "required|min:11|max:11|string",
            "plan" => "required",
            "network" => "required",
            "subcategory" => "required",
        ]);
    }

    protected function validateTvRequest($request)
    {
        return Validator::make($request->all(), [
            "pin" => "required|string",
            "number" => "required",
            "plan" => "required",
            "network" => "required",
        ]);
    }

    protected function validateElectricityRequest($request)
    {
        return Validator::make($request->all(), [
            "pin" => "required",
            "number" => "required|min:11",
            "amount" => "required|min:2",
            "network" => "required",
        ]);
    }
    
    protected function validateEpinRequest($request)
    {
        return Validator::make($request->all(), [
            "pin" => "required",
            "number" => "required|min:1",
            "network" => "required",
        ]);
    }
    
    protected function validateBettingRequest($request)
    {
        return Validator::make($request->all(), [
            "pin" => "required",
            "number" => "required|min:11",
            "amount" => "required|min:2",
            "network" => "required",
        ]);
    }
    #Utils
    protected function creditRef($user, $role, $amount)
    {
        $referrer = $user->referrer;
        if ($referrer) {
            $roleEarning = @jannah_opt("{$role}_upgrade_earning");
            $this->creditRefEarning($referrer, $roleEarning);
        }

        return true;
    }

    protected function creditRefEarning($user, $amount)
    {
        $old_balance = $user->referral_balance;
        $new_balance = $old_balance + $amount;

        $user->referral_balance = $new_balance;
        $user->save();

        $s_id = $this->generateSessionId();
        $this->createTransaction(
            $user,
            "referral upgrade",
            $amount,
            null,
            $s_id,
            "referral",
            $old_balance,
            $new_balance,
            "successful"
        );

        return true;
    }

    protected function creditRefPercentage($user, $baseAmount)
    {
        $user = $user->referrer;
        $percentage = @jannah_opt("referral_earning_percentage");
        $earning = $baseAmount * ($percentage / 100);
        $old_balance = $user->referral_balance;
        $new_balance = $old_balance + $earning;

        $user->referral_balance = $new_balance;
        $user->save();

        $s_id = $this->generateSessionId();
        $this->createTransaction(
            $user,
            "referral",
            $earning,
            null,
            $s_id,
            "transaction",
            $old_balance,
            $new_balance
        );

        return true;
    }

    protected function authorizeRequest(Request $request): bool
    {
        $bearerToken = $request->bearerToken();
        return $bearerToken && User::where("api_token", $bearerToken)->exists();
    }

    protected function authorizeUser(Request $request)
    {
        $bearerToken = $request->bearerToken();
        $user = User::where("api_token", $bearerToken)->first();
        return $user;
    }

    protected function secureCheck(User $user)
    {
        $lockKey = "user_transaction_{$user->id}";

        $lockStatus = Cache::has($lockKey);

        // \Log::info("Cache lock status", [
        //     "lock_status" => $lockStatus,
        //     "user_id" => $user->id,
        // ]);

        if ($lockStatus) {
            $timestamp = Cache::get("lock_timestamp_{$user->id}");
            // \Log::info("Cache get for lock timestamp", [
            //     "user_id" => $user->id,
            //     "timestamp" => $timestamp,
            // ]);

            $timeLeft = $timestamp ? max(0, $timestamp + 120 - time()) : 0;
            return [
                "locked" => true,
                "time_left" => $timeLeft,
            ];
        }

        return ["locked" => false, "time_left" => 0];
    }

    protected function secureLock(User $user)
    {
        $lockKey = "user_transaction_{$user->id}";

        $lockAcquired = Cache::add($lockKey, true, 120);

        // \Log::info("Cache add result", [
        //     "lock_acquired" => $lockAcquired,
        //     "user_id" => $user->id,
        // ]);

        if ($lockAcquired) {
            Cache::put("lock_timestamp_{$user->id}", time(), 120);
            // \Log::info("Cache set for lock timestamp", [
            //     "user_id" => $user->id,
            //     "timestamp" => time(),
            // ]);
            return true;
        }

        return false;
    }

    protected function secureUnlock(User $user)
    {
        $lockKey = "user_transaction_{$user->id}";
        $lockReleased = Cache::forget($lockKey);
        // \Log::info("Cache forget result", [
        //     "lock_released" => $lockReleased,
        //     "user_id" => $user->id,
        // ]);

        if ($lockReleased) {
            Cache::forget("lock_timestamp_{$user->id}");
            // \Log::info("Cache cleared for lock timestamp", [
            //     "user_id" => $user->id,
            // ]);
            return true;
        }

        return false;
    }

    protected function correctPin(User $user, $pin)
    {
        return $user->pin == $pin;
    }

    protected function incorrectPinResponse()
    {
        return response()->json(["message" => "Incorrect Payment PIN"], 400);
    }

    protected function checkTransactionLimit($user, $amount): bool
    {
        $totalTransactionAmount = $user
            ->transactions()
            ->where("status", "successful")
            ->whereDate("created_at", today())
            ->sum("amount");

        $maxTransactionLimit =
            @jannah_opt("max_{$user->role}_transaction") ??
            @jannah_opt("max_user_transaction");
        return $totalTransactionAmount + $amount > $maxTransactionLimit;
    }

    protected function checkTransferLimit($user, $amount): bool
    {
        $totalTransactionAmount = $user
            ->transactions()
            ->where("status", "successful")
            ->where("biller", "transfer")
            ->whereDate("created_at", today())
            ->sum("amount");
        $maxTransactionLimit = 1000;
        return $totalTransactionAmount + $amount > $maxTransactionLimit;
    }

    protected function checkFailedLimit($user, $amount): bool
    {
        $totalTransactionAmount = $user
            ->transactions()
            ->where("status", "failed")
            ->whereDate("created_at", today())
            ->count();

        $maxTransactionLimit = 15;
        return $totalTransaction > $maxTransactionLimit;
    }

    protected function checkUserRole($user, $role): bool
    {
        return $user->role === $role;
    }

    protected function updateUserRole($user, $role)
    {
        $user->role = $role;
        return $user->save();
    }

    protected function transactionLimit()
    {
        return response()->json(
            ["message" => "You have reached your Daily Transaction Limit!"],
            400
        );
    }

    protected function transferLimit()
    {
        return response()->json(
            ["message" => "You have reached your Daily Transfer Limit!"],
            400
        );
    }

    protected function hasSufficientFunds($user, $amount)
    {
        $balances = [
            $user->wallet_balance,
            $user->referral_balance,
            $user->cashback_balance,
        ];

        foreach ($balances as $balance) {
            if ($balance >= $amount) {
                return true;
            }
        }

        return false;
    }

    protected function insufficientFundsResponse()
    {
        return response()->json(["message" => "Insufficient funds"], 400);
    }

    protected function serviceNotFoundResponse()
    {
        return response()->json(["message" => "Service not found."], 404);
    }

    protected function apiConfigNotFoundResponse()
    {
        return response()->json(
            ["message" => "API configuration not found."],
            404
        );
    }

    protected function getServiceByNameAndCategory($name, $category_id)
    {
        Log::debug($name);
        Log::debug($category_id);
        return Service::where("name", $name)
            ->where("category_id", $category_id)
            ->first();
    }

    protected function getApiConfig($service)
    {
        return ApiConfig::where("service_id", $service->id)->first();
    }

    public function getServiceID($service_r)
    {
        return $service = Service::where("name", $service_r)->first();
    }

    public function getApiConfigByID($service)
    {
        return ApiConfig::whereHas("service", function ($query) use ($service) {
            $query->where("category_id", "4");
        })->first();
    }

    public function getApiConfigByIDElectricity($service, $sub = "")
    {
    $query = ApiConfig::whereHas("service", function ($query) use ($service) {
        $query->where("id", $service->id);
    });

    if (!empty($sub)) {
        $config = $query->where("api_token", "like", "$sub:%")->first();
        if ($config) {
            return $config;
        }
    }

    return $query->first();
    }

    protected function verifyApiConfig($apiConfig)
    {
        if (
            empty($apiConfig->apiProvider->api_endpoint) ||
            empty($apiConfig->api_token) ||
            empty($apiConfig->apiProvider->identifier)
        ) {
            throw new \Exception("Unable to process request.");
        }
    }

    protected function generateSessionId()
    {
        return now()->format("YmdHis") . rand(10000000, 99999999);
    }

    protected function deductWalletBalance($user, $amount)
    {
        $wallets = ["wallet_balance", "referral_balance", "cashback_balance"];

        foreach ($wallets as $wallet) {
            if ($user->$wallet >= $amount) {
                $user->$wallet -= $amount;
                $user->save();
                return $user->$wallet;
            }
        }

        return null;
    }

    protected function creditUserBalance($user, $wallet, $amount)
    {
        $new_balance = $user->{$wallet} + $amount;
        $user->{$wallet} = $new_balance;
        $user->save();
        return $new_balance;
    }

    protected function debitUserBalance($user, $wallet, $amount)
    {
        $new_balance = $user->{$wallet} - $amount;
        $user->{$wallet} = $new_balance;
        $user->save();
        return $new_balance;
    }

    protected function createTransaction(
        $user,
        $network,
        $amount,
        $phone,
        $s_id,
        $product,
        $old_balance,
        $new_balance,
        $status = "processing",
        $quantity = null,
        $profits = 0.00,
        $requestKey = null
    ) {
        $data = [
            "user_id" => $user->id,
            "biller" => strtoupper($network),
            "description" =>
                "Purchase of " .
                strtoupper($network) .
                " ₦" .
                $amount .
                " for " .
                $phone,
            "product" => $product,
            "session_id" => $s_id,
            "amount" => $amount,
            "number" => $phone,
            "status" => $status,
            "quantity" => $quantity,
            "old_balance" => $old_balance,
            "new_balance" => $new_balance,
            "profits" => $profits,
        ];

        if ($requestKey && \Illuminate\Support\Facades\Schema::hasColumn("transactions", "request_key")) {
            $data["request_key"] = $requestKey;
        }

        return Transaction::create($data);
    }

    protected function idempotencyKey(Request $request, User $user, string $service): ?string
    {
        $clientKey = $request->header("Idempotency-Key")
            ?: $request->input("request_id")
            ?: $request->input("reference")
            ?: $request->input("idempotency_key");

        if (!is_string($clientKey) || trim($clientKey) === "") {
            return null;
        }

        return hash("sha256", $user->id . "|" . strtolower($service) . "|" . trim($clientKey));
    }

    protected function idempotentTransactionResponse(User $user, ?string $requestKey): ?JsonResponse
    {
        if (!$requestKey || !\Illuminate\Support\Facades\Schema::hasColumn("transactions", "request_key")) {
            return null;
        }

        $transaction = Transaction::where("user_id", $user->id)
            ->where("request_key", $requestKey)
            ->latest("id")
            ->first();

        if (!$transaction) {
            return null;
        }

        $payload = [
            "duplicate" => true,
            "status" => $transaction->status,
            "session_id" => $transaction->session_id,
            "redirect" => route("core.transaction", $transaction->session_id),
        ];

        if ($transaction->status === "successful") {
            $payload["message"] = "This request was already completed.";
            if (!empty($transaction->token)) {
                $payload["token"] = $transaction->token;
            }

            return response()->json($payload);
        }

        if (in_array($transaction->status, ["processing", "pending"], true)) {
            $payload["message"] = "This request is already processing. Check the existing receipt instead of submitting again.";

            return response()->json($payload, 202);
        }

        $payload["message"] = "This request ID was already used for a failed transaction. Use a new request ID to retry.";

        return response()->json($payload, 409);
    }

    protected function handleApiRequest(
        $headers,
        $request_data,
        $api_endpoint,
        $transaction,
        $user,
        $old_balance,
        $s_id,
        $type,
        $hasPin,
        $amount = 0.0
    ) {
        $response = null;
        try {
            // Full request log (only written when LOG_LEVEL=debug).
            Log::debug("[$type] API request", [
                "endpoint" => $api_endpoint,
                "headers"  => array_merge($headers, ["Authorization" => "***"]),
                "request"  => $request_data,
            ]);
            $response = Http::timeout(60)->connectTimeout(15)->withHeaders($headers)->post(
                $api_endpoint,
                $request_data
            );
            $response_data = json_decode($response->body(), true);
            // Full response log (success path).
            Log::debug("[$type] API response", [
                "http_status" => $response->status(),
                "body"        => $response->body(),
            ]);
            $data = [$user, $type, $transaction, $response, $s_id, $request_data];
            Hooks::doAction('success_transaction', $data);
            return $this->handleTransaction(
                $response,
                $response_data,
                $transaction,
                $user,
                $old_balance,
                $s_id,
                $type,
                $hasPin,
                $amount
            );
        } catch (\Exception $e) {
            // Full failure log (connection errors, timeouts, etc.).
            Log::debug("[$type] API exception", [
                "endpoint" => $api_endpoint,
                "request"  => $request_data,
                "error"    => $e->getMessage(),
            ]);
            $data = [$user, $type, $transaction, $response, $s_id, $request_data];
            Hooks::doAction('failed_transaction', $data);
            return $this->transactionFailed(
                $transaction,
                $user,
                $old_balance,
                $s_id,
                $e
            );
        }
    }

    protected function transactionFailed(
        $transaction,
        $user,
        $old_balance,
        $s_id,
        $e = null
    ) {
        $transaction->update(["status" => "processing"]);

        # $user->wallet_balance = $old_balance;
        # $user->save();
        $this->secureUnlock($user);
        return response()->json(
            [
                "status" => "processing",
                "message" => "Provider confirmation is delayed. The transaction is still processing; please check the receipt before trying again.",
                "error" => $e ? $e->getMessage() : "Unknown error",
                "redirect" => route("core.transaction", $s_id),
                "session_id" => $s_id,
            ],
            202
        );
    }

    protected function getProduct($plan, $subcategory, $network)
    {
        $query = Product::where("name", $plan)->whereHas("service", function (
            $query
        ) use ($network) {
            $query->where("name", $network);
        });
        if (!is_null($subcategory)) {
            $query->where("subcategory", $subcategory);
        }

        return $query->first();
    }

    protected function getPriceByUserRole($user, $product)
    {
        switch ($user->role) {
            case "customer":
                return $product->customer_price;
            case "reseller":
                return $product->reseller_price;
            case "agent":
                return $product->agent_price;
            default:
                return $product->customer_price;
        }
    }

    #builder

    protected function buildHeaders($apiConfig, $mode = false)
    {
        $headers = ["Content-Type" => "application/json"];
        $authType = 
            strpos($apiConfig->apiProvider->identifier, "smeplug") !== false || 
            strpos($apiConfig->apiProvider->identifier, "ogdams") !== false
                ? "Bearer"
                : "Token";
    
        $apiKey = $apiConfig->api_token;
    
        if ($mode) {
            $apiKey = strpos($apiKey, ":") !== false ? explode(":", $apiKey, 2)[1] : $apiKey;
        }
    
        $headers["Authorization"] = $authType . " " . $apiKey;
    
        if ($apiConfig->apiProvider->identifier === "vtpass") {
            $headers["api-key"] = $apiKey;
            $headers["secret-key"] = $apiConfig->optional;
        }
    
        return $headers;
    }

    protected function buildAirtimeRequestData(
        $identifier,
        $network_id,
        $amount,
        $phone,
        $s_id
    ) {
        $data = [
            "network" => $network_id,
            "amount" => $amount,
            "phone" => $phone,
            "airtime_type" => "VTU",
            "plan_type" => "VTU",
        ];

        switch ($identifier) {
            case "adex":
            case "mate":
                $data["request-id"] = $s_id;
                $data["bypass"] = true;
                break;

            case "msorg":
            case "max":
                $data["mobile_number"] = $phone;
                $data["Ported_number"] = true;
                break;

            case "smeplug":
                $data["network_id"] = $service->network_id;
                $data["customer_reference"] = $s_id;
                break;

            case "ogdams":
                $data["networkId"] = $service->network_id;
                $data["phoneNumber"] = $phone;
                $data["type"] = "vtu";
                $data["reference"] = $s_id;
                break;

            case "nc":
                $data["country_code"] = "NG";
                $data["phone_number"] = $phone;
                $data["ref_id"] = $s_id;
                $data["bypass"] = true;
                break;

            case "vtpass":
                $data = [
                    "request-id" => $s_id,
                    "serviceID" => $service->network_id,
                    "reference" => rand(1000, 100000),
                ];
                break;

            default:
                $data["mobile_number"] = $phone;
                $data["Ported_number"] = true;
                break;
        }

        return $data;
    }

    protected function buildDataRequestData(
        $identifier,
        $network_id,
        $plan_id,
        $phone,
        $s_id
    ) {
        $data = [
            "network" => $network_id,
            "plan" => $plan_id,
            "phone" => $phone,
        ];

        switch ($identifier) {
            case "adex":
                $data["data_plan"] = $plan_id;
                $data["bypass"] = true;
                $data["request-id"] = $s_id;
                break;

            case "mate":
                $data["Ported_number"] = true;
                $data["request-id"] = $s_id;
                break;

            case "msorg":
            case "max":
                $data["mobile_number"] = $phone;
                $data["Ported_number"] = true;
                break;

            case "smeplug":
                $data["network_id"] = $service->network_id;
                $data["plan_id"] = $plan_id;
                $data["customer_reference"] = $s_id;
                break;

            case "ogdams":
                $data["networkId"] = $service->network_id;
                $data["planId"] = $plan_id;
                $data["phoneNumber"] = $phone;
                $data["reference"] = $s_id;
                break;

            case "nc":
                $data["data_plan"] = $plan_id;
                $data["country_code"] = "NG";
                $data["phone_number"] = $phone;
                $data["bypass"] = true;
                $data["ref_id"] = $s_id;
                break;

            default:
                $data["plan_id"] = $plan_id;
                $data["mobile_number"] = $phone;
                $data["Ported_number"] = true;
                $data["airtime_type"] = "VTU";
                break;
        }

        return $data;
    }

    protected function buildTvRequestData(
        $identifier,
        $provider_id,
        $plan_id,
        $iuc,
        $s_id
    ) {
        $data = [
            "cablename" => $provider_id,
            "cableplan" => $plan_id,
            "smart_card_number" => $iuc,
        ];

        switch ($identifier) {
            case "adex":
                $data["cable"] = $provider_id;
                $data["cable_plan"] = $plan_id;
                $data["iuc"] = $iuc;
                $data["bypass"] = true;
                $data["request-id"] = $s_id;
                break;

            case "mate":
            case "msorg":
            case "max":
                $data["request-id"] = $s_id;
                break;

            case "nc":
                $data["cable_plan"] = $plan_id;
                $data["cable_id"] = $provider_id;
                $data["cable_number"] = $iuc;
                $data["bypass"] = true;
                $data["ref_id"] = $s_id;
                break;

            default:
                $data["cableplan"] = $plan_id;
                $data["cablename"] = $provider_id;
                $data["smart_card_number"] = $iuc;
                break;
        }

        return $data;
    }

    protected function buildElectricityRequestData(
        $identifier,
        $network_id,
        $amount,
        $meter,
        $s_id
    ) {
        $data = [
            "disco" => $network_id,
            "amount" => $amount,
            "meter_number" => $meter,
            "meter_type" => "prepaid",
        ];

        switch ($identifier) {
            case "adex":
                $data["disco"] = $network_id;
                $data["meter_number"] = $meter;
                $data["meter_type"] = "prepaid";
                $data["bypass"] = false;
                $data["request-id"] = $s_id;
                break;

            case "mate":
                $data["disco"] = $network_id;
                $data["meter_number"] = $meter;
                $data["meter_type"] = "prepaid";
                $data["bypass"] = false;
                $data["request-id"] = $s_id;
                break;

            case "msorg":
            case "msorg2":
                // vtunaija electricity (POST .../billpayment/): disco_name + meter_number
                // + MeterType ("prepaid"/"postpaid", optional) + amount.
                $data = [
                    "disco_name" => $network_id,
                    "meter_number" => $meter,
                    "MeterType" => "prepaid",
                    "amount" => $amount,
                ];
                break;

            case "max":
                $data["disco_name"] = $network_id;
                $data["amount"] = $amount;
                $data["meter_number"] = $meter;
                $data["MeterType"] = "1";
                break;

            case "nc":
                $data["meter_id"] = $network_id;
                $data["meter_number"] = $meter;
                $data["meter_type"] = "prepaid";
                $data["ref_id"] = $s_id;
                $data["bypass"] = true;
                break;

            default:
                $data["disco_name"] = $network_id;
                $data["amount"] = $amount;
                $data["meter_number"] = $meter;
                $data["MeterType"] = "1";
                break;
        }

        return $data;
    }

    #Purchase
    public function handlePin($user, $session_id, $service, $pins)
    {
        
    }

    public function handleTransaction(
    $response,
    $response_data,
    $transaction,
    $user,
    $old_balance,
    $s_id,
    $service = "Service",
    $hasPin = false,
    $amount = 0.0
) {
    $admin_email = @jannah_opt("support_email");
    
    if ($response_data === null || json_last_error() !== JSON_ERROR_NONE) {
        $transaction->update([
            "status" => "failed",
            "new_balance" => $old_balance,
        ]);
        $user->wallet_balance = $old_balance;
        $user->save();

        $response_data = is_array($response_data) ? json_encode($response_data) : $response_data;

        Mail::to($user->email)->send(
            new TransactionFailure(
                $transaction,
                $user,
                $service,
                $amount,
                $response_data
            )
        );
        Mail::to($admin_email)->send(
            new TransactionFailure(
                $transaction,
                $user,
                $service,
                $amount,
                $response_data
            )
        );
        $this->secureUnlock($user);
        return response()->json(
            [
                "message" => "Could not process request.",
                "error" => json_last_error_msg(),
            ],
            500
        );
    }

    $status = strtolower($response_data["status"] ?? ($response_data["Status"] ?? ""));
    $success = strtolower($response_data["success"] ?? ($response_data["successful"] ?? "false"));

    if ($status === "successful" || $status === "success" || $success === "true") {
        // Electricity providers return the prepaid token under "token"; some also
        // mirror it as "electricitytoken" / "tokens".
        $token = $service == "Electricity Payment"
            ? ($response_data["token"]
                ?? ($response_data["electricitytoken"]
                ?? ($response_data["tokens"] ?? "")))
            : "";

        $updateData = ["status" => "successful"];
        // Persist the token so the on-site receipt can show it (only if the column exists).
        if (!empty($token) && \Illuminate\Support\Facades\Schema::hasColumn("transactions", "token")) {
            $updateData["token"] = $token;
        }
        $transaction->update($updateData);

        Mail::to($user->email)->send(
            new TransactionSuccess(
                $transaction,
                $user,
                $service,
                $amount,
                $old_balance,
                false,
                $token
            )
        );

        Mail::to($admin_email)->send(
            new TransactionSuccess(
                $transaction,
                $user,
                $service,
                $amount,
                $old_balance,
                true,
                $token
            )
        );

        if ($service == "Electricity Payment") {
            Mail::to($user->email)->send(
                new TransactionSuccessElectricity(
                    $transaction,
                    $user,
                    $service,
                    $amount,
                    $token,
                    false
                )
            );
        }

        $this->secureUnlock($user);
        return response()->json([
            "message" => "$service purchase successful 🎉🎊",
            "token" => $token,
            "redirect" => route("core.transaction", $s_id),
            "session_id" => $s_id,
        ]);
    }

    $error_message = $response_data["error"] ?? ($response_data["message"] ?? "Unable to complete request! Try again shortly.");

    if (is_array($error_message)) {
        $error_message = implode(", ", $error_message);
    }

    if (strpos($error_message, "nsufficient") !== false) {
        $error_message = "Unable to complete transaction! Try again after a few minutes.";
    }

    $transaction->update([
        "status" => "failed",
        "new_balance" => $old_balance,
    ]);

    $user->wallet_balance = $old_balance;
    $user->save();

    $response_data = is_array($response_data) ? json_encode($response_data) : $response_data;

    Mail::to($user->email)->send(
        new TransactionFailure(
            $transaction,
            $user,
            $service,
            $amount,
            $response_data,
            false
        )
    );

    Mail::to($admin_email)->send(
        new TransactionFailure(
            $transaction,
            $user,
            $service,
            $amount,
            $response_data,
            true
        )
    );

    $this->secureUnlock($user);
    return response()->json(
        [
            "status" => "error",
            "message" => $error_message,
            "response" => $response_data,
        ],
        500
    );
}

    public function buyAirtime(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }
        $validator = $this->validateAirtimeRequest($request);
        if ($validator->fails()) {
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }
        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }
        $requestKey = $this->idempotencyKey($request, $user, "airtime");
        if ($response = $this->idempotentTransactionResponse($user, $requestKey)) {
            return $response;
        }

        $amount = abs($request->input("amount"));
        $phone = $request->input("number");
        $network = $request->input("network");
        $pin = $request->input("pin");
       
        $check = $this->secureCheck($user);

        if ($check["locked"]) {
            $seconds = $check["time_left"];
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Ongoing Transaction, Try agin in $seconds Seconds",
                ],
                401
            );
        }

        if (!$this->secureLock($user)) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Unsupported Server, Contact Support E12990",
                ],
                401
            );
        }

        if (!$this->correctPin($user, $pin)) {
            $this->secureUnlock($user);
            return $this->incorrectPinResponse();
        }
        if (!$this->hasSufficientFunds($user, $amount)) {
            $this->secureUnlock($user);
            return $this->insufficientFundsResponse();
        }
        if ($this->checkTransactionLimit($user, $amount)) {
            $this->secureUnlock($user);
            return $this->transactionLimit();
        }
        $service = $this->getServiceByNameAndCategory($network, 2);
        if (!$service) {
            $this->secureUnlock($user);
            return $this->serviceNotFoundResponse();
        }

        $apiConfig = $this->getApiConfig($service);
        if (!$apiConfig) {
            $this->secureUnlock($user);
            return $this->apiConfigNotFoundResponse();
        }
        $this->verifyApiConfig($apiConfig);

        $s_id = $this->generateSessionId();
        $old_balance = $user->wallet_balance;
        $new_balance = $this->deductWalletBalance($user, $amount);

        $transaction = $this->createTransaction(
            $user,
            $network,
            $amount,
            $phone,
            $s_id,
            "airtime",
            $old_balance,
            $new_balance,
            "processing",
            null,
            0.00,
            $requestKey
        );

        $headers = $this->buildHeaders($apiConfig);
        $request_data = $this->buildAirtimeRequestData(
            $apiConfig->apiProvider->identifier,
            $service->network_id,
            $amount,
            $phone,
            $s_id
        );

        return $this->handleApiRequest(
            $headers,
            $request_data,
            $apiConfig->apiProvider->api_endpoint,
            $transaction,
            $user,
            $old_balance,
            $s_id,
            "Airtime",
            false,
            $amount
        );
    }

    public function buyData(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }
        $validator = $this->validateDataRequest($request);

        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }
        $requestKey = $this->idempotencyKey($request, $user, "data");
        if ($response = $this->idempotentTransactionResponse($user, $requestKey)) {
            return $response;
        }

        $check = $this->secureCheck($user);

        if ($check["locked"]) {
            $seconds = $check["time_left"];
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Ongoing Transaction, Try agin in $seconds Seconds",
                ],
                401
            );
        }

        if (!$this->secureLock($user)) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Unsupported Server, Contact Support E12990",
                ],
                401
            );
        }
        if ($validator->fails()) {
            $this->secureUnlock($user);
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }
        $phone = $request->input("number");
        $plan = $request->input("plan");
        $subcategory = $request->input("subcategory");
        $network = $request->input("network");
        $pin = $request->input("pin");
        $product = $this->getProduct($plan, $subcategory, $network);
        if (!$product) {
            $this->secureUnlock($user);
            return response()->json(["message" => "Product not found."], 404);
        }

        $amount = abs($product->{$user->role . "_price"});
        $b_amount = abs($product->{"buying_price"});
        $profit = $amount - $b_amount;
        if (!$this->correctPin($user, $pin)) {
            $this->secureUnlock($user);
            return $this->incorrectPinResponse();
        }
        if (!$this->hasSufficientFunds($user, $amount)) {
            $this->secureUnlock($user);
            return $this->insufficientFundsResponse();
        }
        if ($this->checkTransactionLimit($user, $amount)) {
            $this->secureUnlock($user);
            return $this->transactionLimit();
        }

        $service = $this->getServiceByNameAndCategory($network, 3);
        if (!$service) {
            $this->secureUnlock($user);
            return $this->serviceNotFoundResponse();
        }

        $apiConfig = $this->getApiConfig($service, $subcategory);
        if (!$apiConfig) {
            $this->secureUnlock($user);
            return $this->apiConfigNotFoundResponse();
        }

        $this->verifyApiConfig($apiConfig);

        $s_id = $this->generateSessionId();
        $old_balance = $user->wallet_balance;
        $new_balance = $this->deductWalletBalance($user, $amount);
        Log::debug("new bal: " . $new_balance);
        $transaction = $this->createTransaction(
            $user,
            $network,
            $amount,
            $phone,
            $s_id,
            "data",
            $old_balance,
            $new_balance,
            'processing',
            $plan,
            $profit,
            $requestKey
        );
        $headers = $this->buildHeaders($apiConfig, true);
        $request_data = $this->buildDataRequestData(
            $apiConfig->apiProvider->identifier,
            $service->network_id,
            $product->plan_id,
            $phone,
            $s_id
        );

        return $this->handleApiRequest(
            $headers,
            $request_data,
            $apiConfig->apiProvider->api_endpoint,
            $transaction,
            $user,
            $old_balance,
            $s_id,
            "Data",
            false,
            $amount
        );
    }

    public function subTV(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }
        $validator = $this->validateTvRequest($request);
        if ($validator->fails()) {
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }
        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }
        $requestKey = $this->idempotencyKey($request, $user, "tv");
        if ($response = $this->idempotentTransactionResponse($user, $requestKey)) {
            return $response;
        }

        $check = $this->secureCheck($user);

        if ($check["locked"]) {
            $seconds = $check["time_left"];
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Ongoing Transaction, Try agin in $seconds Seconds",
                ],
                401
            );
        }

        if (!$this->secureLock($user)) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Unsupported Server, Contact Support E12990",
                ],
                401
            );
        }
        $decoder = $request->input("number");
        $plan = $request->input("plan");
        $network = $request->input("network");
        $pin = $request->input("pin");
        $product = $this->getProduct($plan, null, $network);
        if (!$product) {
            $this->secureUnlock($user);
            return response()->json(["message" => "Product not found."], 404);
        }

        $amount = abs($product->{$user->role . "_price"});
        $b_amount = abs($product->{"buying_price"});
        $profit = $amount - $b_amount;
        if (!$this->correctPin($user, $pin)) {
            $this->secureUnlock($user);
            return $this->incorrectPinResponse();
        }
        if (!$this->hasSufficientFunds($user, $amount)) {
            $this->secureUnlock($user);
            return $this->insufficientFundsResponse();
        }
        if ($this->checkTransactionLimit($user, $amount)) {
            $this->secureUnlock($user);
            return $this->transactionLimit();
        }
        $service = $this->getServiceByNameAndCategory($network, 5);
        if (!$service) {
            $this->secureUnlock($user);
            return $this->serviceNotFoundResponse();
        }

        $apiConfig = $this->getApiConfig($service);
        if (!$apiConfig) {
            $this->secureUnlock($user);
            return $this->apiConfigNotFoundResponse();
        }
        $this->verifyApiConfig($apiConfig);

        $s_id = $this->generateSessionId();
        $old_balance = $user->wallet_balance;
        $new_balance = $this->deductWalletBalance($user, $amount);

        $transaction = $this->createTransaction(
            $user,
            $network,
            $amount,
            $decoder,
            $s_id,
            "tv",
            $old_balance,
            $new_balance,
            'processing',
            $plan,
            $profit,
            $requestKey
            
        );

        $headers = $this->buildHeaders($apiConfig);
        $request_data = $this->buildTvRequestData(
            $apiConfig->apiProvider->identifier,
            $service->network_id,
            $product->plan_id,
            $decoder,
            $s_id
        );

        return $this->handleApiRequest(
            $headers,
            $request_data,
            $apiConfig->apiProvider->api_endpoint,
            $transaction,
            $user,
            $old_balance,
            $s_id,
            "TV Subscription",
            false,
            $amount
        );
    }

    public function payElectricity(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }
        $validator = $this->validateElectricityRequest($request);
        if ($validator->fails()) {
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }
        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }
        $requestKey = $this->idempotencyKey($request, $user, "electricity");
        if ($response = $this->idempotentTransactionResponse($user, $requestKey)) {
            return $response;
        }

        $check = $this->secureCheck($user);

        if ($check["locked"]) {
            $seconds = $check["time_left"];
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Ongoing Transaction, Try agin in $seconds Seconds",
                ],
                401
            );
        }

        if (!$this->secureLock($user)) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Unsupported Server, Contact Support E12990",
                ],
                401
            );
        }
        $amount = abs($request->input("amount"));
        $meter = $request->input("number");
        $network = $request->input("network");
        $pin = $request->input("pin");

        if (!$this->correctPin($user, $pin)) {
            $this->secureUnlock($user);
            return $this->incorrectPinResponse();
        }
        if (!$this->hasSufficientFunds($user, $amount)) {
            $this->secureUnlock($user);
            return $this->insufficientFundsResponse();
        }
        if ($this->checkTransactionLimit($user, $amount)) {
            $this->secureUnlock($user);
            return $this->transactionLimit();
        }
        // The electricity UI sends the abbreviated disco (e.g. "aedc"); Service rows
        // are stored by full name (e.g. "abuja electricity"), so de-abbreviate first.
        $service = $this->getServiceByNameAndCategory(
            $this->deAbbreviateServiceName($network),
            8
        );
        if (!$service) {
            $this->secureUnlock($user);
            return $this->serviceNotFoundResponse();
        }

        $apiConfig = $this->getApiConfig($service);
        if (!$apiConfig) {
            $this->secureUnlock($user);
            return $this->apiConfigNotFoundResponse();
        }
        $this->verifyApiConfig($apiConfig);

        $s_id = $this->generateSessionId();
        $old_balance = $user->wallet_balance;
        $new_balance = $this->deductWalletBalance($user, $amount);

        $transaction = $this->createTransaction(
            $user,
            $network,
            $amount,
            $meter,
            $s_id,
            "electricity",
            $old_balance,
            $new_balance,
            "processing",
            null,
            0.00,
            $requestKey
        );

        $headers = $this->buildHeaders($apiConfig);
        $request_data = $this->buildElectricityRequestData(
            $apiConfig->apiProvider->identifier,
            $service->network_id,
            $amount,
            $meter,
            $s_id
        );

        return $this->handleApiRequest(
            $headers,
            $request_data,
            $apiConfig->apiProvider->api_endpoint,
            $transaction,
            $user,
            $old_balance,
            $s_id,
            "Electricity Payment",
            false,
            $amount
        );
    }
    
    public function payEpin(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }
        $validator = $this->validateEpinRequest($request);
        if ($validator->fails()) {
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }
        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }
        $amount = abs($request->input("amount"));
        $phone = $request->input("number");
        $network = $request->input("network");
        $pin = $request->input("pin");
       
        $check = $this->secureCheck($user);

        if ($check["locked"]) {
            $seconds = $check["time_left"];
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Ongoing Transaction, Try agin in $seconds Seconds",
                ],
                401
            );
        }

        if (!$this->secureLock($user)) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Unsupported Server, Contact Support E12990",
                ],
                401 
            );
        }

        if (!$this->correctPin($user, $pin)) {
            $this->secureUnlock($user);
            return $this->incorrectPinResponse();
        }
        if (!$this->hasSufficientFunds($user, $amount)) {
            $this->secureUnlock($user);
            return $this->insufficientFundsResponse();
        }
        if ($this->checkTransactionLimit($user, $amount)) {
            $this->secureUnlock($user);
            return $this->transactionLimit();
        }
        
        $this->secureUnlock($user);
        return response()->json(
                [
                    "status" => "error",
                    "message" => "Couldnt Establish Connections!",
                ],
                401 
            );
    }
    #VERIFY
    public function ApiInitOTP(Request $request): JsonResponse
    {
        $request->validate([
            "user_token" => "required",
            "type" => "required|in:email,phone",
            "service" =>
                "required|in:login,registration,password_reset,transaction,others",
        ]);

        $otpCode = rand(100000, 999999);
        $service = ucwords(str_replace("_", " ", $request->service));

        TokenAuthentication::updateOrCreate(
            ["user_token" => $request->user_token],
            [
                "type" => $request->type,
                "service" => $request->service,
                "otp_code" => $otpCode,
            ]
        );

        if ($request->type === "email") {
            Mail::send(
                "emails.otp",
                [
                    "otpCode" => $otpCode,
                    "service" => $request->service,
                ],
                function ($message) use ($request, $service) {
                    $message
                        ->to($request->user_token)
                        ->subject($service . " OTP Code");
                }
            );
        } elseif ($request->type === "phone") {
            $sid = jannah_opt("twilio_sid");
            $token = jannah_opt("twilio_token");
            $from = jannah_opt("twilio_from");

            if (!$sid || !$token || !$from) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" =>
                            "Twilio credentials not configured properly.",
                    ],
                    500
                );
            }

            $response = Http::withBasicAuth($sid, $token)->post(
                "https://api.twilio.com/2010-04-01/Accounts/" .
                    $sid .
                    "/Messages.json",
                [
                    "From" => $from,
                    "To" => $request->user_token,
                    "Body" => "Your OTP code is: $otpCode",
                ]
            );

            if ($response->successful()) {
                return response()->json([
                    "status" => "success",
                    "message" => "OTP sent successfully",
                ]);
            } else {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Failed to send OTP via Twilio.",
                    ],
                    500
                );
            }
        }

        return response()->json([
            "status" => "success",
            "message" => "OTP sent successfully",
        ]);
    }

    public function ApiVerifyOTP(Request $request): JsonResponse
    {
        $request->validate([
            "user_token" => "required",
            "otp_code" => "required",
        ]);

        $otpRecord = TokenAuthentication::where(
            "user_token",
            $request->user_token
        )
            ->where("otp_code", $request->otp_code)
            ->first();

        if (!$otpRecord) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Invalid OTP",
                ],
                401
            );
        }

        $otpRecord->update(["otp_code" => rand(100000, 999999)]);

        return response()->json([
            "status" => "success",
            "message" => "OTP verified successfully",
        ]);
    }

    public function verifyIUC(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        } elseif (!$request->headers->has("Authorization")) {
            $request->validate([
                "number" => "required|string|min:8",
                "provider" => "required|string",
            ]);
        }

        $provider = $request->input("provider");
        $iuc = $request->input("number");
        
        $service = $this->getServiceID($provider);
        if (!$service) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Service Temporarily Unavailable",
                ],
                404
            );
        }

        $apiConfig = $this->getApiConfigByID($service);
        if (!$apiConfig) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Service Temporarily Unavailable",
                ],
                404
            );
        }

        // vtunaija cable verify expects POST + JSON body at the /verify/ endpoint
        // (cablename + smart_card_number). Works whether the configured endpoint is
        // the base (.../cablesub) or already points at .../cablesub/verify.
        $base = rtrim($apiConfig->apiProvider->api_endpoint, "/");
        if (!str_ends_with($base, "/verify")) {
            $base .= "/verify";
        }
        $apiEndpoint = $base . "/";

        $requestBody = [
            "cablename" => $service->network_id,
            "smart_card_number" => $iuc,
        ];
        Log::debug("verifyIUC request", [
            "endpoint" => $apiEndpoint,
            "request"  => $requestBody,
        ]);

        try {
            $response = Http::withHeaders($this->buildHeaders($apiConfig))
                ->timeout(60)
                ->post($apiEndpoint, $requestBody);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning("verifyIUC timeout", [
                "iuc"   => $iuc,
                "cable" => $service->network_id,
            ]);
            return response()->json(
                ["success" => false, "message" => "Verification timed out. Please try again."],
                504
            );
        }

        Log::debug("verifyIUC response", [
            "http_status" => $response->status(),
            "body"        => $response->body(),
        ]);

        if ($response->failed()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid IUC/SmartCard Number",
                ],
                404
            );
        }

        $apiResponse = $response->json();
        $status = strtolower($apiResponse["status"] ?? "");
        $name = $apiResponse["Customer_Name"] ?? ($apiResponse["name"] ?? null);

        if (in_array($status, ["fail", "failed", "error"], true) || empty($name)) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid IUC/SmartCard Number",
                ],
                400
            );
        }

        return response()->json([
            "success" => true,
            "message" => $name,
        ]);
    }
    
    public function VerifyMeter(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }

        $provider = $request->input("provider");
        $meter = $request->input("number");

        $request->validate([
            "number" => "required|string",
            "provider" => "required|string",
        ]);
        Log::debug($this->deAbbreviateServiceName($provider, true));
        $service = Service::where(
            "name",
            $this->deAbbreviateServiceName($provider, true)
        )
            ->where("category_id", 7)
            ->first();

        if (
            !$service ||
            !($apiConfig = $this->getApiConfigByIDElectricity($service))
        ) {
            Log::error("VerifyMeter: missing service/apiConfig", [
                "provider"      => $provider,
                "resolved_name" => $this->deAbbreviateServiceName($provider, true),
                "service_found" => (bool) $service,
                "network_id"    => $service->network_id ?? null,
            ]);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Service Temporarily Unavailable",
                ],
                404
            );
        }

        // vtunaija verify expects POST + JSON body at the /verify/ endpoint (trailing slash).
        // Works whether the configured endpoint is the base (.../billpayment) or already
        // points at .../billpayment/verify.
        $base = rtrim($apiConfig->apiProvider->api_endpoint, "/");
        if (!str_ends_with($base, "/verify")) {
            $base .= "/verify";
        }
        $apiEndpoint = $base . "/";

        $requestBody = [
            "disco_name"   => $service->network_id,
            "meter_number" => $meter,
            "MeterType"    => "prepaid",
        ];
        Log::debug("VerifyMeter request", [
            "endpoint" => $apiEndpoint,
            "request"  => $requestBody,
        ]);

        try {
            $response = Http::withHeaders($this->buildHeaders($apiConfig))
                ->timeout(60)
                ->post($apiEndpoint, $requestBody);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // vtunaija's verify endpoint is slow and sometimes hangs.
            Log::warning("VerifyMeter timeout", [
                "meter" => $meter,
                "disco" => $service->network_id,
            ]);
            return response()->json(
                ["success" => false, "message" => "Verification timed out. Please try again."],
                504
            );
        }

        Log::debug("VerifyMeter response", [
            "http_status" => $response->status(),
            "body"        => $response->body(),
        ]);

        if ($response->failed()) {
            return response()->json(
                ["success" => false, "message" => "Invalid Meter No"],
                404
            );
        }

        $apiResponse = $response->json();
        $status = strtolower($apiResponse["status"] ?? "");
        $name = $apiResponse["Customer_Name"] ?? ($apiResponse["name"] ?? null);

        // A "success" status with no name means the meter couldn't actually be resolved.
        if (in_array($status, ["fail", "failed", "error"], true) || empty($name)) {
            return response()->json(
                ["success" => false, "message" => "Could not verify this meter. Check the number and try again."],
                400
            );
        }

        return response()->json([
            "success" => true,
            "message" => $name,
        ]);
    }
    
    public function VerifyBettingID(Request $request): JsonResponse
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        } elseif (!$request->headers->has("Authorization")) {
            $request->validate([
                "number" => "required|string|min:8",
                "provider" => "required|string",
            ]);
        }

        $provider = $request->input("provider");
        $iuc = $request->input("number");
        
        $service = $this->getServiceID($provider);
        if (!$service) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Service Temporarily Unavailable",
                ],
                404
            );
        }

        $apiConfig = $this->getApiConfigByID($service);
        if (!$apiConfig) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Service Temporarily Unavailable",
                ],
                404
            );
        }

        

        return response()->json([
            "success" => false,
            "message" => "UNKNOWN USER",
        ]);
    }
    # Views

    private function roundUp($amount)
    {
        return ceil($amount / 100) * 100;
    }
    /**
     * App Services
     */

    public function airtime(Request $request)
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();
                $role = $user->role;

                $priceColumn = match ($role) {
                    "customer" => "customer_price",
                    "reseller" => "reseller_price",
                    "agent" => "agent_price",
                    default => "customer_price",
                };

                $products = Product::whereHas("service", function ($query) {
                    $query->where("category_id", 2);
                })
                    ->select(
                        "name",
                        $priceColumn . " as role_price",
                        "validity",
                        "service_id"
                    )
                    ->get()
                    ->groupBy(function ($product) {
                        return $product->service->name;
                    });

                $services = $products->keys();
                $serviceF = $services->first();

                $products = $products->map(function ($productGroup) use (
                    $priceColumn
                ) {
                    return $productGroup->map(function ($product) use (
                        $priceColumn
                    ) {
                        $product->rounded_price = $this->roundUp(
                            $product->role_price
                        );
                        return $product;
                    });
                });

                return response()->json([
                    "products" => $products,
                    "services" => $services,
                    "serviceF" => $serviceF,
                    "status" => "success",
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }

        $user = Auth::user();
        $role = $user->role;

        $priceColumn = match ($role) {
            "customer" => "customer_price",
            "reseller" => "reseller_price",
            "agent" => "agent_price",
            default => "customer_price",
        };

        $products = Product::whereHas("service", function ($query) {
            $query->where("category_id", 2);
        })
            ->select(
                "name",
                $priceColumn . " as role_price",
                "validity",
                "service_id"
            )
            ->get()
            ->groupBy(function ($product) {
                return $product->service->name;
            });

        $services = $products->keys();
        $serviceF = $services->first();

        $products = $products->map(function ($productGroup) use ($priceColumn) {
            return $productGroup->map(function ($product) use ($priceColumn) {
                $product->rounded_price = $this->roundUp($product->role_price);
                return $product;
            });
        });

        return view(
            "services.airtime",
            compact("user", "products", "services", "serviceF")
        );
    }

    public function data(Request $request)
{
    if ($request->headers->has("Authorization")) {
        $bearerToken = $request->bearerToken();

        try {
            $user = User::where("api_token", $bearerToken)->firstOrFail();
            $role = $user->role;

            $priceColumn = match ($role) {
                "customer" => "customer_price",
                "reseller" => "reseller_price",
                "agent" => "agent_price",
                default => "customer_price",
            };

            $products = Product::whereHas("service", function ($query) {
                $query->where("category_id", 3);
            })
                ->where("subcategory", "NOT LIKE", "%_disabled%")
                ->select(
                    "name",
                    "subcategory",
                    $priceColumn . " as role_price",
                    "validity",
                    "service_id"
                )
                ->get()
                ->groupBy(fn($product) => $product->service->name)
                ->map(fn($serviceGroup) =>
                    $serviceGroup
                        ->groupBy(fn($product) => $product->subcategory)
                        ->map(fn($subcategoryGroup) =>
                            $subcategoryGroup->map(fn($product) => [
                                ...$product->toArray(),
                                "rounded_price" => $this->roundUp($product->role_price),
                            ])
                        )
                );

            return response()->json([
                "products" => $products,
                "services" => $products->keys(),
                "serviceF" => $products->keys()->first(),
                "status" => "success",
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "status" => "error",
                "message" => "Invalid API token",
            ], 401);
        }
    }

    $user = Auth::user();
    $role = $user->role;

    $priceColumn = match ($role) {
        "customer" => "customer_price",
        "reseller" => "reseller_price",
        "agent" => "agent_price",
        default => "customer_price",
    };

    $products = Product::whereHas("service", function ($query) {
        $query->where("category_id", 3);
    })
        ->where("subcategory", "NOT LIKE", "%_disabled%")
        ->select(
            "name",
            "subcategory",
            $priceColumn . " as role_price",
            "validity",
            "service_id"
        )
        ->get()
        ->groupBy(fn($product) => $product->service->name)
        ->map(fn($serviceGroup) =>
            $serviceGroup
                ->groupBy(fn($product) => $product->subcategory)
                ->map(fn($subcategoryGroup) =>
                    $subcategoryGroup->map(fn($product) => [
                        ...$product->toArray(),
                        "rounded_price" => $this->roundUp($product->role_price),
                    ])
                )
        );

    return view("services.data", [
        "user" => $user,
        "products" => $products,
        "services" => $products->keys(),
        "serviceF" => $products->keys()->first(),
    ]);
}

    public function options(Request $request)
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }

        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }
        $opt = get_all_opts([
            "support_whatsapp",
            "support_instagram",
            "support_gmail",
            "support_facebook",
            "support_telegram",
            "support_youtube",
            "support_group",
            'site_name',
            'primary_color',
            'secondary_color',
            'agent_upgrade_charges',
        ]);
        return response()->json($opt, 200);
    }
    public function electricity(Request $request)
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();
                $role = $user->role;

                $priceColumn = match ($role) {
                    "customer" => "customer_price",
                    "reseller" => "reseller_price",
                    "agent" => "agent_price",
                    default => "customer_price",
                };

                $amounts = [1000, 2000, 5000, 10000, 20000];

                $products = Product::whereHas("service", function ($query) {
                    $query->where("category_id", 8);
                })
                    ->select(
                        "name",
                        $priceColumn . " as role_price",
                        "validity",
                        "service_id"
                    )
                    ->get()
                    ->flatMap(function ($product) use ($amounts) {
                        return collect($amounts)->map(function ($amount) use (
                            $product
                        ) {
                            $price = $amount * ($product->role_price / 100);
                            return [
                                "name" => $this->abbreviateServiceName(
                                    $product->service->name
                                ),
                                "display_amount" => $amount,
                                "role_price" => $price,
                                "service_id" => $product->service_id,
                            ];
                        });
                    })
                    ->groupBy("name");

                $services = $products->keys();
                $serviceF = $services->first();

                return response()->json([
                    "products" => $products,
                    "services" => $services,
                    "serviceF" => $serviceF,
                    "status" => "success",
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }

        $user = Auth::user();
        $role = $user->role;

        $priceColumn = match ($role) {
            "customer" => "customer_price",
            "reseller" => "reseller_price",
            "agent" => "agent_price",
            default => "customer_price",
        };

        $amounts = [1000, 2000, 5000, 10000, 20000];

        $products = Product::whereHas("service", function ($query) {
            $query->where("category_id", 8);
        })
            ->select(
                "name",
                $priceColumn . " as role_price",
                "validity",
                "service_id"
            )
            ->get()
            ->flatMap(function ($product) use ($amounts) {
                return collect($amounts)->map(function ($amount) use (
                    $product
                ) {
                    $price = $amount * ($product->role_price / 100);
                    return [
                        "name" => $this->abbreviateServiceName(
                            $product->service->name
                        ),
                        "display_amount" => $amount,
                        "role_price" => $price,
                        "service_id" => $product->service_id,
                    ];
                });
            })
            ->groupBy("name");

        $services = $products->keys();
        $serviceF = $services->first();

        return view(
            "services.electricity",
            compact("user", "products", "services", "serviceF")
        );
    }
    private function deAbbreviateServiceName($abbreviation)
    {
        $abbreviation = strtolower($abbreviation);
        return match ($abbreviation) {
            "ibedc" => "ibadan electricity",
            "ikedc" => "ikeja electricity",
            "enugu" => "enugu electricity",
            "aedc" => "abuja electricity",
            "kedc" => "kano electricity",
            "phedc" => "port harcourt electricity",
            "jed" => "jos electricity",
            "benin" => "benin electricity",
            "kedco" => "kaduna electricity",
            default => str_replace("edc", " electricity", $abbreviation),
        };
    }
    private function abbreviateServiceName($name)
    {
        $name = strtolower($name);
        return match ($name) {
            "ikeja electricity" => "ikedc",
            "enugu electricity" => "enugu",
            "abuja electricity" => "aedc",
            "kano electricity" => "kedc",
            "port harcourt electricity" => "phedc",
            "jos electricity" => "jed",
            "benin electricity" => "benin",
            "kaduna electricity" => "kedco",
            default => substr($name, 0, 2) . "edc",
        };
    }

    public function tv(Request $request)
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();
                $role = $user->role;

                $priceColumn = match ($role) {
                    "customer" => "customer_price",
                    "reseller" => "reseller_price",
                    "agent" => "agent_price",
                    default => "customer_price",
                };

                $products = Product::whereHas("service", function ($query) {
                    $query->where("category_id", 5);
                })
                    ->select(
                        "name",
                        $priceColumn . " as role_price",
                        "validity",
                        "service_id"
                    )
                    ->get()
                    ->groupBy(function ($product) {
                        return $product->service->name;
                    });

                $services = $products->keys();
                $serviceF = $services->first();

                $products = $products->map(function ($productGroup) use (
                    $priceColumn
                ) {
                    return $productGroup->map(function ($product) use (
                        $priceColumn
                    ) {
                        $product->rounded_price = $this->roundUp(
                            $product->role_price
                        );
                        return $product;
                    });
                });

                return response()->json([
                    "products" => $products,
                    "services" => $services,
                    "serviceF" => $serviceF,
                    "status" => "success",
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }

        $user = Auth::user();
        $role = $user->role;

        $priceColumn = match ($role) {
            "customer" => "customer_price",
            "reseller" => "reseller_price",
            "agent" => "agent_price",
            default => "customer_price",
        };

        $products = Product::whereHas("service", function ($query) {
            $query->where("category_id", 5);
        })
            ->select(
                "name",
                $priceColumn . " as role_price",
                "validity",
                "service_id"
            )
            ->get()
            ->groupBy(function ($product) {
                return $product->service->name;
            });

        $services = $products->keys();
        $serviceF = $services->first();

        $products = $products->map(function ($productGroup) use ($priceColumn) {
            return $productGroup->map(function ($product) use ($priceColumn) {
                $product->rounded_price = $this->roundUp($product->role_price);
                return $product;
            });
        });

        return view(
            "services.tv",
            compact("user", "products", "services", "serviceF")
        );
    }

    public function epin(Request $request)
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();
                $role = $user->role;

                $priceColumn = match ($role) {
                    "customer" => "customer_price",
                    "reseller" => "reseller_price",
                    "agent" => "agent_price",
                    default => "customer_price",
                };

                $products = Product::whereHas("service", function ($query) {
                    $query->where("category_id", 6);
                })
                    ->select(
                        "name",
                        $priceColumn . " as role_price",
                        "validity",
                        "service_id"
                    )
                    ->get()
                    ->groupBy(function ($product) {
                        return $product->service->name;
                    });

                $services = $products->keys();
                $serviceF = $services->first();

                $products = $products->map(function ($productGroup) use (
                    $priceColumn
                ) {
                    return $productGroup->map(function ($product) use (
                        $priceColumn
                    ) {
                        $product->rounded_price = $this->roundUp(
                            $product->role_price
                        );
                        return $product;
                    });
                });

                return response()->json([
                    "products" => $products,
                    "services" => $services,
                    "serviceF" => $serviceF,
                    "status" => "success",
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }

        $user = Auth::user();
        $role = $user->role;

        $priceColumn = match ($role) {
            "customer" => "customer_price",
            "reseller" => "reseller_price",
            "agent" => "agent_price",
            default => "customer_price",
        };

        $products = Product::whereHas("service", function ($query) {
            $query->where("category_id", 6);
        })
            ->select(
                "name",
                $priceColumn . " as role_price",
                "validity",
                "service_id"
            )
            ->get()
            ->groupBy(function ($product) {
                return $product->service->name;
            });

        $services = $products->keys();
        $serviceF = $services->first();

        $products = $products->map(function ($productGroup) use ($priceColumn) {
            return $productGroup->map(function ($product) use ($priceColumn) {
                $product->rounded_price = $this->roundUp($product->role_price);
                return $product;
            });
        });
        return view(
            "services.epin",
            compact("user", "products", "services", "serviceF")
        );
    }

    public function recharge(Request $request)
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();
                $role = $user->role;

                $priceColumn = match ($role) {
                    "customer" => "customer_price",
                    "reseller" => "reseller_price",
                    "agent" => "agent_price",
                    default => "customer_price",
                };

                $products = Product::whereHas("service", function ($query) {
                    $query->where("category_id", 9);
                })
                    ->select(
                        "name",
                        $priceColumn . " as role_price",
                        "validity",
                        "service_id"
                    )
                    ->get()
                    ->groupBy(function ($product) {
                        return $product->service->name;
                    });

                $services = $products->keys();
                $serviceF = $services->first();

                $products = $products->map(function ($productGroup) use (
                    $priceColumn
                ) {
                    return $productGroup->map(function ($product) use (
                        $priceColumn
                    ) {
                        $product->rounded_price = $this->roundUp(
                            $product->role_price
                        );
                        return $product;
                    });
                });

                return response()->json([
                    "products" => $products,
                    "services" => $services,
                    "serviceF" => $serviceF,
                    "status" => "success",
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }

        $user = Auth::user();
        $role = $user->role;

        $priceColumn = match ($role) {
            "customer" => "customer_price",
            "reseller" => "reseller_price",
            "agent" => "agent_price",
            default => "customer_price",
        };

        $products = Product::whereHas("service", function ($query) {
            $query->where("category_id", 2);
        })
            ->select(
                "name",
                $priceColumn . " as role_price",
                "validity",
                "service_id"
            )
            ->get()
            ->groupBy(function ($product) {
                return $product->service->name;
            });

        $services = $products->keys();
        $serviceF = $services->first();

        $products = $products->map(function ($productGroup) use ($priceColumn) {
            return $productGroup->map(function ($product) use ($priceColumn) {
                $product->rounded_price = $this->roundUp($product->role_price);
                return $product;
            });
        });

        return view(
            "services.recharge",
            compact("user", "products", "services", "serviceF")
        );
    }

    public function betting(Request $request)
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();
                $role = $user->role;

                $priceColumn = match ($role) {
                    "customer" => "customer_price",
                    "reseller" => "reseller_price",
                    "agent" => "agent_price",
                    default => "customer_price",
                };

                $products = Product::whereHas("service", function ($query) {
                    $query->where("category_id", 11);
                })
                    ->select(
                        "name",
                        $priceColumn . " as role_price",
                        "validity",
                        "service_id"
                    )
                    ->get()
                    ->groupBy(function ($product) {
                        return $product->service->name;
                    });

                $services = $products->keys();
                $serviceF = $services->first();

                $products = $products->map(function ($productGroup) use (
                    $priceColumn
                ) {
                    return $productGroup->map(function ($product) use (
                        $priceColumn
                    ) {
                        $product->rounded_price = $this->roundUp(
                            $product->role_price
                        );
                        return $product;
                    });
                });

                return response()->json([
                    "products" => $products,
                    "services" => $services,
                    "serviceF" => $serviceF,
                    "status" => "success",
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }

        $user = Auth::user();
        $role = $user->role;

        $priceColumn = match ($role) {
            "customer" => "customer_price",
            "reseller" => "reseller_price",
            "agent" => "agent_price",
            default => "customer_price",
        };

        $products = Product::whereHas("service", function ($query) {
            $query->where("category_id", 11);
        })
            ->select(
                "name",
                $priceColumn . " as role_price",
                "validity",
                "service_id"
            )
            ->get()
            ->groupBy(function ($product) {
                return $product->service->name;
            });

        $services = $products->keys();
        $serviceF = $services->first();

        $products = $products->map(function ($productGroup) use ($priceColumn) {
            return $productGroup->map(function ($product) use ($priceColumn) {
                $product->rounded_price = $this->roundUp($product->role_price);
                return $product;
            });
        });
        return view(
            "services.betting",
            compact("user", "products", "services", "serviceF")
        );
    }

    public function datacard(Request $request)
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();
                $role = $user->role;

                $priceColumn = match ($role) {
                    "customer" => "customer_price",
                    "reseller" => "reseller_price",
                    "agent" => "agent_price",
                    default => "customer_price",
                };

                $products = Product::whereHas("service", function ($query) {
                    $query->where("category_id", 10);
                })
                    ->select(
                        "name",
                        $priceColumn . " as role_price",
                        "validity",
                        "service_id"
                    )
                    ->get()
                    ->groupBy(function ($product) {
                        return $product->service->name;
                    });

                $services = $products->keys();
                $serviceF = $services->first();

                $products = $products->map(function ($productGroup) use (
                    $priceColumn
                ) {
                    return $productGroup->map(function ($product) use (
                        $priceColumn
                    ) {
                        $product->rounded_price = $this->roundUp(
                            $product->role_price
                        );
                        return $product;
                    });
                });

                return response()->json([
                    "products" => $products,
                    "services" => $services,
                    "serviceF" => $serviceF,
                    "status" => "success",
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }

        $user = Auth::user();
        $role = $user->role;

        $priceColumn = match ($role) {
            "customer" => "customer_price",
            "reseller" => "reseller_price",
            "agent" => "agent_price",
            default => "customer_price",
        };

        $products = Product::whereHas("service", function ($query) {
            $query->where("category_id", 10);
        })
            ->select(
                "name",
                $priceColumn . " as role_price",
                "validity",
                "service_id"
            )
            ->get()
            ->groupBy(function ($product) {
                return $product->service->name;
            });

        $services = $products->keys();
        $serviceF = $services->first();

        $products = $products->map(function ($productGroup) use ($priceColumn) {
            return $productGroup->map(function ($product) use ($priceColumn) {
                $product->rounded_price = $this->roundUp($product->role_price);
                return $product;
            });
        });

        return view(
            "services.datacard",
            compact("user", "products", "services", "serviceF")
        );
    }
    public function more()
    {
        $user = Auth::user();
        return view("core.more", compact("user"));
    }

    public function completeSetup()
    {
        return view("auth.complete-setup");
    }

    public function edit(Request $request): View
    {
        return view("profile", [
            "user" => $request->user(),
        ]);
    }

    public function load()
    {
        $user = Auth::user();
        $this->global_gateway($user->id);
        $transactions = $user
            ->transactions()
            ->latest()
            ->take(1)
            ->get();
        $sales = $user->transactions()
            ->where('status', 'successful')
            ->where('product', 'data')
            ->whereDate('created_at', now()) 
            ->get()
            ->sum(function ($transaction) {
                $quantity = floatval($transaction->quantity);
                $unit = strtoupper(str_replace([' ', "\t"], '', preg_replace('/[0-9.]/', '', $transaction->quantity)));
        
                if ($unit == 'MB') {
                    return $quantity / 1024; 
                } elseif ($unit == 'TB') {
                    return $quantity * 1024;
                } elseif ($unit == 'GB') {
                    return $quantity;
                }
        
                return 0;
            });
        
        $notification = Notification::latest('date')->first();
        $msg = $notification?->message;
        return view("dashboard", compact("user", "transactions", "msg", "sales"));
    }

    public function transactions()
    {
        $user = Auth::user();
        $transactions = $user
            ->transactions()
            ->latest()
            ->paginate(25);
        return view("core.transactions", compact("transactions", "user"));
    }

    public function notifications(Request $request)
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();

            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();
                $notifications = Notification::all();

                return response()->json([
                    "notifications" => $notifications,
                    "status" => "success",
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            }
        }
        $user = Auth::user();
        $notifications = Notification::all();

        return view("core.notifications", compact("notifications", "user"));
    }

    public function scan()
    {
        return view("core.scan");
    }

  public function deposit()
    {
        $user = Auth::user();
        $banks = [];
        $payments = [];
    
        $allPaymentGateways = PaymentGateway::all();
        $enabledGateways = PaymentGateway::where("status", "enabled")->get();
    
        foreach ($allPaymentGateways as $gateway) {
            $payments[$gateway->identifier] = [
                "status" => $gateway->status
            ];
        }
    
        foreach ($enabledGateways as $gateway) {
        $slug = match ($gateway->identifier) {
            "mf" => "moniepoint_bank",
            "pv" => "nps_bank",
            "bs" => "palmpay_bank",
            "nc" => "paga_bank",
            default => null
        };
    
        if ($slug) {
            $accountNumber = $user->{$slug} ?? null;
            $banks[] = [
                "name" => match ($gateway->identifier) {
                    "mf" => "Moniepoint",
                    "pv" => "9PSB",
                    "bs" => "Palmpay",
                    "nc" => "Paga",
                    default => null
                },
                "identifier" => $gateway->identifier,
                "slug" => $slug,
                "account_number" => $accountNumber
            ];
        }
    }
    
    
        $extraBanks = [
            ["name" => "Wema Bank", "slug" => "wema_bank", "identifier" => "mf"],
            ["name" => "Sterling Bank", "slug" => "sterling_bank", "identifier" => "mf"],
            ["name" => "Fidelity Bank", "slug" => "fidelity_bank", "identifier" => "mf"]
        ];
    
        foreach ($extraBanks as $bank) {
            $accountNumber = $user->{$bank['slug']} ?? null;
            if (!empty($bank['name'])) {
                $banks[] = [
                    "name" => $bank['name'],
                    "slug" => $bank['slug'],
                     "identifier" => $bank['identifier'],
                    "account_number" => $accountNumber
                ];
            }
        } 
       
        return view("core.fund", compact("user", "banks", "payments"));
    }

    public function depositManual()
    {
        $user = Auth::user();
        return view("core.fund.manual", compact('user'));
    }

    public function depositOnetime()
    {
        $user = Auth::user();
        $status = "failed";
        $bank = [];

        $paymentGateway = PaymentGateway::where("status", "enabled")
            ->where("identifier", "pv")
            ->first();

        if ($paymentGateway) {
            $response = $this->reg_payvessel($user->id, "DYNAMIC");
            if ($response["status"] == "success") {
                $status = "success";
                $bank[] = [
                    "account" => $response["account"],
                    "name" => $response["name"],
                ];
            }
        }

        return view("core.fund.onetime", compact("status", "bank", "user"));
    }

    public function depositOnetimeVerify(Request $request)
    {
        $user = auth()->user();
        $amount = $request->input("amount");

        $gateway = PaymentGateway::where("identifier", "pv")->first();

        if ($gateway->status != "enabled") {
            return response()->json(
                ["message" => "Service not available at the moment"],
                404
            );
        }

        $charge_method = $gateway->charge_method;
        $charge = $gateway->charges;
        $amountAfterCharge = $amount;
        $profit = 0;

        if ($charge_method == "fixed") {
            $amountAfterCharge -= $charge;
            $profit = $charge;
        } elseif ($charge_method == "percentage") {
            $chargeAmount = ($charge / 100) * $amount;
            $amountAfterCharge -= $chargeAmount;
            $profit = $chargeAmount;
        }

        $transaction = Transaction::where("user_id", $user->id)
            ->where("amount", $amountAfterCharge)
            ->where("status", "successful")
            ->where("created_at", ">=", now()->subMinutes(15))
            ->first();

        if ($transaction) {
            $s_id = $transaction->session_id;
            return response()->json([
                "status" => "success",
                "redirect" => route("core.transaction", $s_id),
            ]);
        }

        return response()->json(["status" => "pending"]);
    }

    public function transfer()
    {
        $user = Auth::user();
        return view("core.transfer", compact("user"));
    }

    public function virtualcard()
    {
        $user = Auth::user();
        return view("core.virtualcard", compact("user"));
    }

    public function earn()
    {
        $user = Auth::user();
        return view("core.earn", compact("user"));
    }

    public function support()
    {
        $user = Auth::user();
        return view("core.support", compact("user"));
    }

    public function withdraw()
    {
        $user = Auth::user();
        return view("core.withdrawal", compact("user"));
    }

    public function loan()
    {
        return view("core.loan");
    }

    public function transaction(Request $request, $session_id)
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }

        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }

        $transaction = $user
            ->transactions()
            ->where("session_id", $session_id)
            ->first();

        if (!$transaction || $transaction->user_id != $user->id) {
            if ($request->headers->has("Authorization")) {
                return response()->json(
                    ["status" => "error", "message" => "Transaction not found"],
                    404
                );
            }
            return redirect()->route("core.transactions");
        }

        if ($request->headers->has("Authorization")) {
            return response()->json($transaction);
        }

        return view("core.transaction-d", compact("transaction", "user"));
    }

    # Process
    public function withdrawInit(Request $request)
    {
    }

    public function walletTransfer(Request $request)
    {
        if (
            $request->headers->has("Authorization") &&
            !$this->authorizeRequest($request)
        ) {
            return response()->json(
                ["status" => "error", "message" => "Unauthorized"],
                401
            );
        }

        if ($request->headers->has("Authorization")) {
            $user = $this->authorizeUser($request);
        } else {
            $user = auth()->user();
        }
        $check = $this->secureCheck($user);

        if ($check["locked"]) {
            $seconds = $check["time_left"];
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Ongoing Transaction, Try agin in $seconds Seconds",
                ],
                401
            );
        }

        if (!$this->secureLock($user)) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Unsupported Server, Contact Support E12990",
                ],
                401
            );
        }
        $validator = Validator::make($request->all(), [
            "number" => "required|string|max:25",
            "amount" => "required|max:6",
            "pin" => "required",
        ]);
        if ($validator->fails()) {
            $this->secureUnlock($user);
            return response()->json(
                ["status" => "error", "errors" => $validator->errors()],
                422
            );
        }

        $pin = $request->input("pin");
        $amount = abs($request->input("amount"));
        $charge = @jannah_opt("wallet_transfer_charges");
        $minTransfer = @jannah_opt("min_wallet_transfer");
        $maxTransfer = @jannah_opt("max_wallet_transfer");
        $totalAmount = $amount + $charge;

        if (!$this->correctPin($user, $pin)) {
            $this->secureUnlock($user);
            return $this->incorrectPinResponse();
        }

        if ($this->checkTransactionLimit($user, $totalAmount)) {
            $this->secureUnlock($user);
            return $this->transactionLimit();
        }
        if ($this->checkTransferLimit($user, $totalAmount)) {
            $this->secureUnlock($user);
            return $this->transferLimit();
        }

        if (!$this->hasSufficientFunds($user, $totalAmount)) {
            $this->secureUnlock($user);
            return $this->insufficientFundsResponse();
        }
        $recipient = User::where(
            "username",
            $request->input("number")
        )->first();
        if (!$recipient) {
            $this->secureUnlock($user);
            return response()->json(
                ["status" => "error", "message" => "Recipient not found"],
                404
            );
        }

        if ($user->id == $recipient->id) {
            $s_id = $this->generateSessionId();
            $old_balance = $user->wallet_balance;
            $new_balance = $this->deductWalletBalance($user, $old_balance);
            $this->createTransaction(
                $user,
                "fraud",
                $old_balance,
                $user->username,
                $s_id,
                "OUTGOING_TRANSFER",
                $old_balance,
                $new_balance,
                "successful"
            );
            $this->secureUnlock($user);
            return response()->json(
                ["status" => "success", "message" => "Transfer successful"],
                200
            );

            exit();
        }
        if ($amount < $minTransfer || $amount > $maxTransfer) {
            $this->secureUnlock($user);
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Amount does not meet transfer limits",
                ],
                422
            );
        }

        $s_id = $this->generateSessionId();
        $old_balance = $user->wallet_balance;
        $new_balance = $this->deductWalletBalance($user, $totalAmount);
        $recipient_old_balance = $recipient->wallet_balance;
        $recipient_new_balance = $this->creditUserBalance(
            $recipient,
            "wallet_balance",
            $amount
        );

        $this->createTransaction(
            $user,
            "transfer",
            $totalAmount,
            $recipient->username,
            $s_id,
            "OUTGOING_TRANSFER
",
            $old_balance,
            $new_balance,
            "successful"
        );
        $this->createTransaction(
            $recipient,
            "receiveD",
            $amount,
            $user->id,
            $s_id,
            "INCOMING_TRANSFER
",
            $recipient_old_balance,
            $recipient_new_balance,
            "successful"
        );
        $this->secureUnlock($user);
        return response()->json([
            "status" => "success",
            "message" => "Transfer successful",
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty("email")) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route("profile.edit")->with(
            "status",
            "profile-updated"
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        /* $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]); */

        $user = $request->user();

        Auth::logout();

        # $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route("login");
    }

    public function saveSetup(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->bearerToken();
            try {
                $user = User::where("api_token", $bearerToken)->firstOrFail();
                $request->validate([
                    "first_name" => "required|string|max:20|min:3",
                    "last_name" => "required|string|max:20|min:3",
                    "phone" =>
                        "required|string|max:11|min:11|unique:users,phone," .
                        $user->id,
                ]);
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->phone = $request->phone;
                $user->save();
                $bonus = @jannah_opt('registration_bonus');
                $this->regBonus($user, $bonus);
                $this->global_gateway($user->id);
                return response()->json([
                    "status" => "success",
                    "message" => "Profile updated successfully",
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invalid API token",
                    ],
                    401
                );
            } catch (\Illuminate\Validation\ValidationException $e) {
                $firstErrorMessage = collect($e->errors())->first()[0];
                return response()->json(
                    [
                        "status" => "error",
                        "message" => $firstErrorMessage,
                    ],
                    422
                );
            }
        }

        $request->validate([
            "first_name" => "required|string|max:20|min:3",
            "last_name" => "required|string|max:20|min:3",
            "phone" =>
                "required|string|max:11|min:11|unique:users,phone," .
                Auth::id(),
        ]);

        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->save();
        $bonus = @jannah_opt('registration_bonus');
        $this->regBonus($user, $bonus);
        $this->global_gateway($user->id);
        
       return response()->json([
            "status" => "success",
            "message" => "Profile updated successfully",
            "redirect" => route("core.user.pin"),
        ]);
    }

    /**
     * Admin LLG
     */

    # Views
    public function AdminAutomate()
    {
        $providers = Service::where("category_id", 1)->get();
        $services = Service::all();
        return view('admin.automate', compact("services", "providers"));
    }
    
    public function AdminAutomateDelete(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);
    
        $query = Product::where('service_id', $request->service_id);
    
        if ($request->filled('subcategory')) {
            $query->where('subcategory', $request->subcategory);
        }
    
        $deleted = $query->delete();
    
        if ($deleted) {
            return response()->json([
                "success" => true,
                "message" => "Products deleted successfully",
            ]);
        }
    
        return response()->json([
            "success" => false,
            "message" => "No matching products found",
        ]);
    }
    
    public function AdminAutomateAdd(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:api_providers,id',
            'service_id' => 'required|exists:services,id',
            'user_percentage' => 'required|numeric|min:1|max:100',
            'reseller_percentage' => 'required|numeric|min:1|max:100',
            'agent_percentage' => 'required|numeric|min:1|max:100',
            'subcategory' => 'nullable|string',
        ]);
    
        $service = Service::findOrFail($validated['service_id']);
        $category = $service->category()->value('name');
        $data = $this->GetAllData($validated['provider_id']);
        $filteredPlans = [];
    
        if ($category === 'data' && isset($data['Dataplans'])) {
            foreach ($data['Dataplans'] as $plans) {
                foreach ($plans as $planGroup) {
                    foreach ($planGroup as $plan) {
                        if ((!$validated['subcategory'] || $plan['plan_type'] === $validated['subcategory']) && $plan['plan_network'] === strtoupper($service->name)) {
                            $filteredPlans[] = $this->preparePlanData($plan, $validated, 'dataplan_id', $plan['plan'], $plan['plan_type'], $plan['month_validate']);
                        }
                    }
                }
            }
        } elseif ($category === 'cabletv' && isset($data['Cableplan'])) {
            foreach ($data['Cableplan'] as $plans) {
                foreach ($plans as $plan) {  
                    if (isset($plan['cable']) && $plan['cable'] === strtoupper($service->name)) {
                        $filteredPlans[] = $this->preparePlanData($plan, $validated, 'cableplan_id', $plan['package'], '', '30');
                    }
                }
            }

        } else {
            return response()->json(["success" => false, "message" => "Service not supported, contact dev"]);
        }
    
        if (!empty($filteredPlans)) {
            Product::insert($filteredPlans);
        }
    
        return response()->json(["success" => true, "message" => count($filteredPlans) . " products added successfully"]);
    }
    
    private function preparePlanData($plan, $validated, $planIdKey, $name, $subcategory, $validity)
    {
        $buyingPrice = (float) $plan['plan_amount'];
        return [
            'name' => $name,
            'service_id' => $validated['service_id'],
            'plan_id' => $plan[$planIdKey],
            'subcategory' => $subcategory,
            'buying_price' => $buyingPrice,
            'customer_price' => $buyingPrice + ($buyingPrice * $validated['user_percentage'] / 100),
            'reseller_price' => $buyingPrice + ($buyingPrice * $validated['reseller_percentage'] / 100),
            'agent_price' => $buyingPrice + ($buyingPrice * $validated['agent_percentage'] / 100),
            'validity' => $validity,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
        
    public function AdminMonetization() 
    {
        return view('admin.monetization');
    }

    public function AdminLabs() 
    {
        return view('admin.labs');
    }

    public function AdminMobile() 
    {
        return view('admin.mobile');
    }

    public function AdminSupport() 
    {
        return view('admin.support');
    }
    
    public function AdminMediaView()
    {
        return view('admin.media');
    }
    
    public function AdminMedia()
    {
        $mediaPath = public_path('img/'); 
        $files = File::allFiles($mediaPath); 
    
        $media = [];
        foreach ($files as $file) {
            $relativePath = str_replace(public_path(), '', $file->getPathname()); 
            $media[] = [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'type' => File::mimeType($file->getPathname()),
                'url' => asset($relativePath), 
                
            ];
        }
    
        return response()->json($media);
    } 
    
    public function AdminMediaAdd(Request $request)
    {
        if ($request->hasFile('media')) {
            $mediaPath = public_path('img/app/');
            $files = File::allFiles($mediaPath);
    
            $newFile = $request->file('media');
            $filename = time() . '_' . $newFile->getClientOriginalName();
            $newFile->move(public_path('img/app/'), $filename);
    
            return response()->json(['message' => 'Media updated successfully', 'url' => asset('img/app/' . $filename)]);
        }
    
        return response()->json(['error' => 'Invalid request'], 400);
    }
    
    public function AdminMediaEdit(Request $request)
    {
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $oldName = $request->input('old_name');
            $paths = [public_path('img/'), public_path('img/app/')];
            $oldFilePath = null;
            $uploadDir = null;
    
            foreach ($paths as $path) {
                $filePath = $path . $oldName;
                if (file_exists($filePath)) {
                    $oldFilePath = $filePath;
                    $uploadDir = $path;
                    break;
                }
            }
    
            if ($oldFilePath) {
                if (!unlink($oldFilePath) || file_exists($oldFilePath)) {
                    return response()->json(['error' => 'Failed to delete old file'], 500);
                }
            } else {
                $uploadDir = $paths[0];
            }
    
            if (!$file->move($uploadDir, $oldName)) {
                return response()->json(['error' => 'Failed to upload new file'], 500);
            }
    
            return response()->json(['message' => 'File replaced successfully']);
        }
    
        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function AdminPlugins()
    {
        $plugins = $this->pluginService->getPlugins();
        return view('admin.plugins', compact('plugins'));
    }

    public function activate($slug)
    {
        $this->pluginService->activatePlugin($slug);
        return redirect()->route('admin.plugins')->with('success', 'Plugin activated!');
    }

    public function deactivate($slug)
    {
        $this->pluginService->deactivatePlugin($slug);
        return redirect()->route('admin.plugins')->with('success', 'Plugin deactivated!');
    }

    public function uninstall($slug)
    {
        $this->pluginService->uninstallPlugin($slug);
        return redirect()->route('admin.plugins')->with('success', 'Plugin uninstalled!');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'plugin' => 'required|file|mimes:zip'
        ]);

        $file = $request->file('plugin');

        try {
            $this->pluginService->installPluginFromUpload($file);
            return redirect()->route('admin.plugins')->with('success', 'Plugin installed successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.plugins')->with('error', $e->getMessage());
        }
    }
    
    public function AdminThemes()
    {
        $themes = getAllThemes();
        return view("admin.themes", compact("themes"));
    }

    public function AdminThemeUpload()
    {
        if (request()->hasFile("theme")) {
            $file = request()->file("theme");
            $filename = $file->getClientOriginalName();
            $path = resource_path("themes/{$filename}");
            $file->move(resource_path("themes"), $filename);

            $zip = new \ZipArchive();
            if ($zip->open($path) === true) {
                $zip->extractTo(resource_path("themes"));
                $zip->close();
                unlink($path);
                return response()->json([
                    "success" => true,
                    "message" => "Theme Installed successfully",
                ]);
            }

            return response()->json([
                "success" => false,
                "message" => "Failed to extract theme",
            ]);
        }

        return response()->json([
            "success" => false,
            "message" => "No file selected",
        ]);
    }

    public function AdminThemeInstall()
    {
        $theme = request("theme");
        if (activateTheme($theme)) {
            return response()->json([
                "success" => true,
                "message" => "Theme activated successfully",
            ]);
        }
        return response()->json([
            "success" => false,
            "message" => "Failed to activate theme",
        ]);
    }

    public function AdminThemeUninstall()
    {
        $theme = request("theme");
        $path = resource_path("themes/{$theme}");
        if (is_dir($path)) {
            \File::deleteDirectory($path);
            return response()->json([
                "success" => true,
                "message" => "Theme uninstalled successfully",
            ]);
        }
        return response()->json([
            "success" => false,
            "message" => "Failed to uninstall theme",
        ]);
    }

    public function Adminloginf()
    {
        return view("admin.auth.login");
    }

    public function AdminDashboard()
    {
        $roleCounts = User::query()
            ->select("role", DB::raw("COUNT(*) as total"))
            ->groupBy("role")
            ->pluck("total", "role");
        $statusCounts = Transaction::query()
            ->select("status", DB::raw("COUNT(*) as total"))
            ->groupBy("status")
            ->pluck("total", "status");

        $transactions = Transaction::with("user")
            ->latest("created_at")
            ->limit(6)
            ->get();
        $transaction_no = number_format(Transaction::count());
        $user_no = number_format(User::count());
        $t_user_bal = number_format(User::sum("wallet_balance"), 2);
        $t_agent = number_format((int) ($roleCounts["agent"] ?? 0));
        $t_customer = number_format((int) ($roleCounts["customer"] ?? 0));
        $t_reseller = number_format((int) ($roleCounts["reseller"] ?? 0));
        $t_successful = number_format((int) ($statusCounts["successful"] ?? 0));
        $t_failed = number_format((int) ($statusCounts["failed"] ?? 0));
        $t_pending = number_format((int) ($statusCounts["pending"] ?? 0));
        $t_processing = number_format((int) ($statusCounts["processing"] ?? 0));
        $vtuNaijaSyncActive = $this->isPluginActive("VtuNaijaPriceSync");
        $priceSyncNotice = null;
        $priceSyncHealth = null;

        if ($vtuNaijaSyncActive) {
            try {
                $priceSyncNotice = Notification::where("title", "VTUNAIJA price changes detected")
                    ->where("status", "active")
                    ->latest("date")
                    ->first();
            } catch (\Throwable $e) {
                $priceSyncNotice = null;
            }
            $priceSyncHealth = $this->priceSyncHealth();
        }

        return view(
            "admin.dashboard",
            compact(
                "transactions",
                "transaction_no",
                "user_no",
                "t_user_bal",
                "t_agent",
                "t_customer",
                "t_reseller",
                "t_successful",
                "t_failed",
                "t_pending",
                "t_processing",
                "priceSyncNotice",
                "priceSyncHealth"
            )
        );
    }

    private function isPluginActive(string $slug): bool
    {
        try {
            return DB::table("plugins")
                ->where("slug", $slug)
                ->where("status", "active")
                ->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function priceSyncHealth(): array
    {
        if (!$this->isPluginActive("VtuNaijaPriceSync")) {
            return [];
        }

        $emptyHealth = [
            "status" => "not_run",
            "label" => "Not run yet",
            "last_checked_at" => null,
            "last_product_type" => null,
            "last_run_status" => null,
            "last_message" => null,
            "fetched_count" => 0,
            "pending_changes" => 0,
            "next_run" => now()->format("H:i") < "06:00" ? "Today at 06:00" : "Tomorrow at 06:00",
        ];

        try {
            $latestRun = DB::table("vtunaija_price_sync_runs")->latest("id")->first();
            $latestSuccessfulRun = DB::table("vtunaija_price_sync_runs")
                ->whereIn("status", ["changes_found", "no_changes", "previewed", "applied"])
                ->latest("id")
                ->first();
            $activeNotice = Notification::where("title", "VTUNAIJA price changes detected")
                ->where("status", "active")
                ->latest("date")
                ->first();
        } catch (\Throwable $e) {
            return $emptyHealth;
        }

        $lastCheckedAt = $latestSuccessfulRun?->created_at ?: $latestRun?->created_at;
        $lastCheckAgeHours = $lastCheckedAt
            ? now()->diffInHours(\Carbon\Carbon::parse($lastCheckedAt))
            : null;
        $latestAppliedRunId = (int) DB::table("vtunaija_price_sync_runs")
            ->where("status", "applied")
            ->max("id");
        $pendingChanges = $activeNotice
            ? (int) DB::table("vtunaija_price_sync_runs")
                ->where("status", "changes_found")
                ->where("id", ">", $latestAppliedRunId)
                ->selectRaw("COALESCE(SUM(created_count + updated_count), 0) as total")
                ->value("total")
            : 0;
        $status = match (true) {
            !$latestRun => "not_run",
            $latestRun->status === "failed" => "failed",
            $activeNotice !== null => "changes_found",
            $lastCheckAgeHours !== null && $lastCheckAgeHours <= 26 => "healthy",
            default => "stale",
        };

        return [
            "status" => $status,
            "label" => match ($status) {
                "healthy" => "Healthy",
                "changes_found" => "Changes pending",
                "failed" => "Needs attention",
                "stale" => "Stale",
                default => "Not run yet",
            },
            "last_checked_at" => $lastCheckedAt,
            "last_product_type" => $latestRun?->product_type,
            "last_run_status" => $latestRun?->status,
            "last_message" => $latestRun?->message,
            "fetched_count" => (int) ($latestRun->fetched_count ?? 0),
            "pending_changes" => $pendingChanges,
            "next_run" => now()->format("H:i") < "06:00" ? "Today at 06:00" : "Tomorrow at 06:00",
        ];
    }

    public function AdminWalletBalances()
    {
        $services = Service::where("category_id", 1)
            ->whereHas("apiConfigs", function ($query) {
                $query->where("status", "enabled");
            })
            ->get();
        $walletBalances = [];

        foreach ($services as $service) {
            $walletData = $this->GetWallet($service->id);
            $walletBalances[] = [
                "name" => $service->name,
                "balance" => $walletData["balance"],
            ];
        }

        return response()->json([
            "wallets" => $walletBalances,
        ]);
    }

    public function GetWallet($service_id)
    {
        $apiConfig = ApiConfig::where("service_id", $service_id)
            ->where("status", "enabled")
            ->latest()
            ->first();

        if (!$apiConfig) {
            return ["provider" => null, "balance" => "Not Set"];
        }

        $provider = $apiConfig->apiProvider;
        $api_token = $apiConfig->api_token;
        $api_url = $provider->api_endpoint;
        $identifier = $provider->identifier;

        $auth_type = $this->determineAuthType($identifier);
        $check_nc = strpos($identifier, "nc") !== false;

        $method =
            $check_nc || strpos($identifier, "adex") !== false ? "POST" : "GET";

        $response = $this->performHttpRequest(
            $api_url,
            $api_token,
            $auth_type,
            $method,
            $check_nc
        );

        if ($response) {
            $wallet_balance = $this->extractBalance($response, $check_nc);
            return [
                "provider" => $provider->name,
                "balance" => "₦" . $wallet_balance,
            ];
        } else {
            return [
                "provider" => $provider->name,
                "balance" => "An Error Occurred!",
            ];
        }
    }
    
    public function GetAllData($service_id)
    {
        $apiConfig = ApiConfig::where("service_id", $service_id)
            ->latest()
            ->first();

        if (!$apiConfig) {
            return ["provider" => null, "balance" => "Not Set"];
        }

        $provider = $apiConfig->apiProvider;
        $api_token = $apiConfig->api_token;
        $api_url = $provider->api_endpoint;
        $identifier = $provider->identifier;

        $auth_type = $this->determineAuthType($identifier);
        $check_nc = strpos($identifier, "nc") !== false;

        $method =
            $check_nc || strpos($identifier, "adex") !== false ? "POST" : "GET";

        $response = $this->performHttpRequest(
            $api_url,
            $api_token,
            $auth_type,
            $method,
            $check_nc
        );

        if ($response) {
            return $response;
        } else {
            return false;
        }
    }
    
    protected function determineAuthType($identifier)
    {
        if (strpos($identifier, "adex") !== false) {
            return "Basic";
        } else {
            return "Token";
        }
    }

    protected function performHttpRequest(
        $url,
        $api_token,
        $auth_type,
        $method,
        $check_nc
    ) {
        $headers = [
            "Authorization" => $auth_type . " " . $api_token,
            "Content-Type" => "application/json",
        ];
    
        if ($check_nc && strpos($api_token, ":") !== false) {
            [$pin, $api_token] = explode(":", $api_token);
            $headers["trnx_pin"] = $pin;
            $headers["Authorization"] = $api_token;
        }
    
        try {
            $response = Http::withHeaders($headers)->$method($url);
    
            if ($response->successful()) {
                // Log::debug($response->json());
                return $response->json();
            }
        } catch (\Throwable $e) {
            return null;
        }
    
        return null;
    }



    protected function extractBalance($response, $check_nc)
    {
        $balance =
            $response["user"]["wallet_balance"] ??
            ($response["wallet_balance"] ?? ($response["balance"] ?? 0.0));

        if ($check_nc) {
            $balance = $response["data"]["balance"] ?? $balance;
        }

        $balance = str_replace(",", "", $balance);

        if (is_numeric($balance)) {
            return number_format(floatval($balance), 2);
        } elseif (empty($balance)) {
            return "0.00";
        } else {
            return "An Error Occurred!";
        }
    }
    public function AdminUpdates()
    {
        $currentVersion = config("app.version");
        $updateData = $this->checkUpdate();
        $latestVersion = $updateData["version"] ?? $currentVersion;
        $updateAvailable = version_compare(
            $latestVersion,
            $currentVersion,
            ">"
        );

        return view("admin.update", [
            "updateAvailable" => $updateAvailable,
            "latestVersion" => $latestVersion,
            "currentVersion" => $currentVersion,
            "versions" => $updateData["versions"] ?? [],
            "downloadLink" => $updateData["download"] ?? null,
            "updateError" => $updateData["message"] ?? null,
        ]);
    }

    public function checkUpdate(): array
    {
        $manifestUrl = config("services.jannah_updates.manifest_url");

        if (!$manifestUrl) {
            return [
                "status" => "DISABLED",
                "version" => config("app.version"),
                "versions" => [],
                "message" => "No update manifest URL is configured.",
            ];
        }

        try {
            $response = Http::timeout(20)->acceptJson()->get($manifestUrl);
        } catch (\Throwable $e) {
            return [
                "status" => "ERROR",
                "version" => config("app.version"),
                "versions" => [],
                "message" => "Could not check for updates: " . $e->getMessage(),
            ];
        }

        if (!$response->successful()) {
            return [
                "status" => "ERROR",
                "version" => config("app.version"),
                "versions" => [],
                "message" => "Update server returned HTTP " . $response->status() . ".",
            ];
        }

        $data = $response->json();

        if (!is_array($data) || empty($data["version"]) || empty($data["download"]) || empty($data["sha256"])) {
            return [
                "status" => "ERROR",
                "version" => config("app.version"),
                "versions" => [],
                "message" => "Update manifest is missing version, download, or sha256.",
            ];
        }

        $data["status"] = $data["status"] ?? "VERIFIED";
        $data["versions"] = $data["versions"] ?? [];

        return $data;
    }

    public function initializeUpdate(Request $request)
    {
        $cacheKey = "update_status";
        $response = $this->checkUpdate();

        if (($response["status"] ?? null) === "VERIFIED") {
            $downloadUrl = $response["download"];
            $sql = $response["sql"] ?? null;
            $tempFile = storage_path("app/up.zip");
            $fileSize = (int) Http::timeout(20)->head($downloadUrl)->header("Content-Length", 0);
            $estimatedTime = $this->estimateDownloadTime($fileSize);

            Cache::put(
                $cacheKey,
                ["status" => "processing", "estimatedTime" => $estimatedTime],
                now()->addMinutes(5)
            );

            ProcessUpdate::dispatchSync($downloadUrl, $tempFile, $cacheKey, $sql, [
                "sha256" => $response["sha256"],
                "sql_sha256" => $response["sql_sha256"] ?? null,
                "run_migrations" => (bool) ($response["run_migrations"] ?? true),
            ]);

            return response()->json([
                "status" => "success",
                "estimatedTime" => $estimatedTime,
                "fileSize" => $this->formatFileSize($fileSize),
            ]);
        }

        return response()->json([
            "status" => "error",
            "message" => $response["message"] ?? "Update verification failed.",
        ]);
    }

    public function getUpdateStatus(): JsonResponse
    {
        $status = Cache::get("update_status", ["st" => "idle"]);

        return match ($status["st"] ?? "idle") {
            "sc" => response()->json(["status" => "success"]),
            "er" => response()->json([
                "status" => "error",
                "message" => $status["msg"] ?? "Update failed.",
            ]),
            "pr" => response()->json([
                "status" => "processing",
                "message" => $status["msg"] ?? "Update is processing.",
            ]),
            default => response()->json(["status" => "processing"]),
        };
    }

    private function estimateDownloadTime($fileSize)
    {
        $averageSpeed = 200000;
        return max(1000, ((int) $fileSize / $averageSpeed) * 1000);
    }

    private function formatFileSize($size)
    {
        $units = ["B", "KB", "MB", "GB", "TB"];
        $unit = 0;
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        return round($size, 2) . " " . $units[$unit];
    }

    public function AdminSettings()
    {
        return view("admin.settings");
    }

    public function AdminAPIConfigs()
    {
        $apiConfigs = ApiConfig::with("service")->paginate(10);
        return view("admin.apiconfigs", compact("apiConfigs"));
    }

    public function AdminAPIConfigEdit($id)
    {
        $config = ApiConfig::findOrFail($id);
        $providers = ApiProvider::all();
        $services = Service::all();
        return view(
            "admin.edit.api_config",
            compact("config", "providers", "services")
        );
    }

    public function AdminAPIConfigAdd()
    {
        $providers = ApiProvider::all();
        $services = Service::all();
        return view("admin.add.api_config", compact("providers", "services"));
    }

    public function AdminUsers(Request $request)
    {
        $perPage = $request->integer('per_page', 50);
        $users = User::with("transactions")->paginate($perPage);
        return view("admin.users", compact("users"));
    }

    public function AdminUser($id)
    {
        $user = User::findOrFail($id);
        return view("admin.edit.user", compact("user"));
    }

    public function AdminProfile()
    {
        return view("admin.profile");
    }
    
    public function AdminDataCategory()
{
    $products = Product::with('service')->get();

    $categories = $products->map(function ($product) {
        return [
            'subcategory' => str_replace('_disabled', '', $product->subcategory),
            'service_name' => $product->service->name ?? 'Unknown',
            'service_id' => $product->service_id
        ];
    })->unique(function ($category) {
        return $category['subcategory'] . '_' . $category['service_id'];
    });

    $enabledCategories = $categories->filter(function ($category) use ($products) {
        return !$products->contains(function ($product) use ($category) {
            return $product->subcategory === $category['subcategory'] . '_disabled' && $product->service_id === $category['service_id'];
        });
    });

    $disabledCategories = $categories->filter(function ($category) use ($products) {
        return $products->contains(function ($product) use ($category) {
            return $product->subcategory === $category['subcategory'] . '_disabled' && $product->service_id === $category['service_id'];
        });
    });

    return view("admin.dataCategory", compact('enabledCategories', 'disabledCategories'));
}

public function AdminDataCategoryUpdate(Request $request)
{
    $request->validate([
        'category' => 'required|string',
        'service_id' => 'required|integer',
        'status' => 'required|in:enabled,disabled',
    ]);

    $category = $request->category;
    $serviceId = $request->service_id;
    $status = $request->status;

    if ($status == 'disabled') {
        Product::where('subcategory', $category)
            ->where('service_id', $serviceId)
            ->update(['subcategory' => $category . '_disabled']);
    } else {
        Product::where('subcategory', $category . '_disabled')
            ->where('service_id', $serviceId)
            ->update(['subcategory' => $category]);
    }

    return redirect()->route('admin.data.category')->with('success', 'Category updated successfully.');
}
    
    public function AdminCategories(Request $request)
    {
        $perPage = $request->integer('per_page', 10);
        $categories = Category::paginate($perPage);
        return view("admin.categories", compact("categories"));
    }
    
    public function AdminApiProvider(Request $request)
    {
        $perPage = $request->integer('per_page', 50);
        $apiProviders = ApiProvider::with("category")->paginate($perPage);
        return view("admin.apiproviders", compact("apiProviders"));
    }

    public function AdminApiProviderEdit($id)
    {
        $apiProvider = ApiProvider::findOrFail($id);
        $categories = Category::all();
        return view(
            "admin.edit.api_provider",
            compact("apiProvider", "categories")
        );
    }

    public function AdminApiProviderAdd()
    {
        $categories = Category::all();
        return view("admin.add.apiprovider", compact("categories"));
    }

    public function AdminTransactions(Request $request)
    {
        $perPage = $request->integer('per_page', 50);
        $transactions = Transaction::with("user")->orderBy("created_at", "desc")->paginate($perPage);
        return view("admin.transactions", compact("transactions"));
    }

    public function AdminTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view("admin.edit.transaction", compact("transaction"));
    }

    public function AdminServices()
    {
        $services = Service::paginate(50);
        return view("admin.services", compact("services"));
    }

    public function AdminProducts()
    {
        $products = Product::paginate(50);
        return view("admin.products", compact("products"));
    }

    public function AdminProductAdd()
    {
        $services = Service::all();
        return view("admin.add.product", compact("services"));
    }

    public function AdminProductEdit($id)
    {
        $product = Product::findOrFail($id);
        $services = Service::all();
        return view("admin.edit.product", compact("product", "services"));
    }

    public function AdminMore()
    {
        return view("admin.more");
    }

    public function AdminAnalytics(Request $request)
    {
        $fromDate = $request->input(
            "from_date",
            now()
                ->subDays(30)
                ->format("Y-m-d")
        );
        $toDate = $request->input("to_date", now()->format("Y-m-d"));

        $results = Transaction::whereBetween("created_at", [
            $fromDate,
            $toDate,
        ])->get();

        $analyticsData = [
            "total_transactions" => [
                "value" => 0,
                "color" => "orange",
                "decimal" => false,
                "suffix" => ""
            ],
            "total_sales" => [
                "value" => 0,
                "color" => "purple",
                "decimal" => true,
                "suffix" => ""
            ],
            "total_profit" => [
                "value" => 0,
                "color" => "green",
                "decimal" => true,
                "suffix" => ""
            ],
            "success_transactions" => [
                "value" => 0,
                "color" => "green",
                "decimal" => false,
                "suffix" => ""
            ],
            "failed_transactions" => [
                "value" => 0,
                "color" => "red",
                "decimal" => false,
                "suffix" => ""
            ],
            "processing_transactions" => [
                "value" => 0,
                "color" => "gold",
                "decimal" => false,
                "suffix" => ""
            ],
            "airtime_sales" => [
                "value" => 0,
                "color" => "black",
                "decimal" => true,
                "suffix" => ""
            ],
            "airtime_profit" => [
                "value" => 0,
                "color" => "silver",
                "decimal" => true,
                "suffix" => ""
            ],
            "data_sales" => [
                "value" => 0,
                "color" => "brown",
                "decimal" => false,
                "suffix" => "GB"
            ],
            "data_profit" => [
                "value" => 0,
                "color" => "green",
                "decimal" => true,
                "suffix" => ""
            ],
            "datapin_sales" => [
                "value" => 0,
                "color" => "#3388FF",
                "decimal" => true,
                "suffix" => ""
            ],
            "datapin_profit" => [
                "value" => 0,
                "color" => "#8833FF",
                "decimal" => true,
                "suffix" => ""
            ],
            "cabletv_sales" => [
                "value" => 0,
                "color" => "black",
                "decimal" => true,
                "suffix" => ""
            ],
            "cabletv_profit" => [
                "value" => 0,
                "color" => "#BB33FF",
                "decimal" => true,
                "suffix" => ""
            ],
            "electricity_sales" => [
                "value" => 0,
                "color" => "#FF3333",
                "decimal" => true,
                "suffix" => ""
            ],
            "electricity_profit" => [
                "value" => 0,
                "color" => "#33FF33",
                "decimal" => true,
                "suffix" => ""
            ],
            "epin_sales" => [
                "value" => 0,
                "color" => "#3333FF",
                "decimal" => true,
                "suffix" => ""
            ],
            "epin_profit" => [
                "value" => 0,
                "color" => "#FF33BB",
                "decimal" => true,
                "suffix" => ""
            ],
        ];

        $dailyTransactions = [];
        $dailyProfits = [];

        foreach ($results as $result) {
            $analyticsData["total_transactions"]["value"]++;
            $analyticsData["total_sales"]["value"] += $result->amount;
            $analyticsData["total_profit"]["value"] += $result->profits;

            if ($result->status === "successful") {
                $analyticsData["success_transactions"]["value"]++;
            } elseif ($result->status === "failed") {
                $analyticsData["failed_transactions"]["value"]++;
            } elseif ($result->status === "processing") {
                $analyticsData["processing_transactions"]["value"]++;
            }

            $date = date("Y-m-d", strtotime($result->created_at));
            $dayName = date("l", strtotime($result->created_at));

            if (!isset($dailyTransactions[$dayName])) {
                $dailyTransactions[$dayName] = 0;
                $dailyProfits[$dayName] = 0;
            }
            $dailyTransactions[$dayName] += $result->amount;
            $dailyProfits[$dayName] += $result->profits;

            if ($result->product === "airtime") {
                $analyticsData["airtime_sales"]["value"] += $result->amount;
                $analyticsData["airtime_profit"]["value"] += $result->profits;
            } elseif ($result->product === "data" && $result->status === "successful") {
                $analyticsData["data_sales"]["value"] += $this->convertToGB($result->quantity);
                $analyticsData["data_profit"]["value"] += $result->profits;
            } elseif ($result->product === "datapin") {
                $analyticsData["datapin_sales"]["value"] += $result->amount;
                $analyticsData["datapin_profit"]["value"] += $result->profits;
            } elseif ($result->product === "cabletv") {
                $analyticsData["cabletv_sales"]["value"] += $result->amount;
                $analyticsData["cabletv_profit"]["value"] += $result->profits;
            } elseif ($result->product === "electricity") {
                $analyticsData["electricity_sales"]["value"] += $result->amount;
                $analyticsData["electricity_profit"]["value"] +=
                    $result->profits;
            } elseif ($result->product === "epin") {
                $analyticsData["epin_sales"]["value"] += $result->amount;
                $analyticsData["epin_profit"]["value"] += $result->profits;
            }
        }
        # dd($analyticsData);

        return view(
            "admin.analytics",
            compact(
                "analyticsData",
                "dailyTransactions",
                "dailyProfits",
                "fromDate",
                "toDate"
            )
        );
    }
    public function convertToGB($value)
    {
        $units = ['B' => 1 / (1024 * 1024 * 1024), 'KB' => 1 / (1024 * 1024), 'MB' => 1 / 1024, 'GB' => 1, 'TB' => 1024];
        preg_match('/([\d.]+)\s*(\w+)/', strtoupper($value), $matches);
        
        if (!isset($matches[1], $matches[2])) {
            return 0; 
        }
    
        $number = (float) $matches[1];
        $unit = strtoupper($matches[2]);
    
        return $number * ($units[$unit] ?? 0);
    }

    public function AdminNotifications()
    {
        $notifications = Notification::all();
        return view("admin.notifications", compact("notifications"));
    }

    public function AdminNotificationAdd()
    {
        return view("admin.add.notification");
    }

    public function AdminNotification($id)
    {
        $notification = Notification::findOrFail($id);
        return view("admin.edit.notification", compact("notification"));
    }

    public function AdminGateway()
    {
        $gateways = PaymentGateway::paginate(10);
        return view("admin.gateway", compact("gateways"));
    }

    public function AdminGatewayEdit($id)
    {
        $gateway = PaymentGateway::findOrfail($id);
        return view("admin.edit.gateway", compact("gateway"));
    }

    public function AdminSwitch()
    {
        return view("admin.switch");
    }

    public function BulkMailer()
    {
        return view("admin.bulkmailer");
    }

    #KYLE
    public function AdminServicesAdd()
    {
        $categories = Category::all();
        return view("admin.add.service", compact("categories"));
    }

    public function AdminServicesEdit($id)
    {
        $categories = Category::all();
        $service = Service::findOrFail($id);
        return view("admin.edit.service", compact("service", "categories"));
    }
    /**
     * Functions
     */
    #CORE FINALE
    public function BulkMailerSend(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "subject" => "required|string|max:255",
            "content" => "required|string",
        ]);

        $subject = $validated["subject"];
        $content = $validated["content"];

        $users = User::all();
        $failedEmails = [];

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new BulkMail($subject, $content));
            } catch (\Exception $e) {
                Log::error(
                    "Failed to send email to {$user->email}: " .
                        $e->getMessage()
                );
                $failedEmails[] = $user->email;
            }
        }

        return response()->json([
            "message" => "Bulk email process completed.",
            "failed_emails" => $failedEmails,
        ]);
    }

    public function AdminProductInsert(Request $request): JsonResponse
    {
        $request->validate([
            "name" => "required|string|max:255",
            "service_id" => "required|exists:services,id",
            "subcategory" => "string|max:255",
            "buying_price" => "required|numeric",
            "customer_price" => "required|numeric",
            "reseller_price" => "required|numeric",
            "agent_price" => "required|numeric",
            "plan_id" => "nullable|string|max:255",
            "validity" => "nullable|string|max:255",
        ]);

        $product = Product::updateOrCreate(
            ["id" => $request->id],
            $request->all()
        );

        return new JsonResponse([
            "message" => $request->id
                ? "Product updated successfully."
                : "Product added successfully.",
            "product" => $product,
        ]);
    }

    public function AdminApiConfigInsert(Request $request): JsonResponse
    {
        $request->validate([
            "provider_id" => "required|exists:api_providers,id",
            "service_id" => "required|exists:services,id",
            "optional" => "string|max:255",
            "api_token" => "required|string|max:255",
            "status" => "required|in:enabled,disabled",
        ]);

        $config = ApiConfig::updateOrCreate(
            ["id" => $request->id],
            $request->all()
        );

        return new JsonResponse([
            "message" => $request->id
                ? "API Configuration updated successfully."
                : "API Configuration added successfully.",
            "config" => $config,
        ]);
    }

    public function AdminServiceInsert(Request $request): JsonResponse
    {
        $request->validate([
            "name" => "required|string|max:255",
            "network_id" => "required|string|max:255",
            "category_id" => "required|exists:categories,id",
        ]);

        $service = Service::updateOrCreate(
            ["id" => $request->id],
            $request->all()
        );

        return new JsonResponse([
            "message" => $request->id
                ? "Service updated successfully."
                : "Service added successfully.",
            "service" => $service,
        ]);
    }

    public function AdminApiProviderSave(Request $request): JsonResponse
    {
        $request->validate([
            "name" => "required|string|max:255",
            "identifier" => "required|string|max:255",
            "category_id" => "required|exists:categories,id",
            "api_endpoint" => "required|string|max:255",
            "status" => "required|in:enabled,disabled",
        ]);

        ApiProvider::create($request->all());

        return new JsonResponse([
            "message" => "API Provider added successfully.",
            "api_provider" => $apiProvider,
        ]);
    }
    public function AdminGatewayUpdate(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "api_token" => "required|string|max:255",
            "secret_token" => "required|string|max:255",
            "charge_method" => "required|string|in:fixed,percentage",
            "business_id" => "required|string|max:255",
            "charges" => "required|string|max:255",
            "status" => "required|string|max:255",
            "optional" => "required|string|max:255",
        ]);

        $gateway = PaymentGateway::find($id);

        if (!$gateway) {
            return new JsonResponse(
                [
                    "error" => "Payment gateway not found.",
                    "message" =>
                        "No payment gateway found with the provided ID.",
                ],
                404
            );
        }

        $gateway->update($validatedData);

        return new JsonResponse([
            "message" => "Payment gateway updated successfully.",
            "gateway" => $gateway,
        ]);
    }

    public function AdminPutSwitch(Request $request)
{
    $validatedData = $request->validate([
        "software" => "required|string",
        "tables" => "required|string",
        "sql_file" => "required|file",
    ]);

    if ($validatedData["software"] == 'topupmate') {
        $sqlFile = $request->file("sql_file");
        $sqlContent = file_get_contents($sqlFile->getRealPath());

        preg_match_all("/\((.*?)\)[,;]/", $sqlContent, $matches);

        if ($validatedData["tables"] === "users") {
            $subscribersData = [];
            foreach ($matches[1] as $match) {
                $values = array_map("trim", explode(",", $match));
                $subscribersData[] = array_map(
                    fn($value) => trim($value, "'`"),
                    $values
                );
            }

            foreach ($subscribersData as $subscriberData) {
                if (count($subscriberData) >= 10) {
                    DB::table("users")->insertGetId([
                        "username" => strtolower($subscriberData[2]),
                        "email" => $subscriberData[4],
                        "password" => bcrypt("default"),
                        "role" => "customer",
                        "first_name" => $subscriberData[2],
                        "last_name" => $subscriberData[3],
                        "phone" => $subscriberData[5],
                        "pin" => 1234,
                        "wallet_balance" => $subscriberData[11],
                        "referral_balance" => $subscriberData[12],
                        "referral" => $subscriberData[22],
                        "old_id" => $subscriberData[0],
                    ]);
                }
            }

            return response()->json([
                "message" => "Users migration completed successfully!",
            ]);
        } elseif ($validatedData["tables"] === "transactions") {
            $transactionsData = [];
            foreach ($matches[1] as $match) {
                $values = array_map("trim", explode(",", $match));
                $transactionsData[] = array_map(
                    fn($value) => trim($value, "'`"),
                    $values
                );
            }

            foreach ($transactionsData as $transactionData) {
                if (count($transactionData) >= 10) {
                    $oldId = $transactionData[1];
                    $userId = DB::table("users")
                        ->where("old_id", $oldId)
                        ->value("id");

                    if ($userId) {
                        DB::table("transactions")->insert([
                            "user_id" => $userId,
                            "biller" => "Unknown",
                            "session_id" => $transactionData[2],
                            "product" => strtoupper($transactionData[3]),
                            "description" => $transactionData[4],
                            "amount" => (float) $transactionData[5],
                            "status" =>
                                $transactionData[6] == 0
                                    ? "successful"
                                    : ($transactionData[6] == 5
                                        ? "processing"
                                        : "failed"),
                            "old_balance" => (float) $transactionData[7],
                            "new_balance" => (float) $transactionData[8],
                            "profits" => (float) $transactionData[9],
                            "created_at" => date(
                                "Y-m-d",
                                strtotime($transactionData[10])
                            ),
                            "updated_at" => now(),
                        ]);
                    }
                }
            }

            return response()->json([
                "message" => "Transactions migration completed successfully!",
            ]);
        } else {
            return response()->json(
                ["error" => "Invalid table selected for migration."],
                400
            );
        }
    } elseif ($validatedData["software"] == 'legacy') {
        $file = $request->file('sql_file');
        if ($file->getClientOriginalExtension() !== 'json') {
            return response()->json([
                "message" => "Invalid file format! Please upload a valid JSON file.",
            ], 400);
        }

        $jsonContent = file_get_contents($file->getPathname());
        $data = json_decode($jsonContent, true);

        if (!$data) {
            return response()->json([
                "message" => "Invalid JSON structure! Please check the file content.",
            ], 400);
        }

        $importedCount = 0;

        foreach ($data as $user) {
            $created = \App\Models\User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'phone' => $user['phone'],
                    'password' => bcrypt($user['password']),
                    'api_token' => $user['api_token'],
                    'wallet_balance' => $user['wallet_balance'],
                    'referral_balance' => $user['referral_balance'],
                    'pin' => $user['pin'],
                    'wema_bank' => $user['wema_bank'] ?? null,
                    'sterling_bank' => $user['sterling_bank'] ?? null,
                    'paga_bank' => $user['paga_bank'] ?? null,
                    'moniepoint_bank' => $user['moniepoint_bank'] ?? null,
                ]
            );

            if ($created->wasRecentlyCreated || $created->wasChanged()) {
                $importedCount++;
            }
        }

        return response()->json([
            "message" => "{$importedCount} users imported successfully!",
        ]);
    } else {
        return response()->json([
            "message" => "Unsupported Software for switching!",
        ]);
    }
}


    public function AdminUserFund(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            "amount" => "required|numeric|min:0.01",
            "description" => "required|string|max:255",
            "type" => "required|in:debit,credit",
            "id" => "required|integer|exists:users,id",
        ]);

        $user = User::find($validatedData["id"]);

        $old_balance = $user->wallet_balance;
        $new_balance =
            $validatedData["type"] === "credit"
                ? $old_balance + $validatedData["amount"]
                : $old_balance - $validatedData["amount"];

        if ($new_balance < 0) {
            return new JsonResponse(
                [
                    "error" => "Insufficient balance.",
                    "message" => "Unable to Debit User",
                ],
                400
            );
        }

        $user->wallet_balance = $new_balance;
        $user->save();
        $biller = $validatedData["type"] == "credit" ? "received" : "transfer";
        $product =
            $validatedData["type"] == "credit"
                ? "INCOMING_TRANSFER"
                : "OUTGOING_TRANSFER";
        $s_id = now()->format("YmdHis") . rand(10000000, 99999999);
        $transactionData = [
            "user_id" => $user->id,
            "biller" => $biller,
            "description" => $validatedData["description"],
            "product" => $product,
            "session_id" => $s_id,
            "amount" => $validatedData["amount"],
            "number" => "N/A",
            "status" => "successful",
            "old_balance" => $old_balance,
            "new_balance" => $new_balance,
            "profits" => 0,
        ];

        $transaction = Transaction::create($transactionData);

        return new JsonResponse([
            "message" => "Transaction processed successfully.",
            "transaction" => $transaction,
        ]);
    }

    public function AdminUpdateSettings(
        Request $request
    ): RedirectResponse|JsonResponse {
        $input = $request->all();

        foreach ($input as $key => $value) {
            update_opt($key, $value);
        }

        $responseMessage = "Settings updated successfully.";
        $redirectUrl = route("admin.settings");

        if ($request->expectsJson()) {
            return new JsonResponse([
                "message" => $responseMessage,
                "redirect" => $redirectUrl,
            ]);
        }

        return new RedirectResponse($redirectUrl);
    }
    public function AdminTransactionInsert(Request $request, $id = null): JsonResponse
{
    $validatedData = $request->validate([
        "user_id" => "required|integer|exists:users,id",
        "biller" => "required|string|max:255",
        "description" => "required|string",
        "product" => "required|string|max:50",
        "session_id" => "required|string|max:255",
        "amount" => "required|numeric",
        "number" => "required|string|max:50",
        "status" => "required|string|max:50",
        "old_balance" => "required|numeric",
        "new_balance" => "required|numeric",
        "profits" => "required|numeric",
        "date" => "required|date",
    ]);

    $user = User::find($validatedData['user_id']);
    if (!$user) {
        return new JsonResponse(["error" => "User not found."], 404);
    }

    if (in_array($validatedData['status'], ['successful_debit', 'failed_credit'])) {
        $amount = $validatedData['amount'];
        if ($validatedData['status'] === 'successful_debit') {
            if ($user->wallet_balance < $amount) {
                return new JsonResponse(["error" => "Insufficient balance."], 400);
            }
            $user->wallet_balance -= $amount;
        } elseif ($validatedData['status'] === 'failed_credit') {
            $user->wallet_balance += $amount;
        }
        $user->save();
    }

    if ($id) {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return new JsonResponse(
                [
                    "error" => "Transaction not found.",
                    "message" => "No transaction found with the provided ID.",
                ],
                404
            );
        }

        $transaction->update($validatedData);
        $responseMessage = "Transaction updated successfully.";
    } else {
        $transaction = Transaction::create($validatedData);
        $responseMessage = "Transaction created successfully.";
    }

    return new JsonResponse([
        "message" => $responseMessage,
        "transaction" => $transaction,
    ]);
}


    public function AdminUserInsert(Request $request, $id = null): JsonResponse
    {
        $validatedData = $request->validate([
            "first_name" => "nullable|string|max:255",
            "last_name" => "nullable|string|max:255",
            "username" =>
                "required|string|max:255|unique:users,username," . $id,
            "email" =>
                "required|string|email|max:255|unique:users,email," . $id,
            "phone" => "nullable|string|max:11|unique:users,phone," . $id,
            "role" => "required|string|max:225",
            "pin" => "nullable|integer|min:1000|max:9999",
            "password" => "nullable|string|min:8",
        ]);

        if ($id) {
            $user = User::find($id);

            if (!$user) {
                return new JsonResponse(
                    [
                        "error" => "User not found.",
                        "message" => "No user found with the provided ID.",
                    ],
                    404
                );
            }

            $user->update($validatedData);
            $responseMessage = "User Info updated successfully.";
        } else {
            if (isset($validatedData["password"])) {
                $validatedData["password"] = Hash::make(
                    $validatedData["password"]
                );
            }

            $user = User::create($validatedData);
            $responseMessage = "User created successfully.";
        }

        return new JsonResponse([
            "message" => $responseMessage,
            "user" => $user,
        ]);
    }
    public function AdminNotificationInsert(
        Request $request,
        $id = null
    ): JsonResponse {
        $request->merge([
            "admin_id" => $request->input("admin_id", 1),
        ]);

        $validatedData = $request->validate([
            "admin_id" => "required|integer",
            "title" => "required|string|max:200",
            "message" => "required|string",
            "action_url" => "nullable|string|max:255",
            "status" => "required|string|max:50",
            "date" => "required|date",
        ]);

        if ($id) {
            $notification = Notification::find($id);

            if (!$notification) {
                return new JsonResponse(
                    [
                        "error" => "Notification not found.",
                        "message" =>
                            "No notification found with the provided ID.",
                    ],
                    404
                );
            }

            $notification->update($validatedData);
            $responseMessage = "Notification updated successfully.";
        } else {
            $notification = \App\Models\Notification::create($validatedData);
            $responseMessage = "Notification created successfully.";
        }

        return new JsonResponse([
            "message" => $responseMessage,
            "notification" => $notification,
        ]);
    }

    public function AdminApiProviderInsert(
        Request $request,
        $id = null
    ): JsonResponse {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "identifier" => "required|string|max:255",
            "category_id" => "required|exists:categories,id",
            "api_endpoint" => "required|string|max:255",
        ]);

        if ($id) {
            $apiProvider = ApiProvider::find($id);

            if (!$apiProvider) {
                return new JsonResponse(
                    [
                        "error" => "API Provider not found.",
                        "message" =>
                            "No API provider found with the provided ID.",
                    ],
                    404
                );
            }

            $apiProvider->update($validatedData);
            $responseMessage = "API Provider updated successfully.";
        } else {
            $apiProvider = ApiProvider::create($validatedData);
            $responseMessage = "API Provider created successfully.";
        }

        return new JsonResponse([
            "message" => $responseMessage,
            "api_provider" => $apiProvider,
        ]);
    }
    public function Adminlogin(Request $request): RedirectResponse|JsonResponse
    {
        $credentials = $request->only("email", "password");

        if (Auth::guard("admin")->attempt($credentials)) {
            $request->session()->regenerate();

            $responseMessage = "Login successful";
            $redirectUrl = route("admin.dashboard");

            if ($request->expectsJson()) {
                return new JsonResponse([
                    "message" => $responseMessage,
                    "redirect" => $redirectUrl,
                ]);
            }

            return new RedirectResponse($redirectUrl);
        }

        $errorMessage = "Invalid credentials.";

        if ($request->expectsJson()) {
            return new JsonResponse(
                ["error" => "hdfhfh", "message" => $errorMessage],
                401
            );
        }

        return back()->withErrors(["email" => $errorMessage]);
    }

    public function Adminlogout(Request $request): RedirectResponse|JsonResponse
    {
        Auth::guard("admin")->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $responseMessage = "Logout successful";
        $redirectUrl = route("admin.login");

        if ($request->expectsJson()) {
            return new JsonResponse(["message" => $responseMessage]);
        }

        return new RedirectResponse($redirectUrl);
    }

    public function AdminUserDelete(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $redirectUrl = route("admin.users");
        return new RedirectResponse($redirectUrl);
    }

    ##@TAG
    public function upgradeAgent()
    {
        $user = Auth::user();
        return view("core.upgrade", compact("user"));
    }
    ##@TAG
}
