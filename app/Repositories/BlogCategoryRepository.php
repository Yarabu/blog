<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogСategoryRepository.
 */
class BlogCategoryRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class; //абстрагування моделі BlogCategory, для легшого створення іншого репозиторія
    }
    /**
     *  Отримати модель для редагування в адмінці
     *  @param int $id
     *  @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     *  Отримати список категорій для виводу в випадаючий список
     *  @return Collection
     */
    public function getForComboBox()
    {
        //return $this->startConditions()->all();
        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) AS id_title',  //додаємо поле id_title
        ]);
        $result = $this
        ->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->get();

        //dd($result);

        return $result;
    }
    /**
     * Отримати категорію для виводу пагінатором
     *
     * @param int|null $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = 25, $search = null)
    {
        $columns = [
            'id',
            'title',
            'slug',
            'description',
            'parent_id',
        ];

        $query = $this
            ->startConditions()
            ->select($columns)
            ->with(['parentCategory:id,title'])
            ->orderBy('id', 'DESC');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $result = $query->paginate($perPage);

        return $result;
    }
}
