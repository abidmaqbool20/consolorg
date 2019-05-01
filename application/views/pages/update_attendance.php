 
 
    <?php
 
        $record_data = array();

        if($edit_rec && $edit_rec != "")
        {
          $this->db->where(array("attendance.Org_Id"=>$this->org_id,"attendance.Id"=>$edit_rec,"attendance.Employee_Id"=>$data));
          $this->db->select("attendance.*  ");
          $this->db->from("attendance");  
          $this->db->order_by("attendance.Id","ASC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="attendance">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             <div class="col-sm-12  col-xs-12">
              <div class="">
                <label class="control-label">Select Employee <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Employee_Id" id="Employee_Id"  class="form-control select2 " value="<?php if(isset($record_data['Employee_Id']) && $record_data['Employee_Id'] != "" ){ echo $record_data['Employee_Id']; } ?>">
                   <?php 
                     $employees = $this->CI->get_employees_list();
                     $dep_id = 0;
                     $total_records = sizeof($employees);
                     foreach ($employees as $key => $value) {
                        if($dep_id == 0  ){
                          $dep_id = $value->Dep_Id;
                          echo '<optgroup label="'.$value->Department_Name.'">';
                        }

                        if($dep_id != $value->Dep_Id){
                          $dep_id = $value->Dep_Id; 
                          echo '</optgroup><optgroup label="'.$value->Department_Name.'">';
                        }

                        $employee_unique_id =  $value->Joining_Date != "0000-00-00" ? employee_unique_id($value->Joining_Date,$value->Id) : 'Not Joined';
                        
                        $selected = "";
                        if(isset($record_data['Employee_Id'])){ if($record_data['Employee_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                        if($data){ if($data === $value->Id ){ $selected = "selected='selected'"; } }
                        echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.' ( '.$employee_unique_id.' )</option>'; 
                        if($total_records == $key+1){ echo '</optgroup>'; }
                     }
                   ?> 
                  
                </select>
              </div>
            </div>
             
            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Attendance Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" name="Date" id="Date" class="form-control datepicker"  value="<?php if(isset($record_data['Date']) && $record_data['Date'] != "" ){ echo $record_data['Date']; }else{ echo date("Y-m-d"); } ?>"  >  
              </div>
            </div> 
            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Sign In Time<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" name="Signin" id="Signin" class="form-control " value="<?php  if(isset($record_data['Signin']) && $record_data['Signin'] != "" ){ echo $record_data['Signin']; }else{ echo date("Y-m-d H:i:s"); } ?>"  >  
              </div>
            </div> 
            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Sign Out Time<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" name="Signout" id="Signout" class="form-control "  value="<?php if(isset($record_data['Signout']) && $record_data['Signout'] != "" ){ echo $record_data['Signout']; }else{ echo "0000-00-00 00:00:00"; } ?>"  >  
              </div>
            </div> 
            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Time Bonus (Hours:Minutes) <span class="text-danger error"></span></label>
                <label class="control-label" style="color: brown;">** This time will be counted as Employee working time. <span class="text-danger error"></span></label>
                <input type="text" name="Time_Bonus" id="Time_Bonus" class="form-control "  value="<?php if(isset($record_data['Time_Bonus']) && $record_data['Time_Bonus'] != "" ){ echo $record_data['Time_Bonus']; }else{ echo "00:00"; } ?>"  >  
              </div>
            </div> 
        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-left btn-lg"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
            <button type="button" style="margin-left: 10px;"  onclick="load_tab(this,'update_attendance',<?= 1; ?>,'attendance_tabs_body')" class="btn btn-warning pull-left btn-lg"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Reset </button> 
          </div>
        </div>
      </form>
      <br><br>
       
    </div> 
      
         

 


<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>