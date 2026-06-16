<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Http\Requests\BlogPostCreateRequest;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Resources\Api\Blog\Admin\PostResource;

class PostController extends BaseController
{
    use DispatchesJobs;
    public function __construct(private BlogPostRepository $blogPostRepository,
                                private BlogCategoryRepository $blogCategoryRepository)
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 25);
        $search = $request->input('search');

        if ($perPage < 1) {
            $perPage = 25;
        }

        if ($perPage > 100) {
            $perPage = 100;
        }
        // Отримуємо пагіновані дані з репозиторія
        $paginator = $this->blogPostRepository->getAllWithPaginate($perPage, $search);

        // Обгортаємо пагінацію в API Ресурс
        return PostResource::collection($paginator);
    }

    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->validated();

        $item = BlogPost::create($data);

        if ($item) {
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);

            $item->load(['category', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено',
                'data' => new PostResource($item),
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Помилка збереження',
        ], 500);
    }

    public function show($id)
    {
        $post = BlogPost::with(['category', 'user'])->find($id);

        if (empty($post)) {
            return response()->json([
                'success' => false,
                'message' => "Запис id=[{$id}] не знайдено",
            ], 404);
        }

        return new PostResource($post);
    }

    public function update(Request $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Запис id=[{$id}] не знайдено",
            ], 404);
        }

        $data = $request->validated();

        $result = $item->update($data);

        if ($result) {
            $item->load(['category', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено',
                'data' => new PostResource($item),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Помилка збереження',
        ], 500);
    }

    public function destroy(string $id)
    {
        $item = BlogPost::find($id);

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Запис id=[{$id}] не знайдено",
            ], 404);
        }

        $result = $item->delete(); //софт деліт, запис лишається

        //$result = BlogPost::find($id)->forceDelete(); //повне видалення з БД

        if ($result) {
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            return response()->json([
                'success' => true,
                'message' => "Запис id=[{$id}] успішно видалено",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Помилка видалення запису id=[{$id}]",
        ], 500);
    }
}
