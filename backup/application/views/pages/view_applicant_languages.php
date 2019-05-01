<div class="panel-body"> 
	<div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("applicant_languages.Org_Id"=>$this->org_id,"applicant_languages.Deleted"=>0,"applicant_languages.Applicant_Id"=>$data)); 
          $this->db->select("applicant_languages.*,languages.name as Language_Name ");
          $this->db->from("applicant_languages"); 
          $this->db->join("languages","languages.id = applicant_languages.Language_Id","left"); 
          $this->db->order_by("applicant_languages.Id","ASC");
          $applicant_languages = $this->db->get();

          if($applicant_languages->num_rows() > 0)
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
                             foreach ($applicant_languages->result() as $key => $value)
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