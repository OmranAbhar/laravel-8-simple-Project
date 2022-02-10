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
        <div class="row" >
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Add New Product</div>
                    <div class="card-body">
                        <form action="{{route('product.add')}}" method="post" id="add-type-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="">Product Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Type Name">
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Product Type</label>
                                    <select class="form-control" name="type_id">
                                        <option value=""></option>
                                        @foreach($productType as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Price</label>
                                    <input type="number" class="form-control" name="price" placeholder="price">
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control" name="description"></textarea>
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                            </div>
                            <div class="form-group" style="text-align: right;">
                                <button type="submit" class="btn btn-block btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div style="margin-top: 10px;" class="col-md-12">
                <div class="card">
                    <div class="card-header">Products</div>
                    <div class="card-body">
                        <table class="table table-hover table-condensed" id="type-table">
                            <thead>
                            <th>#</th>
                            <th style="width: 20% ">Name</th>
                            <th style="width: 20% ">Type</th>
                            <th style="width: 20% ">Price</th>
                            <th style="width: 20% ">Description</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--update Modal--}}
    @include('product.edit-product-model')
    {{--chart Modal --}}
    @include('product.chart-price-product-model')

    {{--JS Library--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    @include('common.footer')

    {{--AJax--}}
    <script type="text/javascript">
        var priceData = [];
        toastr.options.preventDuplicates = true;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
            // Add Prdouct
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
            //get Product List
            $('#type-table').DataTable({
                processing: true,
                info: true,
                ajax: "{{route('get.products.list')}}",
                "pageLength": 10,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50]],
                columns: [
                    // {data:'id',name:'id'},
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'type_id', name: 'type'},
                    {data: 'price_id', name: 'price'},
                    {data: 'description', name: 'description'},
                    {data: 'action', name: 'action'},
                ]
            });
            //Detail Product
            $(document).on('click', '#editTypeBtn', function () {
                var id = $(this).data('id');
                $('.editType').find('form')[0].reset();
                $('.editType').find('span.error-text').text('');
                $.post('<?= route("get.products.details") ?>', {id: id}, function (data) {
                    $('.editType').find('input[name="cid"]').val(data.details.id);
                    $('.editType').find('input[name="name"]').val(data.details.name);
                    $('.editType').find('input[name="price"]').val(data.details.price_id);
                    $('.editType').find('textarea[name="description"]').val(data.details.description);
                    $('.editType').find('select[name="type_id"]').val(data.details.type_id);
                    $('.editType').modal('show');
                })
            });
            //chart
            $(document).on('click', '#chartTypeBtn', function () {
                var id = $(this).data('id');
                $.post('<?= route("chart.product.price") ?>', {id: id}, function (data) {
                    var xValues = data.obj.y;
                    var yValues = data.obj.x;
                    var barColors = ["red"];
                    new Chart("myChart", {
                        type: "bar",
                        data: {
                            labels: xValues,
                            datasets: [{
                                backgroundColor: barColors,
                                data: yValues
                            }]
                        },
                        options: {
                            legend: {display: false},
                            title: {
                                display: true,
                                text: "Product Price Chart"
                            }
                        }
                    });
                    $('.priceChart').modal('show');
                })
            });
            //Update Product
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
            //Delete product
            $(document).on('click', '#deleteTypeBtn', function () {
                var id = $(this).data('id');
                var url = '<?= route("delete.product") ?>';
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
                        $.post(url, {id: id}, function (data) {
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
