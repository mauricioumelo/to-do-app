<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
        ];
    }
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
