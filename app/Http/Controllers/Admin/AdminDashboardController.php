<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Premium;
use App\Models\Reflection;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'premiumsCount' => Premium::count(),
            'reflectionsCount' => Reflection::count(),
        ]);
    }
}
