<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->pluck('name')),
            'image_url' => $this->image_path ? Storage::disk('public')->url($this->image_path) : null,
            'remind_at' => $this->remind_at,
            'created_at' => $this->created_at,
        ];
    }
}
