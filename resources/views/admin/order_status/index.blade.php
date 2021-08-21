<div class="table-responsive">
    <table class="table table-striped datatable">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th>{{ __('Name') }}</th>
                <th>Status</th>
                <th>{{ __('Note') }}</th>
                <th>{{ __('Created At') }}</th>
                <th style="width: 10%;">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($statuses as $status)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @can('view', $status->issuerable)
                            <a href="{{ $status->issuerable->getIssuerUrl() }} ">
                                {{ $status->issuerable->getIssuerFullname() }} ({{ $status->issuerable->getIssuerRole() }})
                            </a>
                        @else
                            {{ $status->issuerable->getIssuerFullname() }} ({{ $status->issuerable->getIssuerRole() }})
                        @endcan
                    </td>
                    <td>
                        {{ $status->status->label }}
                        @if (\App\Enum\OrderStatus::scheduled()->equals($status->status) || \App\Enum\OrderStatus::rescheduled()->equals($status->status))
                            <br>
                            {{ $status->order->schedule_date->translatedFormat('d F Y H:i:s') }}
                        @endif
                    </td>
                    <td>{{ $status->note ?? '-' }}</td>
                    <td>{{ $status->created_at->translatedFormat('d F Y H:i:s') }}</td>
                    <td>
                        @includeWhen(
                            (Auth::check() && Auth::user()->can('delete', $status)) &&
                            (!\App\Enum\OrderStatus::draft()->equals($status->status) && !\App\Enum\OrderStatus::on_progress()->equals($status->status)),
                            'components.datatables.link-destroy', [
                            'url' => route('admin.order.status.destroy', [
                                'order' => $status->order,
                                'status' => $status,
                            ]),
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
