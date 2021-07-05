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
        return view('model-stats::dashboard', [
            'config' => $this->modelStatsConfig(),
            'models' => $this->getModels(),
        ]);
    }

    private function modelStatsConfig()
    {
        return [
            'appName' => config('app.name'),
            'path' => config('model-stats.routes_prefix'),
            'frontEndVersion' => 4,
        ];
    }

    private function getModels(): Collection
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf(
                    '\%s%s',
                    Container::getInstance()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );

                return $class;
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        ! $reflection->isAbstract();
                }

                return $valid;
            });


        return $models->map(function (string $class) {
            return [
                'class' => $class,
                'fields' => $this->getClassFields($class),
            ];
        })->sortByDesc('class')->values();
    }

    private function getClassFields(string $class)
    {
        return \Schema::getColumnListing((new $class)->getTable());
    }
}
