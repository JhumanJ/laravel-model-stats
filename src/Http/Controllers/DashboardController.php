<?php

namespace Jhumanj\LaravelModelStats\Http\Controllers;

use Jhumanj\LaravelModelStats\Http\Requests\Dashboard\StoreRequest;
use Jhumanj\LaravelModelStats\Http\Requests\Dashboard\UpdateRequest;
use Jhumanj\LaravelModelStats\Models\Dashboard;

class DashboardController extends Controller
{
    public function index()
    {
        return Dashboard::all();
    }

    public function show(Dashboard $dashboard)
    {
        return $dashboard;
    }

    public function store(StoreRequest $request)
    {
        return Dashboard::create($request->validated());
    }

    public function update(Dashboard $dashboard, UpdateRequest $request)
    {
        $dashboard->update($request->validated());

        return $dashboard;
    }

    public function destroy(Dashboard $dashboard)
    {
        $dashboard->delete();

        return $this->success([
           'message' => 'Dashboard deleted',
        ]);
    }
}
