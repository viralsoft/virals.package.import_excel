@extends('viralslaravelexcel::layout')

@section('content')
    @php
        $headers = $crud['headers'] ?? [];
        $columns = $crud['columns'] ?? [];
        $routeName = isset($crud['route_name']) ? route($crud['route_name'].".process-list") : '#';
        $stack_top = $crud['stack_top'] ?? [];
    @endphp
    <table id="crudTable" class="display" style="width:100%">
        <thead>
        <tr>
            @foreach ($headers as $header)
                <td>{{ $header }}</td>
            @endforeach
        </tr>
        </thead>

        <tfoot>
        <tr>
            @foreach ($headers as $header)
                <td>{{ $header }}</td>
            @endforeach
        </tr>
        </tfoot>
    </table>
@endsection


@section('footer')

@endsection


@section('custom_scripts')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let columns = @json($columns);
            $('#crudTable').DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                ajax: "{{ $routeName }}",
                columns: columns,
            });
        } );
    </script>
@endsection

@section('custom_styles')
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@include('viralslaravelexcel::contents.btn_stack')

