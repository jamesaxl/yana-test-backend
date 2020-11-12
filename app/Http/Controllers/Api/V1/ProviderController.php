<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;
use App\Http\Requests\Provider\Store;
use App\Http\Requests\Provider\Update;

class ProviderController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $providers = Provider::with('addBy')->paginate(30);
        return response()->ok($providers, '');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $provider = Provider::with('addBy')->find($id);
        if (!$provider) {
            return response()->errorNotFound();
        }

        return response()->ok($provider, '');
    }

    /**
     * @param Store $request
     * @retun JsonResponse
     */
    public function store(Store $request)
    {
        $request['add_by_id'] = auth()->user()->id;
        $provider = Provider::create($request->all());

        if ($request->hasFile('logo_path')) {
            $provider->uploadLogo($request->file('logo_path'));
            $provider->save();
        }

        return response()->ok($provider, 'provider has been registered');
    }

    /**
     * @param Update $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Update $request, $id)
    {
        $provider = Provider::with('addBy')->find($id);

        if (!$provider) {
            return response()->errorNotFound();
        }

        $updated = $provider->fill($request->all())->save();

        if ($updated) {
            if ($request->hasFile('logo_path')) {
                if ($provider->logo_path)
                    unlink(public_path().'/'.$provider->logo_path);

                $provider->uploadLogo($request->file('logo_path'));
            }

            $provider->add_by_id = auth()->user()->id;
            $provider->save();

            return response()->ok($provider, 'provider has been updated');
        }

        return response()->errorInternal();
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $provider = Provider::find($id);

        if (!$provider) {
            return response()->errorNotFound();
        }

        $provider->delete();
        if ($provider->logo_path)
            unlink(public_path().'/'.$provider->logo_path);

        return response()->ok([], 'provider has been deleted');
    }
}
