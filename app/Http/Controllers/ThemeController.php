<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Toggle user's theme between light and dark
     */
    public function toggle()
    {
        $user = auth()->user();
        
        // Toggle: light → dark, dark → light
        $user->theme = $user->theme === 'light' ? 'dark' : 'light';
        $user->save();
        
        return back();
    }
}