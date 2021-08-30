<div class="row">
    <div class="col-12 col-lg-3">
        <div class="btn-group-vertical">
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

                        <div class="float-right dropdown">
                            <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>

                            <div class="dropdown-menu">
                                <div class="dropdown-title">{{ __('Choose an option') }}</div>
                                <div class="dropdown-divider"></div>
                                @if ((Auth::check() && Auth::user()->can('delete', $status)) &&
                                    (!$status->status->isDraft() && !$status->status->isOn_progress()))
                                    <a href="{{ route('admin.order.status.destroy', [
                                        'order' => $status->order,
                                        'status' => $status,
                                    ]) }}" class="dropdown-item has-icon text-danger" onclick="event.preventDefault(); if (confirm('{{ __('Are you sure you want to delete this data?') }}')) this.querySelector('form').submit();">
                                        <i class="fas fa-trash-alt"></i> <span>{{ __('Delete') }}</span>

                                        <form action="{{ route('admin.order.status.destroy', [
                                            'order' => $status->order,
                                            'status' => $status,
                                        ]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </a>
                                @endif
                            </div>
                        </div>
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
