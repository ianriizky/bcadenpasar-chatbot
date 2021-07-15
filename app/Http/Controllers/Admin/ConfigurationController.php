<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataTables\ConfigurationResource;
use App\Models\Configuration;
use App\Http\Requests\Configuration\StoreRequest;
use App\Http\Requests\Configuration\UpdateRequest;
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
     * @param  \App\Http\Requests\Configuration\StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        Configuration::create($request->validated());

        return redirect()->route('admin.configuration.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was created!', ['resource' => trans('admin-lang.configuration')]),
            ],
        ]);
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
     * @param  \App\Http\Requests\Configuration\UpdateRequest  $request
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Configuration $configuration)
    {
        $configuration->update($request->validated());

        return redirect()->route('admin.configuration.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was updated!', ['resource' => trans('admin-lang.configuration')]),
            ],
        ]);
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

        return redirect()->route('admin.configuration.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.configuration')]),
            ],
        ]);
    }

    /**
     * Remove the specified list of resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyMultiple(Request $request)
    {
        Configuration::destroy($request->input('checkbox', []));

        return redirect()->route('admin.configuration.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.configuration')]),
            ],
        ]);
    }
}
