 
 

    <div class="panel-body"> 
       
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