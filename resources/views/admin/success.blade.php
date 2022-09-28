

@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show p05 alert-autoclose" role="alert">
        {{Session::get('success')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif


<div class="alert alert-success d-none successMessage" role="alert">
  Record has been updated successfully
</div>
