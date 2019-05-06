<a type="button" class="btn btn-primary" href="{{ route($crud['route_name']. '.show', $entry->id) }}">
    {{ $label_bt ?? "Show Data" }}
</a>