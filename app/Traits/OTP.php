<?php

namespace App\Traits;

use Illuminate\Support\Facades\Date;
use Random\RandomException;

trait OTP
{
    /**
     * @throws RandomException
     */
    public function generateOTP(): int
    {
        return random_int(10000, 99999);
    }

    public function verifyOTP($otp): null
    {
        if ($this->getAttribute('otp_expired_at') > Date::now() && $this->getAttribute('otp') === $otp) {
            return true;
        }
        return null;
    }

    public function expireOTP(): \Illuminate\Support\Carbon
    {
        return Date::now()->addMinutes(env('OTP_EXPIRE_TIME', 5));
    }
}
