<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Трансформація ресурсу в масив.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $this вказує на поточний об'єкт моделі BlogPost
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'excerpt'        => $this->excerpt,
            'content_raw'    => $this->content_raw,
            'content_html'   => $this->content_html,
            'is_published'   => (bool) $this->is_published,
            'published_at'   => $this->published_at,
            // Форматуємо дату для зручності фронтенду
            'date_published' => $this->published_at ? \Carbon\Carbon::parse($this->published_at)->format('Y-m-d H:i:s') : null,

            // Передаємо id зв'язаних сутностей
            'user_id'        => $this->user_id,
            'category_id'    => $this->category_id,

            // Приклад виведення зв'язку (якщо у моделі BlogPost налаштовано зв'язки category та user)
            'category'       => $this->category ? ['title' => $this->category->title] : null,
            'user'           => $this->user ? ['name' => $this->user->name] : null,
        ];
    }
}
