<button type="submit"
    id="checkbox-delete-all"
    class="btn btn-danger"
    formaction="{{ $url }}"
    name="_method"
    value="DELETE"
    disabled>
    <i class="fa fa-trash-alt"></i> <span>{{ __('Delete Selected') }}</span>
</button>
