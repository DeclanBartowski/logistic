<?php

namespace App\Http\Controllers;


use App\Http\Requests\MailRequest;
use App\Services\MailService;

class MailController extends Controller
{

    /**
     * @param MailRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(MailRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->validated();
        MailService::send($request->get('email'), $request->get('title'), $request->get('text'));
        return redirect()->back()->with('status', 'Сообщение успешно отправлено!!');
    }

}
