
<?php
  
  $group_data = array();
  if($data != "")
  {
    $check_group = $this->db->get_where("training_groups",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_group->num_rows() > 0)
    {
      $group_data = $check_group->result_array();
      $group_data = $group_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Training Group</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'training_groups_table')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($group_data['Id'])){ echo $group_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="training_groups">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Training Group Information</h4> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Group Title <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Title" id="Title" value="<?php if(isset($group_data['Title'])){ echo $group_data['Title']; } ?>" class="form-control required" placeholder="Type policy title..." required="" aria-required="true">
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Organizer Name<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Organizer_Name" id="Organizer_Name" value="<?php if(isset($group_data['Organizer_Name'])){ echo $group_data['Organizer_Name']; } ?>" class="form-control required" placeholder="Type organizer name..." required="" aria-required="true">
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Select Trainer <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Trainer_Id" id="Trainer_Id" value="<?php if(isset($group_data['Trainer_Id'])){ echo $group_data['Trainer_Id']; } ?>" class="form-control select2 required">
                <?php 
                  $this->db->order_by("First_Name","asc");
                  $employees = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                  if($employees->num_rows() > 0)
                  {
                    foreach ($employees->result() as $key => $value) 
                    {
                      if(isset($group_data['Trainer_Id'])){ if($group_data['Trainer_Id'] == $value->Id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.'</option>';
                    }
                  }
                 ?> 
              </select>
            </div>
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Select Location <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Location_Id" id="Location_Id" value="<?php if(isset($group_data['Location_Id'])){ echo $group_data['Location_Id']; } ?>" class="form-control select2 required">
                <?php 
                  $this->db->order_by("Name","asc");
                  $locations = $this->db->get_where("locations",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                  if($locations->num_rows() > 0)
                  {
                    foreach ($locations->result() as $key => $value) 
                    {
                      if(isset($group_data['Location_Id'])){ if($group_data['Location_Id'] == $value->Id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                    }
                  }
                 ?> 
              </select>
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Start Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text"  name="Start_Date" id="Start_Date" value="<?php if(isset($group_data['Start_Date'])){ echo $group_data['Start_Date']; } ?>" class="form-control required datepicker"  required="" aria-required="true">
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">End Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text"  name="End_Date" id="End_Date" value="<?php if(isset($group_data['End_Date'])){ echo $group_data['End_Date']; } ?>" class="form-control required datepicker"  required="" aria-required="true">
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Start Time<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text"  name="Start_Time" id="Start_Time" value="<?php if(isset($group_data['Start_Time'])){ echo $group_data['Start_Time']; } ?>" class="form-control required timepicker"  required="" aria-required="true">
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">End Time<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text"  name="End_Time" id="End_Time" value="<?php if(isset($group_data['End_Time'])){ echo $group_data['End_Time']; } ?>" class="form-control required timepicker"  required="" aria-required="true">
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Course Covered<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Course_Covered" id="Course_Covered" value="<?php if(isset($group_data['Course_Covered'])){ echo $group_data['Course_Covered']; } ?>" class="form-control required" placeholder="Type organizer name..." required="" aria-required="true">
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <textarea id="Description" name="Description" class="form-control required" required=""><?php if(isset($group_data['Description'])){ echo $group_data['Description']; } ?></textarea> 
              <script> CKEDITOR.replace( 'Description' );</script> 
            </div> 
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'training_groups_table');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_training_group')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
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