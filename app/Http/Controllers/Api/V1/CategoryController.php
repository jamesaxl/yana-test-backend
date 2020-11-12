<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Category\Store;
use App\Http\Requests\Category\Update;


class CategoryController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $categories= Category::with('addBy')->paginate(30);
        return response()->ok($categories, '');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $category = Category::with('addBy')->find($id);
        if (!$category) {
            return response()->errorNotFound();
        }

        return response()->ok($category, '');
    }

    /**
     * @param Store $request
     * @retun JsonResponse
     */
    public function store(Store $request)
    {
        $request['add_by_id'] = auth()->user()->id;
        $category = Category::create($request->all());

        return response()->ok($category, 'category has been registered');
    }

    /**
     * @param Update $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Update $request, $id)
    {
        $category = Category::with('addBy')->find($id);

        if (!$category) {
            return response()->errorNotFound();
        }

        $updated = $category->fill($request->all())->save();

        if ($updated) {
            $category->add_by_id = auth()->user()->id;
            $category->save();
            return response()->ok($category,  'category has been updated');
        }

        return response()->errorInternal();
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->errorNotFound();
        }

        $category->delete();

        return response()->ok([], 'category has been deleted');
    }
}
