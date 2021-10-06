<?php

namespace Jhumanj\LaravelModelStats\Http\Controllers;

use Carbon\Carbon;
use Jhumanj\LaravelModelStats\Http\Requests\Widgets\DataRequest;
use Jhumanj\LaravelModelStats\Services\ModelStats;

class StatController extends Controller
{
    public function widgetData(DataRequest $request)
    {
        $modelStats = new ModelStats($request->model);
        $dateFrom = Carbon::createFromFormat('Y-m-d', $request->date_from);
        $dateTo = Carbon::createFromFormat('Y-m-d', $request->date_to);

        switch ($request->aggregate_type) {
            case 'daily_count':
                return $modelStats->getDailyHistogram($dateFrom, $dateTo, $request->date_column);
            case 'cumulated_daily_count':
                return $modelStats->getDailyHistogram($dateFrom, $dateTo, $request->date_column, null, true);
            case 'period_total':
                return $modelStats->getPeriodTotal($dateFrom, $dateTo, $request->date_column);
            case 'group_by_count':
                return $modelStats->getGroupByCount(
                    $dateFrom,
                    $dateTo,
                    $request->date_column,
                    $request->aggregate_column
                );
            default:
                throw new \Exception('Wigdet aggregate type not supported.');
        }
    }
}
