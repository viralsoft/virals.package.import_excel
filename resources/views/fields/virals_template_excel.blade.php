<?php
$item_name = strtolower(isset($field['entity_singular']) && !empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);

$items = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? [];
if (is_array($items)) {
    if (count($items)) {
        foreach ($items as $index => $item) {
            $items[$index] = (object) $item;
        }
        $items = collect($items);
    } else {
        $items = collect([]);
    }
}
$modelClass = get_class($crud->model);
$column_model = \DB::connection()->getSchemaBuilder()->getColumnListing($crud->model->getTable());
unset($column_model[array_search( with(new $modelClass)->getKeyName(), $column_model)]);
$relations = with(new $modelClass)->relationships();
$column_relation = array_keys($relations);
$options = [];
foreach ($column_model as $column) {
    $options[$column] = $column;
}
?>
<div @include('crud::inc.field_wrapper_attributes') >
    @include('crud::inc.field_translatable_icon')
    <input class="array-json" type="hidden" id="{{ $field['name'] }}" name="{{ $field['name'] }}">
    <div class="array-container form-group">
        <div class="array-controls btn-group m-t-10 m-b-10">
            <button class="btn btn-sm btn-default" type="button" id="show_setting_{{ $field['name'] }}" data-toggle="collapse" data-target="#setting_collum"><i class="fa fa-wrench">  Setting</i></button>
        </div>
        <div id="setting_collum" class="collapse">
            <table class="table table-striped">
                <thead>
                <tr class="info">
                    <th></th>
                    <th scope="col">Column model</th>
                    <th scope="col">Label Column In Excel File</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $column_model as $column )
                    @php
                        $idItem = strtotime ("now") . uniqid() . rand(0, 100);
                    @endphp
                    <tr id="tr_{{ $idItem }}" class="column_{{ $field['name'] }}">
                        <td style="width: 10%" id="checkbox_{{ $idItem }}">
                            <input  class="checkbox_{{ $field['name'] }}" type="checkbox">
                        </td>
                        <td>{{ $column }}</td>
                        <td>
                            <input type="text" placeholder="label..." value="{{ $column }}" name="{{ $column }}" is_relation = "0" class="input_set_label_column_{{ $field['name'] }}">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <table class="table table-striped">
                <thead>
                <tr class="info">
                    <th></th>
                    <th scope="col">Ralation</th>
                    <th scope="col">Label Relation In Excel File</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $column_relation as $column )
                    @php
                        $idItem = strtotime ("now") . uniqid() . rand(0, 100);
                    @endphp
                    <tr id="tr_{{ $idItem }}" class="column_{{ $field['name'] }}">
                        <td style="width: 10%" id="checkbox_{{ $idItem }}">
                            <input class="checkbox_{{ $field['name'] }}" type="checkbox">
                        </td>
                        <td>{{ $column }}</td>
                        <td>
                            <input type="text" placeholder="label..." value="{{ $column }}" name="{{ $column }}" is_relation = "1" class="input_set_label_column_{{ $field['name'] }}">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="array-controls m-t-10" style="width: 100%; text-align: center">
                <button class="btn btn-sm btn-primary" type="button" id="btn_save_setting_{{ $field['name'] }}"><i class="fa fa-save"></i>  Save</button>
            </div>
            <div id="response_{{ $field['name'] }}">

            </div>
        </div>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
@if ($crud->checkIfFieldIsFirstOfItsType($field))
    @push('crud_fields_styles')
        <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <style>
            td {
                width: 45%;
            }
        </style>
    @endpush

    @push('crud_fields_scripts')
        {{-- YOUR JS HERE --}}
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script>
            $(function(){
                let options = @json($options);
                let relations = @json($relations);
                console.log(relations);
                let deleteVN = function (str) {
                    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
                    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
                    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
                    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
                    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
                    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
                    str = str.replace(/đ/g, "d");
                    str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
                    str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
                    str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
                    str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
                    str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
                    str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
                    str = str.replace(/Đ/g, "D");
                    return str;
                }

                //==============setting==================//
                let count = 0;
                $(document).on('click', "#btn_save_setting_{{ $field['name'] }}", function (e) {
                    e.preventDefault();
                    let data = {
                        model_type: "{{ str_replace("\\", "\\\\", get_class($crud->model)) }}",
                        request_type: "{{ str_replace("\\", "\\\\", $field['request_class']) }}"
                    };
                    let checkedColumn = $(".column_{{ $field['name'] }}").each(function () {
                        if ($(this).find("input[type='checkbox']").is(":checked")) {
                            let column = $(this).find("input.input_set_label_column_{{ $field['name'] }}");
                            let name = column.attr('name');
                            let value = column.val();
                            let key = deleteVN(value).replace(' ', '_').toLowerCase();
                            data[key] = {};
                            data[key]['name'] = name;
                            data[key]['label'] = value;
                            data[key]['type'] = '';
                            data[key]['is_relation'] = column.attr('is_relation') == "1" ? 1 : 0;
                            data[key]['relationship_type'] = column.attr('is_relation') == "1" && relations[name]['type'] == 'BelongsToMany'  ? value : '';
                        }
                    });
                    $.ajax({
                        url: "{{ route('virals.excel.getRelatonColumn') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: data
                    }).done(function(response) {
                        $("#response_{{ $field['name'] }}").empty().append(response.message);
                    });
                });
            })
        </script>
    @endpush
@endif

