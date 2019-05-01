 

    <div class="panel-body"> 
      
 
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
                                      <h3 style="color:#fff; text-align:left;"><i class="fa fa-bolt"></i> '.date("l, d F, Y ",strtotime($value->Activity_Date)).' </h3>
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