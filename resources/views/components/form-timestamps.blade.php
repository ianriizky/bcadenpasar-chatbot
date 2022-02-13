<div class="row">
    {{-- created_at --}}
    <div class="form-group col-12 col-lg-6">
        <label for="created_at">{{ __('Created At') }}</label>

        <p class="form-control-plaintext">{{ $model->created_at->translatedFormat('d F Y H:i:s') }}</p>
    </div>
    {{-- /.created_at --}}

    {{-- updated_at --}}
    <div class="form-group col-12 col-lg-6">
        <label for="updated_at">{{ __('Updated At') }}</label>

        <p class="form-control-plaintext">{{ $model->updated_at->translatedFormat('d F Y H:i:s') }}</p>
    </div>
    {{-- /.updated_at --}}
</div>
