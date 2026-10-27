<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'image', 'price', 'price_type', 'is_active', 'sort_order'];

    public function priceLabel(): string
    {
        if ($this->price === null || $this->price_type === 'on_inspection') {
            return 'Price on inspection';
        }
        $amount = "\u{20B9}" . number_format($this->price, 2);

        return $this->price_type === 'starting_from' ? "Starting from $amount" : $amount;
    }

    public function requests()
    {
        return $this->belongsToMany(ServiceRequest::class, 'service_request_service')->withTimestamps();
    }

    public function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = \App\Support\Html::clean($value);
    }
}