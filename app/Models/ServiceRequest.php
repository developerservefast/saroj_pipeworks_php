<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'follow_up' => 'Follow-up',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    protected $fillable = [
        'name', 'email', 'phone', 'address',
        'status', 'admin_notes', 'contacted_at',
    ];

    protected $casts = ['contacted_at' => 'datetime'];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_request_service')->withTimestamps();
    }
}
