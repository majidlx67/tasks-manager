<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'title',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected static function booted(): void
    {
        static::saved(function ($subtask) {
            $subtask->task->updateStatusBasedOnSubtasks();
        });
        static::deleted(function ($subtask) {
            $subtask->task->updateStatusBasedOnSubtasks();
        });
    }


    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

}
