<a href="{{ route('admin.order.store') }}" class="btn btn-success" onclick="event.preventDefault(); if (confirm('{{ __('Are you sure you want to run this action?') }}')) this.querySelector('form').submit();">
    <i class="fa fa-shopping-cart"></i> <span>{{ __('Create :resource', ['resource' => __('admin-lang.order')]) }}</span>

    <form action="{{ route('admin.order.store') }}" method="post">
        @csrf

        <input type="hidden" name="customer_id" value="{{ $customer->getKey() }}">
    </form>
</a>
