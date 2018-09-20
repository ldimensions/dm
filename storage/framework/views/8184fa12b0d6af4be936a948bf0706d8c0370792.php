<?php $__env->startSection('content'); ?>

  <div id="page-wrapper">
    <div style="position: relative;height:30px;"></div>
      <!-- /.row -->
      <?php if(session('status')): ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>
      <div class="row">
          <div class="col-lg-12">
            <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="<?php echo e(url('/admin/dashboard')); ?>">Dashboard</a>
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
                                    <li><a href="<?php echo e(url('/admin/religion_add')); ?>">Add</a>
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
                                  <th data-order='desc' data-field="premium">Premium</th>
                                  <th>Type</th>
                                  <th>Denomination</th>
                                  <th>City</th>
                                  <th data-sortable="false">Link</th>
                                  <th data-sortable="false">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                            <?php $__currentLoopData = $religion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($rel['is_disabled'] !=0): ?>
                                    <tr class="odd gradeX danger">
                                <?php else: ?>
                                    <tr class="odd gradeX">
                                <?php endif; ?>    
                                        <td><?php echo e($rel['name']); ?></td>
                                        <td width="95px;"><?php echo e($rel['premium']); ?></td>
                                        <td><?php echo e($rel['religionName']); ?></td>
                                        <td class="ceter"><?php echo e($rel['denominationName']); ?></td>
                                        <td class="ceter">

                                            <?php if($rel['religionName'] == 'Christianity'): ?>
                                                <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-christian-church')); ?>/<?php echo e($rel['urlName']); ?>" target="_blank" class="title">Link</a><br/>
                                            <?php elseif($rel['religionName'] == 'Hinduism'): ?>
                                                <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-hindu-temple')); ?>/<?php echo e($rel['urlName']); ?>" target="_blank" class="title">Link</a><br/>
                                            <?php elseif($rel['religionName'] == 'Judaism'): ?>
                                                <!-- <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-malayali-temple')); ?>/<?php echo e($rel['urlName']); ?>" class="title"><?php echo e($rel['name']); ?></a><br/> -->
                                            <?php elseif($rel['religionName'] == 'Buddhism'): ?>
                                                <!-- <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-malayali-temple')); ?>/<?php echo e($rel['urlName']); ?>" class="title"><?php echo e($rel['name']); ?></a><br/> -->
                                            <?php elseif($rel['religionName'] == 'Islam'): ?>
                                                <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-islan-mosque')); ?>/<?php echo e($rel['urlName']); ?>" target="_blank" class="title">Link</a><br/>                                                           
                                            <?php endif; ?>
                                        </td>                                        
                                        <td class="ceter"><?php echo e($rel['city']); ?></td>
                                        <td width="75px;">
                                            <a href="<?php echo e(url('/admin/religion_add')); ?>/<?php echo e($rel['religionId']); ?>"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></button></a>
                                            <button type="button" class="btn btn-default btn-sm" onClick="deleteReligion(<?php echo e($rel['religionId']); ?>)"><i class="fa fa-trash-o"></i></button>
                                        </td>                                        
                                    </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        });
    });
    function deleteReligion(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/admin/religion/delete/"+id;
        }
    }      
    </script> 
<?php $__env->stopSection(); ?>
  


<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>