<?php

namespace App\Exports;

use App\Models\Candidate;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DailyReportExport implements FromView, ShouldAutoSize
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        $totalCandidates = Candidate::whereBetween('created_at', [$this->from, $this->to])->count();

        $payments = Payment::with(['invoice.candidate', 'invoice.testType', 'receiver'])
            ->whereBetween('payment_date', [$this->from, $this->to])
            ->orderBy('payment_date')
            ->get();

        $totalCollection = $payments->sum('amount');

        $breakdown = [
            'cash' => $payments->where('payment_method', 'cash')->sum('amount'),
            'cheque' => $payments->where('payment_method', 'cheque')->sum('amount'),
            'online' => $payments->where('payment_method', 'online')->sum('amount'),
        ];

        return view('reports.export', [
            'from' => $this->from,
            'to' => $this->to,
            'totalCandidates' => $totalCandidates,
            'totalCollection' => $totalCollection,
            'breakdown' => $breakdown,
            'payments' => $payments,
        ]);
    }
}
