<?php  $this->load->view('includes/sidebar.php'); ?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>List Subject</h1>
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
                <h3 class="card-title">List Subject</h3>

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
              <div class="card-body table-responsive">
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
                      <th>Subject Code</th>
                      <th>Subject Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($data) > 0) { ?>
                        <?php foreach($data as $single_subject) {  ?>
                            <tr>
                                <td><?php echo  (!empty($single_subject['subject_code']) ? $single_subject['subject_code'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_subject['subject_name']) ? $single_subject['subject_name'] : "N/A"); ?></td>
                                <td><a href="<?php echo base_url('Subject/editSubject/').base64_encode($single_subject['subjectId']); ?>"><button class="btn btn-warning btn-xs">Edit & View</button></a>&nbsp; &nbsp; <button id="<?php echo (!empty($single_subject['subjectId']) ? $single_subject['subjectId'] : "N/A"); ?>" class="btn btn-danger btn-xs deleteButtonSubject">Delete</button></td>

                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="3">No subject Uploaded yet.</td></tr>
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


<script>
  $(document.body).on('click', '.deleteButtonSubject', function(event){
    let subject_id = $(this).attr('id');
    swal({
      title: 'Do you want to delete subject',
      text: "Assign subject record will be automatically deleted.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      allowOutsideClick: false
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url     : '<?php echo base_url('Subject/deleteSubject'); ?>',
            type    : 'POST',
            data    : {"subject_id" : subject_id },
            success : function(data){
              if(data === "success"){
                swal({title: "Wow!", text: "Subject deleted successfully done!", type: "success"}).then(function(){ 
                  location.reload();
                });
              } else {
                swal(
                  'Error!',
                  'While deleting the subejct there is an error',
                  'error'
                )
              }
            }
          })
        }
      })
  });
</script>
