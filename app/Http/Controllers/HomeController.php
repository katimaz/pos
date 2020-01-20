<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function locale($locale){
        if($locale == 'cn' || $locale == 'en'){
            app()->setLocale($locale);
            session()->put('locale', $locale);
        }
        return redirect()->back();
    }
}
