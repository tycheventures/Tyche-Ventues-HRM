<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['task_id']) && $_GET['data']=='task'){
	$assigned_ids = explode(',',$assigned_to);
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Task</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_task").'/'.$task_id; ?>" method="post" name="edit_task" id="edit_task">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $task_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="task_name">Title</label>
          <input class="form-control" placeholder="Title" name="task_name" type="text" value="<?php echo $task_name;?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Start Date</label>
              <input class="form-control edate" placeholder="Start Date" readonly="true" name="start_date" type="text" value="<?php echo $start_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">End Date</label>
              <input class="form-control edate" placeholder="End Date" readonly="true" name="end_date" type="text" value="<?php echo $end_date;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="task_hour" class="control-label">Estimated Hour</label>
              <input class="form-control" placeholder="Estimated Hour" name="task_hour" type="text" value="<?php echo $task_hour;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="employees" class="control-label">Assigned To</label>
              <select multiple class="form-control" name="assigned_to[]" data-plugin="select_hrm" data-placeholder="Employee">
                <option value=""></option>
                <?php foreach($all_employees as $employee) {?>
                <option value="<?php echo $employee->user_id?>" <?php if(in_array($employee->user_id,$assigned_ids)):?> selected 
				<?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="15" id="description2"><?php echo $description;?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Update</button>
  </div>
</form>
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/select2/dist/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/select2/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/ion.rangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script> 
<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("timesheet/task_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		
		$('#description2').summernote({
		  height: 140,
		  minHeight: null,
		  maxHeight: null,
		  focus: false
		});
		$('.note-children-container').hide();
		// Date
		$('.edate').datepicker({
		  changeMonth: true,
		  changeYear: true,
		  dateFormat:'yy-mm-dd',
		  yearRange: new Date().getFullYear() + ':' + (new Date().getFullYear() + 10)
		});

		/* Edit data */
		$("#edit_task").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			var description = $("#description2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=task&form="+action+"&description="+description,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						xin_table.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
  </script> 
<?php }
?>
