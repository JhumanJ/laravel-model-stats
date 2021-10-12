<?php

namespace Jhumanj\LaravelModelStats\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Jhumanj\LaravelModelStats\Http\Requests\Dashboard\StoreRequest;
use Jhumanj\LaravelModelStats\Http\Requests\Dashboard\UpdateRequest;
use Jhumanj\LaravelModelStats\Models\Dashboard;

class DashboardController extends Controller
{
    public function index(): Collection|array
    {
        return Dashboard::all();
    }

    public function show(Dashboard $dashboard): Dashboard
    {
        return $dashboard;
    }

    public function store(StoreRequest $request): Model|Builder
    {
        return Dashboard::query()->create($request->validated());
    }

    public function update(Dashboard $dashboard, UpdateRequest $request): Dashboard
    {
        $dashboard->update($request->validated());

        return $dashboard;
    }

    public function destroy(Dashboard $dashboard): JsonResponse
    {
        $dashboard->delete();

        return $this->success([
           'message' => 'Dashboard deleted',
        ]);
    }
}
