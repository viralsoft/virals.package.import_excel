<a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="{{ route($crud['route_name'] . '.show', $entry->id) }}"
   class="btn btn-xs btn-danger" data-button-type="delete"><i class="fa fa-trash"></i>Delete</a>
<script>
    if (typeof deleteEntry != 'function') {
        $("[data-button-type=delete]").unbind('click');

        function deleteEntry(button) {
            // ask for confirmation before deleting an item
            // e.preventDefault();
            var button = $(button);
            var route = button.attr('data-route');
            var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

            if (confirm("Are you sure you want to delete this item?") == true) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: route,
                    type: 'DELETE',
                    success: function(result) {
                        // Show an alert with the result
                        alert("The item has been deleted successfully");

                        // Hide the modal, if any
                        $('.modal').modal('hide');

                        // Remove the details row, if it is open
                        if (row.hasClass("shown")) {
                            row.next().remove();
                        }

                        // Remove the row from the datatable
                        row.remove();
                    },
                    error: function(result) {
                        alert("There's been an error. Your item might not have been deleted");
                    }
                });
            } else {
                alert("There's been an error. Your item might not have been deleted");
            }
        }
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>