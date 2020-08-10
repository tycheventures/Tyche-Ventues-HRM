<?php
/* Leave view
*/
?>
<script type="text/javascript" src="/hrm/skin/js/moment.min.js"></script>
<script type="text/javascript" src="/hrm/skin/js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php $session = $this->session->userdata('username');?>
<div class="row m-b-1">
  <div class="col-md-12">
    <div class="box box-block bg-white">
      
      <?php 
        if($casual_leave>0){$all_leave_types[0]->days_per_year=$casual_leave;}
        if($medical_leave>0){$all_leave_types[1]->days_per_year=$medical_leave;}
      ?>
      <?php foreach($all_leave_types as $type) {?>
      <?php $count_l = $this->Timesheet_model->count_total_leaves($type->leave_type_id,$session['user_id']);?>
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
      <span>
        <p><strong><?php echo $type->type_name;?> (<?php echo $total_consumed;?>/<?php echo $type->days_per_year;?>)</strong></p>
        <progress class="progress <?php echo $progress_class;?>" value="<?php echo $count_data;?>" max="100"></progress>
        <?php } ?>
      </span>


    </div>
  </div>
</div>
<!--


    <div class="box box-block bg-white">
      <?php 
        if($casual_leave>0){$all_leave_types[0]->days_per_year=$casual_leave;}
        if($medical_leave>0){$all_leave_types[1]->days_per_year=$medical_leave;}
      ?>
      <?php foreach($all_leave_types as $type) {?>
      <?php $count_l = $this->Timesheet_model->count_total_leaves($type->leave_type_id,$session['user_id']);?>
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
//-->

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add Leave</strong>
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("timesheet/add_leave") ?>" method="post" name="add_leave" id="xin-form">
          <input type="hidden" name="user_id" id="user_id" value="<?php echo $session['user_id'];?>">
          <input type="hidden" name="employee_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="leave_type" class="control-label">Leave Type</label>
                    <select class="form-control" name="leave_type" data-plugin="select_hrm" data-placeholder="Leave Type">
                      <option value=""></option>
                      <?php foreach($all_leave_types as $type) {?>
                      <option value="<?php echo $type->leave_type_id;?>"> <?php echo $type->type_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input class="form-control date" placeholder="Start Date" readonly name="start_date" type="text" value="">
                        <input type="text" name="daterange" value="01/01/2018 - 01/15/2018" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input class="form-control date" placeholder="End Date" readonly name="end_date" type="text" value="">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group" style="display: none;">
                    <label for="description">Remarks</label>
                    <textarea class="form-control textarea" placeholder="Remarks" name="remarks" cols="30" rows="15" id="remarks"></textarea>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="summary">Leave Reason</label>
                <textarea class="form-control" placeholder="Leave Reason" name="reason" cols="30" rows="3" id="reason"></textarea>
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="box box-block bg-white">
  <h2><strong>List All</strong> Leave
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add Leave</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee</th>
          <th>Leave Type</th>
          <th>Request Duration</th>
          <th>Applied On</th>
          <th>Reason</th>
          <th>Status</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<script>
$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>