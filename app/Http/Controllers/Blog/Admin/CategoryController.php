<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Resources\Api\Blog\Admin\CategoryResource;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //dd(__METHOD__);
        //$paginator = BlogCategory::paginate(5);

        $perPage = (int) $request->input('per_page', 25);
        $search = $request->input('search');

        if ($perPage < 1) {
            $perPage = 25;
        }

        if ($perPage > 100) {
            $perPage = 100;
        }

        $paginator = $this->blogCategoryRepository->getAllWithPaginate($perPage, $search);

        return CategoryResource::collection($paginator);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $item = BlogCategory::create($data);

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено',
                'data' => new CategoryResource($item),
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Помилка збереження',
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //dd(__METHOD__);

        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Категорію id=[{$id}] не знайдено",
            ], 404);
        }

        return new CategoryResource($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        //dd(__METHOD__);

        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Запис id=[{$id}] не знайдено",
            ], 404);
        }

        $data = $request->validated();

        if ((int) $data['parent_id'] === (int) $id) {
            return response()->json([
                'success' => false,
                'message' => 'Категорія не може бути батьківською сама для себе',
            ], 422);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $result = $item->update($data);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено',
                'data' => new CategoryResource($item),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Помилка збереження',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //dd(__METHOD__);

        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Категорію id=[{$id}] не знайдено",
            ], 404);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Категорію успішно видалено',
        ]);
    }
}
