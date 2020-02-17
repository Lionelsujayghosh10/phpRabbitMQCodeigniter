<?php
$this->load->view('includes/sidebar.php'); ?>




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Section Marks</h1>
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
                <h3 class="card-title">Section Marks Edit</h3>
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
                      <label for="exampleInputEmail1">Exams </label>
                      <select name="exam_id" class="form-control">
                          <option value="">Select an exam</option>
                          <?php if(!empty($exams)) { ?>
                              <?php foreach($exams as $exam) {  ?>
                                  <option value="<?php echo (!empty($exam['examId']) ? $exam['examId'] : ""); ?>"><?php echo (!empty($exam['exam_name']) ? $exam['exam_name'] : "N/A"); ?></option>
                              <?php } ?>
                          <?php }  ?>
                      </select>
                    </div>
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
                        <select name="section_id" class="form-control section_selection" id="section_id">
                            <option value="">Select an class first</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Subject </label>
                        <select name="subject_id" class="form-control" id="subject_selection">
                            <option value="">Select an class first</option>
                        </select>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" name="button" value="Fetch List" >
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
              console.log(data);
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
                    $('#section_id').html(output);
                    $('#subject_selection').html('<option value = "" >Select an section first</option>');
                  }else{
                    let output ='<span class="help-block"><i class="icon-remove-sign"></i> <font color="red"> No Section Found under this class!</font></span>';
                    $('#section_id').html('<option value = "" >Select an section first</option>');
                  }
                }else{
                  output += '<option value = "0" >'+"No Sections Created"+'</option>';
                  $('#section_id').html(output);
                }
            }
        });
    });

    $(document.body).on('change', '.section_selection', function(){
      let class_Id = '.class_selection';
      let sectionId = $(this).children("option:selected").attr('data-sectionId');
      let classId   = $(class_Id).children("option:selected").attr('data-classId');
      console.log(sectionId+"ss"+classId);
      $.ajax({
        url : '<?php echo base_url("Exam/listSubject");?>',
        type: 'POST', 
        data:  {sectionId:sectionId},
        success: function(data){console.log(data);
          if(data !== "error" ){
            let response =JSON.parse(data);
            //console.log(response);
            if(response.length>0){
              let i;
              let output="";
              output += '<option>Select Subject</option>';
              for(i=0; i<response.length; i++){
                  output  +=  '<option value="'+response[i].subject_id+'">'+response[i].subjectName+'</option>';
                  console.log(output);
              }
              $('#subject_selection').html(output);
            }else{
              let output = '<option value = "0" >'+"No Subject Assigned!"+'</option>';
                  $('#subject_selection').html(output);
            }
          }else{
            let output = '<option value = "0" >'+"No Subject Assigned!"+'</option>';
                  $('#subject_selection').html(output);
          }
        }
      });
    });

    

</script>
