<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Sessions\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Sessions\Validators\SessionValidator;

class SessionController extends Controller
{
    public function __construct(SessionValidator $sessionValidator)
    {
        parent::__construct();
        $this->sessionValidator = $sessionValidator;
    }

    public function login()
    {
        return view('sessions.login');
    }

    public function attempt()
    {
        $validator  = $this->sessionValidator->getValidator(request()->all());
        $rememberMe = (request('remember_me')) ? true : false;

        if ($validator->fails())
        {
            return redirect()->route('session.login')->withErrors($validator);
        }

        if (!auth()->attempt(['email' => request('email'), 'password' => request('password')], $rememberMe))
        {
            return redirect()->route('session.login')->with('error', trans('fi.invalid_credentials'));
        }

        if (!auth()->user()->client_id)
        {
            return redirect()->route('dashboard.index');
        }

        return redirect()->route('clientCenter.dashboard');

    }

    public function logout()
    {
        auth()->logout();

        session()->flush();

        return redirect()->route('session.login');
    }
}