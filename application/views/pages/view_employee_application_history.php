 <style type="text/css">
   .interviewer_form
   {
     display: none;
   }
   .interview_table_head
   {
    width: 200px;
    min-width: 200px;
   }
   .notes_form
   {
      display: none;
   }
 </style>
<?php
     

  $record_data = array();
  if($edit_rec != "")
  { 
    $check_record = $this->db->get_where("applicant_interviews",array("Org_Id"=>$this->org_id,"Deleted"=>0,"Status"=>1,"Id"=>$edit_rec));
 
    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0]; 
     
     
 ?>

  <style type="text/css">

   .interviewer_form
   {
     display: block;
   }

 </style>


<?php  }} ?>

    <div class="panel-body"> 
      
 
      <div class="form-group">
        <div class="row">
        <?php

          $employee_cnic = "";
          $this->db->select("CNIC");
          $employee_data = $this->db->get_where("employees",array("Id"=>$data,"Org_Id"=>$this->org_id,"Deleted"=>0,"Employee_Status"=>"Active"));
          if($employee_data->num_rows() > 0)
          {
            $employee_data = $employee_data->result_array();
            $employee_cnic = $employee_data[0]['CNIC'];
          }

          if($employee_cnic != "")
          { 
            $applicant_id = 0;
            $this->db->select("Id");
            $applicant_data = $this->db->get_where("applicants",array("CNIC"=>$employee_cnic,"Deleted"=>0,"Status"=>1));
            if($applicant_data->num_rows() > 0)
            {
              $applicant_data = $applicant_data->result_array();
              $applicant_id = $applicant_data[0]['Id'];
            }

            $this->db->where(array("applicant_applications.Org_Id"=>$this->org_id,"applicant_applications.Deleted"=>0,"applicant_applications.Applicant_Id"=>$applicant_id));
            $this->db->select("applicant_applications.*,locations.Name as Location_Title,  job_posts.Title as Job_Title ");
            $this->db->from("applicant_applications"); 
            $this->db->join("locations","locations.Id = applicant_applications.Location_Id","left"); 
            $this->db->join("job_posts","job_posts.Id = applicant_applications.Job_Id","left"); 
            $this->db->order_by("applicant_applications.Id","DESC");
            $applicant_applications = $this->db->get();

            if($applicant_applications->num_rows() > 0)
            {  
              foreach ($applicant_applications->result() as $key => $value){ 

              // $document = "Not Uploaded";
              // if($value->Document && $value->Document != "")
              // {
              //   $src =  "assets/panel/userassets/applicant_applications/".$value->Id."/".$value->Document;
              //   if(file_exists($src))
              //   {
              //     $document = "<div><a target='_blank' href='".$src."'><i class='fa fa-file'></i> View Document</a></div>";
              //   } 
              // }

              echo '<div class="col-md-12 col-sm-12">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> '.$value->Job_Title.'</h3>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                                  <tr> 
                                    <th>Post Title</th>
                                    <td>'.$value->Job_Title.'</td>
                                    <th>Location</th>
                                    <td>'.$value->Location_Title.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Apply Date</th>
                                    <td>'.$value->Applied_Date.'</td>
                                    <th>Application Status</th>
                                    <td>'.$value->Application_Status.'</td>
                                  </tr>
                                  
                              </table>
                            </div>';

                            $this->db->where(array("applicant_interviews.Org_Id"=>$this->org_id,"applicant_interviews.Deleted"=>0,"applicant_interviews.Applicant_Id"=>$data,"applicant_interviews.Job_Id"=>$value->Job_Id,"applicant_interviews.Application_Id"=>$value->Id));
                            $this->db->select("applicant_interviews.*,employees.First_Name,employees.Last_Name");
                            $this->db->from("applicant_interviews"); 
                            $this->db->join("employees","employees.Id = applicant_interviews.Interviewer","left");  
                            $this->db->order_by("applicant_interviews.Id","DESC");
                            $applicant_interviews = $this->db->get();

                            if($applicant_interviews->num_rows() > 0)
                            {
                              foreach ($applicant_interviews->result() as $index => $interview_data) {
                                 
                                  echo '<div class="table-responsive" style="margin:10px 0px;" id="row_'.$interview_data->Id.'">
                                          <table class="table table-bordered table-primary nomargin"> 
                                               
                                              <tr>  
                                                <th class="interview_table_head">Interviewer Name</th>
                                                <td >'.$interview_data->First_Name.' '.$interview_data->Last_Name.'</td>
                                              </tr>
                                              <tr>  
                                                <th class="interview_table_head">Interview Points</th>
                                                <td>'.$interview_data->Points.'</td>
                                              </tr>
                                              <tr>  
                                                <th class="interview_table_head">Interviewer Reviews</th>
                                                <td>'.$interview_data->Reviews.'</td>
                                              </tr>
                                              <tr>   
                                                <th class="interview_table_head">Interview Date</th>
                                                <td>'.$interview_data->Interview_Date.'</td>
                                              </tr>
                                              
                                          </table>
                                        </div>';


                              }
                            }
                            else
                            {
                              echo '<div class="table-responsive" style="margin:10px 0px;text-align: center;"> 
                                      <div class="col-md-12"><h3>No Interview Conducted</h3></div> 
                                    </div>';
                            }


                            $this->db->where(array("application_notes.Org_Id"=>$this->org_id,"application_notes.Deleted"=>0,"application_notes.Applicant_Id"=>$data,"application_notes.Job_Id"=>$value->Job_Id,"application_notes.Application_Id"=>$value->Id));
                            $this->db->select("application_notes.* ");
                            $this->db->from("application_notes");  
                            $this->db->order_by("application_notes.Id","DESC");
                            $application_notes = $this->db->get();

                            if($application_notes->num_rows() > 0)
                            {
                              foreach ($application_notes->result() as $counter => $notes) {

                               echo '<div class="table-responsive" style="margin:10px 0px;" id="row_'.$notes->Id.'">
                                      <div class="alert alert-info">  
                                        <p>'.$notes->Notes.'</p>
                                      </div>
                                     </div>';
                              }
                            }
                            else
                            {
                              echo '<div class="table-responsive" style="margin:10px 0px;text-align: center;"> 
                                      <div class="col-md-12"><h3>No Notes Added</h3></div> 
                                    </div>';
                            }
 

                   echo '     </div> </div>  </div>';
              }
            }
            else
            {
              echo no_record_found(); 
            }
          }
          else
          {
            echo no_record_found(); 
          }

        ?>
        </div>
      </div>
    </div> 
      
         

 


<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });


  function add_interview($job_id,$application_id)
  {
    $(".interviewer_form").css("display","block");
    $('.select2').select2();  
    $(".interviewer_form").find("#Job_Id").val($job_id);
    $(".interviewer_form").find("#Application_Id").val($application_id);
  }

  function add_notes($job_id,$application_id)
  {
    $(".notes_form").css("display","block");
    $('.select2').select2(); 
    $(".notes_form").find("#Job_Id").val($job_id);
    $(".notes_form").find("#Application_Id").val($application_id);
  
  }
</script>