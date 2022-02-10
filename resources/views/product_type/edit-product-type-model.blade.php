<div class="modal fade editType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
     data-keyboard="false" data-bakdrop="static">
    <div class="modal-dialog model-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= route('update.products.type.details') ?>" method="post" id="update-type-form">
                    @csrf
                    <input type="hidden" name="cid">
                    <div class="form-group">
                        <label for="">Type Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Type Name">
                        <span class="text-dnager error-text name_error"></span>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-success">Save Change</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
