<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Validator;

use App\General;
use App\User;

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
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/dashboard';

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
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'r_username'	=> 'required|max:255|unique:users,username|profanity-filter',
			'email'		=> 'required|email|max:255|unique:users',
			'r_password'	=> 'required|min:6|confirmed',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data)
	{
		if (isset($data['img'])) { $img = General::uploadImg($data['img'], 'users', true); }
		else { $img = null; }

		return User::create([
			'username'	=> $data['r_username'],
			'slug'		=> General::sluggify($data['r_username'], 'users'),
			'email'		=> $data['email'],
			'password'	=> bcrypt($data['r_password']),
		]);
	}
}
