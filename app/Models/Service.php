<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'image', 'is_active', 'sort_order'];

    public function requests()
    {
        return $this->belongsToMany(ServiceRequest::class, 'service_request_service')->withTimestamps();
    }

    public function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = \App\Support\Html::clean($value);
    }
}