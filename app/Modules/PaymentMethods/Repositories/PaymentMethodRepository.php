<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\PaymentMethods\Repositories;

use FI\Modules\PaymentMethods\Models\PaymentMethod;

class PaymentMethodRepository
{
    public function find($id)
    {
        return PaymentMethod::find($id);
    }

    public function paginate()
    {
        return PaymentMethod::sortable(['name' => 'asc'])->paginate(config('fi.resultsPerPage'));
    }

    public function lists()
    {
        return PaymentMethod::orderBy('name')->lists('name', 'id')->all();
    }

    public function create($input)
    {
        return PaymentMethod::create($input);
    }

    public function firstOrCreate($paymentMethod)
    {
        return PaymentMethod::firstOrCreate(['name' => $paymentMethod]);
    }

    public function update($input, $id)
    {
        $paymentMethod = PaymentMethod::find($id);

        $paymentMethod->fill($input);

        $paymentMethod->save();

        return $paymentMethod;
    }

    public function delete($id)
    {
        PaymentMethod::destroy($id);
    }
}