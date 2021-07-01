{{-- created_at --}}
@if ($model->created_at)
    <div class="form-group">
        <label for="created_at">{{ __('Created At') }}</label>

        <input type="text"
            name="created_at"
            id="created_at"
            class="form-control-plaintext"
            value="{{ $model->created_at->translatedFormat('d F Y H:i:s') }}"
            readonly>
    </div>
@endif
{{-- /.created_at --}}

{{-- updated_at --}}
@if ($model->updated_at)
    <div class="form-group">
        <label for="updated_at">{{ __('Updated At') }}</label>

        <input type="text"
            name="updated_at"
            id="updated_at"
            class="form-control-plaintext"
            value="{{ $model->updated_at->translatedFormat('d F Y H:i:s') }}"
            readonly>
    </div>
@endif
{{-- /.updated_at --}}
