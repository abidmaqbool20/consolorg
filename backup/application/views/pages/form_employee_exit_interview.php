 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_exit_interview.Org_Id"=>$this->org_id,"employee_exit_interview.Id"=>$edit_rec));
    $this->db->select("employee_exit_interview.* ");
    $this->db->from("employee_exit_interview");   
    $this->db->order_by("employee_exit_interview.Id","ASC");
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
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_exit_interview">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Interviewer <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select name="Interviewer" id="Interviewer" value="<?php if(isset($record_data['Interviewer'])){ echo $record_data['Interviewer']; } ?>" class="form-control select2 ">
                  <?php 
                    
                    $this->db->order_by("First_Name","asc");
                    $this->db->select("First_Name,Last_Name,Id");
                    $employees = $this->db->get_where("employees",array("Deleted"=>0,"Org_Id"=>$this->org_id,"Status"=>1));
                    if($employees->num_rows() > 0)
                    {
                      foreach ($employees->result() as $key => $value) 
                      {
                        $selected = "";
                        if(isset($record_data['Interviewer'])){ if($record_data['Interviewer'] == $value->Id ){ $selected = "selected='selected'"; } }
                         echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.'</option>';
                      }
                    }

                  ?>
                  
                </select>
              </div>
            </div>
            
            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Saparation Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datepicker required" placeholder="Saparation Date" name="Saparation_Date" id="Saparation_Date" value="<?php if(isset($record_data['Saparation_Date'])){ echo $record_data['Saparation_Date']; } ?>">
              </div>
            </div>


            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Leaving Reason <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Leaving_Reason" name="Leaving_Reason" value="<?php if(isset($record_data['Leaving_Reason']) && $record_data['Leaving_Reason'] != "" ){ echo $record_data['Leaving_Reason']; } ?>">
                  <option value="0">Select Leaving Reason</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Better Employment Conditions"){ echo "selected='selected'"; } } ?> value="Better Employment Conditions">Better Employment Conditions</option>
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Career Prospect"){ echo "selected='selected'"; } } ?>  value="Career Prospect">Career Prospect</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Death"){ echo "selected='selected'"; } } ?>  value="Death">Death</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Dessertion"){ echo "selected='selected'"; } } ?>  value="Dessertion">Dessertion</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Dismissed"){ echo "selected='selected'"; } } ?>  value="Dismissed">Dismissed</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Dissatisfaction with the job"){ echo "selected='selected'"; } } ?>  value="Dissatisfaction with the job">Dissatisfaction with the job</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Emigrating"){ echo "selected='selected'"; } } ?>  value="Emigrating">Emigrating</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Health"){ echo "selected='selected'"; } } ?>  value="Health">Health</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Higher Pay"){ echo "selected='selected'"; } } ?>  value="Higher Pay">Higher Pay</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Personality Conflicts"){ echo "selected='selected'"; } } ?>  value="Personality Conflicts">Personality Conflicts</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Retirement"){ echo "selected='selected'"; } } ?>  value="Retirement">Retirement</option> 
                      <option  <?php if(isset($record_data['Leaving_Reason'])){ if($record_data['Leaving_Reason'] == "Retrenchment"){ echo "selected='selected'"; } } ?>  value="Retrenchment">Retrenchment</option>  
                </select> 
              </div>
            </div>
            
            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Working for this organization again<span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Working_Again_Status" name="Working_Again_Status" value="<?php if(isset($record_data['Working_Again_Status']) && $record_data['Working_Again_Status'] != "" ){ echo $record_data['Working_Again_Status']; } ?>">
                  <option value="0">Working for this organization again</option> 
                      <option  <?php if(isset($record_data['Working_Again_Status'])){ if($record_data['Working_Again_Status'] == "Yes"){ echo "selected='selected'"; } } ?> value="Yes">Yes</option>
                      <option  <?php if(isset($record_data['Working_Again_Status'])){ if($record_data['Working_Again_Status'] == "No"){ echo "selected='selected'"; } } ?>  value="No">No</option> 
                </select> 
              </div>
            </div>
             
            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Evaluate Our Organization ( 0 = Very Bad & 10 = Very Good ) <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <select class="form-control select2" id="Organization_Evaluation_Point" name="Organization_Evaluation_Point" value="<?php if(isset($record_data['Organization_Evaluation_Point']) && $record_data['Organization_Evaluation_Point'] != "" ){ echo $record_data['Organization_Evaluation_Point']; } ?>">
                  <option value="0">Select Evaluation Point</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "0"){ echo "selected='selected'"; } } ?> value="0">0</option>
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "1"){ echo "selected='selected'"; } } ?>  value="1">1</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "2"){ echo "selected='selected'"; } } ?>  value="2">2</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "3"){ echo "selected='selected'"; } } ?>  value="3">3</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "4"){ echo "selected='selected'"; } } ?>  value="4">4</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "5"){ echo "selected='selected'"; } } ?>  value="5">5</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "6"){ echo "selected='selected'"; } } ?>  value="6">6</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "7"){ echo "selected='selected'"; } } ?>  value="7">7</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "8"){ echo "selected='selected'"; } } ?>  value="8">8</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "9"){ echo "selected='selected'"; } } ?>  value="9">9</option> 
                      <option  <?php if(isset($record_data['Organization_Evaluation_Point'])){ if($record_data['Organization_Evaluation_Point'] == "10"){ echo "selected='selected'"; } } ?>  value="10">10</option> 
                </select> 
              </div>
            </div>

            <div class="col-sm-4 col-xs-12 ">
              <div class="">
                <label class="control-label">Document ( Resignation Letter ) <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="file" class="form-control" placeholder="Document" name="Document" id="Document"  >
              </div>
            </div>

            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">What did you like the most of the organization <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Organization_Most_Liked" rows="6" id="Organization_Most_Liked" class="form-control" ><?php if(isset($record_data['Organization_Most_Liked'])){ echo $record_data['Organization_Most_Liked']; } ?></textarea>
              </div>
            </div>

            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Think the organization do to improve staff welfare <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Organization_To_Improve" rows="6" id="Organization_To_Improve" class="form-control" ><?php if(isset($record_data['Organization_To_Improve'])){ echo $record_data['Organization_To_Improve']; } ?></textarea>
              </div>
            </div>

            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Anything you wish to share with us <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Employee_Reviews" rows="6" id="Employee_Reviews" class="form-control" ><?php if(isset($record_data['Employee_Reviews'])){ echo $record_data['Employee_Reviews']; } ?></textarea>
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

          $this->db->where(array("employee_exit_interview.Org_Id"=>$this->org_id,"employee_exit_interview.Deleted"=>0,"employee_exit_interview.Employee_Id"=>$data));
          $this->db->select("employee_exit_interview.*,employees.First_Name,employees.Last_Name ");
          $this->db->from("employee_exit_interview");
          $this->db->join("employees","employees.Id = employee_exit_interview.Interviewer","left");  
          $this->db->order_by("employee_exit_interview.Id","ASC");
          $employee_exit_interview = $this->db->get();

          if($employee_exit_interview->num_rows() > 0)
          {  
            foreach ($employee_exit_interview->result() as $key => $value){ 

              $document = "Not Uploaded";
              if($value->Document && $value->Document != "")
              {
                $src =  "assets/panel/userassets/employee_exit_interview/".$value->Id."/".$value->Document;
                if(file_exists($src))
                {
                  $document = "<div><a target='_blank' href='".$src."'><i class='fa fa-file'></i> View Document</a></div>";
                } 
              }

              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff;"><i class="fa fa-building"></i> Interviewer : '.$value->First_Name.' '.$value->Last_Name.'</h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_employee_exit_interview\','.$data.',\'employee_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'employee_exit_interview\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                                  <tr> 
                                    <th>Leaving Reason</th>
                                    <td>'.$value->Leaving_Reason.'</td>
                                    <th>Saparation Date</th>
                                    <td>'.$value->Saparation_Date.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Want to work again</th>
                                    <td>'.$value->Working_Again_Status.'</td>
                                    <th>Organization Evaluation Point ( By Employee )</th>
                                    <td>'.$value->Organization_Evaluation_Point.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Interview Date</th>
                                    <td>'.date("l, d F, Y",strtotime($value->Date_Added)).'</td>
                                    <th>Document</th>
                                    <td>'.$document.'</td>
                                  </tr> 
                                  <tr> 
                                    <th>Question</th>
                                    <td colspan="3">What did you like the most of the organization</td> 
                                  </tr>
                                  <tr> 
                                    <th>Answer</th>
                                    <td colspan="3">'.$value->Organization_Most_Liked.'</td> 
                                  </tr>
                                  <tr> 
                                    <th>Question</th>
                                    <td colspan="3">Think the organization do to improve staff welfare</td> 
                                  </tr>
                                  <tr> 
                                    <th>Answer</th>
                                    <td colspan="3">'.$value->Organization_To_Improve.'</td> 
                                  </tr>
                                  <tr> 
                                    <th>Question</th>
                                    <td colspan="3">Anything you wish to share with us</td> 
                                  </tr>
                                  <tr> 
                                    <th>Answer</th>
                                    <td colspan="3">'.$value->Employee_Reviews.'</td> 
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