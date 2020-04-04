<?php $this->load->view('includes/sidebar.php'); ?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Exam</h1>
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
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card ccard-info">
              <div class="card-header">
                <h3 class="card-title">Create Exam</h3>
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
                    <label for="exampleInputEmail1">Exam Code </label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="exam_code" required="required" placeholder="Enter Exam Code">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Exam Name </label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="exam_name" required="required" placeholder="Enter Exam Name">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" name="create" value="Create" >
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
            url     : '<?php echo base_url("Exam/listClass");?>',
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
                            output +='<option value ="'+response[i].sectionId+'">'+response[i].section_name+'</option>';                   
                        }
                        $('#section_selection').html(output);
                        
                    }else{
                        let output ='<span class="help-block"><i class="icon-remove-sign"></i> <font color="red"> No Section Found under this class!</font></span>';
                        $('#section_selection').html(output);
                    }
                }else{
                     output += '<option value = "0" >'+"No Sections Created"+'</option>';
                    $('#section_selection').html(output);
                }
            }
        });
    });


</script>