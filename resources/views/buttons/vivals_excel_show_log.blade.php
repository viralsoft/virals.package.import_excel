<button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#note_{{ $entry->id }}">Show Logs</button>
<div colspan="2" role="row" id="note_{{ $entry->id }}" class="even collapse" style="color: #fb8080; background: #f9f9f9">
    {!! $entry->note !!}
</div>

<script>
    noteEle_{{ $entry->id }} = document.getElementById('note_{{ $entry->id }}');
    cpEle_{{ $entry->id }} = noteEle_{{ $entry->id }}.cloneNode(true);
    tr_closest_{{ $entry->id }} = noteEle_{{ $entry->id }}.closest('tr');
    newTr_{{ $entry->id }} = document.createElement('tr');
    newTd_{{ $entry->id }} = document.createElement('td');
    newTd_{{ $entry->id }}.appendChild(cpEle_{{ $entry->id }});
    newTd_{{ $entry->id }}.setAttribute("colspan", "2");
    newTr_{{ $entry->id }}.appendChild(newTd_{{ $entry->id }});
    tr_closest_{{ $entry->id }}.after(newTr_{{ $entry->id }});
    noteEle_{{ $entry->id }}.remove();
</script>
