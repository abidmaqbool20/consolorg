 

    <div class="panel-body"> 
   
      <br><br>
      <div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("employee_assets.Org_Id"=>$this->org_id,"employee_assets.Deleted"=>0,"employee_assets.Employee_Id"=>$data));
          $this->db->select("employee_assets.*,organization_assets.Name as Asset_Name");
          $this->db->from("employee_assets"); 
          $this->db->join("organization_assets","organization_assets.Id = employee_assets.Asset_Id","left"); 
          $this->db->order_by("employee_assets.Id","ASC");
          $employee_assets = $this->db->get();

          if($employee_assets->num_rows() > 0)
          {  
            foreach ($employee_assets->result() as $key => $value){ 

              $document = "Not Uploaded";
              if($value->Document && $value->Document != "")
              {
                $src =  "assets/panel/userassets/employee_assets/".$value->Id."/".$value->Document;
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
                                  <h3 style="color:#fff; text-align:left;"><i class="fa fa-graduation-cap"></i> '.$value->Asset_Name.'</h3>
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
                                    <th>Asset Name</th>
                                    <td>'.$value->Asset_Name.'</td>
                                    <th>Allowed Quantity</th>
                                    <td>'.$value->Allowed_Quantity.'</td>
                                  </tr>
                                  <tr> 
                                    <th>Start Date</th>
                                    <td>'.$value->Start_Date.'</td>
                                    <th>End Date</th>
                                    <td>'.$value->End_Date.'</td>
                                  </tr> 
                                  <tr>  
                                    <th>Document</th>
                                    <td>'.$document.'</td>
                                    <th>Description</th>
                                    <td>'.$value->Description.'</td>
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