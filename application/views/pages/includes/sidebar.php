 <div class="tab-pane active" id="mainmenu">
    <!-- <h5 class="sidebar-title">Favorites</h5> -->
    <ul class="nav nav-pills nav-stacked nav-quirk" id="sidebar-menu"> 
      <?php $allowed_modules_names = $this->allowed_modules_list;  ?> 

      <?php if(in_array('Self Service',$allowed_modules_names)){ ?>
      <li class="nav-parent nav-active" class="active">
        <a href="javascript:;" style="background-color: #d9534f; color: #fff;"><i class="fa fa-user"></i> <span>Self Service</span></a>
        <ul class="children" style="display: block;">
          <?php if(in_array('Employee Dashboard',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'self_service_dashboard');" class="active"><a href="javascript:;">Dashboard</a></li> 
          <?php }if(in_array('Employee Attendance',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'self_service_attendace');"><a href="javascript:;">Attendance</a></li> 
          <?php }if(in_array('Leave Applications',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'self_service_leave_applications');"><a href="javascript:;">Leave Applications</a></li> 
          <?php }if(in_array('Calendar',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'self_service_calendar');"><a href="javascript:;">Holidays Calendar</a></li> 
          <?php }if(in_array('Daily Reports',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'self_service_daily_reports');"><a href="javascript:;">Daily Reports</a></li> 
          <?php }if(in_array('Employee Announcements',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'self_service_announcements');"><a href="javascript:;">Announcements</a></li> 
          <?php } ?>
        </ul>
      </li>
      <?php }if(in_array("Dashboard",$allowed_modules_names)){ ?>
      <li class="active" onclick="load_sidebar_view(this,'dashboard');"><a href="javascript:;"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
      <?php }if(in_array('Organization',$allowed_modules_names)){ ?>
      <li class="nav-parent">
        <a href="javascript:;"><i class="fa fa-building-o"></i> <span>Organization</span></a>
        <ul class="children" style="display: none;">
          <?php if(in_array('Company Profile',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'organization_view');"><a href="javascript:;">Company Profile</a></li> 
          <?php }if(in_array('Departments',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'departments_table');"><a href="javascript:;">Departments</a></li> 
          <?php }if(in_array('Designations',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'designations_table');"><a href="javascript:;">Designation</a></li>
          <?php }if(in_array('Locations',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'locations_table');"><a href="javascript:;">Locations</a></li>
          <?php }if(in_array('Announcements',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'announcements');"><a href="javascript:;">Announcements</a></li> 
          <?php }if(in_array('Policies',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'policies_table');"><a href="javascript:;">Organization Policies</a></li>
           <?php }if(in_array('Training Groups',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'training_groups_table');"><a href="javascript:;">Training Groups</a></li>
          
          <?php }if(in_array('Manage Assets',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'organization_assets_table');"><a href="javascript:;">Manage Assets</a></li> 
          <?php }if(in_array('Organogram',$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'organization_organogram');"><a href="javascript:;">Organization Organogram</a></li> 
          <?php } ?>
        </ul>
      </li>
       <?php }if(in_array('Manage Employees',$allowed_modules_names)){ ?>
      <li class="nav-parent">
        <a href="javascript:;"><i class="fa fa-users"></i> <span>Employees</span></a>
        <ul class="children" style="display: none;"> 
          <?php if(in_array("Employees Dashboard",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'employees_dashboard');"><a href="javascript:;">Dashboard</a></li>
          <?php }if(in_array("Employees",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'employees_table');"><a href="javascript:;">Employees</a></li>
          <?php }if(in_array("Manage Shifts",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'manage_shifts');"><a href="javascript:;">Manage Shifts</a></li>
          <?php }if(in_array("Manage Activities",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'manage_activities');"><a href="javascript:;">Manage Activities</a></li>
          <?php } ?>  
        </ul>
      </li>
      <?php }if(in_array('Attendance',$allowed_modules_names)){ ?>
      <li class="nav-parent">
        <a href="javascript:;"><i class="fa fa-clock-o"></i> <span>Attendance</span></a>
        <ul class="children" style="display: none;"> 
          <?php if(in_array("Attendance Dashboard",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'attendance_dashboard');"><a href="javascript:;">Dashboard</a></li>
          <?php }if(in_array("Manage Attendance",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'manage_attendance');"><a href="javascript:;">Manage Attendance</a></li>
          <?php }if(in_array("Manage Leaves",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'manage_leaves');"><a href="javascript:;">Manage Leaves</a></li>
          <?php } ?>  
        </ul>
      </li> 
      <?php }if(in_array('Manage Organograms',$allowed_modules_names)){ ?>
      <li onclick="load_sidebar_view(this,'manage_organograms');"><a href="javascript:;"><i class="fa fa-sitemap"></i> <span>Manage Organogram</span></a></li> 
      <?php }if(in_array('Manage Subfields',$allowed_modules_names)){ ?>
      <li class="nav-parent">
        <a href="javascript:;"><i class="fa fa-snowflake-o"></i> <span>Manage Subfields</span></a>
        <ul class="children" style="display: none;"> 
          <?php if(in_array("Benefit Types",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'manage_benefit_types');"><a href="javascript:;">Benefit  Types</a></li>
          <?php }if(in_array("Activity Types",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'manage_activity_types');"><a href="javascript:;">Activity  Types</a></li>
          <?php } ?>  
        </ul>
      </li>
      <?php }if(in_array('User Access Control',$allowed_modules_names)){ ?>
      <li class="nav-parent">
        <a href="javascript:;"><i class="fa fa-snowflake-o "></i> <span>User Access Control</span></a>
        <ul class="children" style="display: none;">
          <?php if(in_array("Role's",$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'roles');"><a href="javascript:;">Role's</a></li> 
          <?php }if(in_array("Permissions",$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'permissions');"><a href="javascript:;">Permissions</a></li>  
          <?php }if(in_array("Restrictions",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'restrictions');"><a href="javascript:;">Restrictions</a></li>
          <?php } ?>  
        </ul>
      </li>
      <?php }if(in_array("Recruitment",$allowed_modules_names)){ ?>
      <li class="nav-parent">
        <a href="javascript:;"><i class="fa fa-user"></i> <span>Recruitment</span></a>
        <ul class="children" style="display: none;">
          <?php if(in_array("Job Posts",$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'job-posts');"><a href="javascript:;">Job Posting</a></li> 
          <?php }if(in_array("Applicants",$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'applicants');"><a href="javascript:;">Applicants</a></li> 
          <?php }if(in_array("Interview Questions",$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'manage_interview_questions');"><a href="javascript:;">Interview Questions</a></li> 
          <?php }if(in_array("Applicant Instructions",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'applicant_instructions');"><a href="javascript:;">Applicant Instructions</a></li>   
          <?php } ?>
        </ul>
      </li> 
      <?php }if(in_array("Templates",$allowed_modules_names)){ ?>
      <li class="nav-parent">
        <a href="javascript:;"><i class="fa fa-newspaper-o"></i> <span>Templates</span></a>
        <ul class="children" style="display: none;"> 
          <?php  if(in_array("Email Templates",$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'email_templates_table');"><a href="javascript:;">Email Templates</a></li>   
          <?php } ?>
        </ul>
      </li> 
      <?php }if(in_array("Files",$allowed_modules_names)){ ?>
      <li class="nav-parent">
        <a href="javascript:;"><i class="fa fa-folder-open"></i> <span>Files</span></a>
        <ul class="children" style="display: none;">
          <?php if(in_array("Employee Files",$allowed_modules_names)){ ?>
          <li><a href="general-forms.html">Employee Files</a></li> 
          <?php }if(in_array("Organization Files",$allowed_modules_names)){ ?>
          <li><a href="form-wizards.html">Organization Files</a></li>   
          <?php } ?>
        </ul>
      </li> 
      <?php }if(in_array('Reports',$allowed_modules_names)){ ?>
      <li class="nav-parent">
        <a href="javascript:;"><i class="fa fa-calendar"></i> <span>Reports</span></a>
        <ul class="children" style="display: none;">
          <?php if(in_array("Daily Progress Report",$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'report_daily_progress');"><a href="javascript:;">Daily Progress Report</a></li> 
          <?php }if(in_array("Attendance Report",$allowed_modules_names)){ ?>
          <li onclick="load_sidebar_view(this,'report_attendance');"><a href="javascript:;">Attendance Report</a></li>   
          <?php }if(in_array("Applicants Report",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'report_applicants');"><a href="javascript:;">Applicants Report</a></li>
          <?php }if(in_array("Employees Report",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'report_employees');"><a href="javascript:;">Employees Report</a></li>
          <?php }if(in_array("System Report",$allowed_modules_names)){ ?> 
          <li onclick="load_sidebar_view(this,'report_system');"><a href="javascript:;">System Report</a></li>
          <?php } ?>  
        </ul>
      </li> 
      <?php }if(in_array('Settings',$allowed_modules_names)){ ?>
      <li onclick="load_sidebar_view(this,'settings');"><a href="javascript:;"><i class="fa fa-gear"></i> <span>Settings</span></a></li> 
      <?php } ?>
      <li><a href="<?= base_url("logout"); ?>"><i class="fa fa-power-off"></i> <span>Logout</span></a></li>
      
    </ul>  
  </div> 