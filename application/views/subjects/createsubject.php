<?php
$this->load->view('includes/sidebar.php'); ?>




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                <h3 class="card-title">Create Subject</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" enctype="multipart/form-data">
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
                    <!-- <div class="form-group">
                      <label for="exampleInputEmail1">Exams </label>
                      <select name="exam_id" class="form-control">
                          <option value="">Select an exam</option>
                          <?php if(!empty($exams)) { ?>
                              <?php foreach($exams as $exam) {  ?>
                                  <option value="<?php echo (!empty($exam['examId']) ? $exam['examId'] : ""); ?>"><?php echo (!empty($exam['exam_name']) ? $exam['exam_name'] : "N/A"); ?></option>
                              <?php } ?>
                          <?php }  ?>
                      </select>
                    </div> -->
                    <!-- <div class="form-group">
                        <label for="exampleInputEmail1">Class </label>
                        <select name="class_id" class="form-control class_selection" id="class_id">
                            <option value="">Select an class</option>
                            <?php if(!empty($classes)) { ?>
                                <?php foreach($classes as $class) { ?>
                                  <option value="<?php echo (!empty($class['classId']) ? $class['classId'] : ""); ?>" data-classId="<?php echo (!empty($class['classId']) ? $class['classId'] : ""); ?>"><?php echo (!empty($class['class_name']) ? $class['class_name'] : "N/A"); ?></option>
                                <?php } ?>

                            <?php } ?>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <label for="exampleInputEmail1">Subject Code </label>
                        <input type="text" name="subject_code"  class="form-control" placeholder="Enter Subject Code" required="required" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Subject Name</label>
                        <input type="text" name="subject_name"  class="form-control" placeholder="Enter Subject name" required="required" autocomplete="off">
                    </div>

                    <!-- <div class="form-group">
                        <label for="exampleInputEmail1">Total Marks </label>
                        <input type="text" name="total_marks"  class="form-control" placeholder="Enter total marks" required="required" autocomplete="off">
                    </div> -->

                        <div class="form-group" id="student_id">

                        </div>
                    <div class="col-md-12"></div>
                    <!-- <div class="form-group">
                      <small style="color:red; display:none" class="errorView">Please select class and section to get student list.</small>
                      <a href="javascript:void(0);"><button class="view_student btn btn-primary" >View Student</button></a>
                    </div> -->

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" name="Create" value="Create" >
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
