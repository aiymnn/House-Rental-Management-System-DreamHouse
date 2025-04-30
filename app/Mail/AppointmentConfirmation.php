<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;
    public $date;
    public $time;
    public $agent;

    /**
     * Create a new message instance.
     */
    public function __construct($tenant, $date, $time, $agent)
    {
        $this->tenant = $tenant;
        $this->date = $date;
        $this->time = $time;
        $this->agent = $agent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Appointment Confirmation')
                    ->view('emails.appointment_confirmation')
                    ->with([
                        'tenant' => $this->tenant,
                        'date' => $this->date,
                        'time' => $this->time,
                        'agent' => $this->agent,
                    ]);
    }
}
