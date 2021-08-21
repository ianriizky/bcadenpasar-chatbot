<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Item\StoreRequest;
use App\Http\Requests\Item\UpdateRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ItemController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Order $order)
    {
        $item = new Item;

        $item->setOrderRelationValue($order);

        return view('admin.item.create', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Item\StoreRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function store(StoreRequest $request, Order $order)
    {
        DB::transaction(function () use ($request, $order) {
            /** @var \App\Models\Item $item */
            $item = Item::make($request->validated());

            $item
                ->setDenominationRelationValue($request->getDenominationFromRequest())
                ->setOrderRelationValue($order)
                ->save();

            if ($order->isMaximumTotalOrderExceeded()) {
                Session::flash('alert', [
                    'type' => 'alert-danger',
                    'message' => 'Maaf, total pesanan anda sudah mencapai batas maksimum',
                ]);

                abort(Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });

        return redirect()->route('admin.order.show', $order)->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was created!', ['resource' => trans('admin-lang.order')]),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Order $order, Item $item)
    {
        $item->load('denomination');

        return view('admin.item.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Item\UpdateRequest  $request
     * @param  \App\Models\Order  $order
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function update(UpdateRequest $request, Order $order, Item $item)
    {
        DB::transaction(function () use ($request, $order, $item) {
            $item
                ->fill($request->validated())
                ->setDenominationRelationValue($request->getDenominationFromRequest())
                ->save();

            if ($order->isMaximumTotalOrderExceeded()) {
                Session::flash('alert', [
                    'type' => 'alert-danger',
                    'message' => 'Maaf, total pesanan anda sudah mencapai batas maksimum',
                ]);

                abort(Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });

        return redirect()->route('admin.order.show', $order)->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was updated!', ['resource' => trans('admin-lang.order')]),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order, Item $item)
    {
        $item->delete();

        return redirect()->route('admin.order.show', $order)->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans(':resource Details', ['resource' => __('admin-lang.order')])]),
            ],
        ]);
    }
}
