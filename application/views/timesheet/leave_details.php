<?php
/* Leave Detail view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $user = $this->Xin_model->read_user_info($session['user_id']);?>
<div class="row m-b-1">
  <div class="col-md-4">
    <div class="box box-block bg-white">
      <h2><strong>Leave Detail</strong></h2>
      <table class="table table-striped m-md-b-0">
        <tbody>
          <tr>
            <th scope="row">Employee</th>
            <td class="text-right"><?php echo $first_name.' '.$last_name;?></td>
          </tr>
          <tr>
            <th scope="row">Leave Type</th>
            <td class="text-right"><?php echo $type;?></td>
          </tr>
          <tr>
            <th scope="row">Applied On</th>
            <td class="text-right"><?php echo $this->Xin_model->set_date_format($created_at);?></td>
          </tr>
          <tr>
            <th scope="row">From Date</th>
            <td class="text-right"><?php echo $this->Xin_model->set_date_format($from_date);?></td>
          </tr>
          <tr>
            <th scope="row">To Date</th>
            <td class="text-right"><?php echo $this->Xin_model->set_date_format($to_date);?></td>
          </tr>
        </tbody>
      </table>
      <br>
      <div class="the-notes info"><?php echo $reason;?></div>
    </div>
  </div>
  <?php if($user[0]->user_role_id == 1) {?>
  <div class="col-md-4">
    <div class="box box-block bg-white">
      <h2><strong>Update Status</strong></h2>
      <form action="<?php echo site_url("timesheet/update_leave_status").'/'.$leave_id;?>/" method="post" name="update_status" id="update_status">
        <input type="hidden" name="_token_status" value="<?php echo $leave_id;?>">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
                <option value="1" <?php if($status=='1'):?> selected <?php endif; ?>>Pending</option>
                <option value="2" <?php if($status=='2'):?> selected <?php endif; ?>>Approved</option>
                <option value="3" <?php if($status=='3'):?> selected <?php endif; ?>>Rejected</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="remarks">Remarks</label>
              <textarea class="form-control textarea" placeholder="Remarks" name="remarks" id="remarks" cols="30" rows="5"><?php echo $remarks;?></textarea>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary save">Save</button>
      </form>
    </div>
  </div>
  <?php } ?>
  <div class="col-md-4">
    <div class="box box-block bg-white">
      <?php 
        if($emp_casual_leave>0){$all_leave_types[0]->days_per_year=$emp_casual_leave;}
        if($emp_medical_leave>0){$all_leave_types[1]->days_per_year=$emp_medical_leave;}
      ?>
      <h2><strong>Leave Statistics </strong> of <?php echo $first_name.' '.$last_name;?></h2>
      <?php foreach($all_leave_types as $type) {?>
      <?php $count_l = $this->Timesheet_model->count_total_leaves($type->leave_type_id,$employee_id);?>
      <?php 
      $total_consumed = 0;
      foreach($count_l as $count_lval)
      {
      $total_consumed += $count_lval->dtlog;
      }
       ?>
      <?php
					$count_data = $total_consumed / $type->days_per_year * 100;
					// progress
					if($count_data <= 20) {
						$progress_class = 'progress-success';
					} else if($count_data > 20 && $count_data <= 50){
						$progress_class = 'progress-info';
					} else if($count_data > 50 && $count_data <= 75){
						$progress_class = 'progress-warning';
					} else {
						$progress_class = 'progress-danger';
					}
				?>
      <div id="leave-statistics">
        <p><strong><?php echo $type->type_name;?> (<?php echo $total_consumed;?>/<?php echo $type->days_per_year;?>)</strong></p>
        <progress class="progress <?php echo $progress_class;?>" value="<?php echo $count_data;?>" max="100"></progress>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
