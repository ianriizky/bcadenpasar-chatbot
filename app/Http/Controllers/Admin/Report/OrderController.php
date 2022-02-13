<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\Order\SearchRequest;
use App\Http\Resources\DataTables\Report\OrderResource;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
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
        $this->authorize('viewAny', Order::class);

        return view('admin.report.order.index');
    }

    /**
     * Return datatable server side response.
     *
     * @param  \App\Http\Requests\Report\Order\SearchRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(SearchRequest $request)
    {
        $this->authorize('viewAny', Order::class);

        $query = Order::query()->with('user:id,fullname')
            ->has('items')
            ->whereBetween('created_at', [$request->start_date, $request->end_date]);

        return DataTables::eloquent($query)
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
     * Return excel export download response.
     *
     * @param  \App\Http\Requests\Report\Order\SearchRequest  $request
     * @return \App\Exports\OrderExport
     */
    public function export(SearchRequest $request)
    {
        $query = Item::query()
            ->with('order.user', 'order.customer', 'denomination')
            ->whereHas('order', function (Builder $query) use ($request) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            });

        return new OrderExport($query);
    }
}
