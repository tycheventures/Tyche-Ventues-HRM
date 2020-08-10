<?php
/* Payslip view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php
	$gd = '';
	if($hourly_rate == '') {
		$gd = 'sl';
	} else {
		$gd = 'hr';
	}
?>
<div class="row m-b-1">
  <div class="col-md-12">
    <div class="box box-block bg-white">
      <h2><strong>Payslip</strong>
        <div class="add-record-btn"> 
        <a href="<?php echo site_url();?>payroll/pdf_create/<?php echo $gd;?>/<?php echo $make_payment_id;?>/" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"><span <i="" class="fa fa-file-pdf-o"></span></a> </div>
      </h2>
      <div class="panel">
        <div class="panel-heading p-b-none">
          <p><strong>Salary Month: </strong><?php echo date("F, Y", strtotime($payment_date));?></p>
        </div>
        <div class="panel-body p-none m-b-10">
          <table class="table table-no-border table-condensed">
            <tbody>
              <tr>
                <td><strong class="help-split">Employee ID: </strong>#<?php echo $employee_id;?></td>
                <td><strong class="help-split">Employee Name: </strong><?php echo $first_name.' '.$first_name;?></td>
                <td><strong class="help-split">Payslip NO: </strong><?php echo $make_payment_id;?></td>
              </tr>
              <tr>
                <td><strong class="help-split">Phone: </strong><?php echo $contact_no;?></td>
                <td><strong class="help-split">Joining Date: </strong><?php echo $this->Xin_model->set_date_format($date_of_joining);?></td>
                <td><strong class="help-split">Payment By: </strong><?php echo $payment_method;?></td>
              </tr>
              <tr>
                <td><strong class="help-split">Department: </strong><?php echo $department_name;?></td>
                <td><strong class="help-split">Designation: </strong><?php echo $designation_name;?></td>
                <td><strong class="help-split">&nbsp;</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row  m-b-1">
  <div class="col-md-6">
    <div class="box box-block bg-white">
      <div class="panel">
        <div class="panel-heading p-b-none">
          <h4 class="m-b-10"><strong>Payment Details</strong></h4>
        </div>
        <div class="panel-body p-none">
          <table class="table table-condensed">
            <tbody>
              <?php if($hourly_rate!=0 || $hourly_rate!=''):?>
              <tr>
                <td><strong>Hourly Rate:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($hourly_rate);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_hours_work!=0 || $total_hours_work!=''):?>
              <tr>
                <td><strong>Total Hours Worked:</strong> <span class="pull-right"><?php echo $total_hours_work;?></span></td>
              </tr>
              <?php endif;?>
              <?php if($overtime_rate!=0 || $overtime_rate!=''):?>
              <tr>
                <td><strong>Overtime Salary:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($overtime_rate);?> (Hourly)</span></td>
              </tr>
              <?php endif;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="box box-block bg-white">
      <div class="panel">
        <div class="panel-heading p-b-none">
          <h4 class="m-b-10"><strong>Earning</strong></h4>
        </div>
        <div class="panel-body p-none">
          <?php if($hourly_rate==0 && $hourly_rate==''):?>
          <table class="table table-condensed">
            <tbody>
              <?php if($overtime_rate!=0 || $overtime_rate!=''):?>
              <tr>
                <td><strong>Gross Salary:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($gross_salary);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_allowances!=0 || $total_allowances!=''):?>
              <tr>
                <td><strong>Total Allowance:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($total_allowances);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_deductions!=0 || $total_deductions!=''):?>
              <tr>
                <td><strong>Total Deduction:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($total_deductions);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($net_salary!=0 || $net_salary!=''):?>
              <tr>
                <td><strong>Net Salary:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($net_salary);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($net_salary!=0 || $net_salary!=''):?>
              <tr>
                <td><strong>Paid Amount:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($net_salary);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($payment_method):?>
              <tr>
                <td><strong>Payment Method:</strong> <span class="pull-right"><?php echo $payment_method;?></span></td>
              </tr>
              <?php endif;?>
              <?php if($net_salary!=0 || $net_salary!=''):?>
              <tr>
                <td><strong>Comment:</strong> <span class="pull-right"><?php echo $comments;?></span></td>
              </tr>
              <?php endif;?>
            </tbody>
          </table>
          <?php else:?>
          <table class="table table-condensed">
            <tbody>
              <?php if($payment_amount!=0 || $payment_amount!=''):?>
              <tr>
                <td><strong>Gross Salary:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($payment_amount);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_hours_work!=0 || $total_hours_work!=''):?>
              <tr>
                <td><strong>Net Salary:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($payment_amount);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_hours_work!=0 || $total_hours_work!=''):?>
              <tr>
                <td><strong>Paid Amount:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($payment_amount);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($payment_method):?>
              <tr>
                <td><strong>Payment Method:</strong> <span class="pull-right"><?php echo $payment_method;?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_hours_work!=0 || $total_hours_work!=''):?>
              <tr>
                <td><strong>Comment:</strong> <span class="pull-right"><?php echo $comments;?></span></td>
              </tr>
              <?php endif;?>
            </tbody>
          </table>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- pd--> 
