 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_activities.Org_Id"=>$this->org_id,"employee_activities.Id"=>$edit_rec));
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

    <div class="panel-body"> 
      <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">
            <input type="hidden" name="Employee_Id" id="Employee_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_activities">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-3  col-xs-12">
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
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">Activity Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datepicker required" placeholder="Activity Date" name="Activity_Date" id="Activity_Date" value="<?php if(isset($record_data['Activity_Date'])){ echo $record_data['Activity_Date']; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">From Time <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Start Time" name="Start_Time" id="Start_Time" value="<?php if(isset($record_data['Start_Time'])){ echo $record_data['Start_Time']; }else{ echo "00:00:00"; } ?>">
              </div>
            </div>
            <div class="col-sm-3 col-xs-12 ">
              <div class="">
                <label class="control-label">To Time <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="End Time" name="End_Time" id="End_Time" value="<?php if(isset($record_data['End_Time'])){ echo $record_data['End_Time']; }else{ echo "00:00:00"; } ?>">
              </div>
            </div>
            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Activity <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Activity" id="Activity" class="form-control" ><?php if(isset($record_data['Activity'])){ echo $record_data['Activity']; } ?></textarea>
                <script> CKEDITOR.replace( 'Activity' );</script> 
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

          $this->db->where(array("employee_activities.Org_Id"=>$this->org_id,"employee_activities.Deleted"=>0,"employee_activities.Employee_Id"=>$data)); 
          $this->db->select("employee_activities.*,activity_types.Name as Activity_Type_Name ");
          $this->db->from("employee_activities");  
          $this->db->join("activity_types","activity_types.Id = employee_activities.Activity_Type","left");
          $this->db->order_by("employee_activities.Id","DESC");
          $employee_activities = $this->db->get();

          if($employee_activities->num_rows() > 0)
          { 
             $previous_date = "0"; 
             foreach ($employee_activities->result() as $key => $value) 
             { 
                $date1=date_create($value->Start_Time); 
                $date2=date_create($value->End_Time); 
                $diff=date_diff($date1,$date2);
                $time_spent = $diff->format("%h Hours %i Minutes"); 
                
                $activity_date = date("Y-m-d",strtotime($value->Activity_Date));
                if(strtotime($activity_date) != strtotime($previous_date))
                {
                  if($key > 0)
                  {
                    echo '</table>  </div>  </div>  </div>   </div>';
                  }

                  $previous_date = $activity_date; 
                  echo '<div class="col-md-12 col-sm-12" >
                          <div class="panel panel-inverse">
                            <div class="panel-heading" style="padding:0px;">
                                <div class="row">  
                                  <div class="col-md-12">
                                    <div class="col-md-12">
                                      <h3 style="color:#fff;"><i class="fa fa-bolt"></i> '.date("l, d F, Y ",strtotime($value->Activity_Date)).' </h3>
                                    </div>
                                    
                                  </div>
                                </div>
                            </div>
                            <div class="panel-body" style="background: #dcdcdc;">
                               <div class="table-responsive">
                                  <table class="table table-bordered table-primary nomargin"> ';
                }


                    echo '<tr id="row_'.$value->Id.'"> 
                              <th>'.$value->Activity_Type_Name.'</th>
                              <td>'.$value->Start_Time.'</td>
                              <td>'.$value->End_Time.'</td>
                              <td>'.$time_spent.'</td>
                              <th>
                                <a href="javascript:;" style="color: blue;" onclick="load_tab(this,\'form_employee_activities\','.$data.',\'employee_from_container\', '.$value->Id.')" > <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                                <a href="javascript:;" onclick="delete_record(\'employee_activities\','.$value->Id.',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
                              </th> 
                          </tr>'; 
                 
              } 

              echo '</table>  </div>  </div>  </div>   </div>';
          
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