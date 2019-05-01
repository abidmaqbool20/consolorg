
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

<?php 
 

  $record_data = array();
  $src =  "assets/images/default-user.png";



  $this->db->where(array("applicants.Deleted" =>0,"applicants.Org_Id"=>$this->org_id,"applicants.Id"=>$data));
  $this->db->select('
                applicants.*, 
                job_posts.Title as Job_Title, 
                 
                applicant_applications.Applicant_Id,
                applicant_applications.Job_Id,
                applicant_applications.Application_Status,
                applicant_applications.Applied_Date,
                locations.Name as Location_Name,  
                countries.name as Country_Name,
                states.name as State_Name,
                cities.name as City_Name,
                per_country.name as Per_Country_Name,
                per_states.name as Per_State_Name,
                per_cities.name as Per_City_Name,
                addedby.First_Name as Addedby_FirstName,
                addedby.Last_Name as Addedby_LastName,
                updatedby.First_Name as Updatedby_FirstName,
                updatedby.Last_Name as Updatedby_LastName,
                applicant_education.Institute,
                applicant_education.Marks_Obtained,
                applicant_education.Degree_Type,
                universities.Name as Institute_Name,
                applicant_socialmedia_account.Facebook,
                applicant_socialmedia_account.Linkedin,
                applicant_socialmedia_account.Twitter,
                applicant_socialmedia_account.Instagram,
                applicant_socialmedia_account.Youtube,
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
  $this->db->join("countries as per_country","per_country.id = applicants.P_Country","left");
  $this->db->join("states as per_states","per_states.id = applicants.P_State","left");
  $this->db->join("cities as per_cities","per_cities.id = applicants.P_City","left");
  $this->db->join("applicant_socialmedia_account","applicant_socialmedia_account.Applicant_Id = applicants.Id","left");

  $this->db->join("applicant_education","applicant_education.Applicant_Id = applicants.Id  AND applicant_education.Degree_Type='Bachelors'","left");
  $this->db->join("universities","universities.Id = applicant_education.Institute","left");

  $this->db->order_by("applicant_applications.Job_Id","DESC");  
  $this->db->group_by("applicants.Id");
  $get_record = $this->db->get();

 
  if($get_record->num_rows() > 0)
  {
    $record_data = $get_record->result_array();
    $record_data = $record_data[0];

    $pic_src =  "assets/images/default-user.png";
    if($record_data['Photo'] && $record_data['Photo'] != "")
    {
      $pic_src =  "assets/panel/userassets/applicants/".$record_data['Id']."/".$record_data['Photo'];
      if(!file_exists($src))
      {
         $pic_src =  "assets/images/default-user.png";
      } 
    }

    $resume_src = "Not Uploaded";
    if(isset($record_data['Resume']) && $record_data['Resume'] != "")
    {
      $src =  "assets/panel/userassets/applicants/".$record_data['Id']."/".$record_data['Resume'];
      if(file_exists($src))
      {
        $resume_src = $src;
      } 
    }


    $this->db->order_by("Id","DESC");
    $get_application_rec = $this->db->get_where("applicant_applications",array("Deleted"=>"0","Status"=>1,"Applicant_Id"=>$data,"Org_Id"=>$this->org_id));
    $total_applications = $get_application_rec->num_rows();
     
  }
 
 ?>

<div class="mainpanel">
  <div class="contentpanel"> 
    <div class="row tab-side-wrapper">
      <div class="col-xs-12 col-sm-12" style=" background: #ffd91e; padding: 5px; "> 
        <h2 style="color: white;">Applicant View</h2>
      </div>
      <div class=""  > 
        <div class="col-xs-4 col-sm-2 tab-left applicant_tabs">
          <div>
            <div class="profile-left-heading"> 
              <a href="<?= $pic_src; ?>" target="_blank" class="profile-photo"><img class="img-circle img-responsive" src="<?= $pic_src; ?>" alt=""></a>
              <h2 class="profile-name"><?php if(isset($record_data['First_Name'])){ echo $record_data['First_Name']." ".$record_data['Last_Name']; } ?></h2>
              <h4 class="profile-designation"><?php if(isset($record_data['Job_Title'])){ echo $record_data['Job_Title']; } ?><br>
                ( <?php if(isset($record_data['Location_Name'])){ echo $record_data['Location_Name']; } ?> )
              </h4> 
              <ul class="list-group">
                <li class="list-group-item">Apply Date <a href="javascript:;"><?php echo date("d M, Y", strtotime($record_data['Applied_Date'])); ?></a></li>
                <li class="list-group-item">Total Applications <a href="javascript:;"><?= $total_applications; ?></a></li> 
              </ul> 
              <a href="<?= $resume_src; ?>" target="_blank"><button class="btn btn-danger btn-quirk btn-block profile-btn-follow">View Resume</button></a>
            </div>
          </div> 
          <ul class="nav nav-pills nav-stacked">
            <li onclick="load_tab(this,'view_applicant_personal_info',<?= $data; ?>,'applicant_view_container')" class="active"><a href="#javascript:;"><strong>Personal Information</strong></a></li>
            <li onclick="load_tab(this,'view_applicant_education',<?= $data; ?>,'applicant_view_container')"  ><a href="#javascript:;"><strong>Education</strong></a></li>
            <li onclick="load_tab(this,'view_applicant_experience',<?= $data; ?>,'applicant_view_container')"  ><a href="#javascript:;"><strong>Experience</strong></a></li> 
            <li onclick="load_tab(this,'view_applicant_languages',<?= $data; ?>,'applicant_view_container')"  ><a href="#javascript:;"><strong>Languages</strong></a></li>
            <li onclick="load_tab(this,'view_applicant_skills',<?= $data; ?>,'applicant_view_container')"  ><a href="#javascript:;"><strong>Skills</strong></a></li>
            <li onclick="load_tab(this,'view_applicant_applicantions',<?= $data; ?>,'applicant_view_container')"  ><a href="#javascript:;"><strong>Applications</strong></a></li>
            <li onclick="load_tab(this,'view_applicant_interviews',<?= $data; ?>,'applicant_view_container')"  ><a href="#javascript:;"><strong>Applicant Intervew & Notes</strong></a></li> 
            <li onclick="load_tab(this,'view_applicant_interview_evaluation',<?= $data; ?>,'applicant_view_container')"  ><a href="#javascript:;"><strong>Interview Evaluation</strong></a></li>
          </ul>
        </div>
        <div class="col-xs-8 col-sm-10 col-xs-offset-4 col-sm-offset-2 tab-main"> 
          <div class="tab-content" style="min-height: 100vh;">
            <div class="tab-pane active" id="applicant_view_container" >
              <?php $this->load->view("pages/view_applicant_personal_info",$rec_id); ?>
            </div> 
          </div>
        </div>
      </div>
    </div>
      
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2(); 
</script>
<style type="text/css">
  .tab-pane{
    padding: 0px !important;
  }
</style>

 <style type="text/css">
      .interviewer_img{
        width: 50px;
        margin: 5px 0px 5px 5px;
        padding: 5px;
        float: left;
      }
      .interviewer_name_div{
        float: left;
        text-align: left;
        padding: 5px 0px;
        margin: 5px 0px;
      }
      .interviewer_name{
        font-size: 17px;
        color:white !important;
      }
      .interview_designation{
        color: #ecdac0;
        margin-top: 5px;
        font-size: 11px !important;
      }
      .interview-panel-heading{
        background-color: #014e77 !important;
      }
      .question{
        background-color: #fff;
        padding: 15px;
        margin: 10px 0px;
      }
      .question-statement{
        font-size: 16px;
        margin-bottom: 5px;
       color: #717171;
      }
      .question-options{
       /* margin-left: 30px;
*/
      }
    </style>