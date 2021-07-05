<?php


namespace Jhumanj\LaravelModelStats\Http\Requests\Widgets;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class DataRequest extends FormRequest
{
    const ALLOWED_AGGREGATES_TYPES = [
        'daily_count',
        'cumulated_daily_count',
        'period_total'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'model' => ['required',Rule::in($this->getModels())],
            'aggregate_type' => ['required', Rule::in(static::ALLOWED_AGGREGATES_TYPES)],
            'date_column' => 'required',
            'date_from' => 'required|date_format:Y-m-d|before:date_to',
            'date_to' => 'required|date_format:Y-m-d|after:date_from',
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

        return $models->values();
    }
}
