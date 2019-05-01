 
 
    <div class="panel-body"> 
      
      <br><br>
      <div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("employee_benefits.Org_Id"=>$this->org_id,"employee_benefits.Deleted"=>0,"employee_benefits.Employee_Id"=>$data));  
          $this->db->select("employee_benefits.*,benefit_types.Name as Benefit_Type_Name ,currency.Symbol as Currency");
          $this->db->from("employee_benefits"); 
          $this->db->join("benefit_types","benefit_types.Id = employee_benefits.Benefit_Type_Id","left"); 
          $this->db->join("currency","currency.Id = employee_benefits.Currency","left");
          $this->db->order_by("employee_benefits.Id","ASC"); 
          $employee_benefits = $this->db->get();

          if($employee_benefits->num_rows() > 0)
          {   
              
              echo '<div class="col-md-12 col-sm-12" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <h3 style="color:#fff; text-align:left;"><i class="fa fa-graduation-cap"></i> Employee Benefits </h3>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                               
                      ';
                             foreach ($employee_benefits->result() as $key => $value)
                             {  

                               echo ' <tr id="row_'.$value->Id.'"> 
                                        <th>'.$value->Benefit_Type_Name.'</th>
                                        <td>'.$value->Benefit_Duration.'</td>
                                        <td>'.$value->Currency.' '.$value->Benefit_Worth.'</td> 
                                        <th>
                                           
                                        </th> 
                                      </tr>
                                     ';

                              }

              echo '</table> </div> </div> </div>  </div>';
          
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