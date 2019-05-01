 
<?php
    
  

  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("applicant_applications.Org_Id"=>$this->org_id,"applicant_applications.Id"=>$edit_rec));
    $this->db->select("applicant_applications.*,locations.Name as Location_Title ");
    $this->db->from("applicant_applications"); 
    $this->db->join("locations","locations.Id = applicant_applications.Location_Id","left"); 
    $this->db->order_by("applicant_applications.Id","ASC");
    $check_record = $this->db->get();

    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0]; 
    }

  }
?>

    <div class="panel-body"> 
      <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">
            <input type="hidden" name="Applicant_Id" id="Applicant_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="applicant_applications">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             
             
            <div class="col-sm-3  col-xs-12">
              <div class="">
                <label class="control-label">Select Location <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Location_Id" id="Location_Id" onchange="get_job_posts(this,'Job_Id')" value="<?php if(isset($record_data['Location_Id'])){ echo $record_data['Location_Id']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("Name","asc");
                    $locations = $this->db->get_where("locations",array("Org_Id"=>$this->org_id,"Deleted"=>0,"Status"=>1));
                    if($locations->num_rows() > 0)
                    {
                      foreach ($locations->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Location_Id'])){ if($record_data['Location_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }
                   ?> 
                </select>
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                  <label class="control-label">Select Job <span class="text-danger">*</span><span class="text-danger error"></span></label>
                  <select name="Job_Id" id="Job_Id" value="<?php if(isset($record_data['Job_Id'])){ echo $record_data['Job_Id']; } ?>" class="form-control select2 ">
                    <?php 
                      if(isset($record_data['Job_Id']) && $record_data['Job_Id'] > 0)
                      {
                        $this->db->order_by("id","asc");
                        $job_posts = $this->db->get_where("job_posts",array("Location_Id"=>$record_data['Location_Id'], "Org_Id" => $this->org_id ));
                        if($job_posts->num_rows() > 0)
                        {
                          foreach ($job_posts->result() as $key => $value) 
                          {
                            if(isset($record_data['Job_Id'])){ if($record_data['Job_Id'] == $value->Id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                             echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Title.'</option>';
                          }
                        }
                      }
                    ?>
                    
                  </select>
              </div>
            </div> 
            
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Apply Date: <span class="error_message" style="color: #f00;">  </span></label>
                <input type="text" class="form-control datepicker" placeholder="Apply Date" name="Applied_Date" id="Applied_Date" value="<?php if(isset($record_data['Applied_Date'])){ echo $record_data['Applied_Date']; } ?>">
              </div>
            </div>
            
            <div class="col-sm-3 col-xs-12"> 
              <label class="control-label">Application Status<span class="text-danger">*</span><span class="text-danger error"></span></label> 
              <select class="form-control select2" id="Application_Status" name="Application_Status" value="<?php if(isset($record_data['Application_Status'])){ echo $record_data['Application_Status']; } ?>" > 
                  <option selected="selected" value="New" <?php if(isset($record_data['Application_Status'])){  if($record_data['Application_Status'] == "New"){ echo "selected='selected'"; } } ?>>New</option> 
                  <option value="Junk" <?php if(isset($record_data['Application_Status'])){  if($record_data['Application_Status'] == "Junk"){ echo "selected='selected'"; } } ?>>Junk</option> 
                  <option value="Shortlisted" <?php if(isset($record_data['Application_Status'])){  if($record_data['Application_Status'] == "Shortlisted"){ echo "selected='selected'"; } } ?>>Shortlisted</option> 
                  <option value="Screened"  <?php if(isset($record_data['Application_Status'])){  if($record_data['Application_Status'] == "Screened"){ echo "selected='selected'"; } } ?>>Screened</option>  
                  <option value="Interviews"  <?php if(isset($record_data['Application_Status'])){  if($record_data['Application_Status'] == "Interviews"){ echo "selected='selected'"; } } ?>>Interviews</option>  
                  <option value="Offered"  <?php if(isset($record_data['Application_Status'])){  if($record_data['Application_Status'] == "Offered"){ echo "selected='selected'"; } } ?>>Offered</option>  
                  <option value="Hired"  <?php if(isset($record_data['Application_Status'])){  if($record_data['Application_Status'] == "Hired"){ echo "selected='selected'"; } } ?>>Hired</option>  
                  <option value="Rejected"  <?php if(isset($record_data['Application_Status'])){  if($record_data['Application_Status'] == "Rejected"){ echo "selected='selected'"; } } ?>>Rejected</option>  
              </select> 
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

          $this->db->where(array("applicant_applications.Org_Id"=>$this->org_id,"applicant_applications.Deleted"=>0,"applicant_applications.Applicant_Id"=>$data));
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

              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> '.$value->Job_Title.'</h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_applicant_applicantions\','.$data.',\'applicant_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'applicant_applications\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
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
                            </div>
                        </div>
                      </div> 
                    </div>';
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
</script>