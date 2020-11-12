<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Product\Store;
use App\Http\Requests\Product\Update;

class ProductController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $products = Product::with('addBy', 'category', 'provider')->paginate(30);

        return response()->ok($products, '');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $product = Product::with('addBy', 'category', 'provider')->find($id);
        if (!$product) {
            return response()->errorNotFound();
        }

        return response()->ok($product, '');
    }

    /**
     * @param Store $request
     * @retun JsonResponse
     */
    public function store(Store $request)
    {
        $request['add_by_id'] = auth()->user()->id;
        $product = Product::create($request->all());

        return response()->ok($product, 'product has been registered');
    }

    /**
     * @param Update $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Update $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->errorNotFound();
        }

        $updated = $product->fill($request->all())->save();

        if ($updated) {
            $product->add_by_id = auth()->user()->id;
            $product->save();
            return response()->ok($product = Product::with('addBy', 'category', 'provider')->find($id),
                'product has been updated');
        }

        return response()->errorInternal();
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->errorNotFound();
        }

        $product->delete();

        return response()->ok([], 'product has been deleted');
    }
}
