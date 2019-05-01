 
<?php
    
  

    $record_data = array();
 
    $check_record = $this->db->get_where("email_settings",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));

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
            <input type="hidden" name="Table_Name" id="Table_Name" value="email_settings">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-7 col-xs-12 ">
              <div class="">
                <label class="control-label">Email From <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Email From" name="Email_From" id="Email_From" value="<?php if(isset($record_data['Email_From']) && $record_data['Email_From'] != "" ){ echo $record_data['Email_From']; } ?>">
              </div>
            </div>
           
           <div class="col-sm-10 col-xs-12 ">
              <div class="">
                <label class="control-label">SMTP HOST (//smtp.consol.pk  OR ssl://smtp.consol.pk)<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="SMTP HOST" name="Smtp_Host" id="Smtp_Host" value="<?php if(isset($record_data['Smtp_Host']) && $record_data['Smtp_Host'] != "" ){ echo $record_data['Smtp_Host']; } ?>">
              </div>
            </div>

            <div class="col-sm-7 col-xs-12 ">
              <div class="">
                <label class="control-label">SMTP PORT <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="SMTP PORT" name="Smtp_Port" id="Smtp_Port" value="<?php if(isset($record_data['Smtp_Port']) && $record_data['Smtp_Port'] != "" ){ echo $record_data['Smtp_Port']; } ?>">
              </div>
            </div>

            <div class="col-sm-7 col-xs-12 ">
              <div class="">
                <label class="control-label">SMTP USER <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="SMTP USER" name="Smtp_User" id="Smtp_User" value="<?php if(isset($record_data['Smtp_User']) && $record_data['Smtp_User'] != "" ){ echo $record_data['Smtp_User']; } ?>">
              </div>
            </div>

            <div class="col-sm-7 col-xs-12 ">
              <div class="">
                <label class="control-label">SMTP PASSWORD <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="SMTP PASSWORD" name="Smtp_Password" id="Smtp_Password" value="<?php if(isset($record_data['Smtp_Password']) && $record_data['Smtp_Password'] != "" ){ echo $record_data['Smtp_Password']; } ?>">
              </div>
            </div> 
                  
        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-left"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
          </div>
        </div>
      </form>
      <br><br>
      
    </div> 
      
         

 


<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>