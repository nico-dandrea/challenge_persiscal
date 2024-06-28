<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Booking $booking
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your Booking Confirmation",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-created',
            with: [
                'customerName' => $this->booking->customer_name,
                'numberOfPeople' => $this->booking->number_of_people,
                'bookingDate' => $this->booking->booking_date,
                'tour' => [
                    'name' => $this->booking->tour->name,
                    'price' => $this->booking->tour->price,
                    'startDate' => $this->booking->tour->start_date,
                    'endDate' => $this->booking->tour->end_date,
                ],
                'hotel' => [
                    'name' => $this->booking->hotel->name,
                    'pricePerNight' => $this->booking->hotel->price_per_night,
                    'address' => $this->booking->hotel->address,
                ],
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
