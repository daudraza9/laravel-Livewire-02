<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $casts = [
        'date'=>'datetime',
        'time'=>'datetime',
        ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badge=[
          'SCHEDULED'=>'primary',
          'CLOSED'=>'success'
        ];
        return $badge[$this->status];
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->toFormatDate();
    }

    public function getTimeAttribute($value)
    {

        return Carbon::parse($value)->toFormatTime();
    }
}
