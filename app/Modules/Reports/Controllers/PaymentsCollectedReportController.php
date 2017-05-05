<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Reports\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Reports\Repositories\PaymentsCollectedReportRepository;
use FI\Modules\Reports\Validators\ReportValidator;
use FI\Support\DateFormatter;
use FI\Support\PDF\PDFFactory;

class PaymentsCollectedReportController extends Controller
{
    public function __construct(
        PaymentsCollectedReportRepository $paymentsCollectedReportRepository,
        ReportValidator $reportValidator
    )
    {
        parent::__construct();
        $this->paymentsCollectedReportRepository = $paymentsCollectedReportRepository;
        $this->reportValidator                   = $reportValidator;
    }

    public function index()
    {
        return view('reports.payments_collected');
    }

    public function ajaxValidate()
    {
        $validator = $this->reportValidator->getDateRangeValidator(request()->all());

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        return response()->json(['success' => true]);
    }

    public function html()
    {
        $results = $this->paymentsCollectedReportRepository->getResults(
            DateFormatter::unformat(request('from_date')),
            DateFormatter::unformat(request('to_date'))
        );

        return view('reports._payments_collected')
            ->with('results', $results);
    }

    public function pdf()
    {
        $pdf = PDFFactory::create();

        $results = $this->paymentsCollectedReportRepository->getResults(
            DateFormatter::unformat(request('from_date')),
            DateFormatter::unformat(request('to_date'))
        );

        $html = view('reports._payments_collected')
            ->with('results', $results)->render();

        $pdf->download($html, trans('fi.payments_collected') . '.pdf');
    }
}