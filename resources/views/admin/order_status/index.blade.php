<div class="row">
    <div class="col-12 col-lg-3">
        <div class="btn-group-vertical">
            @if (Auth::check() && Auth::user()->can('create', \App\Models\OrderStatus::class) && $order->latestStatus->cantCreateSchedule() && $order->items->isNotEmpty())
                <a href="{{ route('admin.order.status.create', ['order' => $order, 'enumOrderStatus' => EnumOrderStatus::on_progress()]) }}" class="btn btn-{{ EnumOrderStatus::on_progress()->getColor() }} btn-sm text-left">
                    <i class="fa {{ EnumOrderStatus::on_progress()->getIcon() }}"></i> <span>{{ __('Finish Draft') }}</span>
                </a>
            @endif

            @if (Auth::check() && Auth::user()->can('create', \App\Models\OrderStatus::class) && $order->latestStatus->canCreateSchedule())
                <a href="{{ route('admin.order.status.create', ['order' => $order, 'enumOrderStatus' => EnumOrderStatus::scheduled()]) }}" class="btn btn-{{ EnumOrderStatus::scheduled()->getColor() }} btn-sm text-left">
                    <i class="fa {{ EnumOrderStatus::scheduled()->getIcon() }}"></i> <span>{{ __('Create :name', ['name' => __('Schedule Date')]) }}</span>
                </a>
            @endif

            @if (Auth::check() && Auth::user()->can('create', \App\Models\OrderStatus::class) && $order->latestStatus->hasBeenScheduled())
                <a href="{{ route('admin.order.status.create', ['order' => $order, 'enumOrderStatus' => EnumOrderStatus::rescheduled()]) }}" class="btn btn-{{ EnumOrderStatus::rescheduled()->getColor() }} btn-sm text-left">
                    <i class="fa {{ EnumOrderStatus::rescheduled()->getIcon() }}"></i> <span>{{ __('Create :name', ['name' => __('Reschedule Date')]) }}</span>
                </a>
                <a href="{{ route('admin.order.status.create', ['order' => $order, 'enumOrderStatus' => EnumOrderStatus::canceled()]) }}" class="btn btn-{{ EnumOrderStatus::canceled()->getColor() }} btn-sm text-left">
                    <i class="fa {{ EnumOrderStatus::canceled()->getIcon() }}"></i> <span>{{ __('Cancel Order') }}</span>
                </a>
                <a href="{{ route('admin.order.status.create', ['order' => $order, 'enumOrderStatus' => EnumOrderStatus::finished()]) }}" class="btn btn-{{ EnumOrderStatus::finished()->getColor() }} btn-sm text-left">
                    <i class="fa {{ EnumOrderStatus::finished()->getIcon() }}"></i> <span>{{ __('Finish Order') }}</span>
                </a>
            @endif
        </div>
    </div>

    <div class="activities col-12 col-lg-9">
        @foreach ($statuses as $status)
            <div class="activity">
                <div class="activity-icon bg-{{ $status->status->getColor() }} text-white shadow-{{ $status->status->getColor() }}">
                    <i class="fa {{ $status->status->getIcon() }}"></i>
                </div>

                <div class="activity-detail">
                    <div class="mb-2">
                        <span class="text-job text-primary">{{ $status->created_at->diffForHumans() }}</span>
                        <span class="bullet"></span>
                        <a class="text-job" href="@can('view', $status->issuerable){{ $status->issuerable->getIssuerUrl() }}@else javascript:void(0); @endcan">
                            {{ $status->issuerable->getIssuerFullname() }} ({{ $status->issuerable->getIssuerRole() }})
                        </a>

                        @if ((Auth::check() && Auth::user()->can('delete', $status)) &&
                            (!$status->status->isDraft() && !$status->status->isOn_progress()))
                            <div class="float-right">
                                <a href="{{ route('admin.order.status.destroy', [
                                    'order' => $status->order,
                                    'status' => $status,
                                ]) }}" class="text-danger" data-toggle="tooltip" title="{{ __('Delete') }}" onclick="event.preventDefault(); if (confirm('{{ __('Are you sure you want to delete this data?') }}')) this.querySelector('form').submit();">
                                    <i class="fas fa-trash-alt"></i> <span class="d-none d-lg-inline d-xl-inline">{{ __('Delete') }}</span>

                                    <form action="{{ route('admin.order.status.destroy', [
                                        'order' => $status->order,
                                        'status' => $status,
                                    ]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </a>
                            </div>
                        @endif
                    </div>

                    <p>
                        Status permintaan penukaran uang telah berubah menjadi <strong>{{ $status->status->label }} </strong> pada <strong>{{ $status->created_at->translatedFormat('d F Y H:i') }}</strong>@if ($status->note) dengan catatan sebagai berikut. @else.@endif
                        @if ($status->note)
                            <blockquote>{{ $status->note }}</blockquote>
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>
