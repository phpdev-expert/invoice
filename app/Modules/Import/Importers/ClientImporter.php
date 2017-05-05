<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Import\Importers;

use FI\Modules\Clients\Repositories\ClientRepository;
use FI\Modules\Clients\Validators\ClientValidator;

class ClientImporter extends AbstractImporter
{
    public function __construct(ClientRepository $clientRepository, ClientValidator $clientValidator)
    {
        $this->clientRepository = $clientRepository;
        $this->clientValidator  = $clientValidator;
    }

    public function getFields()
    {
        return [
            'name'        => '* ' . trans('fi.name'),
            'unique_name' => trans('fi.unique_name'),
            'address'     => trans('fi.address'),
            'city'        => trans('fi.city'),
            'state'       => trans('fi.state'),
            'zip'         => trans('fi.postal_code'),
            'country'     => trans('fi.country'),
            'phone'       => trans('fi.phone'),
            'fax'         => trans('fi.fax'),
            'mobile'      => trans('fi.mobile'),
            'email'       => trans('fi.email'),
            'web'         => trans('fi.web')
        ];
    }

    public function getMapRules()
    {
        return ['name' => 'required'];
    }

    public function getValidator($input)
    {
        return $this->clientValidator->getImportValidator($input);
    }

    public function importData($input)
    {
        $row = 1;

        $fields = [];

        foreach ($input as $field => $key)
        {
            if (is_numeric($key))
            {
                $fields[$key] = $field;
            }
        }

        try
        {
            $handle = fopen(storage_path('clients.csv'), 'r');
        }

        catch (\ErrorException $e)
        {
            $this->messages->add('error', 'Could not open the file');

            return false;
        }

        while (($data = fgetcsv($handle, 1000, ',')) !== false)
        {
            if ($row !== 1)
            {
                $record = [];

                foreach ($fields as $key => $field)
                {
                    $record[$field] = $data[$key];
                }

                if ($this->validateRecord($record))
                {
                    $this->clientRepository->create($record);
                }
            }
            $row++;
        }

        fclose($handle);

        return true;
    }
}