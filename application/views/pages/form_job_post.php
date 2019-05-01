
<?php
  
  $record_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("job_posts",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Job Post</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'job-posts')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id'])){ echo $record_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="job_posts">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Training Group Information</h4> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Title <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Title" id="Title" value="<?php if(isset($record_data['Title'])){ echo $record_data['Title']; } ?>" class="form-control required" placeholder="Type policy title..." required="" aria-required="true">
            </div>  
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Select Location <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Location_Id" id="Location_Id" value="<?php if(isset($record_data['Location_Id'])){ echo $record_data['Location_Id']; } ?>" class="form-control select2 required">
                <?php 
                  $this->db->order_by("Name","asc");
                  $locations = $this->db->get_where("locations",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                  if($locations->num_rows() > 0)
                  {
                    foreach ($locations->result() as $key => $value) 
                    {
                      $selected = "";
                      if(isset($record_data['Location_Id'])){ if($record_data['Location_Id'] == $value->Id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                    }
                  }
                 ?> 
              </select>
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Select Department <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select onchange="get_department_designations(this,'Designation_Id')" name="Department_Id" id="Department_Id" value="<?php if(isset($record_data['Department_Id'])){ echo $record_data['Department_Id']; } ?>" class="form-control select2 required">
                
                <?php 
                  $this->db->order_by("Name","asc");
                  $departments = $this->db->get_where("departments",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                  if($departments->num_rows() > 0)
                  {
                    foreach ($departments->result() as $key => $value) 
                    {
                      $selected = "";
                      if(isset($record_data['Department_Id'])){ if($record_data['Department_Id'] == $value->Id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                    }
                  }
                 ?> 
              </select>
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Select Designation <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Designation_Id" id="Designation_Id" value="<?php if(isset($record_data['Designation_Id'])){ echo $record_data['Designation_Id']; } ?>" class="form-control select2 required">
                <option value="0">Select Designation</option>
                <?php 
                  if(isset($record_data['Department_Id']) && $record_data['Department_Id'] > 0){
                    $this->db->order_by("Name","asc");
                    $designations = $this->db->get_where("designations",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Department_Id"=>$record_data['Department_Id']));
                    if($designations->num_rows() > 0)
                    {
                      foreach ($designations->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Designation_Id'])){ if($record_data['Designation_Id'] == $value->Id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                      }
                    }
                  }
                 ?> 
              </select>
            </div>
             <div class="col-sm-6  col-xs-12">
              <label class="control-label">Select Job Type <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Job_Type" id="Job_Type" value="<?php if(isset($record_data['Job_Type'])){ echo $record_data['Job_Type']; } ?>" class="form-control select2 required">
                <option value="Permanent" <?php if(isset($record_data['Job_Type']) && $record_data['Job_Type'] == "Permanent"){ echo 'selected="selected"'; } ?>>Permanent</option>
                <option value="Intern" <?php if(isset($record_data['Job_Type']) && $record_data['Job_Type'] == "Intern"){ echo 'selected="selected"'; } ?>>Intern</option>
                <option value="Contract" <?php if(isset($record_data['Job_Type']) && $record_data['Job_Type'] == "Contract"){ echo 'selected="selected"'; } ?>>Contract</option>
              </select>
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Start Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text"  name="Start_Date" id="Start_Date" value="<?php if(isset($record_data['Start_Date'])){ echo $record_data['Start_Date']; } ?>" class="form-control required datepicker"  required="" aria-required="true">
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">End Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text"  name="End_Date" id="End_Date" value="<?php if(isset($record_data['End_Date'])){ echo $record_data['End_Date']; } ?>" class="form-control required datepicker"  required="" aria-required="true">
            </div> 
             
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Vcancies<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Vcancies" id="Vcancies" value="<?php if(isset($record_data['Vcancies'])){ echo $record_data['Vcancies']; } ?>" class="form-control required" placeholder="Type organizer name..." required="" aria-required="true">
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <textarea id="Description" name="Description" class="form-control required" required=""><?php if(isset($record_data['Description'])){ echo $record_data['Description']; } ?></textarea> 
              <script> CKEDITOR.replace( 'Description' );</script> 
            </div> 
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Requirements <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <textarea id="Requirements" name="Requirements" class="form-control required" required=""><?php if(isset($record_data['Requirements'])){ echo $record_data['Requirements']; } ?></textarea> 
              <script> CKEDITOR.replace( 'Requirements' );</script> 
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Terms & Conditions <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <textarea id="Terms" name="Terms" class="form-control required" required=""><?php if(isset($record_data['Terms'])){ echo $record_data['Terms']; } ?></textarea> 
              <script> CKEDITOR.replace( 'Terms' );</script> 
            </div> 
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'job-posts');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_job_post')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2();
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
  //$('.timepicker').bootstrapMaterialDatePicker({ date: false });
  $('.timepicker').bootstrapMaterialDatePicker
      ({
        date: false, 
        format: 'HH:mm'
      });

</script>