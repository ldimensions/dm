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
                    <li class="breadcrumb-item active">Religion</li>
                </ol>   
                      
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> Religion
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="{{ url('/admin/religion_add') }}">Add</a>
                                    </li>                                   
                                </ul>
                            </div>
                        </div>
                    </div>                    
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#approved" data-toggle="tab">Approved</a> </li>
                        <li><a href="#pending" data-toggle="tab">Pending ({{count($religion_pending)}})</a> </li>                        
                    </ul>   
                    <div class="tab-content"><br/>
                        <div class="tab-pane fade in active" id="approved" >                
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-approved">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th data-order='desc' data-field="premium">Premium</th>
                                        <th>Type</th>
                                        <th>Denomination</th>
                                        <th>City</th>
                                        <th>Link</th>
                                        <th>Last Updated</th>
                                        <th>Updated By</th>                                         
                                        <th data-sortable="false">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($religion as $key => $rel)
                                        @if ($rel['is_disabled'] !=0)
                                            <tr class="odd gradeX danger">
                                        @else
                                            <tr class="odd gradeX">
                                        @endif    
                                                <td>{{$rel['name']}}</td>
                                                <td width="95px;">{{ $rel['premium'] }}</td>
                                                <td>{{ $rel['religionName'] }}</td>
                                                <td width="170px;">{{ $rel['denominationName'] }}</td>
                                                <td width="145px;">{{ $rel['city'] }}</td>
                                                <td width="60px;">

                                                    @if ($rel['religionName'] == 'Christianity')
                                                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-christian-church')}}/{{ $rel['urlName'] }}" target="_blank" class="title">Link</a><br/>
                                                    @elseif($rel['religionName'] == 'Hinduism')
                                                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" target="_blank" class="title">Link</a><br/>
                                                    @elseif($rel['religionName'] == 'Judaism')
                                                        <!-- <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/> -->
                                                    @elseif($rel['religionName'] == 'Buddhism')
                                                        <!-- <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/> -->
                                                    @elseif($rel['religionName'] == 'Islam')
                                                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-islan-mosque')}}/{{ $rel['urlName'] }}" target="_blank" class="title">Link</a><br/>                                                           
                                                    @endif
                                                </td>          
                                                <td width="150px;">{{ $rel['updated_at'] }}</td>
                                                <td width="150px;">{{ $rel['updatedBy'] }}</td>                                                                              
                                                <td width="100px;">
                                                    <a href="{{ url('/admin/religion_add') }}/{{$rel['religionId']}}"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></button></a>
                                                    <button type="button" class="btn btn-default btn-sm" onClick="deleteReligion({{$rel['religionId']}})"><i class="fa fa-trash-o"></i></button>
                                                </td>                                        
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pending">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-pending">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th data-order='desc' data-field="premium">Status</th>
                                        <th>Type</th>
                                        <th>Denomination</th>
                                        <th>City</th>
                                        <th width="50px;">Link</th>
                                        <th>Last Updated</th>
                                        <th>Updated By</th>                                        
                                        <th data-sortable="false">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($religion_pending as $key => $rel)
                                        @if ($rel['status'] == 4)
                                            <tr class="odd gradeX danger">
                                        @else
                                            <tr class="odd gradeX">
                                        @endif    
                                                <td>{{$rel['name']}}</td>
                                                <td width="95px;">
                                                    @if ($rel['status'] == 2)
                                                        Submitted
                                                    @elseif($rel['status'] == 3)
                                                        Approved
                                                    @elseif($rel['status'] == 4)
                                                        Rejected  
                                                    @else                                                  
                                                        Contact Admin
                                                    @endif                                                 
                                                </td>
                                                <td width="100px;">{{ $rel['religionName'] }}</td>
                                                <td width="170px;">{{ $rel['denominationName'] }}</td>
                                                <td width="145px;">{{ $rel['city'] }}</td>
                                                <td width="60px;">
                                                    <a href="{{ URL::to('/') }}/review/religion/{{ $rel['urlName'] }}" target="_blank" class="title">Link</a>    
                                                </td>
                                                <td width="150px;">{{ $rel['updated_at'] }}</td>
                                                <td width="150px;">{{ $rel['updatedBy'] }}</td>
                                                <td width="150px;">
                                                    <button type="button" class="btn btn-default btn-sm" onClick="deleteTempReligion({{$rel['religionId']}})"><i class="fa fa-trash-o"></i></button>
                                                    <button type="button" class="btn btn-default btn-sm" onClick="approveReligion({{$rel['religionId']}})"><i class="fa fa-thumbs-up"></i></button>
                                                    <button data-toggle="modal" data-target="#myModal" data-val="{{$rel['religionId']}}"  data-whatever="@mdo" class="btn btn-default btn-sm"><i class="fa fa-thumbs-down"></i></button>                                                    
                                                </td>                                         
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                        
                    </div>
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
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titleh2" id="exampleModalLabel">Reason for reject</h5>
            </div>
            <div class="modal-body">                      
                <div class="form-group" id="formGrpErrSuggession">
                    <label id="suggession1" class="col-form-label labelfont">Reason:</label>
                    <textarea class="form-control nup" id="suggession" name="suggession" rows="10"></textarea>
                    <div id="sugessionError"></div>                        
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control nup" id="id" name="type" value="1">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="suggessionBtn">Submit</button>
            </div>            
        </div>
    </div>
</div>
<div class="loading-overlay">
    <div class="spin-loader"></div>
</div>  
  <script>
    $(document).ready(function() {
        $('#dataTables-approved').DataTable({
            responsive: true,
            order: [[ 1, 'desc' ]]       
        });
        $('#dataTables-pending').DataTable({
            responsive: true,
            order: [[ 1, 'desc' ]]
        });         
        $('#myModal').on('show.bs.modal', function (event) {
            var myVal = $(event.relatedTarget).data('val');
            document.getElementById("id").value = myVal;
        });    

        $("#suggessionBtn").click(function(e) {

            reason = document.getElementById("suggession").value,
            token = document.getElementById("token").value;
            id = document.getElementById("id").value;
            if(reason.length && id){
                $.ajax({
                    url: "/admin/religion/rejectReligion",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    data: {
                        reason: reason,
                        id: id
                    },
                    cache: !1,  
                    success: function(e) {
                        $("#exampleModal").modal("hide"), $("body").removeClass("modal-open"), $(".modal-backdrop").remove(), $(".loading-overlay").hide(), location.reload()
                    },
                    error: function(e) {
                        $("#exampleModal").modal("hide"), $("body").removeClass("modal-open"), $(".modal-backdrop").remove(), $(".loading-overlay").hide(), location.reload()
                    }
                })
            }else{
                document.getElementById("suggession").style.backgroundColor = "#FFCCCC";
            }

        });

    });
    function deleteReligion(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/admin/religion/delete/"+id;
        }
    } 
    function deleteTempReligion(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/admin/religion_tmp/delete/"+id;
        }
    }    
    function approveReligion(id){
        var r = confirm("Are you sure to approve");
        if (r == true) {
            window.location.href = "/admin/religion/approve/"+id;
        }
    }  
    // function rejectReligion(id){
    //     var reason = prompt("Please enter your reason:", "");
    //     if (reason == null || reason == "") {
            
    //     } else {
    //         window.location.href = "/admin/religion/rejectReligion/"+id;
    //     }
    // }         
    </script> 
@endsection
  

