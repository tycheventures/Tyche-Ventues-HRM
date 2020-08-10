<?php
/* Holidays view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1">
  <div class="col-md-12">
    <div class="box box-block bg-white">
      <h2><strong>List All</strong> Holidays</h2>
      <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
          <thead>
            <tr>
              <th style="width:150px;">Action</th>
              <th>Event Name</th>
              <th>Status</th>
              <th>Start Date</th>
              <th>Start End</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- responsive --> 
    </div>
  </div>
</div>
