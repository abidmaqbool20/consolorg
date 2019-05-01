<?php
 
  $record_data = array();
  if($data != "")
  {
    $this->db->where(array("employee_activities.Org_Id"=>$this->org_id,"employee_activities.Id"=>$data));
    $this->db->select("employee_activities.*  ");
    $this->db->from("employee_activities");  
    $this->db->order_by("employee_activities.Id","ASC");
    $check_record = $this->db->get();

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
            <h3><i class="fa fa-user"></i> Save / Update Employee Activity</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'manage_activities')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <div class="panel">
      <div class="panel-body">
        <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
          <div class="error"></div>
          <div class="form-group">
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">
            <input type="hidden" name="Employee_Id" id="Employee_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_activities">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
            <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12">
              <label class="control-label">Select Employee<span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select class="form-control select2 required" name="Employee_Id" id="Employee_Id" >
                <?php 

                        $this->db->order_by("First_Name","ASC");
                        $this->db->select("First_Name,Last_Name,Id","asc");
                        $employees = $this->db->get_where('employees',array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Status"=>"Active"));
                        if($employees->num_rows() > 0)
                        {
                          foreach ($employees->result() as $key => $value)
                          {
                            if($key == 0){ $first_employee_id = $value->Id; }
                            echo '<option value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.'</option>';
                          }
                        }

                    ?>
              </select>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12">
              <div class="">
                <label class="control-label">Select Activity Type <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Activity_Type" id="Activity_Type" value="<?php if(isset($record_data['Activity_Type'])){ echo $record_data['Activity_Type']; } ?>" class="form-control select2 ">
                  <?php 
                      $this->db->order_by("Name","asc");
                      $activity_types = $this->db->get_where("activity_types",array("Org_Id"=>$this->org_id));
                      if($activity_types->num_rows() > 0)
                      {
                        foreach ($activity_types->result() as $key => $value) 
                        {
                          $selected = "";
                          if(isset($record_data['Activity_Type'])){ if($record_data['Activity_Type'] == $value->Id ){ $selected = "selected='selected'"; } }
                           echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                        }
                      }
                     ?>
                </select>
              </div>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-2 ">
              <div class="">
                <label class="control-label">Activity Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datepicker required" placeholder="Activity Date" name="Activity_Date" id="Activity_Date" value="<?php if(isset($record_data['Activity_Date'])){ echo $record_data['Activity_Date']; } ?>">
              </div>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-2  col-xs-12">
              <div class="">
                <label class="control-label">From Time <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Start Time" name="Start_Time" id="Start_Time" value="<?php if(isset($record_data['Start_Time'])){ echo $record_data['Start_Time']; }else{ echo date("Y-m-d")." 00:00:00"; } ?>">
              </div>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-2  col-xs-12">
              <div class="">
                <label class="control-label">To Time <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="End Time" name="End_Time" id="End_Time" value="<?php if(isset($record_data['End_Time'])){ echo $record_data['End_Time']; }else{ echo date("Y-m-d")." 00:00:00"; } ?>">
              </div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
              <div class="">
                <label class="control-label">Activity <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Activity" id="Activity" rows="6" class="form-control" ><?php if(isset($record_data['Activity'])){ echo $record_data['Activity']; } ?>
</textarea>
                <script> //CKEDITOR.replace( 'Activity' );</script> 
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12"> <br>
              <button type="submit" id="" class="btn btn-success pull-left"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button>
              <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'manage_activities');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
              <button  style="float: right;"  onclick="load_view(this,'form_employee_activity')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' }); 
  $('.datetimepicker').bootstrapMaterialDatePicker
      ({
        date: true,  
        format: 'YYYY-MM-DD HH:mm'
      });
  // $('.timepicker').bootstrapMaterialDatePicker
  //     ({
  //       date: false,  
  //       format: 'HH:mm'
  //     });
</script>