
<?php 
  $rec_id['data'] = $data;
  $record_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("employees",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0];
      $rec_id['data'] = $record_data['Id'];
    } 
  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save Employee</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'employees_table')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row tab-side-wrapper">
        <div class="col-xs-4 col-sm-2 tab-left employee_tabs">
          <!-- Nav tabs -->
          <ul class="nav nav-pills nav-stacked">
            <li onclick="load_tab(this,'form_employee_personal_info',<?= $data; ?>,'employee_from_container')" class="active"><a href="#javascript:;"><strong>Personal Information</strong></a></li>
            <li onclick="load_tab(this,'form_employee_education',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Education</strong></a></li>
            <li onclick="load_tab(this,'form_employee_experience',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Experience History</strong></a></li> 
            <li onclick="load_tab(this,'form_employee_languages',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Languages</strong></a></li>
            <li onclick="load_tab(this,'form_employee_skills',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Skills</strong></a></li>
            <li onclick="load_tab(this,'form_employee_assets',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Assets</strong></a></li>
            <li onclick="load_tab(this,'form_employee_benefit',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Benefits</strong></a></li>
            <li onclick="load_tab(this,'form_employee_trainings',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Trainings</strong></a></li>
            <li onclick="load_tab(this,'form_employee_training_feedback',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Training Feedback</strong></a></li>
            <li onclick="load_tab(this,'form_employee_work_info',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Work Record</strong></a></li>
            <li onclick="load_tab(this,'form_employee_salary_record',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Salary History</strong></a></li> 
            <li onclick="load_tab(this,'form_employee_report_to',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Report To</strong></a></li>  
            <li onclick="load_tab(this,'form_employee_daily_report',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Daily Reports</strong></a></li>
            <li onclick="load_tab(this,'form_employee_activities',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Activities</strong></a></li>
            <li onclick="load_tab(this,'form_employee_documents',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Documents</strong></a></li> 
            <li onclick="load_tab(this,'employee_application_history',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Application History</strong></a></li>  
             <li onclick="load_tab(this,'form_employee_exit_interview',<?= $data; ?>,'employee_from_container')"  ><a href="#javascript:;"><strong>Exit Details</strong></a></li>
            
          </ul>
        </div>
        <div class="col-xs-8 col-sm-10 col-xs-offset-4 col-sm-offset-2 tab-main"> 
          <div class="tab-content" style="min-height: 100vh;">
            <div class="tab-pane active" id="employee_from_container">
              <?php $this->load->view("pages/form_employee_personal_info",$rec_id); ?>
            </div>
            
          </div>
        </div>
      </div>
      
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2(); 
</script>