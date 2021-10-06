<?php

namespace Jhumanj\LaravelModelStats\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Jhumanj\LaravelModelStats\Http\Middleware\CustomCodeEnabled;
use Jhumanj\LaravelModelStats\Services\Tinker;

class CustomCodeController extends Controller
{
    const CHART_TYPES = ['line_chart', 'bar_chart'];

    public function __construct()
    {
        $this->middleware(CustomCodeEnabled::class);
    }

    /**
     * Endpoint used to test customCode when creating widgets.
     */
    public function executeCustomCode(Request $request, Tinker $tinker)
    {
        $validated = $request->validate([
            'code' => 'required',
            'chart_type' => ['required', Rule::in(self::CHART_TYPES)],
        ]);

        $result = $tinker->injectDates(now()->subMonth(), now())
            ->readonly()
            ->execute($validated['code']);
        $codeExecuted = $tinker->lastExecSuccess();

        return $this->success([
            'output' => $result,
            'code_executed' => $codeExecuted,
            'valid_output' => $codeExecuted ? $this->isValidOutput(
                $request->chart_type,
                $tinker->getCustomCodeResult()
            ) : false,
        ]);
    }

    public function widgetData(Request $request, Tinker $tinker)
    {
        $validated = $request->validate([
            'code' => 'required',
            'chart_type' => ['required', Rule::in(self::CHART_TYPES)],
            'date_from' => 'required|date_format:Y-m-d|before:date_to',
            'date_to' => 'required|date_format:Y-m-d|after:date_from',
        ]);

        $dateFrom = Carbon::createFromFormat('Y-m-d', $request->date_from);
        $dateTo = Carbon::createFromFormat('Y-m-d', $request->date_to);

        $result = $tinker->injectDates($dateFrom, $dateTo)
            ->readonly()
            ->execute($validated['code']);

        $codeExecuted = $tinker->lastExecSuccess();
        $dataResult = $tinker->getCustomCodeResult();

        if ($codeExecuted && $this->isValidOutput($request->chart_type, $dataResult)) {
            return $tinker->getCustomCodeResult();
        } else {
            return $this->error([
                'output' => $result,
                'code_executed' => $codeExecuted,
                'valid_output' => $codeExecuted ? $this->isValidOutput(
                    $request->chart_type,
                    $tinker->getCustomCodeResult()
                ) : false,
            ]);
        }
    }

    private function isValidOutput(string $chartType, $data)
    {
        switch ($chartType) {
            case 'bar_chart':
                return $this->validateBarChartData($data);
            case 'line_chart':
                return $this->validateLineChartData($data);
        }

        return false;
    }

    private function validateBarChartData($data)
    {
        if (! is_array($data)) {
            return false;
        }
        foreach ($data as $key => $value) {
            if (! is_string($key) || ! is_numeric($value)) {
                return false;
            }
        }

        return true;
    }

    private function validateLineChartData($data)
    {
        if (! is_array($data)) {
            return false;
        }
        foreach ($data as $key => $value) {
            if (! is_string($key) || ! is_numeric($value)) {
                return false;
            }
        }

        return true;
    }
}
