
<?php
  
  $record_data = array();

  if($data != "")
  {
    $this->db->order_by("Id","ASC");
    $check_rec = $this->db->get_where("shift_setting_details",array("Deleted"=>0,"Shift_Setting_Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_rec->num_rows() > 0)
    {
      $rec_data = $check_rec->result_array();
      $record_data = $rec_data;
    }  
  }
?>
 <style type="text/css">
   .field-head
   {
    font-size: 14px;
    font-weight: bold;
   }
   .day-field
   {
     color: #7bac1e;
     font-size: 14px;
     font-weight: bold;
   }
 </style>
     
    <form id="common_form" method="post" action="<?= base_url("admin/save_shift_settings") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data[0]['Shift_Setting_Id'])){ echo $record_data[0]['Shift_Setting_Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="shift_settings">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
       
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Shift Settings</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-3  col-xs-12">
              
              
              <?php 
                if(isset($record_data[0]['Shift_Id']))
                {

                  $shift_data = $this->db->get_where("shifts",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Id"=>$record_data[0]['Shift_Id']));
                  if($shift_data->num_rows() > 0)
                  {
                    $shift_data = $shift_data->result_array();
                    echo '<label class="form-control" style="font-size:14px; font-weight:bold;">'.$shift_data[0]['Name'].'</label>';
                    echo '<input type="hidden" class="form-control" readonly="readonly" name="Shift_Id" id="Shift_Id" value="'.$record_data[0]['Shift_Id'].'" >';
                  }
                }
                else
                { 
              ?>
                  <label class="control-label">Shifts <span class="text-danger">*</span><span class="text-danger error"></span></label>
                  
                  <select name="Shift_Id" id="Shift_Id" value="<?php if(isset($record_data['Shift_Id'])){ echo $record_data['Shift_Id']; } ?>" class="form-control select2 required">
                    <?php 
                      $this->db->order_by("Id","asc");
                      $shifts = $this->db->get_where("shifts",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                      if($shifts->num_rows() > 0)
                      {
                        foreach ($shifts->result() as $key => $value) 
                        {
                          $selected = "";
                          if(isset($record_data['Shift_Id'])){ if($record_data['Shift_Id'] == $value->Id ){ $selected = "selected='selected'"; }}
                           echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                        }
                      }
                     ?> 
                  </select>
                <?php } ?>
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-sm-2 col-xs-12">
              <label class="control-label field-head">DAY NAME </label>
            </div>
            <div class="col-sm-2 col-xs-12">
              <label class="control-label field-head">DAY TYPE </label>
            </div>
            <div class="col-sm-2 col-xs-12">
              <label class="control-label field-head">DAY STATUS </label>
            </div>
            <div class="col-sm-2 col-xs-12">
              <label class="control-label field-head">START TIME </label>
            </div>
            <div class="col-sm-2 col-xs-12">
              <label class="control-label field-head">END TIME </label>
            </div>
          </div>
          <hr>
          <div class="form-group">  
            <div class="col-sm-2 col-xs-12">
              <input type="hidden" name="Day[]" id="Day" value="<?php if(isset($record_data[0]['Day'])){ echo $record_data[0]['Day']; }else{ echo "Monday"; } ?>" >
              <label class="control-label day-field">Monday</label>
            </div>  
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Type[]" id="Day_Type" value="<?php if(isset($record_data[0]['Day_Type'])){ echo $record_data[0]['Day_Type']; } ?>" class="form-control select2 required">
                <option value="All" <?php if(isset($record_data[0]['Day_Type'])){ if($record_data[0]['Day_Type'] == "All"){ echo "selected='selected'"; }} ?>>All</option>
                <option value="Alternative"  <?php if(isset($record_data[0]['Day_Type'])){ if($record_data[0]['Day_Type'] == "Alternative"){ echo "selected='selected'"; }} ?>>Alternative</option>
              </select>
            </div>
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Status[]" id="Day_Status" value="<?php if(isset($record_data[0]['Day_Status'])){ echo $record_data[0]['Day_Status']; } ?>" class="form-control select2 required">
                <option value="ON" <?php if(isset($record_data[0]['Day_Status'])){ if($record_data[0]['Day_Status'] == "ON"){ echo "selected='selected'"; }} ?>>ON</option>
                <option value="OFF"  <?php if(isset($record_data[0]['Day_Status'])){ if($record_data[0]['Day_Status'] == "OFF"){ echo "selected='selected'"; }} ?>>OFF</option>
              </select>
            </div>
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="Start_Time[]" id="Start_Time" value="<?php if(isset($record_data[0]['Start_Time'])){ echo $record_data[0]['Start_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="End_Time[]" id="End_Time" value="<?php if(isset($record_data[0]['End_Time'])){ echo $record_data[0]['End_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
          </div>

          <div class="form-group"> 
            <div class="col-sm-2 col-xs-12">
              <input type="hidden" name="Day[]" id="Day" value="<?php if(isset($record_data[1]['Day'])){ echo $record_data[1]['Day']; }else{ echo "Tuesday"; } ?>" >
              <label class="control-label day-field">Tuesday</label>
            </div>    
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Type[]" id="Day_Type" value="<?php if(isset($record_data[1]['Day_Type'])){ echo $record_data[1]['Day_Type']; } ?>" class="form-control select2 required">
                <option value="All" <?php if(isset($record_data[1]['Day_Type'])){ if($record_data[1]['Day_Type'] == "All"){ echo "selected='selected'"; }} ?>>All</option>
                <option value="Alternative"  <?php if(isset($record_data[1]['Day_Type'])){ if($record_data[1]['Day_Type'] == "Alternative"){ echo "selected='selected'"; }} ?>>Alternative</option>
              </select>
            </div>
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Status[]" id="Day_Status" value="<?php if(isset($record_data[1]['Day_Status'])){ echo $record_data[1]['Day_Status']; } ?>" class="form-control select2 required">
                <option value="ON" <?php if(isset($record_data[1]['Day_Status'])){ if($record_data[1]['Day_Status'] == "ON"){ echo "selected='selected'"; }} ?>>ON</option>
                <option value="OFF"  <?php if(isset($record_data[1]['Day_Status'])){ if($record_data[1]['Day_Status'] == "OFF"){ echo "selected='selected'"; }} ?>>OFF</option>
              </select>
            </div>
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="Start_Time[]" id="Start_Time" value="<?php if(isset($record_data[1]['Start_Time'])){ echo $record_data[1]['Start_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="End_Time[]" id="End_Time" value="<?php if(isset($record_data[1]['End_Time'])){ echo $record_data[1]['End_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
          </div>

          <div class="form-group"> 
            <div class="col-sm-2 col-xs-12">
              <input type="hidden" name="Day[]" id="Day" value="<?php if(isset($record_data[2]['Day'])){ echo $record_data[2]['Day']; }else{ echo "Wednesday"; } ?>" >
              <label class="control-label day-field">Wednesday</label>
            </div>    
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Type[]" id="Day_Type" value="<?php if(isset($record_data[2]['Day_Type'])){ echo $record_data[2]['Day_Type']; } ?>" class="form-control select2 required">
                <option value="All" <?php if(isset($record_data[2]['Day_Type'])){ if($record_data[2]['Day_Type'] == "All"){ echo "selected='selected'"; }} ?>>All</option>
                <option value="Alternative"  <?php if(isset($record_data[2]['Day_Type'])){ if($record_data[2]['Day_Type'] == "Alternative"){ echo "selected='selected'"; }} ?>>Alternative</option>
              </select>
            </div>
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Status[]" id="Day_Status" value="<?php if(isset($record_data[2]['Day_Status'])){ echo $record_data[2]['Day_Status']; } ?>" class="form-control select2 required">
                <option value="ON" <?php if(isset($record_data[2]['Day_Status'])){ if($record_data[2]['Day_Status'] == "ON"){ echo "selected='selected'"; }} ?>>ON</option>
                <option value="OFF"  <?php if(isset($record_data[2]['Day_Status'])){ if($record_data[2]['Day_Status'] == "OFF"){ echo "selected='selected'"; }} ?>>OFF</option>
              </select>
            </div>
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="Start_Time[]" id="Start_Time" value="<?php if(isset($record_data[2]['Start_Time'])){ echo $record_data[2]['Start_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="End_Time[]" id="End_Time" value="<?php if(isset($record_data[2]['End_Time'])){ echo $record_data[2]['End_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
          </div>

          <div class="form-group"> 
            <div class="col-sm-2 col-xs-12">
              <input type="hidden" name="Day[]" id="Day" value="<?php if(isset($record_data[3]['Day'])){ echo $record_data[3]['Day']; }else{ echo "Thursday"; } ?>" >
              <label class="control-label day-field">Thursday</label>
            </div>    
            <div class="col-sm-2  col-xs-13"> 
              <select name="Day_Type[]" id="Day_Type" value="<?php if(isset($record_data[3]['Day_Type'])){ echo $record_data[3]['Day_Type']; } ?>" class="form-control select2 required">
                <option value="All" <?php if(isset($record_data[3]['Day_Type'])){ if($record_data[3]['Day_Type'] == "All"){ echo "selected='selected'"; }} ?>>All</option>
                <option value="Alternative"  <?php if(isset($record_data[3]['Day_Type'])){ if($record_data[3]['Day_Type'] == "Alternative"){ echo "selected='selected'"; }} ?>>Alternative</option>
              </select>
            </div>
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Status[]" id="Day_Status" value="<?php if(isset($record_data['Day_Status'])){ echo $record_data['Day_Status']; } ?>" class="form-control select2 required">
                <option value="ON" <?php if(isset($record_data[3]['Day_Status'])){ if($record_data[3]['Day_Status'] == "ON"){ echo "selected='selected'"; }} ?>>ON</option>
                <option value="OFF" <?php if(isset($record_data[3]['Day_Status'])){ if($record_data[3]['Day_Status'] == "OFF"){ echo "selected='selected'"; }} ?>>OFF</option>
              </select>
            </div>
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="Start_Time[]" id="Start_Time" value="<?php if(isset($record_data[3]['Start_Time'])){ echo $record_data[3]['Start_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="End_Time[]" id="End_Time" value="<?php if(isset($record_data[3]['End_Time'])){ echo $record_data[3]['End_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
          </div>

          <div class="form-group"> 
            <div class="col-sm-2 col-xs-12">
              <input type="hidden" name="Day[]" id="Day" value="<?php if(isset($record_data[4]['Day'])){ echo $record_data[4]['Day']; }else{ echo "Friday"; } ?>" >
              <label class="control-label day-field">Friday</label>
            </div>    
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Type[]" id="Day_Type" value="<?php if(isset($record_data[4]['Day_Type'])){ echo $record_data[4]['Day_Type']; } ?>" class="form-control select2 required">
                <option value="All" <?php if(isset($record_data[4]['Day_Type'])){ if($record_data[4]['Day_Type'] == "All"){ echo "selected='selected'"; }} ?>>All</option>
                <option value="Alternative"  <?php if(isset($record_data[4]['Day_Type'])){ if($record_data[4]['Day_Type'] == "Alternative"){ echo "selected='selected'"; }} ?>>Alternative</option>
              </select>
            </div>
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Status[]" id="Day_Status" value="<?php if(isset($record_data[4]['Day_Status'])){ echo $record_data[4]['Day_Status']; } ?>" class="form-control select2 required">
                <option value="ON" <?php if(isset($record_data[4]['Day_Status'])){ if($record_data[4]['Day_Status'] == "ON"){ echo "selected='selected'"; }} ?>>ON</option>
                <option value="OFF"  <?php if(isset($record_data[4]['Day_Status'])){ if($record_data[4]['Day_Status'] == "OFF"){ echo "selected='selected'"; }} ?>>OFF</option>
              </select>
            </div>
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="Start_Time[]" id="Start_Time" value="<?php if(isset($record_data[4]['Start_Time'])){ echo $record_data[4]['Start_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="End_Time[]" id="End_Time" value="<?php if(isset($record_data[4]['End_Time'])){ echo $record_data[4]['End_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
          </div>

          <div class="form-group"> 
            <div class="col-sm-2 col-xs-12">
              <input type="hidden" name="Day[]" id="Day" value="<?php if(isset($record_data[5]['Day'])){ echo $record_data[5]['Day']; }else{ echo "Saturday"; } ?>" >
              <label class="control-label day-field">Saturday</label>
            </div>    
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Type[]" id="Day_Type" value="<?php if(isset($record_data[5]['Day_Type'])){ echo $record_data[5]['Day_Type']; } ?>" class="form-control select2 required">
                <option value="All" <?php if(isset($record_data[5]['Day_Type'])){ if($record_data[5]['Day_Type'] == "All"){ echo "selected='selected'"; }} ?>>All</option>
                <option value="Alternative"  <?php if(isset($record_data[5]['Day_Type'])){ if($record_data[5]['Day_Type'] == "Alternative"){ echo "selected='selected'"; }} ?>>Alternative</option>
              </select>
            </div>
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Status[]" id="Day_Status" value="<?php if(isset($record_data['Day_Status'])){ echo $record_data['Day_Status']; } ?>" class="form-control select2 required">
                <option value="ON" <?php if(isset($record_data[5]['Day_Status'])){ if($record_data[5]['Day_Status'] == "ON"){ echo "selected='selected'"; }} ?>>ON</option>
                <option value="OFF"  <?php if(isset($record_data[5]['Day_Status'])){ if($record_data[5]['Day_Status'] == "OFF"){ echo "selected='selected'"; }} ?>>OFF</option>
              </select>
            </div>
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="Start_Time[]" id="Start_Time" value="<?php if(isset($record_data[5]['Start_Time'])){ echo $record_data[5]['Start_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="End_Time[]" id="End_Time" value="<?php if(isset($record_data[5]['End_Time'])){ echo $record_data[5]['End_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
          </div>

          <div class="form-group"> 
            <div class="col-sm-2 col-xs-12">
              <input type="hidden" name="Day[]" id="Day" value="<?php if(isset($record_data[6]['Day'])){ echo $record_data[6]['Day']; }else{ echo "Sunday"; } ?>" >
              <label class="control-label day-field">Sunday</label>
            </div>    
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Type[]" id="Day_Type" value="<?php if(isset($record_data[6]['Day_Type'])){ echo $record_data[6]['Day_Type']; } ?>" class="form-control select2 required">
                <option value="All" <?php if(isset($record_data[6]['Day_Type'])){ if($record_data[6]['Day_Type'] == "All"){ echo "selected='selected'"; }} ?>>All</option>
                <option value="Alternative"  <?php if(isset($record_data[6]['Day_Type'])){ if($record_data[6]['Day_Type'] == "Alternative"){ echo "selected='selected'"; }} ?>>Alternative</option>
              </select>
            </div>
            <div class="col-sm-2  col-xs-12"> 
              <select name="Day_Status[]" id="Day_Status" value="<?php if(isset($record_data['Day_Status'])){ echo $record_data['Day_Status']; } ?>" class="form-control select2 required">
                <option value="ON" <?php if(isset($record_data[6]['Day_Status'])){ if($record_data[6]['Day_Status'] == "ON"){ echo "selected='selected'"; }} ?>>ON</option>
                <option value="OFF"  <?php if(isset($record_data[6]['Day_Status'])){ if($record_data[6]['Day_Status'] == "OFF"){ echo "selected='selected'"; }} ?>>OFF</option>
              </select>
            </div>
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="Start_Time[]" id="Start_Time" value="<?php if(isset($record_data[6]['Start_Time'])){ echo $record_data[6]['Start_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
            <div class="col-sm-2 col-xs-12"> 
              <input type="text" name="End_Time[]" id="End_Time" value="<?php if(isset($record_data[6]['End_Time'])){ echo $record_data[6]['End_Time']; }else{ echo "00:00:00"; } ?>" class="form-control required"  required=""  >
            </div> 
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;" onclick="load_tab(this,'shift_settings',<?= 0; ?>,'shifts_tabs_body')" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;" onclick="load_tab(this,'form_shift_settings',<?= 0; ?>,'shifts_tabs_body')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
   

<script type="text/javascript">
  $('.select2').select2();
</script>