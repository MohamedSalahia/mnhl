<?php

namespace App\Services;

use App\Enums\LoginOtpCreatedForEnum;
use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Notifications\LoginOtpCreated;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use function App\Services\V3\get_browser_locale;
use function App\Services\V3\get_ip_data;

class AuthService
{
    public function createUser($request, $socialLogin = false)
    {
        $requestData = $this->prepareRequest($request, $socialLogin);

        $user = User::create($requestData);

        $user->addRole(UserTypeEnum::USER);

        $this->sendLoginOtp($user, LoginOtpCreatedForEnum::MOBILE);

        $user->refresh();

        return $user;

    }// end of createUser

    public function resetPassword($request)
    {
        $user = User::FindOrFail(User::keyFromHashId($request->user_id));

        $user->update([
            'password' => bcrypt($request->password),
        ]);

    }// end of resetPassword

    public function setPasswordResetCodeToNull($request)
    {
        $user = User::FindOrFail(User::keyFromHashId($request->user_id));

        $user->update([
            'password_reset_code' => null
        ]);

        return $user;

    }// end of setPasswordResetCodeToNull

    public function checkTooManyFailedAttempts()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return false;
        }

        return true;

    }//end of checkTooManyFailedAttempts

    public function throttleKey()
    {
        return Str::lower(request('email')) . '|' . request()->ip();

    }//end of throttle keys

    public function getRequestCountry()
    {
        $ipData = get_ip_data(request()->ip());

        $country = Country::where('alpha_two', $ipData['country_code'])->first();

        if (!$country) {

            $country = Country::where('fallback', 1)->first();

        }

        return $country;

    }// end of getRequestCountry

    public function generateUniqueUserName()
    {
        $name = '';

        do {

            $name = 'kashkom' . str()->random(8);

        } while (User::where('name', $name)->exists());

        return $name;

    }// end of generateUniqueUserName

    public function socialLogin($socialUser, $provider, $countryId, $operatingSystem = null, $uniqueDeviceId = null)
    {
        $isNewUser = false;

        $user = User::query()
            ->with(['categories', 'categories.translations'])
            ->where('email', $socialUser->getEmail())
            // ->where('provider_id', '!=', null)
            // ->where('provider', '!=', $provider)
            ->where('email_verified_at', '!=', null)
            ->first();

        if (!$user) {

            $user = User::create([
                'country_id' => $countryId,
                'service_country_id' => $countryId,
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'name' => $socialUser->getName() ?? $this->generateUniqueUserName(),
                'email' => $socialUser->getEmail(),
                'email_verified_at' => $socialUser->getEmail() ? now() : null,
                'image' => $socialUser->getAvatar(),
                'mobile' => $socialUser->mobile,
                'mobile_verified_at' => $socialUser->mobile ? now() : null,
                'type' => 'user',
                'unique_device_id' => $uniqueDeviceId,
                'operating_system' => $operatingSystem,
                'locale' => request()->hasHeader('X-localization')
                    ? request()->header('X-Localization')
                    : get_browser_locale()
            ]);

            $user->categories()->sync(
                Category::query()
                    ->where('type', CategoryTypeEnum::POST)
                    ->pluck('id')
                    ->toArray()
            );

            $user->syncRolesWithoutDetaching(['user']);

            $isNewUser = true;

            $user->notify(new UserWelcomeMail());

        } else {

            $user->update([
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'operating_system' => $operatingSystem
            ]);

        }

        $profileService = new ProfileService();

        $profileService->updateProfileCompletionPercent($user);

        return [
            'new_user' => $isNewUser,
            'user' => $user,
        ];

    }// end of socialLogin

    public function sendLoginOtp(User $user, $loginOtpCreatedFor)
    {
        do {

            $loginOtp = sprintf("%06d", mt_rand(1, 999999));

        } while (User::where('login_otp', $loginOtp)->exists());

        $user->update([
            'login_otp' => $loginOtp,
            'login_otp_created_for' => $loginOtpCreatedFor,
            'login_otp_verified_at' => null,
            'login_otp_created_at' => now()
        ]);

        if (in_array(config('app.env'), ['local', 'dev']) == false) {

            $user->notify(new LoginOtpCreated($loginOtp, $loginOtpCreatedFor));

        }

    }// end of sendLoginOtp

    public function prepareRequest($request)
    {
        $requestData = $request->validated();

        $requestData['locale'] = request()->hasHeader('x-localization');

        return $requestData;

    }// end of prepareRequest

}//end of service