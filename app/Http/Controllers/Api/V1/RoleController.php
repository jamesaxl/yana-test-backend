<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $roles = Role::all();
        return response()->ok($roles, '');
    }
}
