<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dashboard;
use App\Http\Resources\Dashboard as DashboardResource;
use App\Http\Resources\DashboardCollection;



class DashboardController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'field_id' => 'required',
            'operation' => 'required',
        ]);

        $dashboard = Dashboard::create($data);
        return new DashboardResource($dashboard);
    }

    public function index()
    {
        return new DashboardCollection(Dashboard::orderBy('id')->get());
    }

    public function destroy(Dashboard $dashboard)
    {
        $result = $dashboard->delete();
        response()->json([$result], 200);
    }
}
