 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->where(array("employee_daily_reports.Org_Id"=>$this->org_id,"employee_daily_reports.Id"=>$edit_rec));
    $this->db->select("employee_daily_reports.*  ");
    $this->db->from("employee_daily_reports");  
    $this->db->order_by("employee_daily_reports.Id","ASC");
    $check_record = $this->db->get();

    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0]; 
    }

  }
?>

    <div class="panel-body"> 
      <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">
            <input type="hidden" name="Employee_Id" id="Employee_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="employee_daily_reports">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

           <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Report Date <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control datetimepicker required" placeholder="Report Date" name="Report_Date" id="Report_Date" value="<?php if(isset($record_data['Report_Date'])){ echo $record_data['Report_Date']; } ?>">
              </div>
            </div>

            <div class="col-sm-12 col-xs-12 ">
              <div class="">
                <label class="control-label">Report Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <textarea name="Report" id="Report" class="form-control" ><?php if(isset($record_data['Report'])){ echo $record_data['Report']; } ?></textarea>
                <script> CKEDITOR.replace( 'Report' );</script> 
              </div>
            </div>
                  
        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-right"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
          </div>
        </div>
      </form>
      <br><br>
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
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> Daily Reports </h3>
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
                                          <ul class="list-inline">
                                            <li><a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_employee_daily_report\','.$data.',\'employee_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#505b72;" ><i class="fa fa-pencil"></i></a></li>
                                            <li ><a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'employee_daily_reports\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a> </li>
                                            
                                  
                                           
                                          </ul>
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