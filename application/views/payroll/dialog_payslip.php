<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['emp_id']) && $_GET['data']=='pay_payment' && $_GET['type']=='pay_payment'){ ?>
<?php
$grade_template = $this->Payroll_model->read_template_information($monthly_grade_id);
$hourly_template = $this->Payroll_model->read_hourly_wage_information($hourly_grade_id);
$payment_month = strtotime($payment_date);
$p_month = date('F Y',$payment_month);
if($payment_method==1){
  $p_method = 'Online';
} else if($payment_method==2){
  $p_method = 'PayPal';
} else if($payment_method==3) {
  $p_method = 'Payoneer';
} else if($payment_method==4){
  $p_method = 'Bank Transfer';
} else if($payment_method==5) {
  $p_method = 'Cheque';
} else {
  $p_method = 'Cash';
}
?>
<?php
if($profile_picture!='' && $profile_picture!='no file') {
	$u_file = base_url().'uploads/profile/'.$profile_picture;
} else {
	if($gender=='Male') { 
		$u_file = base_url().'uploads/profile/default_male.jpg';
	} else {
		$u_file = base_url().'uploads/profile/default_female.jpg';
	}
} ?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Salary Details of <?php echo $p_month;?></h4>
</div>
<div class="modal-body">
  <div class="row row-md">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header text-uppercase"><b><?php echo $first_name.' '.$last_name;?></b></div>
        <div class="bg-white product-view">
          <div class="box-block">
            <div class="row">
              <div class="col-md-4 col-sm-5">
                <div class="pv-images mb-sm-0"> <img class="img-fluid" src="<?php echo $u_file;?>" alt=""> </div>
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
                <label for="name" class="control-label" style="text-align:right;"><strong>Salary Month: </strong></label>
                <?php echo $p_month;?> </div>
            </div>
            <?php if($gross_salary):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Gross Salary: </strong></label>
                <?php echo $this->Xin_model->currency_sign($gross_salary);?> </div>
            </div>
            <?php endif;?>
            <?php if($overtime_rate!=0 || $overtime_rate!=''):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Overtime Per Hour: </strong></label>
                <?php echo $this->Xin_model->currency_sign($overtime_rate);?> </div>
            </div>
            <?php endif;?>
            <?php if($hourly_rate):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Hourly Rate: </strong></label>
                <?php echo $this->Xin_model->currency_sign($hourly_rate);?> </div>
            </div>
            <?php endif;?>
            <?php if($total_hours_work):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Total Hours Worked: </strong></label>
                <?php echo $total_hours_work;?></div>
            </div>
            <?php endif;?>
            <?php if($is_payment==1):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Status: </strong></label>
                <span class="tag tag-success">Paid</span></div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
    <?php if($house_rent_allowance!='' || $medical_allowance!='' || $travelling_allowance!='' || $dearness_allowance!=''): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b> Allowances</b> </div>
        <div class="card-block">
          <blockquote class="card-blockquote">
            <div class="row m-b-1">
              <?php if($house_rent_allowance!='' || $house_rent_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>House Rent Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($house_rent_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($medical_allowance!='' || $medical_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Medical Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($medical_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($travelling_allowance!='' || $travelling_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Leave Travel Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($travelling_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($convey_allowance!='' || $convey_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Conveyance Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($convey_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($dearness_allowance!='' || $dearness_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Special Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($dearness_allowance);?> </div>
              </div>
              <?php endif;?>
            </div>
          </blockquote>
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php if($provident_fund!='' || $tax_deduction!='' || $security_deposit!=''): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b> Deductions</b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <?php if($provident_fund!='' || $provident_fund!=0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Provident Fund: </strong></label>
                <?php echo $this->Xin_model->currency_sign($provident_fund);?> </div>
            </div>
            <?php endif;?>
            <?php if($tax_deduction!='' || $tax_deduction!=0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Tax Deduction: </strong></label>
                <?php echo $this->Xin_model->currency_sign($tax_deduction);?> </div>
            </div>
            <?php endif;?>
            <?php if($security_deposit!='' || $security_deposit!=0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Security Deposit: </strong></label>
                <?php echo $this->Xin_model->currency_sign($security_deposit);?> </div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php if(($house_rent_allowance!='' || $medical_allowance!='' || $travelling_allowance!='' || $dearness_allowance!='') && ($provident_fund!='' || $tax_deduction!='' || $security_deposit!='')){
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
            <?php if($gross_salary): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Gross Salary: </strong></label>
                <?php echo $this->Xin_model->currency_sign($gross_salary);?> </div>
            </div>
            <?php endif;?>
            <?php if($total_allowances): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Total Allowance: </strong></label>
                <?php echo $this->Xin_model->currency_sign($total_allowances);?> </div>
            </div>
            <?php endif;?>
            <?php if($total_deductions!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Total Deduction: </strong></label>
                <?php echo $this->Xin_model->currency_sign($total_deductions);?> </div>
            </div>
            <?php endif;?>
            <?php if($net_salary!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Net Salary: </strong></label>
                <?php echo $this->Xin_model->currency_sign($net_salary);?> </div>
            </div>
            <?php endif;?>
            <?php if($net_salary!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Paid Amount: </strong></label>
                <?php echo $this->Xin_model->currency_sign($net_salary);?> </div>
            </div>
            <?php endif;?>
            <?php if($total_hours_work): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Gross Salary: </strong></label>
                <?php 
				$gsalary = $total_hours_work * $hourly_rate;
				echo $this->Xin_model->currency_sign($gsalary);?>
              </div>
            </div>
            <?php endif;?>
            <?php if($total_hours_work): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Net Salary: </strong></label>
                <?php 
				$hrs_salary = $total_hours_work * $hourly_rate;
				echo $this->Xin_model->currency_sign($hrs_salary);?>
              </div>
            </div>
            <?php endif;?>
            <?php if($total_hours_work): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Paid Amount: </strong></label>
                <?php 
				$hrs_sal = $total_hours_work * $hourly_rate;
				echo $this->Xin_model->currency_sign($hrs_sal);?>
              </div>
            </div>
            <?php endif;?>
            <?php if($total_hours_work): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Payment Method: </strong></label>
                <?php echo $p_method;?></div>
            </div>
            <?php endif;?>
            <?php if($net_salary!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Comment: </strong></label>
                <?php echo $comments;?></div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php }
