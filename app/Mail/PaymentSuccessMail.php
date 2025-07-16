<?php

namespace App\Mail;

use App\Models\Rental; // Impor model Rental
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $rental; // Properti untuk menyimpan data pesanan

    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pembayaran Berhasil - GoRentAll',
        );
    }

    public function content(): Content
    {
        // Kita akan menggunakan view 'emails.payment-success'
        return new Content(
            view: 'emails.payment_success',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}