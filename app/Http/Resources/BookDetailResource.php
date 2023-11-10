<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookDetailResource extends JsonResource
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
            'user_id' => $this->user_id,
            'author' => optional($this->user)->name ?? "Unknown Author",
            'long_description' => $this->long_description,
            'short_description' => $this->short_description,
            'published_at' => $this->published_at,
            'reviews' => $this->reviews,
            'average_rating' => $this->averageRating(),
        ];
    }
}
