 
<?php
 
  $record_data = array();
  if($data && $data != "")
  {
    $this->db->where(array("shifts.Org_Id"=>$this->org_id,"shifts.Id"=>$data));
    $this->db->select("shifts.*  ");
    $this->db->from("shifts");  
    $this->db->order_by("shifts.Id","ASC");
    $check_record = $this->db->get();

    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0]; 
    }
  }

?>
<div class="mainpanel">
  <div class="contentpanel">

    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Shift</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'manage_shifts')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("admin/save_shift") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
      <div class="panel">
        <div class="panel-body">  
              <div class="error"></div>
              <div class="form-group">
               
                <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>"> 
                <input type="hidden" name="Table_Name" id="Table_Name" value="shifts">
                <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

                 
               
                 <div class="col-sm-12 col-xs-12 ">
                  <div class="">
                    <label class="control-label">Shift Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
                    <input type="text" class="form-control" placeholder="Enter Shift Name" name="Name" id="Name" value="<?php if(isset($record_data['Name'])){ echo $record_data['Name']; } ?>">
                  </div>
                </div>
                <div class="col-sm-4 col-xs-12 ">
                  <div class="">
                    <label class="control-label">Shift Start Time: ( 24 Hour Formate )  <span class="error_message" style="color: #f00;">  </span></label>
                    <input type="text" class="form-control" placeholder="Start Time" name="Start_Time" id="Start_Time" value="<?php if(isset($record_data['Start_Time'])){ echo $record_data['Start_Time']; }else{ echo "00:00"; } ?>">
                  </div>
                </div>
                <div class="col-sm-4 col-xs-12 ">
                  <div class="">
                    <label class="control-label">Shift End Time: ( 24 Hour Formate ) <span class="error_message" style="color: #f00;">  </span></label>
                    <input type="text" class="form-control" placeholder="End Time" name="End_Time" id="End_Time" value="<?php if(isset($record_data['End_Time'])){ echo $record_data['End_Time']; }else{ echo "00:00"; } ?>">
                  </div>
                </div> 
                <div class="col-sm-4  col-xs-12">
                  <div class="">
                    <label class="control-label">Select Shift Incharge <span class="text-danger">*</span><span class="text-danger error"></span></label>
                    <select name="Shift_Incharge" id="Shift_Incharge" onchange="get_institute(this,'Institute')" value="<?php if(isset($record_data['Shift_Incharge'])){ echo $record_data['Shift_Incharge']; } ?>" class="form-control select2 ">
                      <option value="0">Select Shift Incharge</option>
                      <?php 

                        $this->db->select("First_Name,Last_Name,Id");
                        $this->db->order_by("First_Name","asc");
                        $employees = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                        if($employees->num_rows() > 0)
                        {
                          foreach ($employees->result() as $key => $value) 
                          {
                            $selected = "";
                            if(isset($record_data['Shift_Incharge'])){ if($record_data['Shift_Incharge'] == $value->Id ){ $selected = "selected='selected'"; } }
                             echo '<option '.$selected.' value="'.$value->Id.'">'.$value->First_Name.' '.$value->Last_Name.'</option>';
                          }
                        }

                       ?>
                      
                    </select>
                  </div>
                </div> 
            </div>
            <hr>
            <div class="form-group">
              <div class="row" style="background: #c1c1c1; padding: 10px 0px;">
                 <?php 

                    $shift_all_employees = array();
                    if($data != "" && $data)
                    {
                      $this->db->select("Employee_Id");
                      $shift_employees = $this->db->get_where("shift_employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Shift_Id"=>$data));
                      if($shift_employees->num_rows() > 0)
                      { 
                        foreach ($shift_employees->result() as $key => $value) 
                        {
                          $shift_all_employees[] = $value->Employee_Id;
                        }
                      }
                    }


                    $this->db->where(array(
                                          "employees.Org_Id"=>$this->org_id,
                                          "employees.Deleted"=>0,
                                          "employees.Status"=>1,
                                          "employee_work_record.Deleted"=>0,
                                          "employee_work_record.Status"=>1  
                                        ));
                    $this->db->select("employees.Photo,employees.First_Name,employees.Last_Name,employees.Id,employee_work_record.Designation_Id,designations.Name as Designation");
                    $this->db->from("employees");
                    $this->db->join("employee_work_record","employee_work_record.Employee_Id = employees.Id","left");
                    $this->db->join("designations","designations.Id = employee_work_record.Designation_Id","left"); 
                    $this->db->order_by("employees.Id","asc");
                    $employees = $this->db->get();

                    if($employees->num_rows() > 0)
                    {
                      foreach ($employees->result() as $key => $value) 
                      {
                        $selected_color = "#fffff";
                        $checkbox = "";
                        if(sizeof($shift_all_employees) > 0)
                        {
                          if(in_array($value->Id, $shift_all_employees))
                          {
                            $selected_color = "#ebffe8";
                            $checkbox = "checked='checked'";
                          }
                        }
                         
                        $src =  "assets/images/default-user.png";
                        if($value->Photo && $value->Photo != "")
                        {
                          $src =  "assets/panel/userassets/employees/".$value->Id."/".$value->Photo;
                          if(!file_exists($src) && $value->Photo != "")
                          {
                             $src =  "assets/images/default-user.png";
                          } 
                        }
                       


                   ?>
                    <div class="col-md-2 col-lg-2 col-sm-4 col-xs-12">
                      <div class="panel panel-profile grid-view" style="background: <?= $selected_color; ?>">
                        <div class="panel-heading">
                          <div class="text-center">
                            
                              <div style="width: 80px; margin:0 auto;">
                                <a href="#" class="panel-profile-photo">
                                  <img class="img-circle" src="<?= $src; ?>" alt="" style="width: 100%;">
                                </a>
                              </div> 
                            
                            <h4 class="panel-profile-name"><i class="fa fa-user"></i> <?= $value->First_Name." ".$value->Last_Name; ?></h4>
                            <p class="media-usermeta"> <?= $value->Designation; ?></p>
                          </div>
                          <ul class="panel-options">
                            <li> 
                              <label class="ckbox ckbox-primary">
                                <input type="checkbox" <?= $checkbox; ?> onclick="change_color(this)" name="Shift_Employee[]" Id="Shift_Employee" value="<?= $value->Id; ?>"><span> </span>
                              </label>
                            </li>
                          </ul>
                        </div> 
                         
                      </div>
                    </div>
              <?php }} ?>
                
                

              </div>
            </div> 
        </div>  
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button type="submit" style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'manage_shifts');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_shifts')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>

    </form> 
  </div>
</div>    
 

<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

  function change_color($this)
  {
    if($($this).is(":checked"))
    {
      $($this).parents("div.grid-view").css("background-color","#ebffe8");
    }
    else
    {
      $($this).parents("div.grid-view").css("background-color","#fffff");
    }
  }



</script>