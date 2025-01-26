<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Application;

class UserController extends Controller
{
    public function show($id)
    {

        $user = User::findOrFail($id);
        $applications = Application::where('user_id', $user->id)
            ->with(['status:id,name', 'section:id,name'])
            ->get();
        return Inertia::render('UserProfile', [
            'user' => $user,
            'applications' => $applications,
        ]);
    }
}
