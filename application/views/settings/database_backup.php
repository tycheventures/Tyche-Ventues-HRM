<?php
/* Database Backup Log view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1 animated fadeInRight">
  <div class="col-md-12">
    <div class="box box-block bg-white">
      <h2><strong>List All</strong> Backup Log
        <div class="delete-db-btn">
          <form action="<?php echo site_url("settings/delete_db_backup") ?>" method="post" name="del_backup" id="del_backup">
            <button type="submit" class="btn btn-primary save">Delete old backup?</button>
          </form>
        </div>
        <div class="create-db-btn">
          <form action="<?php echo site_url("settings/create_database_backup") ?>" method="post" name="db_backup" id="db_backup">
            <button type="submit" class="btn btn-primary save">Create Backup</button>
          </form>
        </div>
      </h2>
      <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
          <thead>
            <tr>
              <th>Action</th>
              <th>File</th>
              <th>Date</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- responsive --> 
    </div>
  </div>
</div>
