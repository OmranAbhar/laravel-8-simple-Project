<div class="modal fade editType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
     data-keyboard="false" data-bakdrop="static">
    <div class="modal-dialog model-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times</span>
                </button>
            </div>
            <form action="<?= route('update.users.register.details') ?>" method="post" id="update-user-register-form">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="cid">
                    <div class="form-group">
                        <label for="">User Name</label>
                        <input type="text" class="form-control" name="user_name" placeholder="Enter User Name">
                        <span class="text-dnager error-text user_name_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="Enter Email Address">
                        <span class="text-dnager error-text email_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="">AccessType</label>
                        <select class="form-control" name="access_type_id">
                            <option value=""></option>
                            @foreach($accessType as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text name_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Change Password</label>
                        <input type="checkbox"   name="change_password" id="change_password" onclick="changeState()"/>
                        <span class="text-dnager error-text password_error"></span>
                    </div>
                    <div class="form-group border border-3">
                        <label for="">Current Password</label>
                        <input type="password" class="form-control change_state" disabled name="current_password" placeholder="Enter Current Password">
                        <span class="text-dnager error-text password_error"></span>

                        <label for="">Password</label>
                        <input type="password" class="form-control change_state" disabled name="password" placeholder="Enter password">
                        <span class="text-dnager error-text password_error"></span>

                        <label for="">Confirm Password</label>
                        <input type="password" class="form-control change_state" disabled name="confirm_password"
                               placeholder="Enter Confirm Password">
                        <span class="text-dnager error-text confirm_password_error"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="form-group" style="text-align: right;">
                        <button type="submit" class="btn btn-block btn-success">Save Change</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

