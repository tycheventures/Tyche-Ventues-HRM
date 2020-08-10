<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['holiday_id']) && $_GET['data']=='holiday'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Holiday</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_holiday").'/'.$holiday_id; ?>/" method="post" name="edit_holiday" id="edit_holiday">
  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="title">Event Name</label>
          <input class="form-control" placeholder="Event Name" name="event_name" type="text" value="<?php echo $event_name;?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Start Date</label>
              <input class="form-control mdate" name="start_date" readonly="true" type="text" value="<?php echo $start_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">End Date</label>
              <input class="form-control mdate" name="end_date" readonly="true" type="text" value="<?php echo $end_date;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="designation" class="control-label">Status</label>
              <select name="is_publish" class="select2" data-plugin="select_hrm" data-placeholder="Choose Status...">
                <option value="1" <?php if($is_publish=='1') { ?> selected <?php } ?>>Published</option>
                <option value="0" <?php if($is_publish=='0') { ?> selected <?php } ?>>Un Published</option>
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
    <button type="submit" class="btn btn-primary">Save</button>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
							
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
		"ajax": {
			url : "<?php echo site_url("timesheet/holidays_list") ?>",
			type : 'GET'
		},
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
	});
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	$('#description2').summernote({
	  height: 120,
	  minHeight: null,
	  maxHeight: null,
	  focus: false
	});
	$('.note-children-container').hide();
	
	// Date
	$('.mdate').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1900:' + new Date().getFullYear()
	});
	/* Edit*/
	$("#edit_holiday").submit(function(e){
	var description = $("#description2").code();
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=2&edit_type=holiday&form="+action+"&description="+description,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
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
<?php } else if(isset($_GET['jd']) && isset($_GET['holiday_id']) && $_GET['data']=='view_holiday'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Holiday</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="title">Event Name</label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $event_name;?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Start Date</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $start_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">End Date</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $end_date;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="designation" class="control-label">Status</label>
              <?php if($is_publish=='1'): $status = 'Published';?>  <?php endif; ?>
              <?php if($is_publish=='0'): $status = 'Un Published';?>  <?php endif; ?>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $status;?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label><br />
          <?php echo html_entity_decode($description);?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>
<?php } ?>
