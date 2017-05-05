<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\TaxRates\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\TaxRates\Repositories\TaxRateRepository;
use FI\Modules\TaxRates\Validators\TaxRateValidator;
use FI\Support\NumberFormatter;

class TaxRateController extends Controller
{
    public function __construct(TaxRateRepository $taxRateRepository, TaxRateValidator $taxRateValidator)
    {
        parent::__construct();
        $this->taxRateRepository = $taxRateRepository;
        $this->taxRateValidator  = $taxRateValidator;
    }

    public function index()
    {
        $taxRates = $this->taxRateRepository->paginate();

        return view('tax_rates.index')
            ->with('taxRates', $taxRates);
    }

    public function create()
    {
        return view('tax_rates.form')
            ->with('editMode', false);
    }

    public function store()
    {
        $input = request()->all();

        $input['percent'] = NumberFormatter::unformat($input['percent']);

        $validator = $this->taxRateValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('taxRates.create')
                ->with('editMode', false)
                ->withErrors($validator)
                ->withInput();
        }

        $this->taxRateRepository->create($input);

        return redirect()->route('taxRates.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
        $taxRate = $this->taxRateRepository->find($id);

        return view('tax_rates.form')
            ->with(['editMode' => true, 'taxRate' => $taxRate, 'taxRateInUse' => $this->taxRateRepository->taxRateInUse($id)]);
    }

    public function update($id)
    {
        $input = request()->all();

        $input['percent'] = NumberFormatter::unformat($input['percent']);

        $validator = $this->taxRateValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('taxRates.edit', [$id])
                ->with('editMode', true)
                ->withErrors($validator)
                ->withInput();
        }

        $this->taxRateRepository->update($input, $id);

        return redirect()->route('taxRates.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        $alert = $this->taxRateRepository->delete($id);

        return redirect()->route('taxRates.index')
            ->with('alert', $alert);
    }
}