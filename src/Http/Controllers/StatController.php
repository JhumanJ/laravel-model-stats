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

        return match ($request->get('aggregate_type')) {
            'daily_count' => $modelStats->getDailyHistogram($dateFrom, $dateTo, $request->date_column),
            'cumulated_daily_count' => $modelStats->getDailyHistogram($dateFrom, $dateTo, $request->date_column, null, true),
            'period_total' => $modelStats->getPeriodTotal($dateFrom, $dateTo, $request->date_column),
            'group_by_count' => $modelStats->getGroupByCount($dateFrom, $dateTo, $request->date_column, $request->aggregate_column),
            default => throw new \Exception('Widget aggregate type not supported.'),
        };
    }
}
