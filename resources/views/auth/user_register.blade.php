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
                    <div class="card-header">Add New User</div>
                    <div class="card-body">
                        <form action="{{route('user.register.add')}}" method="post" id="add-user-register-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="">UserName</label>
                                    <input type="text" class="form-control" name="user_name" placeholder="Enter User Name">
                                    <span class="text-danger error-text user_name_error"></span>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter Email">
                                    <span class="text-danger error-text email_error"></span>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">AccessType</label>
                                    <select class="form-control" name="access_type_id">
                                        <option value=""></option>
                                        @foreach($accessType as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Enter Password">
                                    <span class="text-danger error-text password_error"></span>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" placeholder="Enter Confirm Password">
                                    <span class="text-danger error-text confirm_password_error"></span>
                                </div>
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
                <div class="card-header">User List</div>
                <div class="card-body">
                    <table class="table table-hover table-condensed" id="type-table">
                        <thead>
                        <th style=" ">#</th>
                        <th style="width: 30% ">User Name</th>
                        <th style="width: 30% ">Email</th>
                        <th style="width: 30% ">Type</th>
                        <th>Actions</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{--update User Register--}}
    @include('auth.edit-user-register-model')

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
            //Register User
            $('#add-user-register-form').on('submit', function (e) {
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
            //Get User List
            $('#type-table').DataTable({
                processing: true,
                info: true,
                ajax: "{{route('get.users.register.list')}}",
                "pageLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50]],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'user name'},
                    {data: 'email', name: 'email'},
                    {data: 'access_name', name: 'type'},
                     {data: 'actions', name: 'actions'},
                ]
            });
            //Get User Detail
            $(document).on('click', '#editRegisterBtn', function () {
                var id = $(this).data('id');
                $('.editType').find('form')[0].reset();
                $('.editType').find('span.error-text').text('');
                $.post('<?= route("get.users.register.details") ?>', {id: id}, function (data) {
                    $('.editType').find('input[name="cid"]').val(data.details.id);
                    $('.editType').find('input[name="user_name"]').val(data.details.name);
                    $('.editType').find('input[name="email"]').val(data.details.email);
                    $('.editType').find('select[name="access_type_id"]').val(data.details.access_id);
                    $('.editType').modal('show');
                })
            });
            //Update User
            $('#update-user-register-form').on('submit', function (e) {
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
                        console.log(data);
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
            //Delete User
            $(document).on('click', '#deleteRegisterBtn', function () {
                var id = $(this).data('id');
                var url = '<?= route("delete.user.register") ?>';
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

    {{--PasswordChange javaScript--}}
    <script type="text/javascript">
        function changeState()
        {
            if (document.getElementById('change_password').checked)
            {
                $(".change_state").prop("disabled", false);
            } else {
                $(".change_state").prop("disabled", true);
            }
        }
    </script>
</body>
</html>
