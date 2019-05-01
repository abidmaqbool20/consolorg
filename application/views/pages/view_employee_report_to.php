  

    <div class="panel-body"> 
      
      <br><br>
      <div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("employee_report_to.Org_Id"=>$this->org_id,"employee_report_to.Deleted"=>0,"employee_report_to.Employee_Id"=>$data));
          $this->db->select("employee_report_to.*,employees.First_Name,employees.Last_Name  ");
          $this->db->from("employee_report_to"); 
          $this->db->join("employees","employees.Id = employee_report_to.Report_To","left"); 
          $this->db->order_by("employee_report_to.Id","ASC");
          $employee_report_to = $this->db->get();

          if($employee_report_to->num_rows() > 0)
          {  

               
               echo '<div class="col-md-12 col-sm-12" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <h3 style="color:#fff; text-align:left;"><i class="fa fa-graduation-cap"></i> Report To </h3>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin"> 
                               
                      ';
                             foreach ($employee_report_to->result() as $key => $value)
                             {  

                               echo ' <tr id="row_'.$value->Id.'"> 
                                        <th>'.$value->First_Name.' '.$value->Last_Name.'</th> 
                                         
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