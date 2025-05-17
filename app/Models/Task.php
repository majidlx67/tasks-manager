<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($task) {
            $task->subtasks()->delete();
        });
    }

    protected $appends = [
        'status_text',
        'is_overdue',
    ];

    protected function statusText(): Attribute
    {
        return new Attribute(
            get: fn (mixed $value, array $attributes) => match ($attributes['status']) {
                'todo' => 'Todo',
                'in_progress' => 'In progress',
                'done' => 'Done',
                default => 'Unknown',
            },
        );
    }

    protected function isOverdue(): Attribute
    {
        return new Attribute(
            get: fn (mixed $value, array $attributes) => $attributes['due_date'] < now()->format("Y-m-d"),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function updateStatusBasedOnSubtasks(): void
    {
        if ($this->subtasks()->where('status', 'pending')->doesntExist()) {
            $this->update(['status' => 'done']);
        } else {
            if($this->status === 'done'){
                $this->update(['status' => 'in_progress']);
            }
        }
    }

}
