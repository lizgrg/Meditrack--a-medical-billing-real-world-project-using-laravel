<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Payment;
use App\Exports\DailyReportExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected function resolveDateRange(Request $request): array
    {
        $from = $request->query('from') ? \Carbon\Carbon::parse($request->query('from'))->startOfDay() : now()->startOfDay();
        $to = $request->query('to') ? \Carbon\Carbon::parse($request->query('to'))->endOfDay() : now()->endOfDay();

        return [$from, $to];
    }

    protected function buildSummary($from, $to): array
    {
        $totalCandidates = Candidate::whereBetween('created_at', [$from, $to])->count();

        $payments = Payment::whereBetween('payment_date', [$from, $to])->get();

        $totalCollection = $payments->sum('amount');

        $breakdown = [
            'cash' => $payments->where('payment_method', 'cash')->sum('amount'),
            'cheque' => $payments->where('payment_method', 'cheque')->sum('amount'),
            'online' => $payments->where('payment_method', 'online')->sum('amount'),
        ];

        $counts = [
            'cash' => $payments->where('payment_method', 'cash')->count(),
            'cheque' => $payments->where('payment_method', 'cheque')->count(),
            'online' => $payments->where('payment_method', 'online')->count(),
        ];

        return compact('totalCandidates', 'totalCollection', 'breakdown', 'counts', 'payments');
    }

    public function index(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);
        $summary = $this->buildSummary($from, $to);

        return view('reports.index', array_merge($summary, [
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
        ]));
    }

    public function export(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $filename = 'report-' . $from->format('Y-m-d') . '-to-' . $to->format('Y-m-d') . '.xlsx';

        return Excel::download(new DailyReportExport($from, $to), $filename);
    }
}
