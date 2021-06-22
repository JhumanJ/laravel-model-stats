<?php

namespace Jhumanj\LaravelModelStats\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ModelStats
{
    public $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function getDailyHistogram(
        Carbon $from,
        Carbon $to,
        string $dateFieldName = 'created_at',
        $queryFilter = null
    ) {
        $dataQuery = $this->class::where($dateFieldName, '>=', $from->startOfDay())
            ->where($dateFieldName, '<=', $to->endOfDay())
            ->groupBy('date')
            ->orderBy('date', 'ASC');
        if ($queryFilter) {
            $dataQuery = $dataQuery->where(function ($query) use ($queryFilter) {
                return $queryFilter($query);
            });
        }

        $data = $dataQuery->select([
            DB::raw('DATE('.$dateFieldName.') as date'),
            DB::raw('COUNT(*) as "count"'),
        ])->get()->pluck("count", "date");

        return self::fillMissingDays($data, $from, $to);
    }

    /**
     * Completes the histogram dataset with days without any values
     */
    public static function fillMissingDays($data, Carbon $since, Carbon $to, $defaultValue = 0)
    {
        $daysSince = $since->diffInDays($to);

        foreach (array_reverse(range(0, $daysSince)) as $number) {
            $dateKey = Carbon::now()->subDays($number)->format('Y-m-d');
            if (! isset($data[$dateKey])) {
                $data[$dateKey] = $defaultValue;
            }
        }

        return $data;
    }
}
