<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Shift Settings","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>
 
 <style type="text/css">
   .day_name
   {
     font-size: 14px;
     font-weight: bold;
   }
 </style>
    <div class="row">
      <div class="col-md-12"> 
        <div class="col-lg-11 col-md-10 col-sm-10"></div>
        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
          <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
          <button onclick="load_tab(this,'form_shift_settings',<?= 0; ?>,'shifts_tabs_body')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
          <?php } ?>
        </div>
      </div> 
    </div> 
    <hr> 
    <div class="row">
      <?php 

        $this->db->where(array("shifts.Deleted"=>0,"shifts.Status"=>1,"shift_settings.Deleted"=>0,"shift_settings.Status"=>1,"shifts.Org_Id"=>$this->org_id,"shift_settings.Org_Id"=>$this->org_id));
        $this->db->select("
                            shift_settings.Id,  
                            shift_settings.Shift_Id,  
                            shifts.Name as Shift_Name,
                            shifts.Shift_Incharge,
                            employees.First_Name as Incharge_First_Name,
                            employees.Last_Name as Incharge_Last_Name 
                          ");

        $this->db->from("shift_settings"); 
        $this->db->join("shifts","shifts.Id = shift_settings.Shift_Id","left"); 
        $this->db->join("employees","employees.Id = shifts.Shift_Incharge","left");  
        $this->db->order_by("shift_settings.Id","DESC");  
        $this->db->group_by(array("shift_settings.Shift_Id", "employees.Id", "shifts.Id"));
        $shifts_settings = $this->db->get();
        

        if($shifts_settings->num_rows() > 0){
          foreach ($shifts_settings->result() as $key => $value) {
                
                $this->db->select("COUNT(shift_employees.Shift_Id) as Total_Employees");
                $total_employees = $this->db->get_where("shift_employees",array("Deleted"=>0,"Status"=>1,"Shift_Id"=>$value->Shift_Id))->row()->Total_Employees;
         
           
                  echo '<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12" style="padding:10px;" id="row_'.$value->Id.'"> 
                          <table class="table table-bordered table-inverse nomargin" style="border:2px solid #ccc">
                            <tr>
                              <td colspan="2">
                               <h2 class="panel-title" style="font-size: 25px;text-align:center;padding: 25px 25px 5px 25px;">'.$value->Shift_Name.'</h2> 
                               <h4 class="panel-title" style="font-size: 17px;text-align:center;padding: 5px 25px 10px 25px;">Shift Incharge: '.$value->Incharge_First_Name." ".$value->Incharge_Last_Name.'</h4>
                              </td>
                            </tr>
                            <tr><td colspan="2"><h5 style="color: darkmagenta; text-align:center">'.$total_employees.' Employees</5> </td></tr>';


                  $shift_setting_details = $this->db->get_where("shift_setting_details",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Shift_Setting_Id"=>$value->Id)); 

                  if($shift_setting_details->num_rows() > 0){
                    foreach ($shift_setting_details->result() as $index => $record){ 

                    if($record->Day_Status == "ON"){ $day_color = "aqua"; }
                    if($record->Day_Status == "OFF"){ $day_color = "red"; } 
                    if($record->Day_Type == "Alternative"){ $day_color = "#b2dc0c"; } 

                    echo '<tr><td style="background-color:'.$day_color.'"><h5><span class="day_name">'.$record->Day.' : </span> </td><td><table class="table table-bordered table-inverse nomargin"><tr><td>From</td><td>'.$record->Start_Time."</td></tr><tr><td> TO </td><td>".$record->End_Time.'</td></tr></table></td></tr></5>'; 


                    }
                  }
              
                  if(in_array($module_id."_edit", $this->role_permissions)){ $edit = '<button onclick="load_tab(this,\'form_shift_settings\','.$value->Id.',\'shifts_tabs_body\')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-original-title="Edit Shift"><i class="fa fa-pencil"></i></button>'; }else{ $edit = ""; }
                  if(in_array($module_id."_delete", $this->role_permissions)){ $delete = ' <button onclick="delete_shift_settings('.$value->Id.',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" ><i class="fa fa-trash"></i></button> '; }else{ $delete = ""; }

                echo '<tr>
                        <td colspan="2" style=" text-align:center">'.$edit.''.$delete.'</td>
                      </tr>
                      </table>  
                      </div> ';   
            }
          }  
          else{ echo no_record_found(); } 
              
      ?>
       
    
    </div> 
      <hr>
        <div class="row">
          <div class="col-md-12">
            <h2>Manage Shift Setting Modifications</h2>
          </div>
          <div class="col-md-12">  
          <div class="table-responsive">
            <table id="dataTable1" class="table table-bordered table-striped-col">
              <thead>
                <tr> 
                  <th>Shift Name</th>   
                  <th>Saved By</th>  
                  <th>Modified By</th>  
                  <th>Date Saved</th>  
                  <th>Modification Date</th> 
                  <th>Action</th> 
                </tr>
              </thead>  
              <tbody id="shift_settings">
                <?php 

                $this->db->where(array("shifts.Deleted"=>0,"shifts.Status"=>1,"shift_settings.Deleted"=>0,"shift_settings.Status"=>1,"shifts.Org_Id"=>$this->org_id,"shift_settings.Org_Id"=>$this->org_id));
                $this->db->select(" shift_settings.*,  
                                    shift_settings.Shift_Id,  
                                    shifts.Name as Shift_Name,
                                    shifts.Shift_Incharge,
                                    employees.First_Name as Added_By_First_Name,
                                    employees.Last_Name as Added_By_Last_Name,
                                    modified_by.First_Name as Updated_By_First_Name,
                                    modified_by.Last_Name as Updated_By_Last_Name 
                                  ");

                $this->db->from("shift_settings"); 
                $this->db->join("shifts","shifts.Id = shift_settings.Shift_Id","left"); 
                $this->db->join("employees","employees.Id = shift_settings.Added_By","left");  
                $this->db->join("employees as modified_by","modified_by.Id = shift_settings.Modified_By","left");  
                $this->db->order_by("shift_settings.Id","DESC");  
                $this->db->group_by(array("shift_settings.Id"));
                $shifts_settings = $this->db->get();

                  if($shifts_settings->num_rows() > 0)
                  {
                    foreach ($shifts_settings->result() as $key => $value) 
                    { 
                       
                ?>
                <tr id="row_<?= $value->Id; ?>">
                  
                  <td><?= $value->Shift_Name; ?> </td>
                  <td><?= $value->Added_By_First_Name." ".$value->Added_By_Last_Name; ?></td> 
                  <td><?= $value->Updated_By_First_Name." ".$value->Updated_By_Last_Name; ?></td> 
                  <td><?= date("d F, Y H:i:s", strtotime($value->Date_Added)); ?> </td>
                  <td><?= date("d F, Y H:i:s", strtotime($value->Date_Modification)); ?> </td>
                  <td><button onclick="delete_record('shifts_settings','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5"><i class="fa fa-trash"></i></button></td>  
                  
              
                </tr>
                <?php }} ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>     
  <script type="text/javascript">
    $('#dataTable1').DataTable();
  </script>


