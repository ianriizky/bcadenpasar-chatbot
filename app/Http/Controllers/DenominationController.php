<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataTables\DenominationResource;
use App\Models\Denomination;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DenominationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.denomination.index');
    }

    /**
     * Return datatable server side response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        return DataTables::eloquent(Denomination::query())
            ->setTransformer(fn ($model) => DenominationResource::make($model)->resolve())
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('admin.denomination.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Denomination::create($request->all());

        return redirect()->route('denomination.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Denomination  $denomination
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Denomination $denomination)
    {
        return view('admin.denomination.edit', compact('denomination'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Denomination  $denomination
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Denomination $denomination)
    {
        $denomination->update($request->all());

        return redirect()->route('denomination.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Denomination  $denomination
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Denomination $denomination)
    {
        $denomination->delete();

        return redirect()->route('denomination.index');
    }
}
