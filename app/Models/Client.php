<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'name',
        'email',
        'status',
        'joined_at',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
