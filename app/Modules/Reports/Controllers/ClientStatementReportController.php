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
use FI\Modules\Reports\Repositories\ClientStatementReportRepository;
use FI\Modules\Reports\Validators\ClientStatementReportValidator;
use FI\Support\DateFormatter;
use FI\Support\PDF\PDFFactory;

class ClientStatementReportController extends Controller
{
    public function __construct(
        ClientStatementReportRepository $clientStatementReportRepository,
        ClientStatementReportValidator $clientStatementReportValidator
    )
    {
        parent::__construct();
        $this->clientStatementReportRepository = $clientStatementReportRepository;
        $this->clientStatementReportValidator  = $clientStatementReportValidator;
    }

    public function index()
    {
        return view('reports.client_statement');
    }

    public function ajaxValidate()
    {
        $validator = $this->clientStatementReportValidator->getValidator(request()->all());

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
        $results = $this->clientStatementReportRepository->getResults(
            request('client_name'),
            DateFormatter::unformat(request('from_date')),
            DateFormatter::unformat(request('to_date')));

        return view('reports._client_statement')
            ->with('results', $results);
    }

    public function pdf()
    {
        $pdf = PDFFactory::create();

        $results = $this->clientStatementReportRepository->getResults(
            request('client_name'),
            DateFormatter::unformat(request('from_date')),
            DateFormatter::unformat(request('to_date')));

        $html = view('reports._client_statement')
            ->with('results', $results)->render();

        $pdf->download($html, trans('fi.client_statement') . '.pdf');
    }
}