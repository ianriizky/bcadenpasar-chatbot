<?php

namespace App\Http\Controllers\Admin;

use App\Enum\OrderStatus as EnumOrderStatus;
use App\Events\OrderStatusCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatus\StoreScheduledRequest;
use App\Models\Order;
use App\Models\OrderStatus as ModelsOrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;

class OrderStatusController extends Controller
{
    /**
     * Create a new instance class.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function __construct(Request $request)
    {
        if ($enumOrderStatus = $request->route('enumOrderStatus')) {
            /** @var \App\Enum\OrderStatus $enumOrderStatus */
            $enumOrderStatus = EnumOrderStatus::from($enumOrderStatus);

            if (EnumOrderStatus::on_progress()->equals($enumOrderStatus) ||
                EnumOrderStatus::draft()->equals($enumOrderStatus)) {
                Session::flash('alert', [
                    'type' => 'alert-danger',
                    'message' => 'Status pesanan tidak valid',
                ]);

                abort(Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @param  \App\Enum\OrderStatus  $enumOrderStatus
     * @return \Illuminate\Contracts\Support\Renderable
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function create(Request $request, Order $order, EnumOrderStatus $enumOrderStatus)
    {
        switch ($enumOrderStatus) {
            case EnumOrderStatus::scheduled():
                $icon = 'fa-calendar-plus';
                $title = trans('Create :name', ['name' => __('Schedule Date')]);
                break;

            case EnumOrderStatus::rescheduled():
                $icon = 'fa-calendar-week';
                $title = trans('Create :name', ['name' => __('Reschedule Date')]);
                break;

            case EnumOrderStatus::canceled():
                $icon = 'fa-calendar-times';
                $title = trans('Cancel Order');
                break;

            case EnumOrderStatus::finished():
                $icon = 'fa-calendar-check';
                $title = trans('Finish Order');
                break;
        }

        $url = route('admin.order.status.create', compact('order', 'enumOrderStatus'));

        $optionUsers = User::when($order->branch, function (Builder $query) use ($order) {
            $query->whereHas('branch', function (Builder $query) use ($order) {
                $query->whereKey($order->branch->getKey());
            });
        })->pluck('fullname', 'id');

        return view('admin.order_status.create', compact(
            'order', 'enumOrderStatus', 'optionUsers',
            'url', 'icon', 'title'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\OrderStatus\StoreScheduledRequest  $request
     * @param  \App\Models\Order  $order
     * @param  \App\Enum\OrderStatus  $enumOrderStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreScheduledRequest $request, Order $order, EnumOrderStatus $enumOrderStatus)
    {
        if (EnumOrderStatus::scheduled()->equals($enumOrderStatus) ||
            EnumOrderStatus::rescheduled()->equals($enumOrderStatus)) {
            $order
                ->setAttribute('schedule_date', $request->getScheduleDate())
                ->setBranchRelationValue($request->getBranchFromRequest())
                ->setUserRelationValue($request->getUserFromRequest())
                ->save();
        }

        $order->statuses()->save(ModelsOrderStatus::make([
            'status' => $enumOrderStatus,
            'note' => $request->input('order_status.note'),
        ])->setIssuerableRelationValue(Auth::user()));

        Event::dispatch(new OrderStatusCreated($order));

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
     * @param  \App\Models\OrderStatus  $status
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Order $order, ModelsOrderStatus $status)
    {
        if (EnumOrderStatus::on_progress()->equals($status->status) ||
            EnumOrderStatus::draft()->equals($status->status)) {
            Session::flash('alert', [
                'type' => 'alert-danger',
                'message' => 'Status pesanan tidak bisa dihapus',
            ]);

            abort(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $status->delete();

        return redirect()->route('admin.order.show', $order)->with([
            'alert' => [
                'type' => 'alert-success',
                'message' => trans('The :resource was deleted!', ['resource' => trans('Order Status')]),
            ],
        ]);
    }
}
