 

    <div class="panel-body"> 
 
      <div class="form-group">
        <div class="row">
        <?php

          $this->db->where(array("employee_daily_reports.Org_Id"=>$this->org_id,"employee_daily_reports.Deleted"=>0,"employee_daily_reports.Employee_Id"=>$data)); 
          $this->db->select("employee_daily_reports.* ");
          $this->db->from("employee_daily_reports");  
          $this->db->order_by("employee_daily_reports.Id","DESC");
          $employee_daily_reports = $this->db->get();

          if($employee_daily_reports->num_rows() > 0)
          {  
            
              
              echo '<div class="col-md-12 col-sm-12" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <h3 style="color:#fff; text-align:left;"><i class="fa fa-graduation-cap"></i> Daily Reports </h3>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="col-sm-12 col-md-12 col-lg-12">
                              <div class="timeline-wrapper">
                              ';

                              $previous_date = "0";
                              foreach ($employee_daily_reports->result() as $key => $value) {
                                
                                $report_date = date("Y-m-d",strtotime($value->Report_Date));
                                if(strtotime($report_date) != strtotime($previous_date))
                                {
                                  $previous_date = $report_date; 
                                  echo '<div class="timeline-date">'.date("l, d F, Y ",strtotime($value->Report_Date)).'</div>';
                                }

                                echo '<div class="panel panel-post-item"  id="row_'.$value->Id.'">
                                        <span class="media-time">'.date("H:i:s",strtotime($value->Report_Date)).'</span> 
                                        <div class="panel-body">
                                          '.$value->Report.'
                                        </div>
                                        <div class="panel-footer" style="padding:0px;">
                                           
                                        </div> 
                                      </div> 
                                      '; 
                              }
                               
                       
                             
              echo ' </div> </div>  </div></div>  </div>';
          
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
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd H:i:s' });
  $('.datetimepicker').bootstrapMaterialDatePicker
      ({
        date: true,  
        format: 'YYYY-MM-DD HH:mm'
      });
</script>