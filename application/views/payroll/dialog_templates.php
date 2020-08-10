<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['salary_template_id']) && $_GET['data']=='payroll'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Payroll Template</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("payroll/update_template").'/'.$salary_template_id; ?>" method="post" name="update_template" id="update_template" autocomplete="off">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $salary_template_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $salary_grades;?>">
  <div class="modal-body">
    <div class="bg-white">
      <div class="box-block">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="salary_grades">Name of Template</label>
                  <input class="form-control" placeholder="Name of Template" name="salary_grades" type="text" value="<?php echo $salary_grades;?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="basic_salary" class="control-label">Basic Salary</label>
                  <input class="form-control m_salary" placeholder="Basic Salary" name="basic_salary" type="text" value="<?php echo $basic_salary;?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="overtime_rate" class="control-label">Overtime Rate ( Per Hour)</label>
                  <input class="form-control" placeholder="Overtime Rate ( Per Hour)" name="overtime_rate" type="text" value="<?php echo $overtime_rate;?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="house_rent_allowance">House Rent Allowance</label>
                  <input class="form-control m_salary m_allowance" placeholder="Amount" name="house_rent_allowance" type="text" value="<?php echo $house_rent_allowance;?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="medical_allowance">Medical Allowance</label>
                  <input class="form-control m_salary m_allowance" placeholder="Amount" name="medical_allowance" type="text" value="<?php echo $medical_allowance;?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="travelling_allowance">Leave Travel Allowance</label>
                  <input class="form-control m_salary m_allowance" placeholder="Amount" name="travelling_allowance" type="text" value="<?php echo $travelling_allowance;?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="dearness_allowance">Special Allowance</label>
                  <input class="form-control m_salary m_allowance" placeholder="Amount" name="dearness_allowance" type="text" value="<?php echo $dearness_allowance;?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="travelling_allowance">Conveyance Allowance</label>
                  <input class="form-control m_salary m_allowance" placeholder="Amount" name="convey_allowance" type="text" value="<?php echo $convey_allowance;?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="provident_fund">Provident Fund</label>
                  <input class="form-control m_deduction" placeholder="Amount" name="provident_fund" type="text" value="<?php echo $provident_fund;?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="tax_deduction">Tax Deduction</label>
                  <input class="form-control m_deduction" placeholder="Amount" name="tax_deduction" type="text" value="<?php echo $tax_deduction;?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="security_deposit">Security Deposit</label>
                  <input class="form-control m_deduction" placeholder="Amount" name="security_deposit" type="text" value="<?php echo $security_deposit;?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-right">
            <h2><strong>Total Salary Details</strong></h2>
            <table class="table table-bordered custom-table">
              <tbody>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;">Gross Salary :</th>
                  <td class="hidden-print"><input type="text" name="gross_salary" readonly id="m_total" class="form-control" value="<?php echo $gross_salary;?>"></td>
                </tr>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;">Total Allowance :</th>
                  <td class="hidden-print"><input type="text" name="total_allowance" readonly id="m_total_allowance" class="form-control" value="<?php echo $total_allowance;?>"></td>
                </tr>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;">Total Deduction :</th>
                  <td class="hidden-print"><input type="text" name="total_deduction" readonly id="m_total_deduction" class="form-control" value="<?php echo $total_deduction;?>"></td>
                </tr>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;">Net Salary :</th>
                  <td class="hidden-print"><input type="text" name="net_salary" readonly id="m_net_salary" class="form-control" value="<?php echo $net_salary;?>"></td>
                </tr>
              </tbody>
            </table>
          </div>
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
				url : "<?php echo site_url("payroll/template_list") ?>",
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 

		/* Edit data */
		$("#update_template").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=payroll&form="+action,
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
	$(document).on("keyup", function () {
	var sum_total = 0;
	var deduction = 0;
	var net_salary = 0;
	var allowance = 0;
	$(".m_salary").each(function () {
		sum_total += +$(this).val();
	});
	
	$(".m_deduction").each(function () {
		deduction += +$(this).val();
	});
	
	$(".m_allowance").each(function () {
		allowance += +$(this).val();
	});
	
	$("#m_total").val(sum_total);
	$("#m_total_deduction").val(deduction);
	$("#m_total_allowance").val(allowance);
	
	var net_salary = sum_total - deduction;
	$("#m_net_salary").val(net_salary);
	});
  </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payroll_template' && $_GET['type']=='payroll_template'){ ?>
