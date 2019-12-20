<?php $this->load->view('includes/header.php'); ?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="<?php echo base_url(); ?>assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item has-treeview <?php if($this->uri->segment(2) === 'listClass' || $this->uri->segment(2) === 'listSection') { echo "menu-open"; } ?>">
            <a href="#" class="nav-link <?php if($this->uri->segment(2) === 'listClass' || $this->uri->segment(2) === 'listSection') { echo "active"; } ?>">
              <i class="nav-icon fas fa-copy"></i>
              <p>
              Class & Section
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">5</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/layout/top-nav.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Class</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('ClassSection/listClass'); ?>" class="nav-link <?php if($this->uri->segment(2) === 'listClass') { echo "active"; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Class</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Section</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('ClassSection/listSection'); ?>" class="nav-link <?php if($this->uri->segment(2) === 'listSection') { echo "active"; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Section</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('ClassSection/uploadCsv'); ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>CSV Upload</p>
                </a>
              </li>

            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
              Subject & Assign
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">4</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0);" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Subject</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Subject/listSubject'); ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Subject</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Subject/assignSubjectList'); ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assign Subject</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Subject/uploadCsv'); ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>CSV Upload</p>
                </a>
              </li>

            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
              Exam
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('Exam/createExam');?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Exam</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Exam/listExam'); ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Exam</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
              Student
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">4</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Student</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Student/listStudent'); ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Student</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Student/uploadCsv'); ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Student CSV Upload</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="Javascript:void(0);" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Parent/Student CSV Upload</p>
                </a>
              </li>
            </ul>
          </li>


          <li class="nav-item has-treeview <?php if($this->uri->segment(2) === 'listStudentMarks' || $this->uri->segment(2) === 'studentMark' || $this->uri->segment(2) === 'sectionResult' || $this->uri->segment(2) === 'tabulationSheet') { echo "menu-open"; } ?>">
            <a href="#" class="nav-link <?php if($this->uri->segment(2) === 'listStudentMarks' || $this->uri->segment(2) === 'studentMark' || $this->uri->segment(2) === 'sectionResult' || $this->uri->segment(2) === 'tabulationSheet') { echo "active"; } ?>">
              <i class="nav-icon fas fa-copy"></i>
              <p>
              Result
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">4</span>
              </p>
            </a>
            <ul class="nav nav-treeview ">
              <li class="nav-item">
                <a href="<?php echo base_url('Marks/studentMark'); ?>" class="nav-link <?php if($this->uri->segment(2) === 'studentMark') { echo "active"; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Marks Entery</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Marks/listStudentMarks'); ?>" class="nav-link <?php if($this->uri->segment(2) === 'listStudentMarks') { echo "active"; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Studnent Marks</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Marks/sectionResult'); ?>" class="nav-link <?php if($this->uri->segment(2) === 'sectionResult') { echo "active"; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Section Wise Result</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('Marks/tabulationSheet'); ?>" class="nav-link <?php if($this->uri->segment(2) === 'tabulationSheet') { echo "active"; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tabulation Sheet List</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
