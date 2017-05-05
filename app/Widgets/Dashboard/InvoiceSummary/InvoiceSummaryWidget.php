<?php

namespace FI\Widgets\Dashboard\InvoiceSummary;

use FI\Widgets\Dashboard\InvoiceSummary\Repositories\InvoiceSummaryWidgetRepository;

class InvoiceSummaryWidget
{
    public function __construct(InvoiceSummaryWidgetRepository $invoiceSummaryWidgetRepository)
    {
        $this->invoiceSummaryWidgetRepository = $invoiceSummaryWidgetRepository;
    }

    public function compose($view)
    {
        $view->with('invoicesTotalDraft', $this->invoiceSummaryWidgetRepository->getInvoiceTotalDraft())
            ->with('invoicesTotalSent', $this->invoiceSummaryWidgetRepository->getInvoiceTotalSent())
            ->with('invoicesTotalPaid', $this->invoiceSummaryWidgetRepository->getInvoiceTotalPaid())
            ->with('invoicesTotalOverdue', $this->invoiceSummaryWidgetRepository->getInvoiceTotalOverdue());
    }
}