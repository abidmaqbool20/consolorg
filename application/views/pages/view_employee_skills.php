<div class="panel-body"> 
	<div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("employee_skills.Org_Id"=>$this->org_id,"employee_skills.Deleted"=>0,"employee_skills.Employee_Id"=>$data)); 
          $this->db->select("employee_skills.*,skills.Name as Skill_Name ");
          $this->db->from("employee_skills"); 
          $this->db->join("skills","skills.Id = employee_skills.Skill_Id","left"); 
          $this->db->order_by("employee_skills.Id","ASC");
          $employee_skills = $this->db->get();

          if($employee_skills->num_rows() > 0)
          {  
            
              
              echo '<div class="col-md-12 col-sm-12" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <h4 style="color:#fff; text-align:left"><i class="fa fa-graduation-cap"></i> Skills </h4>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin view_applicant_table"> 
                               
                      ';
                             foreach ($employee_skills->result() as $key => $value)
                             {  

                               echo '<tr id="row_'.$value->Id.'"> 
                                        <th>'.$value->Skill_Name.'</th>
                                        <td>'.$value->Skill_Level.'</td>
                                         
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