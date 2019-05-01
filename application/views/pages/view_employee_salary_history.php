 
    <div class="panel-body"> 
    
      <br><br>
      <div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("employee_salary_record.Org_Id"=>$this->org_id,"employee_salary_record.Deleted"=>0,"employee_salary_record.Employee_Id"=>$data));
          $this->db->select("employee_salary_record.*,currency.Symbol as Currency");
          $this->db->from("employee_salary_record");  
          $this->db->join("currency","currency.Id = employee_salary_record.Currency","left");
          $this->db->order_by("employee_salary_record.Id","DESC");
          $employee_salary_record = $this->db->get();

          if($employee_salary_record->num_rows() > 0)
          {  
            foreach ($employee_salary_record->result() as $key => $value){  

              

             
              $start_date = date("d F, Y",strtotime($value->Start_Date));
              if($value->End_Date && $value->End_Date != "" && $value->End_Date != ""){ $end_date = date("d F, Y",strtotime($value->End_Date)); }
              
              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff; text-align:left;"><i class="fa fa-building"></i> '.$start_date.' - '.$end_date.'</h3>
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
                                    <th>From Date</th>
                                    <td>'.$start_date.'</td>
                                    <th>To Date</th>
                                    <td>'.$end_date.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Salary </th>
                                    <td colspan="3">'.$value->Currency.' '.$value->Salary.'</td> 
                                  </tr> 
                                  <tr> 
                                    <th>Job Description</th>
                                    <td colspan="3">'.$value->Description.'</td> 
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