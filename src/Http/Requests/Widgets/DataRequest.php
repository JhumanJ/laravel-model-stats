<?php

namespace Jhumanj\LaravelModelStats\Http\Requests\Widgets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

/**
 * @property string $model
 * @property string $aggregate_type
 * @property string $date_from
 * @property string $date_to
 * @property string $date_column
 * @property string $aggregate_column
 */
class DataRequest extends FormRequest
{
    public const ALLOWED_AGGREGATES_TYPES = [
        'daily_count',
        'cumulated_daily_count',
        'period_total',
        'group_by_count',
    ];

    public const AGGREGATES_TYPES_WITH_AGGREGATE_COLUMN = [
        'group_by_count',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'model' => ['required', Rule::in($this->getModels())],
            'aggregate_type' => ['required', Rule::in(static::ALLOWED_AGGREGATES_TYPES)],
            'date_column' => 'required',
            'date_from' => 'required|date_format:Y-m-d|before:date_to',
            'date_to' => 'required|date_format:Y-m-d|after:date_from',
            'aggregate_column' => [Rule::requiredIf(in_array($this->aggregate_type, self::AGGREGATES_TYPES_WITH_AGGREGATE_COLUMN, true))],
        ];
    }

    private function getModels(): Collection
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();

                return sprintf(
                    '\%s%s',
                    app()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );
            })->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class)
                        && !$reflection->isAbstract();
                }

                return $valid;
            });

        return $models->values();
    }
}
