<?php  $this->load->view('includes/sidebar.php'); ?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>List Class</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Class & Section</li>
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
                <h3 class="card-title">List Class</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
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
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Class Code</th>
                      <th>Class Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>   
                    <?php if(count($data) > 0) { ?>
                        <?php foreach($data as $single_class) {  ?>
                            <tr>
                                <td><?php echo  (!empty($single_class['class_code']) ? $single_class['class_code'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_class['class_name']) ? $single_class['class_name'] : "N/A"); ?></td>
                                <td><a href="<?php echo base_url('ClassSection/fetchClass/').base64_encode($single_class['classId']); ?>"><button class="btn btn-warning btn-xs">View & Edit</button></a>&nbsp; &nbsp; <button id="<?php echo (!empty($single_class['classId']) ? $single_class['classId'] : "N/A"); ?>" class="btn btn-danger btn-xs deleteButton">Delete</button></td>
                            
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="3">No class Uploaded yet.</td></tr>
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