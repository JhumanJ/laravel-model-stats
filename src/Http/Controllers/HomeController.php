<?php

namespace Jhumanj\LaravelModelStats\Http\Controllers;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function home()
    {
        return view('model-stats::dashboard');
    }
}
