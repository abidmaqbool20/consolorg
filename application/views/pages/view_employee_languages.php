<div class="panel-body"> 
	<div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("employee_languages.Org_Id"=>$this->org_id,"employee_languages.Deleted"=>0,"employee_languages.Employee_Id"=>$data)); 
          $this->db->select("employee_languages.*,languages.name as Language_Name ");
          $this->db->from("employee_languages"); 
          $this->db->join("languages","languages.id = employee_languages.Language_Id","left"); 
          $this->db->order_by("employee_languages.Id","ASC");
          $employee_languages = $this->db->get();

          if($employee_languages->num_rows() > 0)
          {  
            
              
              echo '<div class="col-md-12 col-sm-12" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <h4 style="color:#fff; text-align:left"><i class="fa fa-graduation-cap"></i> Languages </h4>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin view_applicant_table"> 
                               
                      ';
                             foreach ($employee_languages->result() as $key => $value)
                             {  

                               echo ' <tr id="row_'.$value->Id.'"> 
                                        <th>'.$value->Language_Name.'</th>
                                        <td>'.$value->Language_Level.'</td> 
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