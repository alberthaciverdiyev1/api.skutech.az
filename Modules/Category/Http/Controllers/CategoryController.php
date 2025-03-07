<?php

namespace Modules\Category\Http\Controllers;

use App\Services\CrudService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Http\Requests\CategoryStoreRequest;
use Modules\Category\Http\Requests\CategoryUpdateRequest;
use Nwidart\Modules\Facades\Module;

class CategoryController extends Controller
{
    protected CrudService $crudService;

    public function __construct()
    {
        $this->crudService = new CrudService();

//        if (Module::find('Role')->isEnabled()) {
//            $this->middleware('permission:view categories')->only('index');
//            $this->middleware('permission:create category')->only('create');
//            $this->middleware('permission:store category')->only('store');
//            $this->middleware('permission:edit category')->only('edit');
//            $this->middleware('permission:update category')->only('update');
//            $this->middleware('permission:destroy category')->only('destroy');
//        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        return $this->crudService->store($request, Category::class, function ($category) use ($request) {
//            $category->filters()->sync($request->input('filters'));
        });
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        return $this->crudService->update($request, $category, function ($category) use ($request) { });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        return $this->crudService->destroy($category);
    }
}
