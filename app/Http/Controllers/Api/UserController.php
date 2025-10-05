<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
class UserController extends Controller
{
     public function getTopUsers(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);

        $users = User::with('level:id,name')
            ->orderBy('score', 'desc')
            ->paginate(10, [ 'name', 'phone', 'email', 'school', 'score', 'level_id'], 'page', $page);


        $formatted = $users->getCollection()->transform(function ($user) {
            return [
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'school' => $user->school,
                'score' => $user->score,
                'level' => $user->level ? $user->level->name : null
            ];
        });

        $users->setCollection($formatted);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

}
