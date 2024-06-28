<?php

namespace App\Casts;

use App\Enums\BookingStatusEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class BookingStatus implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     * @return BookingStatusEnum $value
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $status, array $attributes): mixed
    {
        if (! $status instanceof BookingStatusEnum) {
            throw new \InvalidArgumentException('Invalid booking status');
        }

        $canceled = BookingStatusEnum::CANCELED->value;

        if ($attributes['status'] === $canceled && $status->value === $canceled) {
            throw new \App\Exceptions\CannotCancelConfirmedBookingException($attributes['id']);
        }

        return $status;
    }
}
