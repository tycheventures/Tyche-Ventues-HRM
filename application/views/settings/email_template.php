<?php
/* Email Templates view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Email Template</h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Action</th>
          <th>Status</th>
          <th>Template Name</th>
          <th>Subject</th>
        </tr>
      </thead>
    </table>
  </div>
</div>