<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Resources\DataTables\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.role.index');
    }

    /**
     * Return datatable server side response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        return DataTables::eloquent(Role::query())
            ->setTransformer(fn ($model) => RoleResource::make($model)->resolve())
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Role\StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        Role::create($request->validated());

        return redirect()->route('admin.role.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was created!', ['resource' => trans('admin-lang.role')]),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Role $role)
    {
        return view('admin.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Role\UpdateRequest  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $role->update($request->validated());

        return redirect()->route('admin.role.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was updated!', ['resource' => trans('admin-lang.role')]),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.role.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.role')]),
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
        Role::destroy($request->input('checkbox', []));

        return redirect()->route('admin.role.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.role')]),
            ],
        ]);
    }
}
