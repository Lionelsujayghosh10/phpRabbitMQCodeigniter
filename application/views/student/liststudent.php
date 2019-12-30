<?php  $this->load->view('includes/sidebar.php'); ?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>List Student</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Student</li>
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
                <h3 class="card-title">List Student</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <form method="get" action="<?php echo base_url('Student/search'); ?>">
                      <input type="text" name="table_search" class="form-control float-right" autocomplete="off" placeholder="Search" value="<?php echo (!empty($_GET['table_search']) ? $_GET['table_search'] : ""); ?>">

                      <div class="input-group-append">
                        <input type="submit" class="btn btn-primary btn-xs" name="search" value="search">
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Student Code</th>
                      <th>Student Id</th>
                      <th>Student Name</th>
                      <th>Class Name</th>
                      <th>Section Name</th>
                      <th>Roll Number</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>   
                    <?php if(count($students) > 0) { ?>
                        <?php foreach($students as $single_student) {  ?>
                            <tr>
                                <td><?php echo  (!empty($single_student['student_code']) ? $single_student['student_code'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student['student_id']) ? $single_student['student_id'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student['student_name']) ? $single_student['student_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student['class_name']) ? $single_student['class_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student['section_name']) ? $single_student['section_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student['student_rollNumber']) ? $single_student['student_rollNumber'] : "N/A"); ?></td>
                                <td><a href="javascript:void(0);"><button class="btn btn-warning btn-xs">Edit</button></a>&nbsp; &nbsp; <button id="<?php echo (!empty($single_student['studentId']) ? $single_student['studentId'] : "N/A"); ?>" class="btn btn-danger btn-xs deleteButton">Delete</button></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="3">No Student created yet.</td></tr>
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