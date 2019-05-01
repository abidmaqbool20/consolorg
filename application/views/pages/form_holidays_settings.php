 
<?php
     
      $record_data = array();
  
      $check_record = $this->db->get_where("holiday_period",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id )); 
      if($check_record->num_rows() > 0)
      {
        $record_data = $check_record->result_array(); 
        $record_data = $record_data[0];
      }
     

   
?>

 

    <div class="panel-body"> 
      <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>"> 
            <input type="hidden" name="Table_Name" id="Table_Name" value="holiday_period">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-12 col-xs-12 ">
              <h3>Leaves Settings</h3>
              <hr>
              <div class="form-group">
                <label class="col-lg-1 col-md-1 col-sm-2 col-xs-12 control-label" style="text-align: center;">Add </label>
                <div class="col-lg-1 col-md-1 col-sm-4 col-xs-12">
                  <input type="text" name="Holidays" id="Holidays" class="form-control" placeholder="Holidays" value="<?php if(isset($record_data['Holidays'])){ echo $record_data['Holidays']; } ?>"  >
                </div>
                <label class="col-lg-2 col-md-2 col-sm-6 col-xs-12 control-label" style="text-align: center;">Leaves for the employees of </label>
                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12">
                  <select name="Location_Id" id="Location_Id" onchange="set_shift()" required="required" value="<?php if(isset($record_data['Location_Id'])){ echo $record_data['Location_Id']; } ?>" class="form-control select2 required ">
                      <option value="0">Select Location</option>
                      <?php  
                        $this->db->order_by("Name","asc");
                        $locations = $this->db->get_where("locations",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($locations->num_rows() > 0)
                        {
                          foreach ($locations->result() as $key => $value) 
                          {
                            $selected = "";
                            if(isset($record_data[0]['Location_Id'])){ if($record_data[0]['Location_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                             echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                          }
                        }

                       ?>
                      
                  </select>
                </div> 
                <label class="col-lg-1 col-md-1 col-sm-2 col-xs-12 control-label" style="text-align: center;"> OR </label>
                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12" > 

                    <select name="Shift_Id" id="Shift_Id" onchange="set_location()" required="required" value="<?php if(isset($record_data['Shift_Id'])){ echo $record_data['Shift_Id']; } ?>" class="form-control select2 required ">
                        <option value="0">Select Shift</option>
                        <?php  
                          $this->db->order_by("Name","asc");
                          $shifts = $this->db->get_where("shifts",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                          if($shifts->num_rows() > 0)
                          {
                            foreach ($shifts->result() as $key => $value) 
                            {
                              $selected = "";
                              if(isset($record_data['Shift_Id'])){ if($record_data['Shift_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                               echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                            }
                          }

                         ?>
                        
                    </select>
                </div>
              </div>
               
              <div class="form-group">
                <label class="col-lg-1 col-md-1 col-sm-2 col-xs-12 control-label" style="text-align: center;">After every </label>
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                  <input type="text" name="Days_Period" id="Days_Period" class="form-control" placeholder="Days" value="<?php if(isset($record_data['Days_Period'])){ echo $record_data['Days_Period']; } ?>"  >
                </div>
                <label class="col-lg-1 col-md-1 col-sm-2 col-xs-12 control-label" style="text-align: center;">Days. </label>
              </div>
            </div>
            
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <div class="col-sm-12">
              <br><br>
              <button  type="submit" id="" class="btn btn-success pull-left btn-lg"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
              <button  onclick="load_tab(this,'form_holidays_settings','','settings_from_container')" type="button" id="" style="margin-left: 10px;" class="btn btn-warning pull-left btn-lg"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Reset </button> 
            </div>
          </div>
        </div>
      </form>
      <div class="col-sm-12 col-xs-12 "><br><br>
          <h3>Leave Quota Settings</h3> <hr>
          <div class="form-group">
              <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                <label><h4> Remaining leaves of current year should be automatically added into the leave quota of next year.</h4>  </label>
              </div> 
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"> 
                <label class="switch">
                  <input type="checkbox" <?php if(isset($record_data['Add_Remaining_Leaves_Into_Next_Year']) && $record_data['Add_Remaining_Leaves_Into_Next_Year'] == "Yes"){ echo "checked='checked'"; } ?>  onchange="change_annual_leave_quota_setting(this)"  >
                  <span class="slider"></span>
                </label>
            </div>
          </div>   
      </div> 
       
<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

  function set_shift(){
    $("#Shift_Id option[value=0]").attr("selected","selected");
    $("#Shift_Id").val("0");
    $('#Shift_Id').select2(); 
  }

  function set_location(){
    $("#Location_Id option[value=0]").attr("selected","selected");
    $("#Location_Id").val("0");
    $('#Location_Id').select2(); 
  }
</script>