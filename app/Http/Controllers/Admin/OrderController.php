<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataTables\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.order.index');
    }

    /**
     * Return datatable server side response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        return DataTables::eloquent(Order::query()->with('user:id,fullname'))
            ->setTransformer(fn ($model) => OrderResource::make($model)->resolve())
            ->orderColumn('customer_fullname', function ($query, $direction) {
                $query->join('customers', 'orders.customer_id', '=', 'customers.id')
                    ->orderBy('customers.fullname', $direction);
            })
            ->filterColumn('customer_fullname', function ($query, $keyword) {
                $query->whereHas('customer', function ($query) use ($keyword) {
                    $query->where('fullname', 'like', '%' . $keyword . '%');
                });
            })
            ->orderColumn('status', function ($query, $direction) {
                $query->join('order_statuses', 'order_statuses.order_id', '=', 'orders.id')
                    ->orderBy('order_statuses.status', $direction);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereHas('latestStatus', function ($query) use ($keyword) {
                    $query->where('status', 'like', '%' . $keyword . '%');
                });
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
        return view('admin.order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Order::create($request->all());

        return redirect()->route('admin.order.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Order $order)
    {
        return view('admin.order.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Order $order)
    {
        $order->update($request->all());

        return redirect()->route('admin.order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.order.index');
    }
}
