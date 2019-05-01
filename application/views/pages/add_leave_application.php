 
 
    <?php
 
        $record_data = array();

        if($edit_rec && $edit_rec != "")
        {
          $this->db->where(array("leave_applications.Org_Id"=>$this->org_id,"leave_applications.Deleted"=>0 ,"leave_applications.Id"=>$edit_rec,"leave_applications.Employee_Id"=>$data));
          $this->db->select("leave_applications.*  ");
          $this->db->from("leave_applications");  
          $this->db->order_by("leave_applications.Id","ASC");
          $check_record = $this->db->get();

          if($check_record->num_rows() > 0)
          {
            $record_data = $check_record->result_array();
            $record_data = $record_data[0]; 
          }
        }

    ?>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-md-12 col-sm-12 col-xs-12">
          <h2>Add / Update Leave Application</h2> <hr>
        </div>
      </div> 
    </div>

    <div class="panel-body"> 

      <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group"> 
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">  
            <input type="hidden" name="Table_Name" id="Table_Name" value="leave_applications">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             <div class="col-sm-4  col-xs-12">
              <div class="">
                <label class="control-label">Select Employee <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Employee_Id" id="Employee_Id"  class="form-control select2 required" value="<?php if(isset($record_data['Employee_Id']) && $record_data['Employee_Id'] != "" ){ echo $record_data['Employee_Id']; } ?>">
                  <?php 
    
                    $this->db->select("First_Name,Last_Name,Id");
                    $this->db->order_by("First_Name","ASC");
                    $employees = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Status"=>"Active")); 
                    if($employees->num_rows() > 0)
                    {
                      foreach ($employees->result() as $key => $value) 
                      { 
                        $selected = "";
                        if(isset($record_data['Employee_Id'])){ if($record_data['Employee_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
            <div class="col-sm-2 col-xs-12 ">
              <div class="">
                <label class="control-label">Leave Type<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Leave_Type" id="Leave_Type"  class="form-control select2 required" value="<?php if(isset($record_data['Leave_Type']) && $record_data['Leave_Type'] != "" ){ echo $record_data['Leave_Type']; } ?>">
                  <option value="Full Day" <?php if(isset($record_data['Leave_Type']) && $record_data['Leave_Type'] == "Full Day" ){ echo "selected='selected'"; } ?>>Full Day</option>
                  <option value="Half Day" <?php if(isset($record_data['Leave_Type']) && $record_data['Leave_Type'] == "Half Day" ){ echo "selected='selected'"; } ?>>Half Day</option>
                </select> 
              </div>
            </div> 
            <div class="col-sm-2 col-xs-12 ">
              <div class="">
                <label class="control-label">From Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" name="From_Date" id="From_Date" class="form-control datepicker required"  value="<?php if(isset($record_data['From_Date']) && $record_data['From_Date'] != "" ){ echo $record_data['From_Date']; }else{ echo date("Y-m-d"); } ?>"  >  
              </div>
            </div> 
            <div class="col-sm-2 col-xs-12 ">
              <div class="">
                <label class="control-label">To Date<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" name="To_Date" id="To_Date" class="form-control datepicker required" value="<?php  if(isset($record_data['To_Date']) && $record_data['To_Date'] != "" ){ echo $record_data['To_Date']; }else{ echo date("Y-m-d"); } ?>"  >  
              </div>
            </div> 
            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Leave Reason<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea class="form-control required" placeholder="Write leave reason" name="Reason" id="Reason"><?php if(isset($record_data['Reason'])){ echo $record_data['Reason']; } ?></textarea> 
                <script> CKEDITOR.replace( 'Reason' );</script> 
              </div>
            </div> 

        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-left btn-lg"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Submit </button> 
            <button type="button" style="margin-left: 10px;"  onclick="load_tab(this,'add_leave_application',<?= 1; ?>,'leave_tabs_body')" class="btn btn-warning pull-left btn-lg"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Reset </button> 
            <button type="button" style="margin-left: 10px;" onclick="load_tab(this,'manage_leave_applications',0,'leave_tabs_body')" class="btn btn-danger pull-right btn-lg"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancel </button>
          </div>
        </div>
      </form>
      <br><br>
       
    </div> 
       

<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>