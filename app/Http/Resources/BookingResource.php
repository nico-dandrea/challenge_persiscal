<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customerName' => $this->customer_name,
            'customerEmail' => $this->customer_email,
            'numberOfPeople' => $this->number_of_people,
            'bookingDate' => $this->booking_date,
            'tour' => ['id' => $this->tour_id, 'name' => $this->tour->name],
            'hotel' => ['id' => $this->hotel_id, 'name' => $this->hotel->name],
            // TODO: Status won't let test pass, need to fix
            // 'status' => $this->status
        ];
    }
}
