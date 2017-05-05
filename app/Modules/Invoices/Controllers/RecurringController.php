<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Invoices\Controllers;

use FI\Modules\Invoices\Repositories\RecurringInvoiceRepository;
use FI\Http\Controllers\Controller;
use FI\Support\Frequency;

class RecurringController extends Controller
{
    public function __construct(RecurringInvoiceRepository $recurringInvoiceRepository)
    {
        parent::__construct();
        $this->recurringInvoiceRepository = $recurringInvoiceRepository;
    }

    public function index()
    {
        $recurringInvoices = $this->recurringInvoiceRepository->paginate(request('search'));

        return view('recurring.index')
            ->with('recurringInvoices', $recurringInvoices)
            ->with('frequencies', Frequency::lists())
            ->with('displaySearch', true);
    }

    public function delete($id)
    {
        $this->recurringInvoiceRepository->delete($id);

        return redirect()->route('recurring.index');
    }

    public function stop($id)
    {
        $this->recurringInvoiceRepository->stop($id);

        return redirect()->route('recurring.index');
    }
}