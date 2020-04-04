<?php
$this->load->view('includes/sidebar.php'); ?>




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Section Wise Result Generate</h1>
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
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card ccard-info">
              <div class="card-header">
                <h3 class="card-title">Section Wise Result Generate </h3>
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
                    <div class="form-group">
                        <label for="exampleInputEmail1">Class </label>
                        <select name="class_id" class="form-control class_selection" id="class_id">
                            <option value="">Select an class</option>
                            <?php if(!empty($classes)) { ?>
                                <?php foreach($classes as $class) { ?>
                                  <option value="<?php echo (!empty($class['classId']) ? $class['classId'] : ""); ?>" data-classId="<?php echo (!empty($class['classId']) ? $class['classId'] : ""); ?>"><?php echo (!empty($class['class_name']) ? $class['class_name'] : "N/A"); ?></option>
                                <?php } ?>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Section </label>
                        <select name="section_id" class="form-control" id="section_selection">
                            <option value="">Select an class first</option>
                        </select>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" name="button" value="Generate" >
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

<script>


    $(document.body).on('change', '.class_selection', function(){
        let classId  = $(this).children("option:selected").attr('data-classId');
        console.log(classId);
        $.ajax({
            url     : '<?php echo base_url("Exam/listSection");?>',
            type    : 'POST',
            data    : {classId : classId},
            success	: function(data){
                let output = '';
                if(data !== "error" ){
                  let response = JSON.parse(data);
                  if(response.length > 0){
                    let i;
                    let output = '';
                    output += '<option value = "" >'+"Select Section"+'</option>';
                    for( i = 0; i < response.length; i++ ){
                      output +='<option value ="'+response[i].sectionId+'" data-sectionId="'+response[i].sectionId+'">'+response[i].section_name+'</option>';
                    }
                    $('#section_selection').html(output);
                    $('#subject_selection').html('<option value = "" >Select an section first</option>');
                  }else{
                    let output ='<span class="help-block"><i class="icon-remove-sign"></i> <font color="red"> No Section Found under this class!</font></span>';
                    $('#section_selection').html('<option value = "" >Select an section first</option>');
                  }
                }else{
                  output += '<option value = "0" >'+"No Sections Created"+'</option>';
                  $('#section_selection').html(output);
                }
            }
        });
    });



    $(document.body).on('change', '#section_selection', function(){
        let sectionId  = $(this).children("option:selected").attr('data-sectionId');
        $.ajax({
            url     : '<?php echo base_url("Exam/listSubject");?>',
            type    : 'POST',
            data    : {sectionId : sectionId},
            success	: function(data){
              let output = '';
              if(data !== "error" ){
                let response = JSON.parse(data);
                if(response.length > 0){
                  let i;
                  let output = '';
                  output += '<option value = "" >'+"Select Subject"+'</option>';
                  for( i = 0; i < response.length; i++ ){
                    output +='<option value ="'+response[i].subject_id+'" data-sectionId="'+response[i].subject_id+'">'+response[i].subject_name+'</option>';
                  }
                  $('#subject_selection').html(output);
                } else {
                  let output ='<span class="help-block"><i class="icon-remove-sign"></i> <font color="red"> No subject Found under this section!</font></span>';
                  $('#subject_selection').html(output);
                }
              } else {
                output += '<option value = "0" >'+"No Subject Created"+'</option>';
                $('#subject_selection').html(output);
              }
            }
        });
    });

</script>
