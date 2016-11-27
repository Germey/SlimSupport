<?php
namespace Germey\Support;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Models\User;

class Auth
{
	/**
	 * @param $email
	 * @param $password
	 * @return bool
	 */
	public function attempt($username, $password)
	{
		if (is_email($username)) {
			$credentials = ['email' => $username];
		} else if (is_phone($username)) {
			$credentials = ['phone' => $username];
		} else {
			$credentials = ['name' => $username];
		}
		$user = User::where($credentials)->first();
		if (! $user) {
			return false;
		}
		if (Hash::check($password, $user->password)) {
			return $user;
		}
		return false;
	}
	
}