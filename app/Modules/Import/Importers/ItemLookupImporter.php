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

use FI\Modules\ItemLookups\Repositories\ItemLookupRepository;
use FI\Modules\ItemLookups\Validators\ItemLookupValidator;

class ItemLookupImporter extends AbstractImporter
{
    public function __construct(ItemLookupRepository $itemLookupRepository, ItemLookupValidator $itemLookupValidator)
    {
        $this->itemLookupRepository = $itemLookupRepository;
        $this->itemLookupValidator  = $itemLookupValidator;
    }

    public function getFields()
    {
        return [
            'name'        => '* ' . trans('fi.name'),
            'description' => '* ' . trans('fi.description'),
            'price'       => '* ' . trans('fi.price')
        ];
    }

    public function getMapRules()
    {
        return [
            'name'        => 'required',
            'description' => 'required',
            'price'       => 'required'
        ];
    }

    public function getValidator($input)
    {
        return $this->itemLookupValidator->getValidator($input);
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
            $handle = fopen(storage_path('itemLookups.csv'), 'r');
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
                    $this->itemLookupRepository->create($record);
                }
            }
            $row++;
        }

        fclose($handle);

        return true;
    }
}