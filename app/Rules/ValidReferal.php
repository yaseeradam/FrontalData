<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class ValidReferral implements Rule
{
    public function __construct()
    {
        // DarK XYT iNT vcasghfty14e#Q@#Q54336 niluh*frt Hrgw bnM GH8724JNBU65e@qfchfxE42YY1Y78 >n*(U845NKJ89)
    }

    public function passes($attribute, $value)
    {
        return User::where('username', $value)->exists();
    }

    public function message()
    {
         return new JsonResponse([
                'message' => 'invalid Referral code'
            ]);
    }
}
