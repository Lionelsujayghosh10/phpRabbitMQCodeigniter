<?php 
$this->load->view('includes/sidebar.php'); ?>







<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Student Mark</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('Dashboard'); ?>">Home</a></li>
              <li class="breadcrumb-item active">Result</li>
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
                <h3 class="card-title">Edit Student Mark</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" action="<?php echo base_url('Marks/edit'); ?>">
                <div class="card-body">
                <?php if(!empty($this->session->flashdata('error'))) { ?>
                    <div class="alert alert-danger">
                        <i class="fa fa-check-circle fa-fw fa-lg"></i>
                        <strong><?php echo $this->session->flashdata('error'); ?></strong>
                    </div>
                <?php } ?>
                <?php if(!empty($this->session->flashdata('success'))) { ?>
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle fa-fw fa-lg"></i>
                        <strong><?php echo $this->session->flashdata('success'); ?></strong>
                    </div>
                <?php } ?>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Exam Name </label>
                        <input type="text"   class="form-control"  required="required" autocomplete="off" value="<?php echo $exam_name; ?>" disabled>
                    </div>
                    <input type="hidden" name="raw" value="<?php  echo base64_encode($studentMarksId); ?>">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Class Name </label>
                        <input type="text"   class="form-control"  required="required" autocomplete="off" value="<?php echo $class_name; ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Section Name </label>
                        <input type="text"  class="form-control"  required="required" autocomplete="off" value="<?php echo $section_name; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Subject  Name </label>
                        <input type="text"   class="form-control"  required="required" autocomplete="off" value="<?php echo $subject_name; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Student Name </label>
                        <input type="text"   class="form-control"  required="required" autocomplete="off" value="<?php echo $student_name; ?>" disabled>
                    </div>


                    <div class="form-group">
                        <label for="exampleInputEmail1">Total Mark </label>
                        <input type="text"   class="form-control" name='total_marks'  required="required" autocomplete="off" value="<?php echo $total_marks; ?>" >
                    </div>
                    

                    <div class="form-group">
                        <label for="exampleInputEmail1">Obtained Mark </label>
                        <input type="text"   class="form-control" name="otained_marks" required="required" autocomplete="off" value="<?php echo $otained_marks; ?>" >
                    </div>


                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" name="update" value="Update" >
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




