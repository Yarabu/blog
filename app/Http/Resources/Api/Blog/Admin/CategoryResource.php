<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,

            'parent_category' => $this->whenLoaded('parentCategory', function () {
                return [
                    'id' => $this->parentCategory?->id,
                    'title' => $this->parentCategory?->title,
                ];
            }),
        ];
    }
}
