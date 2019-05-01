
<?php 
  $rec_id['data'] = $data;
  $record_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("applicants",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
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
            <h3><i class="fa fa-user"></i> Update Applicant</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'applicants')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row tab-side-wrapper">
        <div class="col-xs-4 col-sm-2 tab-left applicant_tabs">
          <!-- Nav tabs -->
          <ul class="nav nav-pills nav-stacked">
            <li onclick="load_tab(this,'form_applicant_personal_info',<?= $data; ?>,'applicant_from_container')" class="active"><a href="#javascript:;"><strong>Personal Information</strong></a></li>
            <li onclick="load_tab(this,'form_applicant_education',<?= $data; ?>,'applicant_from_container')"  ><a href="#javascript:;"><strong>Education</strong></a></li>
            <li onclick="load_tab(this,'form_applicant_experience',<?= $data; ?>,'applicant_from_container')"  ><a href="#javascript:;"><strong>Experience</strong></a></li> 
            <li onclick="load_tab(this,'form_applicant_languages',<?= $data; ?>,'applicant_from_container')"  ><a href="#javascript:;"><strong>Languages</strong></a></li>
            <li onclick="load_tab(this,'form_applicant_skills',<?= $data; ?>,'applicant_from_container')"  ><a href="#javascript:;"><strong>Skills</strong></a></li>
            <li onclick="load_tab(this,'form_applicant_socialmedia_account',<?= $data; ?>,'applicant_from_container')"  ><a href="#javascript:;"><strong>Social Media Account</strong></a></li> 
            <li onclick="load_tab(this,'form_applicant_applicantions',<?= $data; ?>,'applicant_from_container')"  ><a href="#javascript:;"><strong>Applications</strong></a></li>
            <li onclick="load_tab(this,'form_applicant_interviews',<?= $data; ?>,'applicant_from_container')"  ><a href="#javascript:;"><strong>Applicant Intervew & Notes</strong></a></li>
            
            
            
          </ul>
        </div>
        <div class="col-xs-8 col-sm-10 col-xs-offset-4 col-sm-offset-2 tab-main"> 
          <div class="tab-content" style="min-height: 100vh;">
            <div class="tab-pane active" id="applicant_from_container">
              <?php $this->load->view("pages/form_applicant_personal_info",$rec_id); ?>
            </div> 
          </div>
        </div>
      </div>
      
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2(); 
</script>