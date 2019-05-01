
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Applicants","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>


<style type="text/css">
  .email_template
  {
    display: none;
  }
  
  #change_application_status_from
  {
    display: none;
  }
  #assign_interviewers_form
  {
    display: none;
  }
  #add_note_form
  {
    display: none;
  }
  #move_applicants_form
  {
     display: none;
  }
  #send_email_form
  {
    display: none;
  }

  .table-layout
  {
      background: #ccc;
      display: none;
      width: 97.6%;
      height: 80%;
      z-index: 1000;
      position: absolute;
      opacity: 0.4;
  }
</style>
<div class="mainpanel">

    <div class="contentpanel"> 
    
    <div class="panel"> 
        <div class="panel-body">
            <div class="col-md-12">
              <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                <h3><i class="fa fa-cubes"></i> Manage Applicants</h3>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <select class="form-control select2 selected_action"  onchange="perform_group_action('applicants',this)">
                    <option selected="selected">With Selected</option> 
                    <option value="Send_Email" >Send Email</option>
                    <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <option value="Application_Status" >Change Application Status</option>  
                    <?php } ?>
                    <option value="Move_To_Employees" >Move To Employees</option>
                    <option value="Assign_Interviewer" >Assign Interviewer</option> 
                    <option value="Add_Note" >Add Note</option>

                    <?php if(in_array($module_id."_export", $this->role_permissions)){ ?>
                    <option value="Export" >Export</option>
                    <?php } ?>
                    <?php if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                    <option value="Delete">Delete</option>
                    <?php } ?>
                </select>
              </div>    
              <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
                <button onclick="load_view(this,'form_applicant_add')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button>
                <?php } ?> 
              </div>
            </div>
        </div>
    </div>
    <div class="panel" id="assign_interviewers_form"> 
        <div class="panel-body">
            <form method="post"  action="<?= base_url("admin/assign_interviewers"); ?>" id="assign_interviewers_form_id" onsubmit="return assign_interviewers(this)">
                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-3"></div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <select class="form-control select2" name="Interviewers[]" id="Interviewers" multiple="multiple">  
                          <option value="0" selected="selected">Select Interviewer</option>
                          <?php
                            $employees = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Status"=>"Active"));
                            if($employees->num_rows() > 0)
                            {
                              foreach ($employees->result() as $key => $value) { 
                          ?>
                          <option value="<?= $value->Id; ?>"><?= $value->First_Name." ".$value->Last_Name; ?></option> 
                          <?php }} ?>
                      </select>
                    </div>
                     
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin: 20px 0px;">
                     <button class="btn btn-danger btn-quirk" type="button" onclick="cancel_assign_interviewers_form()"><i class="fa fa-times"></i>&nbsp; Cancel </button> 
                     <button class="btn btn-success btn-quirk" type="submit" ><i class="fa fa-check"></i>&nbsp; Submit </button> 
                    </div>
                </div> 
                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-3"></div>
            </form>
        </div>
    </div>
    <div class="panel" id="send_email_form"> 
        <div class="panel-body">
            <form method="post"  action="<?= base_url("admin/send_email_to_selected"); ?>" id="Send_Email_Form" onsubmit="return send_email_to_selected(this)">
                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-3"></div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    
                     
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 "  >
                       <select class="form-control select2" name="EmailTemplates" id="EmailTemplates"  >
                          <option selected="selected" value="0">Select Email Template</option>
                          <?php 

                            $this->db->order_by("Id","DESC");
                            $records = $this->db->get_where('email_templates',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                            if($records->num_rows() > 0)
                            {
                              foreach ($records->result() as $key => $value) {
                                echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                              }
                            }

                          ?>  
                      </select>
                    </div> 
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin: 20px 0px;">
                     <button class="btn btn-danger btn-quirk" type="button" onclick="cancel_application_status_form()"><i class="fa fa-times"></i>&nbsp; Cancel </button> 
                     <button class="btn btn-success btn-quirk" type="submit" ><i class="fa fa-check"></i>&nbsp; Submit </button> 
                    </div>
                </div> 
                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-3"></div>
            </form>
        </div>
    </div>
    <div class="panel" id="move_applicants_form"> 
        <div class="panel-body">
            <form method="post"  action="<?= base_url("admin/move_applicants_into_employees"); ?>" id="move_applicants_form_id" onsubmit="return move_applicants_into_employees(this)">
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <select class="form-control select2" name="Role_Id" id="Role_Id" >  
                        <option value="0" selected="selected">Select Role</option>
                        <?php
                          $roles = $this->db->get_where("roles",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id ));
                          if($roles->num_rows() > 0)
                          {
                            foreach ($roles->result() as $key => $value) { 
                        ?>
                        <option value="<?= $value->Id; ?>"><?= $value->Name; ?></option> 
                        <?php }} ?>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <select class="form-control select2" name="Permission_Id" id="Permission_Id" >  
                        <option value="0" selected="selected">Select Permissions</option>
                        <?php
                          $permissions = $this->db->get_where("permissions",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id ));
                          if($permissions->num_rows() > 0)
                          {
                            foreach ($permissions->result() as $key => $value) { 
                        ?>
                        <option value="<?= $value->Id; ?>"><?= $value->Title; ?></option> 
                        <?php }} ?>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <select class="form-control select2" name="Location_Id" id="Location_Id" >  
                        <option value="0" selected="selected">Select Locations</option>
                        <?php
                          $locations = $this->db->get_where("locations",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id ));
                          if($locations->num_rows() > 0)
                          {
                            foreach ($locations->result() as $key => $value) { 
                        ?>
                        <option value="<?= $value->Id; ?>"><?= $value->Name; ?></option> 
                        <?php }} ?>
                    </select>
                  </div>
                </div>
                <hr> 
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <button class="btn btn-danger btn-quirk" type="button" onclick="cancel_move_applicants_form()"><i class="fa fa-times"></i>&nbsp; Cancel </button> 
                      <button class="btn btn-success btn-quirk" type="submit" ><i class="fa fa-check"></i>&nbsp; Continue </button>
                  </div> 
                </div>
               
            </form>
        </div>
    </div>
    <div class="panel" id="add_note_form"> 
        <div class="panel-body">
            <form method="post"  action="<?= base_url("admin/add_note_for_selected_applicants"); ?>" id="add_note_form_id" onsubmit="return add_note_for_selected_applicants(this)">
                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-3"></div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <input type="text" class="form-control" name="Note" id="Note">   
                    </div>
                     
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin: 20px 0px;">
                     <button class="btn btn-danger btn-quirk" type="button" onclick="cancel_add_note_form()"><i class="fa fa-times"></i>&nbsp; Cancel </button> 
                     <button class="btn btn-success btn-quirk" type="submit" ><i class="fa fa-check"></i>&nbsp; Submit </button> 
                    </div>
                </div> 
                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-3"></div>
            </form>
        </div>
    </div>
    <div class="panel" id="change_application_status_from"> 
        <div class="panel-body">
            <form method="post"  action="<?= base_url("admin/change_application_status"); ?>" id="Application_Status_Change_Form" onsubmit="return change_application_status(this)">
                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-3"></div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <select class="form-control select2" name="Application_Status">  
                          <option value="New" selected="selected">New</option>
                          <option value="Shortlisted">Shortlisted</option> 
                          <option value="Screened">Screened</option>   
                          <option value="Interviews">Interviews</option>   
                          <option value="Offered">Offered</option>   
                          <option value="Hired">Hired</option>   
                          <option value="Rejected">Rejected</option>   
                      </select>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin: 20px 0px;">
                      <label class="ckbox ckbox-primary">
                          <input type="checkbox" id="Send_Email" name="Send_Email" onclick="show_email_templates(this)"><span>Send Email</span>
                      </label>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 email_template"  >
                       <select class="form-control select2" name="Email_Templates" id="Email_Templates"  >
                          <option selected="selected" value="0">Select Email Template</option>
                          <?php 

                            $this->db->order_by("Id","DESC");
                            $records = $this->db->get_where('email_templates',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                            if($records->num_rows() > 0)
                            {
                              foreach ($records->result() as $key => $value) {
                                echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                              }
                            }

                          ?>  
                      </select>
                    </div> 
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin: 20px 0px;">
                     <button class="btn btn-danger btn-quirk" type="button" onclick="cancel_application_status_form()"><i class="fa fa-times"></i>&nbsp; Cancel </button> 
                     <button class="btn btn-success btn-quirk" type="submit" ><i class="fa fa-check"></i>&nbsp; Submit </button> 
                    </div>
                </div> 
                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-3"></div>
            </form>
        </div>
    </div>
    <div class="panel" style="min-height: 75vh;">
        <div class="panel-heading">
          <form method="post"  action="<?= base_url("admin/get_applicants"); ?>" id="filter_form">
            <div class="row">  
              <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                 <input type="text" class="form-control" name="Text" id="Text" placeholder="Write to search">
              </div>
              <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                   <select class="form-control select2" name="Job_Id" id="Job_Id"  >
                      <option selected="selected" value="0">All Job Posts</option>
                      <?php 

                        $this->db->order_by("Id","DESC");
                        $job_posts = $this->db->get_where('job_posts',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($job_posts->num_rows() > 0)
                        {
                          foreach ($job_posts->result() as $key => $value) {
                            echo '<option value="'.$value->Id.'">'.$value->Title.'</option>';
                          }
                        }

                      ?>  
                  </select>
              </div>

              <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                  <select class="form-control select2" name="Department_Id" id="Department_Id" >
                      <option selected="selected" value="0">All Department</option>
                      <?php 

                        $this->db->order_by("Id","DESC");
                        $departments = $this->db->get_where('departments',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($departments->num_rows() > 0)
                        {
                          foreach ($departments->result() as $key => $value) {
                            echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                      ?>  
                  </select>
              </div>
             
              <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                   <select class="form-control select2" name="Location_Id" id="Location_Id">
                      <option selected="selected" value="0">All Locations</option>
                      <?php 

                        $this->db->order_by("Id","DESC");
                        $locations = $this->db->get_where('locations',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($locations->num_rows() > 0)
                        {
                          foreach ($locations->result() as $key => $value) {
                            echo '<option value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                      ?>  
                  </select>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <select class="form-control select2" name="Application_Status">
                      <option value="New">New</option>
                      <option value="0" selected="selected">All Applicants</option> 
                      <option value="Shortlisted">Shortlisted</option> 
                      <option value="Screened">Screened</option>   
                      <option value="Interviews">Interviews</option>   
                      <option value="Offered">Offered</option>   
                      <option value="Hired">Hired</option>   
                      <option value="Rejected">Rejected</option>   
                  </select>
                </div>  
              <div class="col-md-1 col-lg-1 col-sm-2 col-xs-12">
                 <button class="btn btn-warning btn-quirk" type="button" id="show_more_filter" ><i class="fa fa-filter"></i>&nbsp;  </button> 
              </div>
              <div class="col-md-1 col-lg-1 col-sm-2 col-xs-12">
                 <button class="btn btn-success btn-quirk" type="button" onclick="fetch_records('<?= base_url('admin/get_applicants/1'); ?>')" ><i class="fa fa-filter"></i>&nbsp; Submit </button> 
              </div>
            </div>

            <div class="row more_filters" style="display: none;">  <br>
              <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0;"> 
                  <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                     <label class="control-label">Nationality </label>
                     <select class="form-control select2" name="Nationality" id="Nationality"  >
                        <option selected="selected" value="0">Nationality</option>
                        <?php 

                          $this->db->order_by("Id","DESC");
                          $countries = $this->db->get_where('countries');
                          if($countries->num_rows() > 0)
                          {
                            foreach ($countries->result() as $key => $value) {
                              echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                            }
                          }

                        ?>  
                      </select>
                  </div>

                  <div class="col-sm-2  col-xs-12">
                    <label class="control-label">Country  </label>
                    <select name="Country" id="Country" onchange="get_states(this,'State','')" class="form-control select2 ">
                      <option selected="selected" value="0">Select Country</option>
                      <?php 
                        $this->db->order_by("id","asc");
                        $countries = $this->db->get_where("countries");
                        if($countries->num_rows() > 0)
                        {
                          foreach ($countries->result() as $key => $value) 
                          { 
                             echo '<option  value="'.$value->id.'">'.$value->name.'</option>';
                          }
                        }
                       ?>
                      
                    </select>
                  </div>
                  <div class="col-sm-2  col-xs-12">
                   <label class="control-label">State  </label>
                   <select name="State" id="State"  onchange="get_cities(this,'City','')" class="form-control select2 "> 
                    <option selected="selected" value="0">Select State</option> 
                     
                   </select>
                  </div>
                  <div class="col-sm-2  col-xs-12">
                   <label class="control-label">City</label>
                   <select name="City" id="City" class="form-control  select2"> 
                    <option selected="selected" value="0">Select City</option> 
                     
                   </select>
                  </div> 
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label class="control-label">Gender</label>
                    <select class="form-control select2" name="Gender" id="Gender"> 
                        <option value="0" selected="selected">Any Gender</option> 
                        <option value="Male">Male</option> 
                        <option value="Female">Female</option>   
                        <option value="Other">Other</option>    
                    </select>
                  </div>  
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0; margin-top: 10px;"> 
                  <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                     <label class="control-label">Degree Type </label>
                     <select class="form-control select2" name="Degree_Type" id="Degree_Type"  >
                        <option selected="selected" value="0"> Degree Type </option>
                        <option value="High School">High School</option>
                        <option value="Matriculation/O-Levels">Matriculation/O-Levels</option>
                        <option value="Intermediate/A-Levels">Intermediate/A-Levels</option>
                        <option value="Bachelors">Bachelors</option>
                        <option value="Masters">Masters</option>
                        <option value="Doctorate">Doctorate</option>
                        <option value="Certificate">Certificate</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Other">Other</option>
                      </select>
                  </div>

                 <div class="col-sm-2  col-xs-12">
                  <div class="">
                    <label class="control-label">Select Country <span class="text-danger">*</span><span class="text-danger error"></span></label>
                    <select  id="Country" onchange="get_institute(this,'Institute')" value="<?php if(isset($record_data['Country'])){ echo $record_data['Country']; } ?>" class="form-control select2 ">
                      <?php 
                        $this->db->order_by("name","asc");
                        $countries = $this->db->get_where("countries");
                        if($countries->num_rows() > 0)
                        {
                          foreach ($countries->result() as $key => $value) 
                          {
                            $selected = "";
                            if(isset($record_data['Country'])){ if($record_data['Country'] == $value->sortname ){ $selected = "selected='selected'"; } }
                             echo '<option '.$selected.' value="'.$value->sortname.'">'.$value->name.'</option>';
                          }
                        }
                       ?>
                      
                    </select>
                  </div>
                </div>
                <div class="col-sm-2 col-xs-12 ">
                  <div class="">
                      <label class="control-label">Select Institute <span class="text-danger">*</span><span class="text-danger error"></span></label>
                      <select name="Institute" id="Institute" multiple="multiple" class="form-control select2 ">
                         <option selected="selected" value="0"> Select Institute </option>
                      </select>
                  </div>
                </div> 
                 <div class="col-sm-2  col-xs-12">
                  <label class="control-label">Marks Obtained</label>
                   <input type="text" class="form-control" name="Marks_Obtained" id="Marks_Obtained">
                 </div> 
                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label class="control-label">Marks Value</label>
                    <select class="form-control select2" name="Marks_Value" id="Marks_Value"> 
                        <option value="Equal_To">Equal To</option> 
                        <option value="Greater_Than">Greater Than</option>   
                        <option value="Less_Than">Less Than</option>
                        <option value="Greater_Than_Equal_To">Greater Than & Equal To</option>   
                        <option value="Less_Than_Equal_To">Less Than & Equal To</option>    
                    </select>
                 </div>  
              </div>

            </div>  
          </form>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <div class="table-layout"></div>
            <table class="table table-bordered nomargin">
              <thead>
                 <tr> 
                  <th>
                    <label class="ckbox ckbox-primary">
                      <input type="checkbox" onclick="selectallrecords(this,'applicants')" ><span></span>
                    </label>
                  </th> 
                  <th></th> 
                  <th>Basic Information</th>   
                   
                  <th>Job Post</th>  
                  <th>Location</th>   
                  <th>Application Status</th>   
                  <th>Degree Type</th>
                  <th>Institute</th>
                  <th>Applied Date</th>
                  <th>Applicant Address</th>
                   
                   
                   
                </tr>
              </thead>
              <tbody id="applicants" class="table_records">
                <?php 
 
                   

                  $this->db->where(array("applicants.Deleted" =>0,"applicants.Org_Id"=>$this->org_id));
                  $this->db->select('
                                applicants.*, 
                                job_posts.Title as Job_Title, 
                                job_posts.Location_Id, 
                                applicant_applications.Applicant_Id,
                                applicant_applications.Job_Id,
                                applicant_applications.Application_Status,
                                applicant_applications.Applied_Date,
                                locations.Name as Location_Name,  
                                countries.name as Country_Name,
                                states.name as State_Name,
                                cities.name as City_Name,
                                addedby.First_Name as Addedby_FirstName,
                                addedby.Last_Name as Addedby_LastName,
                                updatedby.First_Name as Updatedby_FirstName,
                                updatedby.Last_Name as Updatedby_LastName,
                                applicant_education.Institute,
                                applicant_education.Marks_Obtained,
                                applicant_education.Degree_Type,
                                universities.Name as Institute_Name,
                              ');

                  $this->db->from("applicants"); 
                  $this->db->join("applicant_applications","applicant_applications.Applicant_Id = applicants.Id","left"); 
                  $this->db->join("job_posts","job_posts.Id = applicant_applications.Job_Id","left"); 
                  $this->db->join("locations","locations.Id = job_posts.Location_Id","left"); 
                  $this->db->join("employees as addedby","addedby.Id = applicants.Added_By","left");
                  $this->db->join("employees as updatedby","updatedby.Id = applicants.Modified_By","left"); 
                  $this->db->join("countries","countries.id = applicants.Country","left");
                  $this->db->join("states","states.id = applicants.State","left");
                  $this->db->join("cities","cities.id = applicants.City","left");
                  $this->db->join("applicant_education","applicant_education.Applicant_Id = applicants.Id  AND applicant_education.Degree_Type='Bachelors'","left");
                  $this->db->join("universities","universities.Id = applicant_education.Institute","left");

                  $this->db->order_by("applicants.Id","DESC");  
                  $this->db->group_by("applicants.Id");
                  $total_records = $this->db->get()->num_rows();
                  








                  $this->db->where(array("applicants.Deleted" =>0, "applicants.Org_Id"=>$this->org_id));
                  $this->db->select('
                                applicants.*, 
                                job_posts.Title as Job_Title, 
                                job_posts.Location_Id, 
                                applicant_applications.Applicant_Id,
                                applicant_applications.Job_Id,
                                applicant_applications.Application_Status,
                                applicant_applications.Applied_Date,
                                locations.Name as Location_Name,  
                                countries.name as Country_Name,
                                states.name as State_Name,
                                cities.name as City_Name,
                                addedby.First_Name as Addedby_FirstName,
                                addedby.Last_Name as Addedby_LastName,
                                updatedby.First_Name as Updatedby_FirstName,
                                updatedby.Last_Name as Updatedby_LastName,
                                applicant_education.Institute,
                                applicant_education.Marks_Obtained,
                                applicant_education.Degree_Type,
                                universities.Name as Institute_Name,
                              ');

                  $this->db->from("applicants"); 
                  $this->db->join("applicant_applications","applicant_applications.Applicant_Id = applicants.Id","left"); 
                  $this->db->join("job_posts","job_posts.Id = applicant_applications.Job_Id","left"); 
                  $this->db->join("locations","locations.Id = job_posts.Location_Id","left"); 
                  $this->db->join("employees as addedby","addedby.Id = applicants.Added_By","left");
                  $this->db->join("employees as updatedby","updatedby.Id = applicants.Modified_By","left"); 
                  $this->db->join("countries","countries.id = applicants.Country","left");
                  $this->db->join("states","states.id = applicants.State","left");
                  $this->db->join("cities","cities.id = applicants.City","left");
                  $this->db->join("applicant_education","applicant_education.Applicant_Id = applicants.Id AND applicant_education.Degree_Type='Bachelors'","left");
                  $this->db->join("universities","universities.Id = applicant_education.Institute","left");

                  $this->db->order_by("applicants.Id","DESC");  
          
                  $this->db->limit("10");
                  $this->db->group_by("applicants.Id");
                  $applicants = $this->db->get();
                  if($applicants->num_rows() > 0)
                  {
                    foreach ($applicants->result() as $key => $value) 
                    { 
                      $applicant_address = $value->Country_Name.", ".$value->State_Name.", ".$value->City_Name.", ".$value->Address;

                      $src =  "assets/images/default-user.png";
                      if($value->Photo && $value->Photo != "")
                      {
                        $src =  "assets/panel/userassets/applicants/".$value->Id."/".$value->Photo;
                        if(!file_exists($src))
                        {
                           $src =  "assets/images/default-user.png";
                        } 
                      }
                ?>
                <tr id="row_<?= $value->Id; ?>">
                  <td>
                    <label class="ckbox ckbox-primary">
                      <input class="table_record_checkbox" value="<?= $value->Id; ?>" type="checkbox" id="record_<?= $value->Id; ?>" ><span></span>
                    </label>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="dropdown-toggle action-dropdown" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                      <ul class="dropdown-menu" role="menu">
                        <?php if(in_array($module_id."_view", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,'applicant_view','<?= $value->Id; ?>')"> <i class="fa fa-eye"></i> View </a></li>
                        <?php }if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_applicant_edit','<?= $value->Id; ?>')"> <i class="fa fa-pencil"></i> Edit </a></li>
                        <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                        <li><a href="javascript:;" onclick="delete_record('applicants','<?= $value->Id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i> Delete</a></li>
                        <?php } ?> 
                      </ul>
                    </div>
                  </td>
                  <td>
                      <div class="table-profile-img" >
                        <?php if(in_array($module_id."_view", $this->role_permissions)){ $view = 'onclick="open_modal_window(this,\'applicant_view\','.$value->Id.')"'; }else{ $view=""; }?>
                        <a  href="javascript:;" <?= $view; ?> >
                          <img src="<?= $src; ?>" class="pro-img">
                        </a> 
                      </div> 
                      <div class="emp-tabe-name">
                          <?= $value->First_Name." ".$value->Last_Name; ?>
                          <span class="emp-table-email"><?= $value->Email; ?></span>  
                      </div>
                  </td> 
                  <td><?= $value->Job_Title; ?>   </td> 
                  <td><?= $value->Location_Name; ?>   </td> 
                  <td><?= $value->Application_Status; ?>   </td> 
                  <td><?= $value->Degree_Type; ?></td>   
                  <td><?= $value->Institute_Name; ?></td>
                  <td><?= $value->Applied_Date; ?></td>
                  <td><?= $applicant_address; ?></td> 
                  <!-- <td><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ?></td>  -->
                  
                </tr>
                <?php }} ?>
                
              </tbody>
            </table>
          </div><!-- table-responsive -->
        </div>
        <div class="panel-heading" style="padding-top: 0;   ">
          <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12">
                <select class="form-control select2 selected_action" id="Per_Page" onchange="fetch_records('<?= base_url('admin/get_applicants/1'); ?>')"> 
                    <option selected="selected" value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option> 
                    <option value="500">500</option> 
                    <option value="1000">1000</option> 
                </select>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12">
              <button class="btn btn-warning btn-quirk" onclick="load_sidebar_view(this,'applicants');"><i class="fa fa-refresh"></i> &nbsp; Refresh</button>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
              <div style="text-align: center;">
                <h4>Total Applicants: <span id="total_records"> <?= $total_records; ?></span></h4>
              </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
              <div style="text-align: center;">
                <h4>Selected Records: <span id="total_selected_number"> 0</span></h4>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">  
              <ul class="pagination pagination-bordered" style="float: right; margin: 0px;">
                 <?php

                    //$ci =& get_instance();
                    
                    $config['base_url'] = base_url('admin/get_applicants');
                    $config['total_rows'] = $total_records;
                    $config['per_page'] = 5;
                    $config["uri_segment"] = 3;  
                    $config['num_links'] = 3;
                    $config['use_page_numbers'] = TRUE;
                    $config['reuse_query_string'] = TRUE;  
                    $config['enable_query_strings']= TRUE;
                    $config['first_url'] = base_url('admin/get_applicants'); 
                    $config['first_link'] = 'First Page';
                    $config['first_tag_open'] = '<li class="firstlink">';
                    $config['first_tag_close'] = '</li>'; 
                    $config['last_link'] = 'Last Page';
                    $config['last_tag_open'] = '<li class="lastlink">';
                    $config['last_tag_close'] = '</li>'; 
                    $config['next_link'] = 'Next Page';
                    $config['next_tag_open'] = '<li class="nextlink">';
                    $config['next_tag_close'] = '</li>'; 
                    $config['prev_link'] = 'Prev Page';
                    $config['prev_tag_open'] = '<li class="prevlink">';
                    $config['prev_tag_close'] = '</li>'; 
                    $config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
                    $config['cur_tag_close'] = '</a></li>'; 
                    $config['num_tag_open'] = '<li class="numlink">';
                    $config['num_tag_close'] = '</li>'; 

                    $this->pagination->initialize($config);

                    echo $this->pagination->create_links();

                 ?>
              </ul>
 

            </div>
          </div> 
        </div>
    </div>
  </div> 
</div>


<div class="modal bounceIn animated" id="change_application_status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        Content goes here...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div>


<script type="text/javascript">
  $('.datepicker').datepicker();
  $('.select2').select2();

function show_email_templates($this)
{ 
  if($($this).is(":checked"))
  {  
      $(".email_template").css("display","block");
      $('.select2').select2();
  }
  else
  {
      $(".email_template").css("display","none");
  }
}

function cancel_application_status_form()
{
  $("#Application_Status_Change_Form")[0].reset();
  $("#change_application_status_from").css("display","none");
  $(".email_template").css("display","none");
  $(".table-layout").css("display","none");
}

function cancel_assign_interviewers_form()
{
  $("#assign_interviewers_form_id")[0].reset();
  $("#assign_interviewers_form").css("display","none"); 
  $(".table-layout").css("display","none");
}

function cancel_add_note_form()
{
  $("#add_note_form_id")[0].reset();
  $("#add_note_form").css("display","none"); 
  $(".table-layout").css("display","none");
}

function cancel_move_applicants_form()
{
  $("#move_applicants_form_id")[0].reset();
  $("#move_applicants_form").css("display","none"); 
  $(".table-layout").css("display","none");
}


$( "#show_more_filter" ).click(function() {     
    if($('.more_filters:visible').length)
    {
        $('.more_filters').hide("slide", { direction: "top" }, 600);
    }
    else
    {
        $('.more_filters').show("slide", { direction: "bottom" }, 600); 
        $('.select2').select2();  
    }     
});
</script>