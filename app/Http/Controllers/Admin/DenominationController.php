<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Denomination\StoreRequest;
use App\Http\Requests\Denomination\UpdateRequest;
use App\Http\Resources\DataTables\DenominationResource;
use App\Models\Denomination;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DenominationController extends Controller
{
    /**
     * Create a new instance class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Denomination::class, 'denomination');
    }

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
        $this->authorize('viewAny', Denomination::class);

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
     * @param  \App\Http\Requests\Denomination\StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        /** @var \App\Models\Denomination $denomination */
        $denomination = Denomination::make(Arr::except($request->validated(), 'image'));

        if ($filename = $request->storeImage()) {
            $denomination->image = $filename;
        }

        $denomination->save();

        return redirect()->route('admin.denomination.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was created!', ['resource' => trans('admin-lang.denomination')]),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Denomination  $denomination
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Denomination $denomination)
    {
        return view('admin.denomination.show', compact('denomination'));
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
     * @param  \App\Http\Requests\Denomination\UpdateRequest  $request
     * @param  \App\Models\Denomination  $denomination
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Denomination $denomination)
    {
        $denomination->fill(Arr::except($request->validated(), 'image'));

        if ($filename = $request->updateImage()) {
            $denomination->image = $filename;
        }

        $denomination->save();

        return redirect()->route('admin.denomination.edit', $denomination)->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was updated!', ['resource' => trans('admin-lang.denomination')]),
            ],
        ]);
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

        return redirect()->route('admin.denomination.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.denomination')]),
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
        DB::transaction(function () use ($request) {
            foreach ($request->input('checkbox', []) as $id) {
                $denomination = Denomination::find($id, ['id', 'image']);

                $this->authorize('delete', $denomination);

                $denomination->delete();
            }
        });

        return redirect()->route('admin.denomination.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.denomination')]),
            ],
        ]);
    }

    /**
     * Remove the image of specified resource from storage.
     *
     * @param  \App\Models\Denomination  $denomination
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyImage(Denomination $denomination)
    {
        Storage::delete(Denomination::IMAGE_PATH . '/' . $denomination->getRawOriginal('image'));

        $denomination->update(['image' => null]);

        return redirect()->route('admin.denomination.edit', $denomination)->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('This image')]),
            ],
        ]);
    }
}
