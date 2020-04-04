<?php
$this->load->view('includes/sidebar.php'); ?>




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Marks Entry</h1>
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
                <h3 class="card-title">Single Student Marks</h3>
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
                   
                    <div>Exam Name: <?php echo (!empty($list[0]['exam_name']) ? $list[0]['exam_name'] : "N/A"); ?></div>
                    <div>Class Name: <?php echo (!empty($list[0]['class_name']) ? $list[0]['class_name'] : "N/A"); ?></div>
                    <div>Section Name: <?php echo (!empty($list[0]['section_name']) ? $list[0]['section_name'] : "N/A"); ?></div>
                    <div>Student Name: <?php echo (!empty($list[0]['student_name']) ? $list[0]['student_name'] : "N/A"); ?></div>

                    
                    <input type="hidden" name="studentId" value="<?php echo (!empty($list[0]['student_id']) ? $list[0]['student_id'] : "N/A"); ?>">
                    <input type="hidden" name="examId" value="<?php echo (!empty($list[0]['exam_id']) ? $list[0]['exam_id'] : "N/A"); ?>" >
                    <div class="form-group">
                        <label for="exampleInputEmail1">Total Marks </label>
                        <input type="text" name="total_marks"  class="form-control" placeholder="Enter total marks" required="required" autocomplete="off">
                    </div>

                    <div class="form-group" >
                    <?php foreach($list as $key => $subject_list) { ?>
                        <?php echo (!empty($subject_list['subject_name']) ? $subject_list['subject_name'] : "N/A"); ?>
                        <input type="hidden" name="subjectId[]" value="<?php echo (!empty($subject_list['subject_id']) ? $subject_list['subject_id'] : "N/A"); ?>" > 
                        <input type="text" class="form-control"  name="marks[]" value="" placeholder="Enter Marks"><br>
                    <?php } ?>
                    </div>

                    <div class="col-md-12"></div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" target="_blank" name="create" value="Entry" >
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

