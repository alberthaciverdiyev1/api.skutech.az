<?php

namespace Modules\Blog\Http\Controllers;

use App\Services\CrudService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blog\Http\Requests\BlogStoreRequest;
use Modules\Blog\Http\Requests\BlogUpdateRequest;
use Modules\Blog\Entities\Blog;
use Modules\Core\Traits\Files\ImageCompressor;
use Nwidart\Modules\Facades\Module;

class BlogController extends Controller
{
    use ImageCompressor;

    protected CrudService $crudService;

    public function __construct()
    {
        $this->crudService = new CrudService();

        if (Module::find('Role')->isEnabled()) {
            $this->middleware('permission:view blogs')->only('index');
            $this->middleware('permission:create blog')->only('create');
            $this->middleware('permission:store blog')->only('store');
            $this->middleware('permission:edit blog')->only('edit');
            $this->middleware('permission:update blog')->only('update');
            $this->middleware('permission:destroy blog')->only('destroy');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('blog::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blog::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogStoreRequest $request): JsonResponse
    {
        return $this->crudService->store($request, Blog::class, function ($blog) use ($request) {
            //TODO: MOVE TO HasGallery!!!
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $image) {
                    $blog->gallery()->create([
                        'image' => self::compressImage($image, 'BlogGalleryImage'),
                    ]);
                }
            }
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        $gallery = $blog->gallery->map(function ($image) {
            return [
                'id' => $image->getAttribute('id'),
                'src' => $image->getImage(),
            ];
        });
        return view('blog::edit', compact([
            'blog',
            'gallery'
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogUpdateRequest $request, Blog $blog): JsonResponse
    {
        return $this->crudService->update($request, $blog, function ($blog) use ($request) {
            //TODO: MOVE TO HasGallery!!!
            if (!empty($request->input('preloaded'))) {
                $deletedIds = array_diff($blog->gallery->pluck('id')->toArray(), $request->input('preloaded'));

                if (!empty($deletedIds)) {
                    $blog->gallery()->whereIn('id', $deletedIds)->delete();
                }
            } else {
                $blog->gallery()->delete();
            }

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $image) {
                    $blog->gallery()->create([
                        'image' => $this->compressImage($image, 'BlogGalleryImage'),
                    ]);
                }
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog): JsonResponse
    {
        return $this->crudService->destroy($blog);
    }
}
