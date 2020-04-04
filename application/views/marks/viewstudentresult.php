<?php  $this->load->view('includes/sidebar.php');
$subject_array = array();
foreach($student_result as $key => $value) {
  foreach($value as $single_entry) {
    $subject_array[] = $single_entry['subject_name'];
  } 
  break; 
}

















?>

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
                <h3 class="card-title">Student Result</h3>

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
                <table class="table table-hover" id="student_view_table">
                  <thead>
                    <tr>
                      <th>Student Name</th>
                      <?php foreach($subject_array as $subject) { ?>  
                        <th><?php echo (!empty($subject) ? $subject : "N/A"); ?></th>
                      <?php } ?>
                      <th>Total Marks</th>
                      <th>Percentage</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php for($i = 0; $i < count($student_result); $i++){ $total_number = 0; $percentage = 0; $total_obtained_marks = 0; ?>
                        <?php foreach($student_result[$i] as $index => $single_data) {  ?>
                          <?php if($index == 0){
                            if($single_data['subject_name'] === 'C.C.A' || $single_data['subject_name'] === 'S.U.P.W') {
                              $html = '<tr><td>'.$single_data['student_name'].'</td><td>'.$single_data['otained_marks'].'</td>';
                            } else {
                              $html = '<tr><td>'.$single_data['student_name'].'</td><td>'.$single_data['otained_marks'].'/'.$single_data['total_marks'].'</td>';
                            }
                          } else { 
                            if($single_data['subject_name'] === 'C.C.A' || $single_data['subject_name'] === 'S.U.P.W') {
                              $html .= '<td>'.$single_data['otained_marks'].'</td>';
                            } else {
                              $html .= '<td>'.$single_data['otained_marks'].'/'.$single_data['total_marks'].'</td>';
                            }
                          } $total_number =  $single_data['total_marks'] + $total_number;  $total_obtained_marks  = $single_data['otained_marks'] + $total_obtained_marks; ?>

                        <?php } $percentage = ($total_obtained_marks*100)/$total_number; $html .= '<td>'.$total_obtained_marks.'</td><td>'.sprintf('%0.2f', $percentage).' %</td></tr>';  echo  $html; ?>
                      <?php } ?> 
                  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
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
$(document).ready(function() {
  $('#student_view_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'pdfHtml5',
        orientation: 'landscape',
        pageSize: 'LEGAL'
      }
    ],
    searching: false, paging: false,info: false,
    "scrollX": true
 });
});
</script>