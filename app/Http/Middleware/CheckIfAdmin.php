<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class CheckIfAdmin
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (!isset($user)) {
            return redirect(route('login'));
        }

        if (!$user->isAdmin()) {
            return redirect(route('home'));
        }
        App::setLocale('ru');

        return $next($request);
    }
}
