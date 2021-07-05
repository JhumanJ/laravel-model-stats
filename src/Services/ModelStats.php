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

    /**
     * Returns daily histogram for a model
     *
     * Has the option to be cumulating values
     */
    public function getDailyHistogram(
        Carbon $from,
        Carbon $to,
        string $dateFieldName = 'created_at',
        $queryFilter = null,
        $cumulated = false
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

        $cumulatedSum = null;
        if ($cumulated) {
            $cumulatedSum = $this->class::where($dateFieldName, '>=', $from->copy()->startOfDay())->count();
        }

        return self::fillMissingDays($data, $from, $to, 0, $cumulatedSum);
    }

    /**
     *  Get the total of items with date within range.
     *  Also returns previous period as a comparison.
     */
    public function getPeriodTotal(
        Carbon $from,
        Carbon $to,
        string $dateFieldName = 'created_at'
    ) {
        $diff = $to->diffInDays($from);

        $periodCount = $this->class::where($dateFieldName, '>=', $from->startOfDay())
            ->where($dateFieldName, '<=', $to->endOfDay())->count();
        $previousPeriodCount = $this->class::where($dateFieldName, '>=', $from->copy()->subDays($diff)->startOfDay())
            ->where($dateFieldName, '<=', $from->startOfDay())->count();

        return [
            'current_period' => $periodCount,
            'previous_period' => $previousPeriodCount
        ];
    }

    /**
     * Completes the histogram dataset with days without any values
     *
     * If cumulatedSum passed, will fill days by adding some of previous
     */
    public static function fillMissingDays($data, Carbon $since, Carbon $to, $defaultValue = 0, $cumulatedSum = null)
    {
        $daysSince = $since->diffInDays($to);

        foreach (array_reverse(range(0, $daysSince)) as $number) {
            $dateKey = Carbon::now()->subDays($number)->format('Y-m-d');
            if (!isset($data[$dateKey])) {
                $data[$dateKey] = $defaultValue;
            }

            if ($cumulatedSum != null) {
                $data[$dateKey] += $cumulatedSum;
                $cumulatedSum = $data[$dateKey];
            }
        }

        return $data;
    }
}
