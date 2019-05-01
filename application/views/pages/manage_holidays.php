<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Manage Holidays","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
 ?>
 
    <div class="row">
      <div class="col-md-12"> 
        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
          <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
          <button onclick="load_tab(this,'form_organization_holiday',<?= 0; ?>,'leave_tabs_body')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add Holiday</button> 
          <button onclick="load_tab(this,'form_organization_holiday_replacement',<?= 0; ?>,'leave_tabs_body')" style="float: right; margin-right: 5px;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add Holiday Replacement</button> 
          <?php } ?>
        </div>
      </div> 
    </div> 
    <hr> 
    <div class="row">
      <?php 

          $this->db->where(array("organization_holidays.Deleted"=>0,"organization_holidays.Status"=>1,"organization_holidays.Org_Id"=>$this->org_id));
          $this->db->select("
                              organization_holidays.*,  
                              addedby.First_Name as Addedby_FirstName,
                              addedby.Last_Name as Addedby_LastName,
                              updatedby.First_Name as Updatedby_FirstName,
                              updatedby.Last_Name as Updatedby_LastName, 
                            ");

          $this->db->from("organization_holidays");  
          $this->db->join("employees as addedby","addedby.Id = organization_holidays.Added_By","left");
          $this->db->join("employees as updatedby","updatedby.Id = organization_holidays.Modified_By","left");  
          $this->db->order_by("Id","DESC");
          $this->db->group_by(array("organization_holidays.Id"));
          $organization_holidays = $this->db->get();

          if($organization_holidays->num_rows() > 0)
          {
            foreach ($organization_holidays->result() as $key => $value) 
            { 
             
             if($value->Repeat_Yearly == "on"){ 
              $back_ground_color = "antiquewhite"; 
              $date_difference = strtotime(date("Y-m-d",strtotime($value->From_Day))) - strtotime(date("Y-m-d",strtotime($value->To_Day)));
              $days = date("d",$date_difference);
              $holiday_duration = date("d F",strtotime(date("Y")."-".$value->From_Day))." TO ".date("d F",strtotime(date("Y")."-".$value->To_Day));
             }
             else{ 
              $back_ground_color = "#d8dce3"; 
              $date_difference = strtotime($value->To_Date) - strtotime($value->From_Date);
              $days = date("d",$date_difference);
              $holiday_duration = date("F d, Y",strtotime($value->From_Date))." TO ".date("F d, Y",strtotime($value->To_Date));
             }

             
      ?>
      <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12" id="row_<?= $value->Id; ?>">
        <div class="panel org_role" style="background-color: <?= $back_ground_color; ?>">
          <div class="panel-heading">
            <h4 class="panel-title" style="font-size: 18px;"><?= $value->Title; ?></h4> 
            <h5 style="color: darkmagenta;"><?php if($value->Repeat_Yearly == "on"){ echo "Every Year"; }else{echo "This Year Only"; } ?> </h5>
            <hr style="border-color: black">
            <h5><?= $holiday_duration; ?></5>
            <h5 style="color: green;"><?= $days; ?> Days</5>
          </div>
          <div class="panel-body"> 
            <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
            <button onclick="load_tab(this,'form_organization_holiday',<?= $value->Id; ?>,'leave_tabs_body')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-original-title="Edit Shift"><i class="fa fa-pencil"></i></button>
            <button onclick="load_tab(this,'holiday_replacements',<?= $value->Id; ?>,'leave_tabs_body')" class="btn btn-warning btn-stroke btn-icon tooltips mr5" data-original-title="Holiday Replacement"><i class="fa fa-exchange"></i></button>
            <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
            <button onclick="delete_record('organization_holidays','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" ><i class="fa fa-trash"></i></button> 
            <?php } ?>
          </div>
        </div>
      </div> 
    <?php }}else{ echo no_record_found(); } ?>
    </div> 
         
  <script type="text/javascript">
    $('#dataTable1').DataTable();
  </script>


