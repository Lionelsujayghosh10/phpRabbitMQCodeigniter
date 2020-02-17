<?php
$this->load->view('includes/sidebar.php'); ?>




<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1></h1>
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
                <h3 class="card-title">Create Assign Subject</h3>
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
                    <!-- <div class="form-group">
                      <label for="exampleInputEmail1">Exams </label>
                      <select name="exam_id" class="form-control">
                          <option value="">Select an exam</option>
                          <?php if(!empty($exams)) { ?>
                              <?php foreach($exams as $exam) {  ?>
                                  <option value="<?php echo (!empty($exam['examId']) ? $exam['examId'] : ""); ?>"><?php echo (!empty($exam['exam_name']) ? $exam['exam_name'] : "N/A"); ?></option>
                              <?php } ?>
                          <?php }  ?>
                      </select>
                    </div> -->
                    <div class="form-group">
                        <label for="exampleInputEmail1">Class </label>
                        <select name="class_id" class="form-control classselection" required="required" id="class_id">
                            <option value="">Select an class</option>
                            <?php if(!empty($classes)){ ?>
                              <?php foreach($classes as $class){ ?>
                              <option value="<?php echo (!empty($class['classId']) ? $class['classId'] : "");?>" data-classId="<?php echo (!empty($class['classId']) ? $class['classId'] : "");?>"><?php echo (!empty($class['class_name']) ? $class['class_name'] : "N/A");?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                    <input type="checkbox" name="sectionStatus" value="true">
                    <label for="sectionStatus" >Select All Sections</label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Section </label>
                        <select name="section_id" class="form-control" id="section_selection">
                            <option value="">Select an class first</option>
                        </select>
                        <div class="sectionError"></div>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Subject </label>
                        <select name="subject_id" class="form-control" required="required" id="subject_selection">
                            <option value="">Select Subject</option>
                            <?php if(!empty($subjects)){ ?>
                              <?php foreach($subjects as $subject){ ?>
                              <option value="<?php echo (!empty($subject['subjectId']) ? $subject['subjectId'] : "");?>" data_classId=""><?php echo (!empty($subject['subject_name']) ? $subject['subject_name'] : "N/A");?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label for="exampleInputEmail1">Total Marks </label>
                        <input type="text" name="total_marks"  class="form-control" placeholder="Enter total marks" required="required" autocomplete="off">
                    </div> -->

                        <div class="form-group" id="student_id">

                        </div>
                    <div class="col-md-12"></div>
                    <!-- <div class="form-group">
                      <small style="color:red; display:none" class="errorView">Please select class and section to get student list.</small>
                      <a href="javascript:void(0);"><button class="view_student btn btn-primary" >View Student</button></a>
                    </div> -->

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" name="Assign" value="Assign" >
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
 $(document.body).on('change', '.classselection', function(){
  let classId  = $('.classselection').children("option:selected").attr('data-classId');
  //console.log(classId);
  $.ajax({
    url     : '<?php echo base_url("Subject/sectionList");?>',
    type    : 'POST',
    data    : {classId : classId},
    success : function(data){
      let response = JSON.parse(data);
      if(response.length > 0){
        let i;
        let output = "";
        $('.sectionError').html(output);
        output  +=   '<option>Select Section</option>';
        for(i=0; i<response.length; i++){
          output += '<option value="'+response[i].sectionId+'">'+response[i].section_name+'</option>';
        }
        $('#section_selection').html(output);
      }else{
        let output ='<span class="help-block"><i class="icon-remove-sign"></i> <font color="red"> No Section Found under this class!</font></span>';
        $('.sectionError').html(output);
      }
      
  }
   });
 });

    
</script>
