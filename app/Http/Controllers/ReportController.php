<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportFilterRequest;
use App\Services\ReportService;
use Illuminate\View\View;

/**
 * Controller for Financial & Transaction Reports Module (Slide 6, 18, 25 PPT).
 */
class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    /**
     * Display reports dashboard with interactive date range and category filters.
     */
    public function index(ReportFilterRequest $request): View
    {
        $filters = $request->validated();
        $reportType = $filters['report_type'] ?? 'sales';

        $summary = $this->reportService->getFinancialSummary($filters);
        $stockSummary = $this->reportService->getStockValuationSummary();

        $data = match ($reportType) {
            'purchases' => $this->reportService->getPurchasesReport($filters, 15),
            'stock' => $this->reportService->getStockReport(15),
            default => $this->reportService->getSalesReport($filters, 15),
        };

        return view('reports.index', compact(
            'summary',
            'stockSummary',
            'data',
            'reportType',
            'filters'
        ));
    }

    /**
     * Display clean print-ready layout for reports (Print View / Cetak Laporan).
     */
    public function print(ReportFilterRequest $request): View
    {
        $filters = $request->validated();
        $printData = $this->reportService->getPrintData($filters);

        return view('reports.print', compact('printData'));
    }
}
