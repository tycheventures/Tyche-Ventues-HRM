<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['exit_id']) && $_GET['data']=='exit'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Employee Exit</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("employee_exit/update").'/'.$exit_id; ?>" method="post" name="edit_exit" id="edit_exit">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $exit_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Employee to Exit</label>
          <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
            <option value=""></option>
            <?php foreach($all_employees as $employee) {?>
            <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$employee_id):?> selected="selected"<?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="exit_date">Exit Date</label>
              <input class="form-control d_date" placeholder="Exit Date" readonly name="exit_date" type="text" value="<?php echo $exit_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="type">Type of Exit</label>
              <select class="select2" data-plugin="select_hrm" data-placeholder="Type of Exit" name="type">
                <option value=""></option>
                <?php foreach($all_exit_types as $exit_type) {?>
                <option value="<?php echo $exit_type->exit_type_id?>" <?php if($exit_type->exit_type_id==$exit_type_id):?> selected="selected"<?php endif;?>><?php echo $exit_type->type;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="exit_interview">Conducted Exit Interview</label>
              <select class="select2" data-plugin="select_hrm" data-placeholder="Conducted Exit Interview<" name="exit_interview">
                <option value="1" <?php if(1==$exit_interview):?> selected="selected"<?php endif;?>>Yes</option>
                <option value="0" <?php if(0==$exit_interview):?> selected="selected"<?php endif;?>>No</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="is_inactivate_account">Inactivate Employee Account</label>
              <select class="select2" data-plugin="select_hrm" data-placeholder="Inactivate Employee Account" name="is_inactivate_account">
                <option value="1" <?php if(1==$is_inactivate_account):?> selected="selected"<?php endif;?>>Yes</option>
                <option value="0" <?php if(0==$is_inactivate_account):?> selected="selected"<?php endif;?>>No</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control textarea" placeholder="Reason" name="reason" cols="30" rows="10" id="reason2"><?php echo $reason;?></textarea>
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
<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("employee_exit/exit_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		
		$('#reason2').summernote({
		  height: 120,
		  minHeight: null,
		  maxHeight: null,
		  focus: false
		});
		$('.note-children-container').hide();
		$('.d_date').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:'yy-mm-dd',
		yearRange: '1900:' + (new Date().getFullYear() + 10),
		beforeShow: function(input) {
			$(input).datepicker("widget").show();
		}
		});

		/* Edit data */
		$("#edit_exit").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			var reason = $("#reason2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=exit&form="+action+"&reason="+reason,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['exit_id']) && $_GET['data']=='view_exit'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Employee Exit</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Employee to Exit</label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($employee_id==$employee->user_id):?><?php echo $employee->first_name.' '.$employee->last_name;?><?php endif;?><?php } ?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="exit_date">Exit Date</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $exit_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="type">Type of Exit</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_exit_types as $exit_type) {?><?php if($exit_type_id==$exit_type->exit_type_id):?><?php echo $exit_type->type;?><?php endif;?><?php } ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="exit_interview">Conducted Exit Interview</label>
              <?php if($exit_interview=='1'): $interview = 'Yes';?>  <?php endif; ?>
              <?php if($exit_interview=='0'): $interview = 'No';?>  <?php endif; ?>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $interview;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="is_inactivate_account">Inactivate Employee Account</label>
              <?php if($is_inactivate_account=='1'): $account = 'Yes';?>  <?php endif; ?>
              <?php if($is_inactivate_account=='0'): $account = 'No';?>  <?php endif; ?>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $account;?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label><br />
          <?php echo html_entity_decode($reason);?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>
<?php }
?>
