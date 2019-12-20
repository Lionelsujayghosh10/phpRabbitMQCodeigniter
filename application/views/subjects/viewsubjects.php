<?php  $this->load->view('includes/sidebar.php'); ?>



<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Subject</h1>
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
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card ccard-info">
              <div class="card-header">
                <h3 class="card-title">View Subject</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" action="<?php echo base_url('Subject/editSubject/').base64_encode($subjectData['subjectId']); ?>">
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
                <input type="hidden" name="subjectId" value="<?php echo (!empty($subjectData['subjectId']) ? $subjectData['subjectId'] : ""); ?>">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Subject Code </label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="subject_code" required="required" autocomplete="off" value="<?php echo (!empty($subjectData['subject_code']) ? $subjectData['subject_code'] : ""); ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Subject Name </label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="subject_name" required="required" autocomplete="off" value="<?php echo (!empty($subjectData['subject_name']) ? $subjectData['subject_name'] : ""); ?>">
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
