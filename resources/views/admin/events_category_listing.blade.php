@extends('layouts.admin')

@section('content')

  <div id="page-wrapper">
    <div style="position: relative;height:30px;"></div>
      <!-- /.row -->
      @if (session('status'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('status') }}
            </div>
        @endif
         <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Events Category</li>
                </ol>          
                <div class="panel panel-default">  
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> Category
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="{{ url('/admin/events_category_add') }}">Add</a>
                                    </li>                                   
                                </ul>
                            </div>
                        </div>
                    </div>
                                                    
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                      <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th data-sortable="false">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($category as $key => $rel)
                                @if ($rel['is_disabled'] !=0)
                                    <tr class="odd gradeX danger">
                                @else
                                    <tr class="odd gradeX">
                                @endif    
                                        <td>{{$rel['name']}}</td>
                                        <td width="75px;">
                                            <a href="{{ url('/admin/events_category_add') }}/{{$rel['id']}}"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></button></a>
                                            <button type="button" class="btn btn-default btn-sm" onClick="deleteCategory({{$rel['id']}})"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                    </tr>
                              @endforeach
                          </tbody>
                      </table>
                      <!-- /.table-responsive -->
                  </div>
                  <!-- /.panel-body -->
              </div>
              <!-- /.panel -->
          </div>
          <!-- /.col-lg-12 -->
      </div>
      <!-- /.row -->
  </div>
  <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
            'bSort': false,
            'aoColumns': [
                    //{ sWidth: "15%", bSearchable: true, bSortable: false },
                    { bSearchable: true, bSortable: false },
                    { bSearchable: true, bSortable: false },
            ],
            //"scrollY":        "200px",
            //"scrollCollapse": false,
            //"info":           true,
            "paging":         true
        });
    });
    function deleteCategory(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/admin/events_category/delete/"+id;
        }
    }      
    </script> 
@endsection
  

