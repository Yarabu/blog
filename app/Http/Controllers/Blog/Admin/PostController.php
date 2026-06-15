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
    public function index()
    {
        // Отримуємо пагіновані дані з репозиторія
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        // Обгортаємо пагінацію в API Ресурс
        return PostResource::collection($paginator);
    }

    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input(); //отримаємо масив даних, які надійшли з форми

        $item = (new BlogPost())->create($data); //створюємо об'єкт і додаємо в БД

        if ($item) {
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);
            return ['success' => 'Успішно збережено'];
        } else {
            return ['msg' => 'Помилка збереження'];
        }
    }

    public function show($id)
    {
        // Шукаємо пост за ID і одразу підтягуємо його категорію та автора
        $post = \App\Models\BlogPost::with(['category', 'user'])->findOrFail($id);

        // Повертаємо у форматі JSON
        return response()->json(['data' => $post]);
    }

    public function update(Request $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) { //якщо ід не знайдено
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all(); //отримаємо масив даних, які надійшли з форми
        $result = $item->update($data); //оновлюємо дані об'єкта і зберігаємо в БД

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    public function destroy(string $id)
    {
        $result = BlogPost::destroy($id); //софт деліт, запис лишається

        //$result = BlogPost::find($id)->forceDelete(); //повне видалення з БД

        if ($result) {
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);
            return ['success' => true, 'message' => "Запис id=[{$id}] успішно видалено"];
        } else {
            return ['message' => "Помилка видалення запису id=[{$id}]"];
        }
    }
}
