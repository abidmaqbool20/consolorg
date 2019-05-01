  
    <?php
 
        $record_data = array();

        if($edit_rec && $edit_rec != "")
        {
          $this->db->where(array("employee_leaves.Org_Id"=>$this->org_id,"employee_leaves.Id"=>$edit_rec,"employee_leaves.Employee_Id"=>$data));
          $this->db->select("employee_leaves.*  ");
          $this->db->from("employee_leaves");  
          $this->db->order_by("employee_leaves.Id","ASC");
          $check_record = $this->db->get();

          if($check_record->num_rows() > 0)
          {
            $record_data = $check_record->result_array();
            $record_data = $record_data[0]; 
          }
        }

    ?>

    <div class="panel-body">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-md-12 col-sm-12 col-xs-12">
          <h2>Add / Update Leave</h2> <hr>
        </div>
      </div> 
    </div> 
      <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group"> 
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">  
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_leaves">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             <div class="col-sm-12  col-xs-12">
              <div class="">
                <label class="control-label">Select Employee <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Employee_Id" id="Employee_Id" onchange="get_employee_leaves(this)"  class="form-control select2 ">
                  <option value="0">Select Employee</option>
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
                         
                        if(isset($record_data['Employee_Id'])){ if($record_data['Employee_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                        echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.' ( '.$employee_unique_id.' )</option>'; 
                        if($total_records == $key+1){ echo '</optgroup>'; }
                     }
                   ?> 
                  
                </select>
              </div>
              <hr>
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Paid Leaves<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="number" min="0" max="100" name="Paid_Leaves" id="Paid_Leaves" class="form-control"  value="<?php if(isset($record_data['Paid_Leaves']) && $record_data['Paid_Leaves'] != "" ){ echo $record_data['Paid_Leaves']; } ?>"  >  
              </div>
            </div> 
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Unpaid Leaves<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="number" min="0" max="100" name="Unpaid_Leaves" id="Unpaid_Leaves" class="form-control " value="<?php  if(isset($record_data['Unpaid_Leaves']) && $record_data['Unpaid_Leaves'] != "" ){ echo $record_data['Unpaid_Leaves']; } ?>"  >  
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Consumed Paid Leaves<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="number" min="0" max="100" name="Consumed_Paid_Leaves" id="Consumed_Paid_Leaves" class="form-control"  value="<?php if(isset($record_data['Consumed_Paid_Leaves']) && $record_data['Consumed_Paid_Leaves'] != "" ){ echo $record_data['Consumed_Paid_Leaves']; } ?>"  >  
              </div>
            </div> 
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Consumed Unpaid Leaves<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="number" min="0" max="100" name="Consumed_Unpaid_Leaves" id="Consumed_Unpaid_Leaves" class="form-control " value="<?php  if(isset($record_data['Consumed_Unpaid_Leaves']) && $record_data['Consumed_Unpaid_Leaves'] != "" ){ echo $record_data['Consumed_Unpaid_Leaves']; } ?>"  >  
              </div>
            </div> 
            

        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-left btn-lg"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
            <button type="button" style="margin-left: 10px;"  onclick="load_tab(this,'update_leaves',<?= 1; ?>,'leave_tabs_body')" class="btn btn-warning pull-left btn-lg"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Reset </button> 
          </div>
        </div>
      </form>
      <br><br>
       
    </div> 
      
         

 


<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>