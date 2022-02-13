<button type="submit"
    id="checkbox-delete-all"
    class="btn btn-danger"
    formaction="{{ $url }}"
    name="_method"
    value="DELETE"
    onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')"
    disabled>
    <i class="fa fa-trash-alt"></i> <span>{{ __('Delete Selected') }}</span>
</button>
