@section('pre-style')
    <link rel="stylesheet" href="{{ mix('node_modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('script')
    <script src="{{ mix('node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/custom-file-input.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            @include('components.select2-change', ['olds' => Arr::except(old() ?: $order, '_token')])
        });
    </script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ $title }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.order.index') }}">
                        <i class="fas fa-shopping-cart"></i> <span>{{ __('admin-lang.order') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.order.show', $order) }}">
                        <i class="fas fa-edit"></i> <span>{{ __('Edit :name', ['name' => __('admin-lang.order')]) }} {{ $order->code }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ $url }}">
                        <i class="fas {{ $icon }}"></i> <span>{{ $title }}</span>
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.order.status.store', compact('order', 'enumOrderStatus')) }}" method="post">
            @csrf

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @if (\App\Enum\OrderStatus::scheduled()->equals($enumOrderStatus) || \App\Enum\OrderStatus::rescheduled()->equals($enumOrderStatus))
                                {{-- branch_id --}}
                                <div class="form-group col-12 col-lg-6">
                                    <label for="branch_id">{{ __('admin-lang.branch') }}<span class="text-danger">*</span></label>

                                    <select name="branch_id"
                                        id="branch_id"
                                        class="form-control select2 @error('branch_id') is-invalid @enderror"
                                        data-placeholder="--{{ __('Choose :field', ['field' => __('admin-lang.branch') ]) }}--"
                                        data-allow-clear="true"
                                        required
                                        autofocus>
                                        @foreach (\App\Models\Branch::pluck('name', 'id') as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>

                                    <x-invalid-feedback :name="'branch_id'"/>
                                </div>
                                {{-- /.branch_id --}}

                                {{-- schedule_date --}}
                                <div class="form-group col-12 col-lg-6">
                                    <label for="schedule_date">{{ __('Schedule Date') }}<span class="text-danger">*</span></label>

                                    <input type="datetime-local"
                                        name="schedule_date"
                                        id="schedule_date"
                                        class="form-control @error('schedule_date') is-invalid @enderror"
                                        value="{{ old('schedule_date') }}"
                                        required>

                                    <x-invalid-feedback :name="'schedule_date'"/>
                                </div>
                                {{-- /.schedule_date --}}
                            @endif

                            {{-- order_status.note --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="order_status.note">{{ __('Note') }}<span class="text-danger">*</span></label>

                                <textarea name="order_status[note]"
                                    id="order_status_note"
                                    class="form-control @error('order_status.note') is-invalid @enderror"
                                    style="resize: vertical; height: auto;"
                                    required>{{ old('order_status.note') }}</textarea>

                                <x-invalid-feedback :name="'order_status.note'"/>
                            </div>
                            {{-- /.order_status.note --}}
                        </div>
                    </div>

                    <div class="card-footer">
                        @can('view', $order)
                            <a href="{{ route('admin.order.show', $order) }}" class="btn btn-secondary">
                                @include('components.datatables.button-back')
                            </a>
                        @endcan

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> <span>{{ __('Save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>
