<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinancialReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfPath;

    public function __construct($pdfPath)
    {
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Daily Financial Report - ' . now()->format('Y-m-d'))
                    ->view('emails.financial_report')
                    ->attach($this->pdfPath, [
                        'as' => 'financial_report_' . now()->format('Ymd') . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}