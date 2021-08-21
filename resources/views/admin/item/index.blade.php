<div class="table-responsive">
    <table class="table table-striped datatable">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th>{{ __('admin-lang.denomination') }}</th>
                <th>{{ __('Bundle Quantity') }}</th>
                <th>{{ __('Quantity') }}</th>
                <th>{{ __('Total') }}</th>
                <th style="width: 20%;">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $elements = [];

                    if (Auth::check() && Auth::user()->can('update', $item)) {
                        $elements[] = view('components.datatables.link-edit', [
                            'url' => route('admin.order.item.edit', [
                                'order' => $item->order,
                                'item' => $item->denomination_value,
                            ]),
                        ]);
                    }

                    if (Auth::check() && Auth::user()->can('delete', $item)) {
                        $elements[] = view('components.datatables.link-destroy', [
                            'url' => route('admin.order.item.destroy', [
                                'order' => $item->order,
                                'item' => $item->denomination_value,
                            ]),
                        ]);
                    }
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @can('view', $item->denomination)
                            <a href="{{ route('admin.denomination.show', $item->denomination) }}">
                                {{ $item->denomination->value_rupiah }} ({{ $item->denomination_name }})
                            </a>
                        @else
                            {{ $item->denomination->value_rupiah }} ({{ $item->denomination_name }})
                        @endcan
                    </td>
                    <td>{{ $item->bundle_quantity }} {{ __('bundle') }}</td>
                    <td>{{ $item->quantity }} {{ Str::lower($item->denomination->type->label) }}</td>
                    <td>{{ format_rupiah($item->total) }}</td>
                    <td>
                        @include('components.datatables.button-group', compact('elements'))
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
