<?php

namespace Illuminate\Http
{
    class Request
    {
        public function hasValidSignature($absolute = true)
        {
            return URL::hasValidSignature($this, $absolute);
        }

        public function validate(array $rules, ...$params = null)
        {
            return validator()->validate($this->all(), $rules, ...$params);
        }

        public function validateWithBag(string $errorBag, array $rules, ...$params = null)
        {
            try {
                return $this->validate($rules, ...$params);
            } catch (ValidationException $e) {
                $e->errorBag = $errorBag;

                throw $e;
            }
        }
    }
}

namespace Illuminate\Routing
{
    class Router
    {
        public function auth($options = [
        ])
        {
            // Login Routes...
            if ($options['login'] ?? true) {
                $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
                $this->post('login', 'Auth\LoginController@login');
            }

            // Logout Routes...
            if ($options['logout'] ?? true) {
                $this->post('logout', 'Auth\LoginController@logout')->name('logout');
            }

            // Registration Routes...
            if ($options['register'] ?? true) {
                $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
                $this->post('register', 'Auth\RegisterController@register');
            }

            // Password Reset Routes...
            if ($options['reset'] ?? true) {
                $this->resetPassword();
            }

            // Password Confirmation Routes...
            if ($options['confirm'] ??
                class_exists($this->prependGroupNamespace('Auth\ConfirmPasswordController'))) {
                $this->confirmPassword();
            }

            // Email Verification Routes...
            if ($options['verify'] ?? false) {
                $this->emailVerification();
            }
        }

        public function confirmPassword()
        {
            $this->get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
            $this->post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
        }

        public function emailVerification()
        {
            $this->get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
            $this->get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
            $this->post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
        }

        public function resetPassword()
        {
            $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
            $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
            $this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
        }
    }
}
