<div class="modal fade editType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
     data-keyboard="false" data-bakdrop="static">
    <div class="modal-dialog model-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times</span>
                </button>
            </div>
            <form action="<?= route('update.products.details') ?>" method="post" id="update-type-form">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="cid">
                    <div class="">
                        <div class=" form-group">
                            <label for="">Product Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Type Name">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <div class=" form-group">
                            <label for="">Product Type</label>
                            <select class="form-control" name="type_id">
                                <option value=""></option>
                                @foreach($productType as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <div class=" form-group">
                            <label for="">Price</label>
                            <input type="number" class="form-control" name="price" placeholder="price">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <div class=" form-group">
                            <label for="">Description</label>
                            <textarea class="form-control" name="description"></textarea>
                            <span class="text-danger error-text name_error"></span>
                        </div>
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
