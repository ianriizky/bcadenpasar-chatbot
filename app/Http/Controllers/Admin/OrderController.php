<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Http\Resources\DataTables\OrderResource;
use App\Models\Branch;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                    ->select('orders.*', 'customers.id as customer_id', 'customers.fullname as customer_fullname')
                    ->orderBy('customers.fullname', $direction);
            })
            ->filterColumn('customer_fullname', function ($query, $keyword) {
                $query->whereHas('customer', function ($query) use ($keyword) {
                    $query->where('fullname', 'like', '%' . $keyword . '%');
                });
            })
            ->orderColumn('status', function ($query, $direction) {
                $query->join('order_statuses', 'order_statuses.order_id', '=', 'orders.id')
                    ->select('orders.*', 'order_statuses.id as order_status_id', 'order_statuses.status as order_status')
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
     * Return datatable row child data.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function datatableRowChild(Order $order)
    {
        return view('admin.order.datatable-row-child', compact('order'));
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
     * @param  \App\Http\Requests\Order\StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $alert = [
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was created!', ['resource' => trans('admin-lang.user')]),
            ],
        ];

        try {
            DB::transaction(function () use ($request) {
                $order = $request->getOrder();

                $order->setCustomerRelationValue($request->getCustomer());
                transform($request->getUser(), fn (User $user) => $order->setUserRelationValue($user));
                transform($request->getBranch(), fn (Branch $branch) => $order->setBranchRelationValue($branch));

                $order->save();

                $order->statuses()->save($request->getOrderStatus());
                $order->items()->saveMany($request->getItems());
            });
        } catch (\Throwable $th) {
            throw $th;

            $alert = [
                'alert' => [
                    'type' => 'alert-danger',
                    'message' => $th->getMessage(),
                ],
            ];
        }

        return redirect()->route('admin.order.index')->with($alert);
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
     * @param  \App\Http\Requests\Order\UpdateRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Order $order)
    {
        $order->update($request->validated());

        return redirect()->route('admin.order.index')->with([
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.order.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.order')]),
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
        Order::destroy($request->input('checkbox', []));

        return redirect()->route('admin.order.index')->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('admin-lang.order')]),
            ],
        ]);
    }
}
