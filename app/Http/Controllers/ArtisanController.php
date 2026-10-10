<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function run($command)
    {
        if (! app()->environment('local')) {
            abort(404);
        }

        // Only allow certain safe commands
        $allowed = [
            'migrate' => ['--force' => true],
            'storage:link' => [],
            'install:api' => [],
            'config:cache' => [],
            'route:cache' => [],
            'view:clear' => [],
        ];

        if (! array_key_exists($command, $allowed)) {
            return response()->json(['error' => 'Command not allowed'], 403);
        }

        Artisan::call($command, $allowed[$command]);

        return response()->json([
            'command' => $command,
            'output' => Artisan::output(),
        ]);
    }
}
