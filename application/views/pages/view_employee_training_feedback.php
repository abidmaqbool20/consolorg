 
 

    <div class="panel-body"> 
      
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
                                  <h3 style="color:#fff; text-align:left;"><i class="fa fa-building"></i> '.$value->Title.'</h3>
                                </div>
                                <div class="col-md-6">
                                  
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