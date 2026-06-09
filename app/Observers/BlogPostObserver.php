<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;

class BlogPostObserver
{
    /**
     * Обробка перед оновленням запису.
     */
    public function updating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
    }

    /**
     * Генеруємо дату, якщо треба
     */
    protected function setPublishedAt(BlogPost $blogPost)
    {
        if (empty($blogPost->published_at) && $blogPost->is_published) {
            $blogPost->published_at = Carbon::now();
        }
    }

    /**
     * Генеруємо псевдонім, якщо порожній
     */
    protected function setSlug(BlogPost $blogPost)
    {
        if (empty($blogPost->slug)) {
            $blogPost->slug = \Illuminate\Support\Str::slug($blogPost->title);
        }
    }
}
