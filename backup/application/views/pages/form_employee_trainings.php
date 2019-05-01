 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_training_record.Org_Id"=>$this->org_id,"employee_training_record.Id"=>$edit_rec ));
    $this->db->select("employee_training_record.*,training_groups.Title as Training_Title  ");
    $this->db->from("employee_training_record"); 
    $this->db->join("training_groups","training_groups.Id = employee_training_record.Training_Group_Id","left");  
    $this->db->order_by("employee_training_record.Id","ASC");
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
            <input type="hidden" name="Employee_Id" id="Employee_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_training_record">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             <div class="col-sm-6  col-xs-12">
              <div class="">
                <label class="control-label">Select Training Group <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Training_Group_Id" id="Training_Group_Id"  value="<?php if(isset($record_data['Training_Group_Id'])){ echo $record_data['Training_Group_Id']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("Date_Added","DESC");
                    $training_groups = $this->db->get_where("training_groups");
                    if($training_groups->num_rows() > 0)
                    {
                      foreach ($training_groups->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Training_Group_Id'])){ if($record_data['Training_Group_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Title.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
            <div class="col-sm-6 col-xs-12 ">
              <div class="">
                <label class="control-label">Training Status<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Training_Status" name="Training_Status" value="<?php if(isset($record_data['Training_Status']) && $record_data['Training_Status'] != "" ){ echo $record_data['Training_Status']; } ?>">
                  <option value="0">Select Degree Type</option> 
                      <option  <?php if(isset($record_data['Training_Status'])){ if($record_data['Training_Status'] == "Active"){ echo "selected='selected'"; } } ?> value="Active">Active</option>
                      <option  <?php if(isset($record_data['Training_Status'])){ if($record_data['Training_Status'] == "Blocked"){ echo "selected='selected'"; } } ?>  value="Blocked">Blocked</option> 
                </select>
              </div>
            </div> 
            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Training Note<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" name="Note" id="Note" class="form-control"  value="<?php if(isset($record_data['Note']) && $record_data['Note'] != "" ){ echo $record_data['Note']; } ?>">  
              </div>
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

          $this->db->where(array("employee_training_record.Org_Id"=>$this->org_id,"employee_training_record.Deleted"=>0 ));
          $this->db->select("employee_training_record.*,training_groups.Title as Training_Title,training_groups.Start_Date,training_groups.End_Date ");
          $this->db->from("employee_training_record"); 
          $this->db->join("training_groups","training_groups.Id = employee_training_record.Training_Group_Id","left"); 
          $this->db->order_by("employee_training_record.Id","DESC");

          $employee_training_record = $this->db->get();

          if($employee_training_record->num_rows() > 0)
          {  
            
              foreach ($employee_training_record->result() as $key => $value)
              {  

                  $date = $this->date;

                  if(strtotime($date) > strtotime($value->End_Date) ){ $training_status = "Completed"; }
                  if(strtotime($date) > strtotime($value->Start_Date) && strtotime($date) < strtotime($value->End_Date)){ $training_status = "Continue"; }
                  if(strtotime($date) < strtotime($value->Start_Date) ){ $training_status = "Pending"; }


                   echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                          <div class="panel panel-inverse">
                            <div class="panel-heading" style="padding:0px;">
                                <div class="row">  
                                  <div class="col-md-12">
                                    <div class="col-md-6">
                                      <h3 style="color:#fff;"><i class="fa fa-building"></i> '.$value->Training_Title.' </h3>
                                    </div>
                                    <div class="col-md-6">
                                      <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_employee_trainings\','.$data.',\'employee_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                      <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'employee_training_record\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="panel-body" style="background: #dcdcdc;">
                               <div class="table-responsive">
                                  <table class="table table-bordered table-primary nomargin"> 
                                      <tr> 
                                        <th>Training Title</th>
                                        <td>'.$value->Training_Title.'</td>
                                        <th>Training Status</th>
                                        <td>'.$value->Training_Status.'</td>
                                      </tr>
                                      <tr> 
                                        <th>Start Date</th>
                                        <td>'.$value->Start_Date.'</td>
                                        <th>End Date</th>
                                        <td>'.$value->End_Date.'</td>
                                      </tr>
                                      <tr> 
                                        <th>Current Status</th>
                                        <td colspan="3">'.$training_status.'</td> 
                                      </tr>
                                      <tr> 
                                        <th>Note</th>
                                        <td colspan="3">'.$value->Note.'</td> 
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