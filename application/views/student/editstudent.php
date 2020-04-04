<?php //echo "<pre>"; print_r($studentDetails); die;?>
<?php  $this->load->view('includes/sidebar.php'); ?>



<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Student</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
             <!--  <li class="breadcrumb-item active"></li> -->
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card ccard-info">
              <div class="card-header">
                <h3 class="card-title">Edit Student</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" action="<?php echo base_url('Student/EditStudent/'.$studentDetails['studentId']);?>">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Student Name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="student_name" required="required" autocomplete="off" value="<?php echo $studentDetails['student_name'];?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Roll Number</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="student_rollNumber" required="required" autocomplete="off" value="<?php echo $studentDetails['student_rollNumber'];?>">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-success" name="update" value="Update" >
                </div>
              </form>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>






















<?php $this->load->view('includes/footer.php'); ?>
