
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Training Groups","Deleted"=>0,"Status"=>1));
  if($module_data->num_rows() > 0)
  {
    $module_info = $module_data->result_array();
    $module_id = $module_info[0]['Id'];
  }
 
?>



<div class="mainpanel">

  <div class="contentpanel">

   <div class="panel"> 
      <div class="panel-body">
          <div class="col-md-12">
            <div class="col-lg-9 col-md-8 col-sm-8">
              <h3><i class="fa fa-cubes"></i> Manage Training Groups</h3>
            </div> 
            
            <div class="col-lg-2 col-md-2 col-sm-2"> 
              <div class="btn-group pull-right people-pager">
                <button class="btn btn-primary-active"  onclick="load_view(this,'training_groups_table');"><i class="fa fa-th"></i></button>
                <a href="javascript:;" class="btn btn-default"  onclick="load_view(this,'training_groups_list');"><i class="fa fa-th-list"></i></a>
              </div>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-2">
              <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
              <button onclick="load_view(this,'form_training_group')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
              <?php } ?>
            </div>
          </div>
      </div>
    </div>

    <div class="panel"> 
      <div class="panel-body">
        <div class=""> 
          <div class="row">
            <?php

                $this->db->where(array("training_groups.Deleted"=>0,"training_groups.Org_Id"=>$this->org_id));
                $this->db->select("
                                    training_groups.*, 
                                    employees.First_Name as Trainer_FirstName,
                                    employees.First_Name as Trainer_LastName,
                                    locations.Name as Location_Name,
                                    addedby.First_Name as Addedby_FirstName,
                                    addedby.Last_Name as Addedby_LastName,
                                    updatedby.First_Name as Updatedby_FirstName,
                                    updatedby.Last_Name as Updatedby_LastName
                                  ");

                $this->db->from("training_groups"); 
                $this->db->join("employees","employees.Id = training_groups.Trainer_Id","left");
                $this->db->join("locations","locations.Id = training_groups.Location_Id","left");
                $this->db->join("users as addedby","addedby.Id = training_groups.Added_By","left");
                $this->db->join("users as updatedby","updatedby.Id = training_groups.Modified_By","left"); 
                $this->db->order_by("Id","DESC");
                $training_groups = $this->db->get();

                if($training_groups->num_rows() > 0)
                {
                  foreach ($training_groups->result() as $key => $value) 
                  {  
                   
            ?>

            <div class="col-sm-4 col-md-3 col-lg-2">
            <div class="profile-left mb20">
              <div class="profile-left-heading"> 
                <h2 class="profile-name"><?= $value->Title; ?></h2>
                <?php if($value->Trainer_FirstName != ""){ ?>
                  <h4 class="profile-designation"><i class="fa fa-user"></i> <?= $value->Trainer_FirstName." ".$value->Trainer_LastName; ?></h4>
                <?php } ?>

                <ul class="list-group">
                  <li class="list-group-item">Start Date <a href="timeline.html"><?= $value->Start_Date; ?></a></li>
                  <li class="list-group-item">End Date <a href="timeline.html"><?= $value->End_Date; ?></a></li>
                  <li class="list-group-item">Start Time <a href="timeline.html"><?= $value->Start_Time; ?></a></li>
                  <li class="list-group-item">End Time <a href="timeline.html"><?= $value->End_Time; ?></a></li> 
                  <li class="list-group-item">Location <a href="timeline.html"><?= $value->Location_Name; ?></a></li> 
                </ul>
                <div class="">
                  <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                  <button onclick="load_view(this,'form_training_group','<?= $value->Id; ?>')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Edit Role"><i class="fa fa-pencil"></i></button>
                  <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                  <button onclick="delete_record('training_groups','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Delete Role"><i class="fa fa-trash"></i></button> 
                  <?php } ?>
                </div>
              </div> 
            </div> 
          </div>

           
          <?php }} ?>
          </div> 
        </div>
      </div>
    </div> 

   

  </div><!-- contentpanel -->
</div>

 