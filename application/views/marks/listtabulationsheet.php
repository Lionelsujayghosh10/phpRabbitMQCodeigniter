<?php   $this->load->view('includes/sidebar.php'); ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluidtabulatonSheets">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tabulation Sheet List</h1>
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

        <!-- /.row -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List</h3>

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
                      <th>Class Name</th>
                      <th>Section Name</th>
                      <th>CSV Name</th>
                      <th>Complete Status</th>
                      <th>Time Stamp</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($tabulatonSheets) > 0) { ?>
                        <?php foreach($tabulatonSheets as $single_tabulation) {  ?>
                            <tr>
                                <td><?php echo  (!empty($single_tabulation['exam_name']) ? $single_tabulation['exam_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_tabulation['class_name']) ? $single_tabulation['class_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_tabulation['section_name']) ? $single_tabulation['section_name'] : "N/A"); ?></td>
                                <td><?php echo  (!empty($single_tabulation['csv_name']) ? $single_tabulation['csv_name'] : "N/A"); ?></td>
                                <td><?php if($single_tabulation['isComplete'] === '0') { ?> <b style="color:red;">Processing...</b> <?php } else { ?> <b style="color:red;">Generate.</b> <?php } ?></td>
                                <td>
                                  <?php if(!empty($single_tabulation['generate_time'])){ $time = strtotime($single_tabulation['generate_time']); $dateInLocal = date("Y-m-d H:i:s", $time); echo $dateInLocal; } else { echo "N/A"; } ?>
                                  
                                </td>
                                <td><?php if($single_tabulation['isComplete'] === '0') { ?> <button class="btn btn-xs btn-primary" disabled>Download</button></button> <?php } else { ?> <a href="<?php echo base_url('Marks/download/').urlencode($single_tabulation['csv_name']); ?>"><button class="btn btn-xs btn-primary">Download</button></a>&nbsp; &nbsp; <button class="btn btn-xs btn-warning deleteSheet" id="<?php echo $single_tabulation['sheetId'] ?>">Delete</button> <?php } ?></td>

                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="3">No tabulation sheet generated.</td></tr>
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



<script type="text/javascript">

  $(document.body).on('click' , '.deleteSheet' , function(){
    var sheetId        = $(this).attr('id');
    swal({
      title: 'Do you  want to delete it?',
      text: "Tabulation Sheet will be deleted. To get tabulation sheet of this section need to regenerate.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      allowOutsideClick: false,
    }).then((result) => {
      if (result.value) {
          $.ajax({
            url     : '<?php echo base_url('Marks/deleteSheet'); ?>',
            type    : 'POST',
            data    : {sheetId : sheetId},
            success : function(data){
              if(data == 1){
                swal({
                  title: 'Deleted successfully done.',
                  text: "You won't be able to revert this!",
                  type: 'success',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Ok!'
                }).then((result) => {
                  location.reload();
                })
              } else {
                  swal(
                    'Error!',
                    'While deleting the company there is an error',
                    'error'

                  )
              }
            }
          })
        
      }
    })
      
  });
</script>