<?php
$grade_template = $this->Payroll_model->read_template_information($monthly_grade_id);
$hourly_template = $this->Payroll_model->read_hourly_wage_information($hourly_grade_id);
?>
<?php
if($profile_picture!='' && $profile_picture!='no file') {
	$u_file = 'uploads/profile/'.$profile_picture;
} else {
	if($gender=='Male') { 
		$u_file = 'uploads/profile/default_male.jpg';
	} else {
		$u_file = 'uploads/profile/default_female.jpg';
	}
} ?>
<div class="modal-header animated fadeInRight">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Employee Salary Details (Payroll Template)</h4>
</div>
<div class="modal-body animated fadeInRight">
  <div class="row row-md">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header text-uppercase"><b><?php echo $first_name.' '.$last_name;?></b></div>
        <div class="bg-white product-view">
          <div class="box-block">
            <div class="row">
              <div class="col-md-4 col-sm-5">
                <div class="pv-images mb-sm-0"> <img class="img-fluid" src="<?php echo base_url().$u_file;?>" alt=""> </div>
              </div>
              <div class="col-md-8 col-sm-7">
                <div class="pv-content">
                  <div class="table-responsive" data-pattern="priority-columns">
                    <table class="table-hover">
                      <tbody>
                        <tr>
                          <td><strong>EMP ID</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $employee_id;?></td>
                        </tr>
                        <tr>
                          <td><strong>Departments</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $department_name;?></td>
                        </tr>
                        <tr>
                          <td><strong>Designation</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $designation_name;?></td>
                        </tr>
                        <tr>
                          <td><strong>Joining Date</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $date_of_joining;?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-1">
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b>Salary Details</b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Payroll Template: </strong></label>
                <?php echo $grade_template[0]->salary_grades;?> </div>
            </div>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Basic Salary: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->basic_salary);?></div>
            </div>
            <?php if($grade_template[0]->overtime_rate!=0 || $grade_template[0]->overtime_rate!=''):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Overtime Per Hour: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->overtime_rate);?> </div>
            </div>
            <?php endif;?>
            <?php if(isset($_GET['mode']) && $_GET['mode'] == 'not_paid'):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Status: </strong></label>
                <span class="tag tag-danger">Not Paid</span></div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
    <?php if($grade_template[0]->house_rent_allowance!='' || $grade_template[0]->medical_allowance!='' || $grade_template[0]->travelling_allowance!='' || $grade_template[0]->dearness_allowance!=''): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b> Allowances</b> </div>
        <div class="card-block">
          <blockquote class="card-blockquote">
            <div class="row m-b-1">
              <?php if($grade_template[0]->house_rent_allowance!='' || $grade_template[0]->house_rent_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>House Rent Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($grade_template[0]->house_rent_allowance);?></div>
              </div>
              <?php endif;?>
              <?php if($grade_template[0]->medical_allowance!='' || $grade_template[0]->medical_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Medical Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($grade_template[0]->medical_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($grade_template[0]->travelling_allowance!='' || $grade_template[0]->travelling_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Travelling Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($grade_template[0]->travelling_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($grade_template[0]->dearness_allowance!='' || $grade_template[0]->dearness_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Dearness Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($grade_template[0]->dearness_allowance);?> </div>
              </div>
              <?php endif;?>
            </div>
          </blockquote>
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php if($grade_template[0]->provident_fund!='' || $grade_template[0]->tax_deduction!='' || $grade_template[0]->security_deposit!=''): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b> Deductions</b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <?php if($grade_template[0]->provident_fund!='' || $grade_template[0]->provident_fund!=0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Provident Fund: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->provident_fund);?> </div>
            </div>
            <?php endif;?>
            <?php if($grade_template[0]->tax_deduction!='' || $grade_template[0]->tax_deduction!=0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Tax Deduction: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->tax_deduction);?> </div>
            </div>
            <?php endif;?>
            <?php if($grade_template[0]->security_deposit!='' || $grade_template[0]->security_deposit!=0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Security Deposit: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->security_deposit);?> </div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php if(($grade_template[0]->house_rent_allowance!='' || $grade_template[0]->medical_allowance!='' || $grade_template[0]->travelling_allowance!='' || $grade_template[0]->dearness_allowance!='') && ($grade_template[0]->provident_fund!='' || $grade_template[0]->tax_deduction!='' || $grade_template[0]->security_deposit!='')){
		$col_sm = 'col-sm-12';
		$offset = 'offset-2md-3';
	} else {
		$col_sm = 'col-sm-12';
		$offset = '';
	}?>
    <div class="<?php echo $col_sm;?> col-xs-12 <?php echo $offset;?>">
      <div class="card">
        <div class="card-header text-uppercase"><b> Total Salary Details</b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <?php if($grade_template[0]->gross_salary!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Gross Salary: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->gross_salary);?> </div>
            </div>
            <?php endif;?>
            <?php if($grade_template[0]->total_allowance && $grade_template[0]->total_allowance!='0'): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Total Allowance: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->total_allowance);?> </div>
            </div>
            <?php endif;?>
            <?php if($grade_template[0]->total_deduction!='' && $grade_template[0]->total_deduction!='0'): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Total Deduction: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->total_deduction);?> </div>
            </div>
            <?php endif;?>
            <?php if($grade_template[0]->net_salary!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Net Salary: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->net_salary);?> </div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } else if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='hourlywages' && $_GET['type']=='hourlywages'){ ?>
<?php
$grade_template = $this->Payroll_model->read_template_information($monthly_grade_id);
$hourly_template = $this->Payroll_model->read_hourly_wage_information($hourly_grade_id);
?>
<?php
if($profile_picture!='' && $profile_picture!='no file') {
	$u_file = 'uploads/profile/'.$profile_picture;
} else {
	if($gender=='Male') { 
		$u_file = 'uploads/profile/default_male.jpg';
	} else {
		$u_file = 'uploads/profile/default_female.jpg';
	}
} ?>
<div class="modal-header animated fadeInRight">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Employee Salary Details (Hourly Wage)</h4>
</div>
<div class="modal-body animated fadeInRight">
  <div class="row row-md">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header text-uppercase"><b><?php echo $first_name.' '.$last_name;?></b></div>
        <div class="bg-white product-view">
          <div class="box-block">
            <div class="row">
              <div class="col-md-4 col-sm-5">
                <div class="pv-images mb-sm-0"> <img class="img-fluid" src="<?php echo base_url().$u_file;?>" alt=""> </div>
              </div>
              <div class="col-md-8 col-sm-7">
                <div class="pv-content">
                  <div class="table-responsive" data-pattern="priority-columns">
                    <table class="table-hover">
                      <tbody>
                        <tr>
                          <td><strong>EMP ID</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $employee_id;?></td>
                        </tr>
                        <tr>
                          <td><strong>Departments</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $department_name;?></td>
                        </tr>
                        <tr>
                          <td><strong>Designation</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $designation_name;?></td>
                        </tr>
                        <tr>
                          <td><strong>Joining Date</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $date_of_joining;?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group row"> 
    <!-- ********************************* Salary Details Panel ***********************-->
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b>Salary Details</b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Hourly Wage: </strong></label>
                <?php echo $hourly_template[0]->hourly_grade;?> </div>
            </div>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Hourly Rate: </strong></label>
                <?php echo $this->Xin_model->currency_sign($hourly_template[0]->hourly_rate);?> </div>
            </div>
            <?php if(isset($_GET['mode']) && $_GET['mode'] == 'not_paid'):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Status: </strong></label>
                <span class="tag tag-danger">Not Paid</span></div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php }
?>
