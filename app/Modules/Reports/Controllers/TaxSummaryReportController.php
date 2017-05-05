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
use FI\Modules\Reports\Repositories\TaxSummaryReportRepository;
use FI\Modules\Reports\Validators\ReportValidator;
use FI\Support\DateFormatter;
use FI\Support\PDF\PDFFactory;

class TaxSummaryReportController extends Controller
{
    public function __construct(
        TaxSummaryReportRepository $taxSummaryReportRepository,
        ReportValidator $reportValidator)
    {
        parent::__construct();
        $this->taxSummaryReportRepository = $taxSummaryReportRepository;
        $this->reportValidator            = $reportValidator;
    }

    public function index()
    {
        return view('reports.tax_summary');
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
        $results = $this->taxSummaryReportRepository->getResults(DateFormatter::unformat(request('from_date')), DateFormatter::unformat(request('to_date')));

        return view('reports._tax_summary')
            ->with('results', $results);
    }

    public function pdf()
    {
        $pdf = PDFFactory::create();

        $results = $this->taxSummaryReportRepository->getResults(DateFormatter::unformat(request('from_date')), DateFormatter::unformat(request('to_date')));

        $html = view('reports._tax_summary')
            ->with('results', $results)->render();

        $pdf->download($html, trans('fi.tax_summary') . '.pdf');
    }
}