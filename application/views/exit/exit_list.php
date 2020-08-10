<?php
/* Employee Exit view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add New</strong> Employee Exit
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("employee_exit/add_exit") ?>" method="post" name="add_exit" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee">Employee to Exit</label>
                    <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                      <option value=""></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="exit_date">Exit Date</label>
                        <input class="form-control date" placeholder="Exit Date" readonly name="exit_date" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="type">Type of Exit</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Type of Exit" name="type">
                          <option value=""></option>
                          <?php foreach($all_exit_types as $exit_type) {?>
                          <option value="<?php echo $exit_type->exit_type_id?>"><?php echo $exit_type->type;?></option>
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
                          <option value="1">Yes</option>
                          <option value="0">No</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="is_inactivate_account">Inactivate Employee Account</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Inactivate Employee Account" name="is_inactivate_account">
                          <option value="1">Yes</option>
                          <option value="0">No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control textarea" placeholder="Reason" name="reason" cols="30" rows="10" id="reason"></textarea>
                  </div>
                </div>
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
  <h2><strong>List All</strong> Employee Exit
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee</th>
          <th>Exit Type</th>
          <th>Exit Date</th>
          <th>Exit Interview</th>
          <th>Inactivate Account</th>
          <th>Added By</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
