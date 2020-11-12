<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\User\Store;
use App\Http\Requests\User\Update;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $users= User::with('role')->paginate(30);
        return response()->ok($users, '');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = User::with('role')->find($id);
        if (!$user) {
            return response()->errorNotFound();
        }

        return response()->ok($user, '');
    }

    /**
     * @param Store $request
     * @retun JsonResponse
     */
    public function store(Store $request)
    {
        $request['password'] = Hash::make($request->password);
        $user = User::create($request->all());

        if ($request->hasFile('photo_path')) {
            $user->uploadPhoto($request->file('photo_path'));
            $user->save();
        }

        return response()->ok($user, 'User has been registered');
    }

    /**
     * @param Update $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Update $request, $id)
    {
        $user = User::with('role')->find($id);

        if (!$user) {
            return response()->errorNotFound();
        }

        if ($request->has('password')) {
            $request['password'] = Hash::make($request->password);
        }

        $updated = $user->fill($request->all())->save();

        if ($updated) {
            if ($request->hasFile('photo_path')) {
                if ($user->photo_path)
                    unlink(public_path().'/'.$user->photo_path);

                $user->Uploadphoto($request->file('photo_path'));
                $user->save();
            }

            return response()->ok($user, 'user has been updated');
        }

        return response()->errorInternal();
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->errorNotFound();
        }

        $user->delete();
        if ($user->photo_path)
            unlink(public_path().'/'.$user->photo_path);

        return response()->ok([], 'user has been deleted');
    }
}
