<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataTables\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * Return datatable server side response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        return DataTables::eloquent(User::query())
            ->setTransformer(fn ($model) => UserResource::make($model)->resolve())
            ->orderColumn('branch_name', function ($query, $direction) {
                $query->with(['branch' => function ($query) use ($direction) {
                    $query->orderBy('name', $direction);
                }]);
            })
            ->filterColumn('branch_name', function ($query, $keyword) {
                $query->with(['branch' => function ($query) use ($keyword) {
                    $query->where('name', 'like', $keyword);
                }]);
            })
            ->filterColumn('is_active', function ($query, $keyword) {
                $active = Str::lower(trans('Active'));
                $notActive = Str::lower(trans('Not Active'));

                if (in_array($keyword, [$active, $notActive])) {
                    $query->where('is_active', $keyword === $active);
                }
            })
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
