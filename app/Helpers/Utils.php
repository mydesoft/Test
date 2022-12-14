<?php

namespace App\Helpers;

use App\Repositories\GeocodingRepository;
use App\Services\GoogleDistance;
use Carbon\Carbon;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Illuminate\Support\Facades\DB;

class Utils
{
    public static function generateOtp(int $digits = 4): string
    {
        $p = 0;
        $otp = '';

        while ($p < $digits) {
            $otp .= mt_rand(0, 9);
            $p++;
        }

        return $otp;
    }

    public static function isValidOTP(int|string $otp): bool
    {
        return  DB::table('password_resets')
            ->where( 'token', $otp)
            ->whereTime('created_at', '>=', Carbon::now()->subMinutes(10))->count() === 1;
    }

    public static function generatePassword(int $length = 10): string
    {
        $generator = new ComputerPasswordGenerator();

        $generator
            ->setUppercase()
            ->setLowercase()
            ->setNumbers()
            ->setSymbols()
            ->setLength($length);

        return $generator->generatePassword();
    }

    public static function generateRandomCode(int $length, string $prefix = null): string
    {
        $identifier = '';

        $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeAlphabet .= '0123456789';

        $max = strlen($codeAlphabet);

        for ($i = 0; $i < $length; $i++) {
            $identifier .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return "$prefix$identifier";
    }

 
}
