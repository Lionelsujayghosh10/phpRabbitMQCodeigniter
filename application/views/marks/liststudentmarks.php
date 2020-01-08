<?php   $this->load->view('includes/sidebar.php'); ?>
<style>
.pagination li{ padding:10px; }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>List Student Marks</h1>
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

        <!-- /.row -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List Student Marks</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <form method="get" action="<?php echo base_url('Marks/search'); ?>">
                      <input type="text" name="table_search" class="form-control float-right" autocomplete="off" placeholder="Search" value="<?php echo (!empty($_GET['table_search']) ? $_GET['table_search'] : ""); ?>">

                      <div class="input-group-append" style="float: right; margin-top: -38px; height: 38px;">
                        <input type="submit" class="btn btn-primary btn-xs" name="search" value="search">
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Exam Name</th>
                      <th>Class Name</th>
                      <th>Section Name</th>
                      <th>Subject Name</th>
                      <th>Student Name</th>
                      <th>Total Marks</th>
                      <th>Obtain Marks</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($studentmarks) > 0) { ?>
                        <?php foreach($studentmarks as $single_student_marks) {  ?>
                            <tr>
                                <td><?php echo  (!empty($single_student_marks['exam_name']) ? $single_student_marks['exam_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student_marks['class_name']) ? $single_student_marks['class_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student_marks['section_name']) ? $single_student_marks['section_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student_marks['subject_name']) ? $single_student_marks['subject_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student_marks['student_name']) ? $single_student_marks['student_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student_marks['total_marks']) ? $single_student_marks['total_marks'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_student_marks['otained_marks']) ? $single_student_marks['otained_marks'] : "N/A"); ?></td>
                                <td><a href="<?php echo base_url('Marks/fetch/').base64_encode($single_student_marks['studentMarksId']); ?>"><button class="btn btn-warning btn-xs">Edit</button></a>&nbsp; &nbsp; <button id="<?php echo (!empty($single_student_marks['studentMarksId']) ? $single_student_marks['studentMarksId'] : "N/A"); ?>" class="btn btn-danger btn-xs deleteButton">Delete</button></td>

                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="3">No subject assign.</td></tr>
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
  $(document.body).on('click', '.deleteButton', function(event){
    let raw_id = $(this).attr('id');
    swal({
      title: 'Are you want to delete mark details for this student',
      text: "It can not be revert back.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      allowOutsideClick: false
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url     : '<?php echo base_url('Marks/delete'); ?>',
            type    : 'POST',
            data    : {"raw_id" : raw_id },
            success : function(data){
              if(data === "success"){
                swal({title: "Wow!", text: "Mark deleted successfully done!", type: "success"}).then(function(){ 
                  location.reload();
                });
              } else {
                swal(
                  'Error!',
                  'While deleting the mark there is an error',
                  'error'
                )
              }
            }
          })
        }
      })
  });


</script>
