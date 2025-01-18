<?php

namespace Jiannius\Atom\Actions;

use Illuminate\Support\Facades\Auth;

class Logout
{
    public function run()
    {
        Auth::logout();

        request()->session()->flush();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
