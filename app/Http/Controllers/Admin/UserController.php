<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\DataTables\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
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
        return view('admin.user.index');
    }

    /**
     * Return datatable server side response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        return DataTables::eloquent(User::query()->with('branch:id,name'))
            ->setTransformer(fn ($model) => UserResource::make($model)->resolve())
            ->orderColumn('branch_name', function ($query, $direction) {
                $query->join('branches', 'users.branch_id', '=', 'branches.id')
                    ->select('users.*', 'branches.id as branch_id', 'branches.name as branch_name')
                    ->orderBy('branches.name', $direction);
            })
            ->filterColumn('branch_name', function ($query, $keyword) {
                $query->whereHas('branch', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
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
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\User\StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = User::make($request->validated())->setBranchRelationValue(
            $request->getBranch()
        );

        $user->save();

        $user->syncRoles($request->input('role'));

        Event::dispatch(new Registered($user));

        return redirect()->route('admin.user.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was created!', ['resource' => trans('admin-lang.user')]),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\User\UpdateRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, User $user)
    {
        /** @var \App\Models\User $user */
        $user = $user->fill($request->validated())->setBranchRelationValue(
            $request->getBranch()
        );

        $user->save();

        $user->syncRoles($request->input('role'));

        return redirect()->route('admin.user.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was updated!', ['resource' => trans('admin-lang.user')]),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.user')]),
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
        User::destroy($request->input('checkbox', []));

        return redirect()->route('admin.user.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.user')]),
            ],
        ]);
    }
}
