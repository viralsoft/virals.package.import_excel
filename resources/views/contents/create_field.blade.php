@extends('viralslaravelexcel::layout')

@section('content')
    <div class="form-group">
        <label for="field_name">Name Field</label>
        <input type="text" class="form-control" id="field_name">
    </div>

    <div class="form-group">
        <label for="select_table">Select table</label>
        <select class="form-control" id="select_table">
            @foreach($models as $table => $model)
                <option value="{{ $model }}">{{ $table }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="exampleFormControlSelect1">Setting table</label>
        <table class="table table-striped">
            <thead>
            <tr class="info">
                <th></th>
                <th scope="col">Column/Ralation</th>
                <th scope="col">Custom label</th>
                <th scope="col">Unique column of relation table</th>
            </tr>
            </thead>
            <tbody id="table_content">

            </tbody>
        </table>
        <div class="array-controls m-t-10" style="width: 100%; text-align: center">
            <button class="btn btn-sm btn-primary" type="button" id="btn_save_setting_table"><i class="fa fa-save"></i>Save</button>
        </div>
        <div id="response_table">

        </div>
    </div>
@endsection

@section('custom_styles')
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
          rel="stylesheet" type="text/css"/>

    {{--style alert--}}
    <style>
        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }
    </style>
@endsection

@section('custom_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>

    <script>
        $(function() {
            //==============Set Select2 in select table==================//
            $('#select_table').select2({
                theme: "bootstrap"
            });

            //==============Handle event change select table==================//
            var model_type = '';
            var request_type = '';
            $(document).on('change', '#select_table', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('excel-fields.get-columns') }}",
                    type: 'POST',
                    data: {
                        model: $(this).val(),
                        table: $(this).find('option:selected').text()
                    },
                    success:function(data){
                        $('#table_content').empty().append(trTemplate(data['template']));
                        model_type = data['model_type'];
                        request_type = data['request_type'];
                    },
                    error: function(jqXhr, json, errorThrown){
                        let errorMessage = jqXhr.status + ': ' + jqXhr.responseText;
                        alert('Error - ' + errorMessage);
                    }
                });
            });

            //==============Function template table body==================//
            let trTemplate = function(columns) {
                let template = '';
                for(let index in columns) {
                    let idItem = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                    template += `<tr id="tr_${idItem}" class="column_table" relationship_type="${columns[index]['relationship_type']}">
                                 <td style="width: 10%" id="checkbox_${idItem}">
                                 <input class="checkbox_table" type="checkbox">
                                 </td>
                                 <td>${columns[index]['is_relation'] ? '<b><i>' + columns[index]['name'] + '</i><b>' : columns[index]['name']}</td>
                                 <td>
                                 <input type="text" placeholder="label..."
                                        value="${columns[index]['name']}" name="${columns[index]['name']}"
                                        is_relation = "${columns[index]['is_relation']}" class="input_set_label_column_table">
                                 </td>`;
                    if (columns[index]['is_relation']) {
                        template += `<td><select class="select2_field input_select_column_table" name="" style="width: 100%">`;
                        for (let i in columns[index]['columns']) {
                            template += `<option value="${columns[index]['columns'][i]}">${columns[index]['columns'][i]}</option>`;
                        }
                        template += ` </select></td></tr>`;
                    } else {
                        template += `<td></td>`;
                    }
                }
                return template;
            };

            //==============Setting field==================//
            $(document).on('click', "#btn_save_setting_table", function (e) {
                e.preventDefault();
                let data = {
                    field_name: $('#field_name').val(),
                    model_type: model_type,
                    request_type: request_type
                };

                $(".column_table").each(function () {
                    if ($(this).find("input[type='checkbox']").is(":checked")) {
                        let column = $(this).find("input.input_set_label_column_table");
                        let name = column.attr('name');
                        let value = column.val();
                        let key = deleteVN(value).trim().replace(' ', '_').toLowerCase();
                        let columOfRelationSelect = $(this).find("select.input_select_column_table");
                        let columOfRelationSelected = columOfRelationSelect.val();
                        data[key] = {};
                        data[key]['name'] = name;
                        data[key]['label'] = value;
                        data[key]['type'] = '';
                        data[key]['is_relation'] = column.attr('is_relation');
                        data[key]['relationship_type'] = ['BelongsTo', 'BelongsToMany'].includes($(this).attr('relationship_type')) ? $(this).attr('relationship_type') : '';
                        data[key]['relationship_column_select'] = columOfRelationSelected;
                    }
                });
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('excel-files.getRelatonColumn') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success:function(data){
                        $("#response_table").empty().append(data.message);
                    },
                    error: function(jqXhr, json, errorThrown){
                        let errorMessage = jqXhr.status + ': ' + jqXhr.statusText + '<br>' + jqXhr.responseText;
                        let errorBox = `<div class="alert">
                                        <span class="closebtn"
                                            onclick="this.parentElement.style.display='none';">&times;
                                        </span>
                                        ${errorMessage}
                                    </div>`;
                        $('#response_table').empty().append(errorBox);
                    }
                });
            });

            // trigger select2 for each untriggered select2 box
            $('.select2_field').each(function (i, obj) {
                if (!$(obj).hasClass("select2-hidden-accessible"))
                {
                    $(obj).select2({
                        theme: "bootstrap"
                    });
                }
            });

            //==============Function delete Vietnamese accents==================//
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
            };
        })
    </script>
@endsection
