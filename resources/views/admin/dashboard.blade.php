@extends('layouts.admin')

@section('content')
    <div id="page-wrapper">
        <div style="position: relative;height:30px;"></div>
        <div class="row">

            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Actions
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="{{ url('/admin/sitemap') }}">SiteMap</a>
                                    </li>  
                                     <li><a href="">DB Backup</a>
                                    </li>                                                                       
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p class="text-success">Run the <a href="{{ URL::to('/sitemap.xml') }}" target="_blank" class="title">SiteMap</a></p>
                        <p class="text-success">Run the Database backup.</p>
                    </div>                
                </div>
            </div>

        </div>
    </div>
  <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script  
@endsection
  

