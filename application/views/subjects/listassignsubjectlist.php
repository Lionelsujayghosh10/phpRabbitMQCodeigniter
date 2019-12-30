<?php  $this->load->view('includes/sidebar.php'); ?>




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>List Assign Subject</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Subject & Assign</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- /.row -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Assign Subject List</h3>

                <div class="card-tools">
                  <form method="get" action="<?php echo base_url('Subject/assignSubjectSearch'); ?>">
                    <div class="input-group input-group-sm" style="width: 150px;">
                      <input type="text" name="Search Data" class="form-control float-right" placeholder="Search">

                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Class Name</th>
                      <th>Section Name</th>
                      <th>Subject Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($assignSubjects) > 0) { ?>
                        <?php foreach($assignSubjects as $single_assign_subject) {  ?>
                            <tr>
                                <td><?php echo  (!empty($single_assign_subject['class_name']) ? $single_assign_subject['class_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_assign_subject['section_name']) ? $single_assign_subject['section_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_assign_subject['subject_name']) ? $single_assign_subject['subject_name'] : "N/A"); ?></td>
                                <td><a href="javascript:void(0);"><button class="btn btn-warning btn-xs">Edit</button></a>&nbsp; &nbsp; <button id="<?php echo (!empty($single_assign_subject['assignSubjectId']) ? $single_assign_subject['assignSubjectId'] : "N/A"); ?>" class="btn btn-danger btn-xs deleteButton">Delete</button></td>

                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="3">No subject assign.</td></tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <?php echo $this->pagination->create_links(); ?>
                </ul>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>


<?php $this->load->view('includes/footer.php'); ?>
