<?php

namespace App\Http\Controllers;


use App\Http\Requests\ForgetRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\SiteSettings;
use App\Services\UserAuth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    /**
     * @param SiteSettings $siteSettings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function login(SiteSettings $siteSettings)
    {
        Session::forget('checking_phone');
        return view('auth.login', ['siteSettings' => $siteSettings->getSiteSettings()]);
    }

    /**
     * @param UserAuth $userAuthService
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authUser(UserAuth $userAuthService, LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        return $userAuthService->authUser($request->validated());
    }


    /**
     * @param SiteSettings $siteSettings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function register(SiteSettings $siteSettings)
    {
        Session::forget('checking_phone');
        return view('auth.register', ['siteSettings' => $siteSettings->getSiteSettings()]);
    }

    /**
     * @param UserAuth $userAuthService
     * @param RegisterRequest $request
     * @return array
     */
    public function userRegister(UserAuth $userAuthService, RegisterRequest $request): array
    {
        return $userAuthService->registerUser($request->validated());
    }

    /**
     * @param SiteSettings $siteSettings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function forgetPassword(SiteSettings $siteSettings)
    {
        return view('auth.forget', ['siteSettings' => $siteSettings->getSiteSettings()]);
    }

    /**
     * @param UserAuth $userAuthService
     * @param ForgetRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendNewPassword(
        UserAuth $userAuthService,
        ForgetRequest $request
    ): \Illuminate\Http\RedirectResponse {
        return $userAuthService->forgetPassword($request->validated());
    }

}
