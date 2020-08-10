<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='add_attendance'){
	// get addd by > template
		$user = $this->Xin_model->read_user_info($_GET['employee_id']);
		$ful_name = $user[0]->first_name. ' '.$user[0]->last_name;
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Add Attendance for <?php echo $ful_name; ?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/add_attendance") ?>" method="post" name="add_attendance" id="add_attendance">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="employee_id_m" id="employee_id_m" value="<?php echo $_GET['employee_id'];?>" />
  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group"> </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="date">Attendance Date</label>
              <input class="form-control attendance_date_m" placeholder="Attendance Date" readonly="true" id="attendance_date_m" name="attendance_date_m" type="text">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="clock_in">Office In Time</label>
              <input class="form-control timepicker_m" placeholder="Office In Time" readonly="true" id="clock_in_m" name="clock_in_m" type="text">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="clock_out">Office Out Time</label>
              <input class="form-control timepicker_m" placeholder="Office Out Time" readonly="true" id="clock_out_m" name="clock_out_m" type="text">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save</button>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
							
		// Clock
		var input = $('.timepicker_m').clockpicker({
			placement: 'bottom',
			align: 'left',
			autoclose: true,
			'default': 'now'
		});
		
		// attendance date
		$('.attendance_date_m').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:'yy-mm-dd',
			altField: "#date_format",
			altFormat: "d M, yy",
			yearRange: '1970:' + new Date().getFullYear(),
			beforeShow: function(input) {
				$(input).datepicker("widget").show();
			}
		});	 
				  
		/* Add Attendance*/
		$("#add_attendance").submit(function(e){
			var attendance_date_m = $("#attendance_date_m").val();
			var emp_id = $("#employee_id_m").val();
			var clock_in_m = $("#clock_in_m").val();
			var clock_out_m = $("#clock_out_m").val();
			if(attendance_date_m!='' && emp_id!='' && clock_in_m!='' && clock_out_m!='') {
				var xin_table = $('#xin_table').dataTable({
				"bDestroy": true,
				"ajax": {
					url : "<?php echo site_url("timesheet/update_attendance_list") ?>?employee_id="+emp_id+"&attendance_date="+attendance_date_m,
					type : 'GET'
				},
				"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();          
				}
			});
			}
		/*Form Submit*/
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=4&add_type=attendance&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						$('.add-modal-data').modal('toggle');
							xin_table.api().ajax.reload(function(){ 
								toastr.success(JSON.result);
							}, true);
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
  </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['attendance_id']) && $_GET['type']=='attendance' && $_GET['data']=='attendance'){?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Attendance for <?php echo $full_name;?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_attendance").'/'.$time_attendance_id;?>/" method="post" name="edit_attendance" id="edit_attendance">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $_GET['attendance_id'];?>">
  <input type="hidden" name="emp_att" id="emp_att" value="<?php echo $employee_id;?>" />
  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="date">Attendance Date</label>
          <input class="form-control attendance_date_e" placeholder="Attendance Date" readonly="true" id="attendance_date_e" name="attendance_date_e" type="text" value="<?php echo $attendance_date;?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="clock_in">Office In Time</label>
              <input class="form-control timepicker" placeholder="Office In Time" readonly="true" name="clock_in" type="text" value="<?php echo $clock_in;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="clock_out">Office Out Time</label>
              <input class="form-control timepicker" placeholder="Office Out Time" readonly="true" name="clock_out" type="text" value="<?php echo $clock_out;?>">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary save">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	
	// Clock
	var input = $('.timepicker').clockpicker({
		placement: 'bottom',
		align: 'left',
		autoclose: true,
		'default': 'now'
	});
	
	// attendance date
	$('.attendance_date_e').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:'yy-mm-dd',
		altField: "#date_format",
		altFormat: "d M, yy",
		yearRange: '1970:' + new Date().getFullYear(),
		beforeShow: function(input) {
			$(input).datepicker("widget").show();
		}
	});	 
  
	/* Edit Attendance*/
		$("#edit_attendance").submit(function(e){
		var attendance_date_e = $("#attendance_date_e").val();
		var emp_att = $("#emp_att").val();
		var xin_table2 = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : "<?php echo site_url("timesheet/update_attendance_list") ?>?employee_id="+emp_att+"&attendance_date="+attendance_date_e,
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
		});
		/*Form Submit*/
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=3&edit_type=attendance&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						$('.edit-modal-data').modal('toggle');
						xin_table2.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('.save').prop('disabled', false);
					}
				}
			});
		});
});	
</script>
<?php }
?>
