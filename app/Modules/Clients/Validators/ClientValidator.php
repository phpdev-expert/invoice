<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Clients\Validators;

use Validator;

class ClientValidator
{
    public function getValidator($input)
    {
        $rules = [
            'name'        => 'required',
            'unique_name' => 'required|unique:clients'
        ];

        if ($emailRules = $this->getEmailRules($input))
        {
            $rules['email'] = $emailRules;
        }

        if ($passwordRules = $this->getPasswordRules($input))
        {
            $rules['password'] = $passwordRules;
        }

        return Validator::make($input, $rules);
    }

    public function getUpdateValidator($input, $client)
    {
        $rules = [
            'name'        => 'required',
            'unique_name' => 'required|unique:clients,unique_name,' . $client->id
        ];

        if ($emailRules = $this->getUpdateEmailRules($input, $client))
        {
            $rules['email'] = $emailRules;
        }

        if ($passwordRules = $this->getUpdatePasswordRules($input, $client))
        {
            $rules['password'] = $passwordRules;
        }

        return Validator::make($input, $rules);
    }

    public function getImportValidator($input)
    {
        return Validator::make($input, [
            'name' => 'required'
        ]);
    }

    public function getApiUpdateValidator($input, $id)
    {
        $rules = ['id' => 'required'];

        if (isset($input['unique_name']))
        {
            $rules['unique_name'] = 'unique:clients,unique_name,' . $id;
        }

        return Validator::make($input, $rules);
    }

    private function getEmailRules($input)
    {
        if ($this->allowLogin($input))
        {
            return 'required|email|unique:clients,email|unique:users,email';
        }

        if (isset($input['email']) and $input['email'])
        {
            return 'email|unique:clients,email|unique:users,email';
        }

        return null;
    }

    private function getPasswordRules($input)
    {
        if ($this->allowLogin($input))
        {
            return 'required|confirmed';
        }

        return null;
    }

    private function getUpdateEmailRules($input, $client)
    {
        if ($this->allowLogin($input))
        {
            if (!count($client->user) or !$client->user->password)
            {
                return 'required|email|unique:clients,email,' . $client->id . '|unique:users,email';

            }
            elseif (count($client->user))
            {
                return 'required|email|unique:clients,email,' . $client->id . '|unique:users,email,' . $client->id . ',client_id';
            }
        }
        else
        {
            if (isset($input['email']) and $input['email'])
            {
                return 'required|email|unique:clients,email,' . $client->id . '|unique:users,email';
            }
        }

        return null;
    }

    private function getUpdatePasswordRules($input, $client)
    {
        if ($this->allowLogin($input))
        {
            if (!count($client->user) or !$client->user->password)
            {
                return 'required|confirmed';
            }
        }

        return null;
    }

    private function allowLogin($input)
    {
        if (isset($input['allow_login']) and $input['allow_login'])
        {
            return true;
        }

        return false;
    }
}