<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Currencies\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Currencies\Support\CurrencyConverterFactory;
use FI\Modules\Currencies\Repositories\CurrencyRepository;
use FI\Modules\Currencies\Validators\CurrencyValidator;

class CurrencyController extends Controller
{
    protected $currencyRepository;

    protected $currencyValidator;

    public function __construct(CurrencyRepository $currencyRepository, CurrencyValidator $currencyValidator)
    {
        parent::__construct();
        $this->currencyRepository = $currencyRepository;
        $this->currencyValidator  = $currencyValidator;
    }

    public function index()
    {
        $currencies = $this->currencyRepository->paginate();

        return view('currencies.index')
            ->with('currencies', $currencies)
            ->with('baseCurrency', config('fi.baseCurrency'));
    }

    public function create()
    {
        return view('currencies.form')
            ->with('editMode', false);
    }

    public function store()
    {
        $input = request()->all();

        $validator = $this->currencyValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('currencies.create')
                ->with('editMode', false)
                ->withErrors($validator)
                ->withInput();
        }

        $this->currencyRepository->create($input);

        return redirect()->route('currencies.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
        $currency = $this->currencyRepository->find($id);

        return view('currencies.form')
            ->with(['editMode' => true, 'currency' => $currency, 'currencyInUse' => $this->currencyRepository->currencyInUse($id)]);
    }

    public function update($id)
    {
        $input = request()->all();

        $validator = $this->currencyValidator->getUpdateValidator($input, $id);

        if ($validator->fails())
        {
            return redirect()->route('currencies.edit', [$id])
                ->with('editMode', true)
                ->withErrors($validator)
                ->withInput();
        }

        $this->currencyRepository->update($input, $id);

        return redirect()->route('currencies.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        $alert = $this->currencyRepository->delete($id);

        return redirect()->route('currencies.index')
            ->with('alert', $alert);
    }

    public function getExchangeRate()
    {
        $currencyConverter = CurrencyConverterFactory::create();

        return $currencyConverter->convert(config('fi.baseCurrency'), request('currency_code'));
    }
}