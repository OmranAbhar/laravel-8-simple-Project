<div class="card-header" style="color: white; font-weight: bold;padding-left: 100px">
    <div class="row">
        <div class="col-md-11">
            <a href="{{ url('/product') }}" class="btn btn-xs btn-info pull-right">Product</a>
            <a href="{{ url('/productType') }}" class="btn btn-xs btn-info pull-right">Product Type</a>
            @if(session()->get('accessTypeId')==\App\Http\Common\UserAccess::$Admin)
                <a href="{{ url('/userRegister') }}" class="btn btn-xs btn-info pull-right">Register User</a>
            @endif
        </div>
        <div class="col-md-1 right">
            <a href="{{ url('/SignOut') }}" class="btn btn-xs btn-light pull-right">Sign Out</a>
        </div>
    </div>
</div>
