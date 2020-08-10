<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['trainer_id']) && $_GET['data']=='trainer'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Trainer</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("trainers/update").'/'.$trainer_id; ?>/" method="post" name="edit_trainer" id="edit_trainer">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $trainer_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input class="form-control" placeholder="First Name" name="first_name" type="text" value="<?php echo $first_name;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="last_name" class="control-label">Last Name</label>
              <input class="form-control" placeholder="Last Name" name="last_name" type="text" value="<?php echo $last_name;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="contact_number">Contact Number</label>
              <input class="form-control" placeholder="Contact Number" name="contact_number" type="text" value="<?php echo $contact_number;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email" class="control-label">Email</label>
              <input class="form-control" placeholder="Email" name="email" type="text" value="<?php echo $email;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="designation">Designation</label>
              <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="Designation">
                <option value=""></option>
                <?php foreach($all_designations as $designation) {?>
                <option value="<?php echo $designation->designation_id?>" <?php if($designation_id==$designation->designation_id):?> selected="selected" <?php endif;?>><?php echo $designation->designation_name?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="expertise">Expertise</label>
          <textarea class="form-control textarea" placeholder="Expertise" name="expertise" cols="30" rows="5" id="expertise2"><?php echo $expertise;?></textarea>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="address">Address</label>
      <textarea class="form-control" placeholder="Address" name="address" cols="30" rows="3" id="address"><?php echo $address;?></textarea>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Update</button>
  </div>
</form>
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/select2/dist/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/select2/dist/js/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/summernote/summernote.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : "<?php echo site_url("trainers/trainer_list") ?>",
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		
		$('#expertise2').summernote({
		  height: 133,
		  minHeight: null,
		  maxHeight: null,
		  focus: false
		});
		$('.note-children-container').hide();
		/* Edit data */
		$("#edit_trainer").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			var expertise = $("#expertise2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=trainer&form="+action+"&expertise="+expertise,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['trainer_id']) && $_GET['data']=='view_trainer'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Trainer</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $first_name;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="last_name" class="control-label">Last Name</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $last_name;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="contact_number">Contact Number</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $contact_number;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email" class="control-label">Email</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $email;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="designation">Designation</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_designations as $designation) {?><?php if($designation_id==$designation->designation_id):?><?php echo $designation->designation_name;?><?php endif;?><?php } ?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="expertise">Expertise</label><br />
          <?php echo html_entity_decode($expertise);?>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="address">Address</label><br />
      <?php echo $address;?>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>
<?php }
?>
