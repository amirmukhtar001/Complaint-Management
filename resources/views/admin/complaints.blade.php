@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

<div class="block row">
    <div class="col-md-2"><h1 class="m-0 text-dark">{{$content_header}}</h1></div>

    <div class="block row col-md-8 text-white">
      <div class="col-md-4 bulkButton"><a class="btn btn-sm btn-block btn-primary bulkNonAssign">Bulk Unasssigned Status </a></div>
      <div class="col-md-4 bulkButton"><a class="btn btn-sm btn-block btn-primary bulkActive">Bulk Active Complaints Status</a></div>
      <div class="col-md-4 bulkButton"><a class="btn btn-sm btn-block btn-primary btnBulkResolve">Bulk Resolved Complaints Status</a></div>
    </div>

</div>


@stop

@section('content')
  @include('admin.errors')
  @include('admin.success')

    {{-- <p>Welcome to this Complaints page admin panel.</p> --}}

    <table class="table table-bordered text-center" id="dataTable">
    <thead>
        <tr style = "text-align: center">
            {{-- <th>Id</th> --}}
            <th><input name="select_all" id="cbx_all" type="checkbox" /></th>

            <th>Title</th>
            <th>Description</th>
            <th>User</th>
            <th>Status</th>
            <th>Created at</th>
        </tr>
    </thead>
  </table>


  <div class="d-none">
    <form method="POST" class="bulkUnAssignedForm" action="{{route('bulk.unassign')}}">
      @csrf
      <div class="cbx_list">
      </div>
    </form>
  </div>

  <div class="d-none">
    <form method="POST" class="bulkActiveForm" action="{{route('bulk.active')}}">
      @csrf
      <div class="cbx_list">
      </div>
    </form>
  </div>

  <div class="d-none">
    <form method="POST" class="btnBulkResolveForm" action="{{route('bulk.resolved')}}">
      @csrf
      <div class="cbx_list">
      </div>
    </form>
  </div>

@stop

@section('plugins.Datatables')

@stop

@section('js')

<script type="text/javascript">
  var APP_URL = {!! json_encode(url('/')) !!}
</script>


<script>
jQuery(function() {
  jQuery('#dataTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
          url: '{!! route('comlaintsDataTable') !!}',
          data: function (d) {
                d.status = $('.filter_status').val()
            }
        },
      columns: [
          { data: 'id', name: 'id' },
          { data: 'title', name: 'title' },
          { data: 'description', name: 'description' },
          { data: 'user', name: 'user' },
          { data: 'status', name: 'status' },
          { data: 'created_at', name: 'created_at' },
      ],

      columnDefs: [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
             return '<input type="checkbox" name="cbx[]" value="'+ $('<div/>').text(data).html() + '">';
         }
      }],

  });

  //========================================================================//
  // Change filter_status append value to url
  //========================================================================//

  jQuery(document).on('change', '.statusChange',function(){
    jQuery.ajaxSetup({headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')}});
        jQuery.ajax({
            type:'POST',
            url: APP_URL+'/admin/ajax/status/update/'+jQuery(this).data('id'),
            data: {status: jQuery(this).val()},
            success: function(data){
                // console.log(' data ', data);
                if(data.status == 1){

                  jQuery('.successMessage').removeClass('d-none');
                  setTimeout(function(){
                    jQuery('.successMessage').addClass('d-none');

                  },3000);
                }
            }
        });
  });


  $('#cbx_all').on('click', function(event){
    var checked_status = this.checked;
    console.log(' checked_status ', checked_status, this );
      // Check/uncheck all checkboxes in the table
      // var rows =  jQuery('#dataTable').DataTable().rows().nodes();

    $.each($("input[name='cbx[]'"), function(){
         $(this).prop('checked', checked_status);
    });
      // event.preventDefault();
  });


  $(document).on('click','.bulkNonAssign', function(){
    console.log(' bulkNonAssign click ');
    var cbx = $('input[name="cbx[]"]:checked').map(function(){return $(this).val(); }).toArray();
      if(cbx.length <= 0){
        alert('Please Select Checkboxes');
        return false;
      }
      var cbx_hidden =  '';
      cbx.forEach(function(id){ cbx_hidden += '<input type="hidden" name="cbx[]" value="'+id+'" />'  });
      $('.bulkUnAssignedForm .cbx_list').html(cbx_hidden);
      $('.bulkUnAssignedForm').submit();
  });


  $(document).on('click','.bulkActive', function(){
    console.log(' bulkActive click ');
    var cbx = $('input[name="cbx[]"]:checked').map(function(){return $(this).val(); }).toArray();
      if(cbx.length <= 0){
        alert('Please Select Checkboxes');
        return false;
      }
      var cbx_hidden =  '';
      cbx.forEach(function(id){ cbx_hidden += '<input type="hidden" name="cbx[]" value="'+id+'" />'  });
      $('.bulkActiveForm .cbx_list').html(cbx_hidden);
      $('.bulkActiveForm').submit();
  });

  $(document).on('click','.btnBulkResolve', function(){
    console.log(' btnBulkResolve click ');
    var cbx = $('input[name="cbx[]"]:checked').map(function(){return $(this).val(); }).toArray();
      if(cbx.length <= 0){
        alert('Please Select Checkboxes');
        return false;
      }
      var cbx_hidden =  '';
      cbx.forEach(function(id){ cbx_hidden += '<input type="hidden" name="cbx[]" value="'+id+'" />'  });
      $('.btnBulkResolveForm .cbx_list').html(cbx_hidden);
      $('.btnBulkResolveForm').submit();
  });

  

});


</script>
@stop