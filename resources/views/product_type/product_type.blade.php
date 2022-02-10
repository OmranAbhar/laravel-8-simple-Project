<!DOCTYPE html>
<html lang="">
<head>
    {{--Css Library--}}
    @include('common.head')
</head>
<body style="background-color:lightblue">

    {{--Menue--}}
    @include('common.menue')

    <div style="margin: 0 auto; padding: 100px;padding-top: 50px!important;">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Add New Product Type</div>
                    <div class="card-body">
                        <form action="{{route('product.type.add')}}" method="post" id="add-type-form">
                            @csrf
                            <div class="col-md-6 form-group">
                                <label for="">Type Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Type Name">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                            <div class="form-group" style="text-align: right;">
                                <button type="submit" class="btn btn-block btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top: 10px;" class="col-md-12">
            <div class="card">
                <div class="card-header">Product Type</div>
                <div class="card-body">
                    <table class="table table-hover table-condensed" id="type-table">
                        <thead>
                        <th style="width: 20% ">#</th>
                        <th style="width: 80% ">Name</th>
                        <th>Actions</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{--update Product Type--}}
    @include('product_type.edit-product-type-model')

    {{--JS Library--}}
    @include('common.footer')

    {{--AJax--}}
    <script type="text/javascript">
        toastr.options.preventDuplicates = true;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
            //Add Product Type
            $('#add-type-form').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function () {
                        $(form).find('span.error-text').text('');
                    },
                    success: function (data) {
                        if (data.code == 0) {
                            $.each(data.error, function (prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            $(form)[0].reset();
                            $("#type-table").DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        }
                    }
                });
            });
            //Get Product Type List
            $('#type-table').DataTable({
                processing: true,
                info: true,
                ajax: "{{route('get.products.type.list')}}",
                "pageLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50]],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'actions', name: 'actions'},
                ]
            });
            //Get Product Type Detail
            $(document).on('click', '#editTypeBtn', function () {
                var type_id = $(this).data('id');
                $('.editType').find('form')[0].reset();
                $('.editType').find('span.error-text').text('');
                $.post('<?= route("get.products.type.details") ?>', {type_id: type_id}, function (data) {
                    $('.editType').find('input[name="cid"]').val(data.details.id);
                    $('.editType').find('input[name="name"]').val(data.details.name);
                    $('.editType').modal('show');
                })
            });
            //Update Product Type
            $('#update-type-form').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function () {
                        $(form).find('span.error-text').text('');
                    },
                    success: function (data) {
                        if (data.code == 0) {
                            $.each(data.error, function (prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            $("#type-table").DataTable().ajax.reload(null, false);
                            $('.editType').modal('hide');
                            toastr.success(data.msg);
                        }
                    }
                });
            });
            //Delete Product Type
            $(document).on('click', '#deleteTypeBtn', function () {
                var type_id = $(this).data('id');
                var url = '<?= route("delete.product.type") ?>';
                swal.fire({
                    title: 'Are you sure ?',
                    html: 'You want to <b> delete </b> this Type',
                    showCancelButton: true,
                    showCloseButton: true,
                    cancelButtonText: 'cancel',
                    confirmButtonText: 'Yes',
                    width: 300,
                    allowOutsideClick: false
                }).then(function (result) {
                    if (result.value) {
                        $.post(url, {type_id: type_id}, function (data) {
                            if (data.code == 1) {
                                $("#type-table").DataTable().ajax.reload(null, false);
                                toastr.success(data.msg);
                            } else {
                                toastr.error(data.msg);
                            }
                        }, 'json')
                    }
                });
            });

        });
    </script>
</body>
</html>
