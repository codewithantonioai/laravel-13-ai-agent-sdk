<?php

namespace App\Models;

use Database\Factories\NoteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['title', 'content', 'image_path', 'remind_at'])]
class Note extends Model
{
    /** @use HasFactory<NoteFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::updated(function (Note $note) {
            if ($note->wasChanged('image_path') && $note->getOriginal('image_path')) {
                Storage::disk('public')->delete($note->getOriginal('image_path'));
            }
        });

        static::deleted(function (Note $note) {
            if ($note->image_path) {
                Storage::disk('public')->delete($note->image_path);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'remind_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
