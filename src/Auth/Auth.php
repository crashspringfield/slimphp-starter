<?php

namespace App\Auth;

use App\Models\User;

class Auth {

  public function attempt($email, $password) {
    // verify the user exists
    $user = User::where('email', $email)->first();

    if (!$user) {
      return false;
    }

    // verify the password is correct
    if (!password_verify($password, $user->password)) {
      return false;
    }

    // set the session
    $_SESSION['user'] = $user->id;
    return true;
  }

  public function check() {
    return isset($_SESSION['user']);
  }

  public function user() {
    if (isset($_SESSION['user'])) {
      return User::find($_SESSION['user']);
    }
    return false;
  }

  public function logout() {
    unset($_SESSION['user']);
  }
}
