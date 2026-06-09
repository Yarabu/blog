<?php

namespace App\Observers;

use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategoryObserver
{
    /**
     * Обробка перед створенням запису.
     */
    public function creating(BlogCategory $blogCategory)
    {
        $this->setSlug($blogCategory);
    }

    /**
     * Обробка перед оновленням запису.
     */
    public function updating(BlogCategory $blogCategory)
    {
        $this->setSlug($blogCategory);
    }

    /**
     * Генеруємо псевдонім
     */
    protected function setSlug(BlogCategory $blogCategory)
    {
        if (empty($blogCategory->slug)) {
            $blogCategory->slug = Str::slug($blogCategory->title);
        }
    }
}
