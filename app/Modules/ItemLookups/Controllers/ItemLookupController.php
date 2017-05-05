<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\ItemLookups\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\ItemLookups\Repositories\ItemLookupRepository;
use FI\Modules\ItemLookups\Validators\ItemLookupValidator;
use FI\Support\NumberFormatter;

class ItemLookupController extends Controller
{
    protected $itemLookup;

    protected $validator;

    public function __construct(ItemLookupRepository $itemLookupRepository, ItemLookupValidator $itemLookupValidator)
    {
        parent::__construct();
        $this->itemLookupRepository = $itemLookupRepository;
        $this->itemLookupValidator  = $itemLookupValidator;
    }

    public function index()
    {
        $itemLookups = $this->itemLookupRepository->paginate();

        return view('item_lookups.index')
            ->with('itemLookups', $itemLookups);
    }

    public function create()
    {
        return view('item_lookups.form')
            ->with('editMode', false);
    }

    public function store()
    {
        $input = request()->all();

        $input['price'] = NumberFormatter::unformat($input['price']);

        $validator = $this->itemLookupValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('itemLookups.create')
                ->with('editMode', false)
                ->withErrors($validator)
                ->withInput();
        }

        $this->itemLookupRepository->create($input);

        return redirect()->route('itemLookups.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
        $itemLookup = $this->itemLookupRepository->find($id);

        return view('item_lookups.form')
            ->with(['editMode' => true, 'itemLookup' => $itemLookup]);
    }

    public function update($id)
    {
        $input = request()->all();

        $input['price'] = NumberFormatter::unformat($input['price']);

        $validator = $this->itemLookupValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('itemLookups.edit', [$id])
                ->with('editMode', true)
                ->withErrors($validator)
                ->withInput();
        }

        $this->itemLookupRepository->update($input, $id);

        return redirect()->route('itemLookups.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        $this->itemLookupRepository->delete($id);

        return redirect()->route('itemLookups.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function ajaxItemLookup()
    {
        return $this->itemLookupRepository->getJsonLookup(request('query'));
    }
}