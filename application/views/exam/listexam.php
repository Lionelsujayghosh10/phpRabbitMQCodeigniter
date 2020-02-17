<?php  $this->load->view('includes/sidebar.php'); ?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>List Exam</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Exam</li>
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
                <h3 class="card-title">List Exam</h3>

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
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Exam Name</th>
                      <th>Exam Code</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>   
                    <?php if(count($value) > 0) { ?>
                        <?php foreach($value as $single_exam) {  ?>
                            <tr>
                                <td><?php echo  (!empty($single_exam['exam_name']) ? $single_exam['exam_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_exam['exam_code']) ? $single_exam['exam_code'] : "N/A"); ?></td>
                                <td><a href="javascript:void(0);"><button class="btn btn-warning btn-xs">Edit</button></a>&nbsp; &nbsp; <button id="<?php echo (!empty($single_exam['examId']) ? $single_exam['examId'] : "N/A"); ?>" class="btn btn-danger btn-xs deleteButton">Delete</button></td>
                            
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="3">No Exam Created yet.</td></tr>
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