<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\CustomFields\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CustomFields\Repositories\CustomFieldRepository;
use FI\Modules\CustomFields\Validators\CustomFieldValidator;
use FI\Support\CustomFields;

class CustomFieldController extends Controller
{
    protected $customFieldRepository;

    protected $customFieldValidator;

    public function __construct(CustomFieldRepository $customFieldRepository, CustomFieldValidator $customFieldValidator)
    {
        parent::__construct();
        $this->customFieldRepository = $customFieldRepository;
        $this->customFieldValidator  = $customFieldValidator;
    }

    public function index()
    {
        $customFields = $this->customFieldRepository->paginate();

        return view('custom_fields.index')
            ->with('customFields', $customFields)
            ->with('tableNames', CustomFields::tableNames());
    }

    public function create()
    {
        return view('custom_fields.form')
            ->with('editMode', false)
            ->with('tableNames', CustomFields::tableNames())
            ->with('fieldTypes', CustomFields::fieldTypes());
    }

    public function store()
    {
        $input = request()->all();

        $validator = $this->customFieldValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('customFields.create')
                ->with('editMode', false)
                ->withErrors($validator)
                ->withInput();
        }

        $input['column_name'] = $this->customFieldRepository->getNextColumnName($input['table_name']);

        $this->customFieldRepository->create($input);

        $this->customFieldRepository->createCustomColumn($input['table_name'], $input['column_name'], $input['field_type']);

        return redirect()->route('customFields.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
        $customField = $this->customFieldRepository->find($id);

        return view('custom_fields.form')
            ->with('editMode', true)
            ->with('customField', $customField)
            ->with('tableNames', CustomFields::tableNames())
            ->with('fieldTypes', CustomFields::fieldTypes());
    }

    public function update($id)
    {
        $input = request()->all();

        unset($input['table_name']);

        $validator = $this->customFieldValidator->getUpdateValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('customFields.edit', [$id])
                ->with('editMode', true)
                ->withErrors($validator)
                ->withInput();
        }

        $this->customFieldRepository->update($input, $id);

        return redirect()->route('customFields.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        $customField = $this->customFieldRepository->find($id);

        $this->customFieldRepository->deleteCustomColumn($customField->table_name, $customField->column_name);

        $this->customFieldRepository->delete($id);

        return redirect()->route('customFields.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }
}
