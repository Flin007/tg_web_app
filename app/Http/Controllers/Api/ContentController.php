<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentSetting;

class ContentController extends Controller
{
    public function getHomeTitle()
    {
        $setting = ContentSetting::where('key', 'home_title')
            ->where('is_active', true)
            ->first();;

        if ($setting) {
            return response()->json([
                'value' => $setting->value,
                'is_active' => $setting->is_active
            ], 200);
        }

        // Если компонент не найден или не активен, возвращаем 404 Not Found
        return response()->json([], 404);
    }
}
