<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Validators;

use Validator;

class SendEmailValidator
{
    public function getValidator($input)
    {
        Validator::extend('emails', function ($attribute, $value, $parameters)
        {
            $emails = explode(',', $value);

            $rules = ['email' => 'required|email'];

            foreach ($emails as $email)
            {
                $data = ['email' => $email];

                $validator = Validator::make($data, $rules);

                if ($validator->fails())
                {
                    return false;
                }
            }

            return true;
        });

        $rules = [
            'subject' => 'required',
            'body'    => 'required'
        ];

        if (strstr($input['to'], ','))
        {
            $rules['to'] = 'required|emails';
        }
        else
        {
            $rules['to'] = 'required|email';
        }

        if ($input['cc'])
        {
            if (strstr($input['cc'], ','))
            {
                $rules['cc'] = 'required|emails';
            }
            else
            {
                $rules['cc'] = 'required|email';
            }
        }

        if ($input['bcc'])
        {
            if (strstr($input['bcc'], ','))
            {
                $rules['bcc'] = 'required|emails';
            }
            else
            {
                $rules['bcc'] = 'required|email';
            }
        }

        return Validator::make($input, $rules, ['emails' => trans('fi.multiple_email_validation')]
        );
    }
}