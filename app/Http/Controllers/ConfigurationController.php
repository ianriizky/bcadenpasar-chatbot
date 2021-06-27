<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataTables\ConfigurationResource;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.configuration.index');
    }

    /**
     * Return datatable server side response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        return DataTables::eloquent(Configuration::query())
            ->setTransformer(fn ($model) => ConfigurationResource::make($model)->resolve())
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('admin.configuration.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Configuration::create($request->all());

        return redirect()->route('configuration.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Configuration $configuration)
    {
        return view('admin.configuration.edit', compact('configuration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Configuration $configuration)
    {
        $configuration->update($request->all());

        return redirect()->route('configuration.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Configuration $configuration)
    {
        $configuration->delete();

        return redirect()->route('configuration.index');
    }
}
