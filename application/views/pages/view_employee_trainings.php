 

    <div class="panel-body"> 
      
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
                                      <h3 style="color:#fff; text-align:left;"><i class="fa fa-building"></i> '.$value->Training_Title.' </h3>
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