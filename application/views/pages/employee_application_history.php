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
      <form id="common_form"  method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal interviewer_form" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">
            <input type="hidden" name="Applicant_Id" id="Applicant_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="applicant_interviews">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">  
            <input type="hidden" name="Job_Id" id="Job_Id" value="<?php if(isset($record_data['Job_Id'])){ echo $record_data['Job_Id']; } ?>" >  
            <input type="hidden" name="Application_Id" id="Application_Id" value="<?php if(isset($record_data['Application_Id'])){ echo $record_data['Application_Id']; } ?>" >  
             
            <div class="col-sm-6  col-xs-12">
              <div class="">
                <label class="control-label">Select Interviewer <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Interviewer" id="Interviewer" value="<?php if(isset($record_data['Interviewer'])){ echo $record_data['Interviewer']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("First_Name","asc");
                    $this->db->select("First_Name,Last_Name,Id");
                    $employees = $this->db->get_where("employees",array("Org_Id"=>$this->org_id,"Deleted"=>0,"Status"=>1,"Employee_Status"=>"Active"));
                    if($employees->num_rows() > 0)
                    {
                      foreach ($employees->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Interviewer'])){ if($record_data['Interviewer'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.'</option>';
                      }
                    }
                   ?> 
                </select>
              </div>
            </div> 
            
            <div class="col-sm-6 col-xs-12"> 
              <label class="control-label">Applicant Points<span class="text-danger">*</span><span class="text-danger error"></span></label> 
              <select class="form-control select2" id="Points" name="Points" value="<?php if(isset($record_data['Points'])){ echo $record_data['Points']; } ?>" > 
                  <option selected="selected" value="0" <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "0"){ echo "selected='selected'"; } } ?>>0</option> 
                  <option value="1" <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "1"){ echo "selected='selected'"; } } ?>>1</option> 
                  <option value="2" <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "2"){ echo "selected='selected'"; } } ?>>2</option> 
                  <option value="3"  <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "3"){ echo "selected='selected'"; } } ?>>3</option>  
                  <option value="4"  <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "4"){ echo "selected='selected'"; } } ?>>4</option>  
                  <option value="5"  <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "5"){ echo "selected='selected'"; } } ?>>5</option>  
                  <option value="6"  <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "6"){ echo "selected='selected'"; } } ?>>6</option>  
                  <option value="7"  <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "7"){ echo "selected='selected'"; } } ?>>7</option>  
                  <option value="8"  <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "8"){ echo "selected='selected'"; } } ?>>8</option>  
                  <option value="9"  <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "9"){ echo "selected='selected'"; } } ?>>9</option>  
                  <option value="10"  <?php if(isset($record_data['Points'])){  if($record_data['Points'] == "10"){ echo "selected='selected'"; } } ?>>10</option>  
              </select> 
            </div>
            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Reviews: <span class="error_message" style="color: #f00;">  </span></label>
                <textarea class="form-control"  name="Reviews" id="Reviews" ><?php if(isset($record_data['Reviews'])){ echo $record_data['Reviews']; } ?></textarea>
              </div>
            </div>
                  
        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-right"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
          </div>
        </div>
      </form>

       <form id="common_form"  method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal notes_form" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">
            <input type="hidden" name="Applicant_Id" id="Applicant_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="application_notes">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">  
            <input type="hidden" name="Job_Id" id="Job_Id" value="<?php if(isset($record_data['Job_Id'])){ echo $record_data['Job_Id']; } ?>" >  
            <input type="hidden" name="Application_Id" id="Application_Id" value="<?php if(isset($record_data['Application_Id'])){ echo $record_data['Application_Id']; } ?>" >  
              
            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Notes: <span class="error_message" style="color: #f00;">  </span></label>
                <textarea class="form-control"  name="Notes" id="Notes" ><?php if(isset($record_data['Notes'])){ echo $record_data['Notes']; } ?></textarea>
              </div>
            </div>
                  
        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-right"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
          </div>
        </div>
      </form>
      <br><br>

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