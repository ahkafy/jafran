<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessController extends Controller
{
    /**
     * Show access blocked page
     */
    public function blocked()
    {
        $country = session('blocked_country', 'Unknown');
        $ip = session('blocked_ip', 'Unknown');

        return view('access.blocked', compact('country', 'ip'));
    }
}
