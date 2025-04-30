<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;
    public $pdf;
    public $property;

    /**
     * Create a new message instance.
     */
    public function __construct($tenant, $pdf, $property)
    {
        $this->tenant = $tenant;
        $this->pdf = $pdf;
        $this->property = $property; // Pass the property
    }

    public function build()
    {
        return $this->subject('Your Rental Contract')
                    ->view('emails.contract_email') // Ensure this view exists
                    ->attachData($this->pdf->output(), 'Rental_Contract.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
