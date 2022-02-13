<div class="row">
    {{-- created_at --}}
    <div class="form-group col-12 col-lg-6">
        <label for="created_at">{{ __('Created By') }}</label>

        <p class="form-control-plaintext">
            @if ($model->is($model->issuerable))
                {{ __('Self Created') }}
            @else
                <a href="{{ $model->issuerable->getIssuerUrl() }}">{{ $model->issuerable->getIssuerFullname() }} ({{ $model->issuerable->getIssuerRole() }})</a>
            @endif
        </p>
    </div>
    {{-- /.created_at --}}
</div>
