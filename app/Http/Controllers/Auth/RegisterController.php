<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/app';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(\Illuminate\Http\Request $request)
    {
        $referralCode = $request->get('ref');
        $sponsor = null;

        if ($referralCode) {
            $sponsor = User::where('referral_code', $referralCode)->first();
        }

        return view('auth.register', compact('referralCode', 'sponsor'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        // If referral code is provided, validate it exists
        if (isset($data['referral_code']) && !empty($data['referral_code'])) {
            $rules['referral_code'] = ['string', 'exists:users,referral_code'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $sponsor_id = null;

        // If referral code is provided, find the sponsor
        if (isset($data['referral_code']) && !empty($data['referral_code'])) {
            $sponsor = User::where('referral_code', $data['referral_code'])->first();
            if ($sponsor) {
                $sponsor_id = $sponsor->id;
            }
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'sponsor_id' => $sponsor_id,
            'wallet_balance' => 0,
            'commission_balance' => 0,
            'status' => 'active',
        ]);
    }
}
