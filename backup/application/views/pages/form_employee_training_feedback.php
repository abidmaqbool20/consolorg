 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_training_feedback.Org_Id"=>$this->org_id,"employee_training_feedback.Id"=>$edit_rec));
    $this->db->select("employee_training_feedback.*");
    $this->db->from("employee_training_feedback");  
    $this->db->order_by("employee_training_feedback.Id","DESC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_training_feedback">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

             <div class="col-sm-6  col-xs-12">
              <div class="">
                <label class="control-label">Select Employee Trainings<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Training_Group_Id" id="Training_Group_Id" value="<?php if(isset($record_data['Training_Group_Id'])){ echo $record_data['Training_Group_Id']; } ?>" class="form-control select2 ">
                  <?php 

                    $this->db->where(array("employee_training_record.Deleted"=>0,"employee_training_record.Employee_Id"=>$data,"employee_training_record.Org_Id"=>$this->org_id));
                    $this->db->select("employee_training_record.*,training_groups.Title");
                    $this->db->from("employee_training_record");
                    $this->db->join("training_groups","training_groups.Id = employee_training_record.Training_Group_Id","left"); 
                    $this->db->order_by("employee_training_record.Id","DESC");
                    $employee_training_record = $this->db->get();
                    if($employee_training_record->num_rows() > 0)
                    {
                      foreach ($employee_training_record->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Training_Group_Id'])){ if($record_data['Training_Group_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                        echo '<option '.$selected.' value="'.$value->Training_Group_Id.'">'.$value->Title.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
            <div class="col-sm-6 col-xs-12 ">
              <div class="">
                <label class="control-label">Feedback Points (1 = Very Positive & 0 = Very negative)  <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Feedback_Score" name="Feedback_Score" value="<?php if(isset($record_data['Feedback_Score']) && $record_data['Feedback_Score'] != "" ){ echo $record_data['Feedback_Score']; } ?>">
                
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "0"){ echo "selected='selected'"; } } ?> value="0">0</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "1"){ echo "selected='selected'"; } } ?> value="1">1</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "2"){ echo "selected='selected'"; } } ?> value="2">2</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "3"){ echo "selected='selected'"; } } ?> value="3">3</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "4"){ echo "selected='selected'"; } } ?> value="4">4</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "5"){ echo "selected='selected'"; } } ?> value="5">5</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "6"){ echo "selected='selected'"; } } ?> value="6">6</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "7"){ echo "selected='selected'"; } } ?> value="7">7</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "8"){ echo "selected='selected'"; } } ?> value="8">8</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "9"){ echo "selected='selected'"; } } ?> value="9">9</option>
                      <option  <?php if(isset($record_data['Feedback_Score'])){ if($record_data['Feedback_Score'] == "10"){ echo "selected='selected'"; } } ?> value="10">10</option>
                      
                </select> 
              </div>
            </div> 
            <div class="col-sm-6 col-xs-12 ">
              <div class="">
                <label class="control-label">Feedback Grade <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Feedback_Grade" name="Feedback_Grade" value="<?php if(isset($record_data['Feedback_Grade']) && $record_data['Feedback_Grade'] != "" ){ echo $record_data['Feedback_Grade']; } ?>">
                      <option value="0">Select Feedback Grade </option> 
                      <option  <?php if(isset($record_data['Feedback_Grade'])){ if($record_data['Feedback_Grade'] == "A"){ echo "selected='selected'"; } } ?> value="A">A</option>
                      <option  <?php if(isset($record_data['Feedback_Grade'])){ if($record_data['Feedback_Grade'] == "B"){ echo "selected='selected'"; } } ?>  value="B">B</option> 
                      <option  <?php if(isset($record_data['Feedback_Grade'])){ if($record_data['Feedback_Grade'] == "C"){ echo "selected='selected'"; } } ?>  value="C">C</option> 
                      <option  <?php if(isset($record_data['Feedback_Grade'])){ if($record_data['Feedback_Grade'] == "D"){ echo "selected='selected'"; } } ?>  value="D">D</option> 
                      <option  <?php if(isset($record_data['Feedback_Grade'])){ if($record_data['Feedback_Grade'] == "E"){ echo "selected='selected'"; } } ?>  value="E">E</option> 
                      <option  <?php if(isset($record_data['Feedback_Grade'])){ if($record_data['Feedback_Grade'] == "F"){ echo "selected='selected'"; } } ?>  value="F">F</option> 
                </select> 
              </div>
            </div>
            <div class="col-sm-6  col-xs-12">
              <div class="">
                <label class="control-label">Feedback Given By<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Feedbacker_Id" id="Feedbacker_Id" value="<?php if(isset($record_data['Feedbacker_Id'])){ echo $record_data['Feedbacker_Id']; } ?>" class="form-control select2 ">
                  <?php 
                    $this->db->order_by("First_Name","asc");
                    $this->db->select("First_Name,Last_Name,Id");
                    $employees = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                    if($employees->num_rows() > 0)
                    {
                      foreach ($employees->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Feedbacker_Id'])){ if($record_data['Feedbacker_Id'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.'</option>';
                      }
                    }
                   ?>
                  
                </select>
              </div>
            </div>
            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Training Feedback <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control required" placeholder="Feedback" name="Feedback" id="Feedback" value="<?php if(isset($record_data['Feedback']) && $record_data['Feedback'] != "" ){ echo $record_data['Feedback']; } ?>">
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

          $this->db->where(array("employee_training_feedback.Org_Id"=>$this->org_id,"employee_training_feedback.Deleted"=>0,"employee_training_feedback.Employee_Id"=>$data));
          $this->db->select("employee_training_feedback.*,training_groups.Title,training_groups.Start_Date,training_groups.End_Date,employees.First_Name,employees.Last_Name");
          $this->db->from("employee_training_feedback"); 
          $this->db->join("training_groups","training_groups.Id = employee_training_feedback.Training_Group_Id","left"); 
          $this->db->join("employees","employees.Id = employee_training_feedback.Feedbacker_Id","left"); 
          $this->db->order_by("employee_training_feedback.Id","ASC");
          $employee_training_feedback = $this->db->get();

          if($employee_training_feedback->num_rows() > 0)
          {  
            foreach ($employee_training_feedback->result() as $key => $value){ 

               
              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff;"><i class="fa fa-building"></i> '.$value->Title.'</h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_employee_training_feedback\','.$data.',\'employee_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'employee_training_feedback\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                                  <tr> 
                                    <th>Training Title</th>
                                    <td>'.$value->Title.'<br> ( '.$value->Start_Date.' - '.$value->End_Date.' ) </td>
                                    <th>Feedback Given By</th>
                                    <td>'.$value->First_Name.' '.$value->Last_Name.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Feedback Points</th>
                                    <td>'.$value->Feedback_Score.'</td>
                                    <th>Feedback Grade</th>
                                    <td>'.$value->Feedback_Grade.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Date Added</th>
                                    <td>'.$value->Date_Added.'</td>
                                    <th>Last Modified </th>
                                    <td>'.$value->Date_Modification.'</td>
                                  </tr>
                                   
                                  <tr> 
                                    <th>Feedback</th>
                                    <td colspan="3">'.$value->Feedback.'</td> 
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