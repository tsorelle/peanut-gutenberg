<?php
namespace Peanut\WordpressTools\services;
use Tops\mail\TEmailValidator;
use Tops\services\TServiceCommand;
class MakeWpuserCommand extends TServiceCommand
{
    protected function run()
    {
        if (!current_user_can('create_users')) {
            $this->addErrorMessage('Insufficient permissions');
            return;
        }

        $request = $this->getRequest();
        if (!$request)
        {
            $this->addErrorMessage('No request received');
            return;
        }
        if (empty($request->username)) {
            $this->addErrorMessage('No username provided');
            return;
        }

        $username = sanitize_user($request->username);
        if (empty($username)) {
            $this->addErrorMessage('Invalid username');
            return;
        }

        if (empty($request->password)) {
            $request->password = wp_generate_password();
        }

        if (empty($request->email))
        {
            $this->addErrorMessage('No email provided');
            return;
        }
        if (TEmailValidator::Invalid($request->email)) {
            $this->addErrorMessage('Invalid email address');
            return;
        }

        $firstname = isset($request->firstName) ? sanitize_text_field($request->firstName) : '';
        $lastname  = isset($request->lastName) ? sanitize_text_field($request->lastName) : '';
        $fullname  = trim($firstname.' '.$lastname);

        if (empty($fullname))
        {
            $this->addErrorMessage('Name not provided');
            return;
        }
        if (strlen($fullname) < 2) {
            $this->addErrorMessage('Full name is too short');
            return;
        }

        $existing = get_user_by('login', $username);
        if ($existing) {
            $this->addErrorMessage('Username already in use');
            return;
        }
        $existing = get_user_by('email', $request->email);
        if ($existing) {
            $this->addErrorMessage('Email already in use');
            return;
        }
        if (empty($request->role)) {
            $request->role = 'subscriber';
        }
        $role = sanitize_key($request->role);

        $user_data = [
            'user_login'   => $username,
            'user_email'   => $request->email,
            'user_pass'    => $request->password,
            'role'         => $role,
            'display_name' => $fullname,
            'first_name'   => $firstname,
            'last_name'    => $lastname,
        ];

        $user_id = wp_insert_user($user_data);

        if (is_wp_error($user_id)) {
            $this->addErrorMessage('User creation failed: ' . $user_id->get_error_message());
            return;
        }

        $user = get_userdata($user_id);
        if (!$user) {
            $this->addErrorMessage('User creation failed: user not found');
            return;
        }
        $key = get_password_reset_key($user);
        if (is_wp_error($key)) {
            $this->addWarningMessage('Cannot get reset key: ' . $key->get_error_message());
            return;
        }
        $response = new \stdClass();
        $response->newUser = $fullname;
        $response->resetKey = $key;
        $response->url =
            network_site_url("wp-login.php?action=rp&key=$key&login=" .
                rawurlencode($user->user_login), 'login');
        $this->setReturnValue($response);
    }
}

