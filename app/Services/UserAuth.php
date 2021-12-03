<?php


namespace App\Services;

use App\Mail\Password;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserAuth
{
    public function authUser($arData): \Illuminate\Http\RedirectResponse
    {

        $user = User::where('email', $arData['login'])->orWhere('phone', $arData['login'])->first();

        if ($user) {
            if ($res = Auth::attempt(['id' => $user->id, 'password' => $arData['password']], true)) {
                return redirect()->intended(route('home'));
            } else {
                return back()->withErrors(__('auth/login.error'))->withInput($arData);
            }
        } else {
            return back()->withErrors(__('auth/login.not_found'))->withInput($arData);
        }
    }

    /**
     * @param $arData
     * @return array|string[]
     */
    public function registerUser($arData)
    {
        if (isset($arData['code']) && $arData['code']) {
            $savedCode = Session::get('checking_phone');
            if ($savedCode) {
                $savedCode = json_decode($savedCode, true);
                if (isset($savedCode[$arData['phone']]) && $savedCode[$arData['phone']] == $arData['code']) {
                    $arData['password'] = Hash::make($arData['password']);
                    $user = User::create($arData);
                    Auth::login($user);
                    Session::forget('checking_phone');
                    return [
                        'status' => 'success'
                    ];
                } else {
                    return ['status' => 'error', 'message' => __('auth/register.unavailable_code')];
                }
            } else {
                return ['status' => 'error', 'message' => __('auth/register.code_already_send')];
            }
        } else {
            return $this->sendCode($arData['phone']);
        }
    }

    public function forgetPassword($arData)
    {
        $user = User::where('email', $arData['login'])->orWhere('phone', $arData['login'])->first();
        if ($user) {
            $this->sendNewPassword($user);
            return redirect()->route('login')->with('status', __('auth/forget.password_send'));
        } else {
            return back()->withErrors(__('auth/forget.not_found'))->withInput($arData);
        }
    }

    /**
     * @param $phone
     * @return array
     */
    private function sendCode($phone)
    {
        $savedCode = Session::get('checking_phone');
        if ($savedCode) {
            $savedCode = json_decode($savedCode, true);
            if (isset($savedCode[$phone]) && $savedCode[$phone]) {
                return ['status' => 'error', 'message' => __('auth/register.code_already_send')];
            }
        }
        $code = rand(1000, 9999);

        Session::put('checking_phone', json_encode([$phone => $code]));
        $SmsPilot = new SmsPilot();
        $SmsPilot->send($phone, __('auth/register.confirm_code', ['code' => $code]));
        return [
            'status' => 'success',
            //'code' => $code,
            'html' => sprintf('<div class="field tq-info-text">
                   %s
                </div><div class="field-name" id="tq_code">
                    %s
                </div>
                <div class="field">
                    <input type="text" name="code"  placeholder="1234">
                </div>', __('auth/register.info'), __('auth/register.code'))
        ];
    }

    private function sendNewPassword($user)
    {
        $password = Str::random(10);
        $user->password = Hash::make($password);
        $user->save();
        Mail::send(new Password(__('auth/forget.forget', ['password' => $password]), $user->email));
    }

}
