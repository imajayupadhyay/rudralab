<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'status',
        'read_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function markRead(): void
    {
        if ($this->read_at) {
            return;
        }

        $this->forceFill([
            'status' => 'read',
            'read_at' => now(),
        ])->save();
    }
}
