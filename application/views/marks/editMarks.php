<?php
//echo "<pre>"; print_r($list); die;
$this->load->view('includes/sidebar.php'); ?>




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Students Marks</h1>
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
                <h3 class="card-title">Student Marks</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" action="<?php echo base_url('Marks/updateMarks')?>" enctype="multipart/form-data">
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
                        <label for="exampleInputEmail1">Highest Marks </label>
                        <input type="text" name="highest_marks"  class="form-control" placeholder="Enter Highest Marks" required="required" value="<?php echo (!empty($list[0]['highestMarks']) ? $list[0]['highestMarks'] : "0"); ?>" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Total Marks </label>
                        <input type="text" name="total_marks"  class="form-control" placeholder="Enter total marks" required="required" autocomplete="off" value="<?php echo (!empty($list[0]['total_marks']) ? $list[0]['total_marks'] : "0"); ?>">
                    </div>

                        <div class="form-group" id="student_id">
                          <?php if(!empty($list)) { ?>
                            <?php foreach($list as $single){?> 

                              <?php echo "<b>".$single['studentName']."</b>"; ?><input type="hidden" name="studentId[]" value="<?php echo (!empty($single['student_id']) ? $single['student_id'] : "0"); ?>" ><input type="hidden" name="primaryKey[]" value="<?php echo (!empty($single['studentMarksId']) ? $single['studentMarksId'] : "0"); ?>" > <input type="text" class="form-control"  name="marks[]"  placeholder="Enter Marks" value="<?php echo (!empty($single['otained_marks']) ? $single['otained_marks'] : "0"); ?>">
                            <?php } ?>
                          <?php } ?>     
                        </div>
                    <div class="col-md-12"></div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" name="create" value="Edit" >
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


