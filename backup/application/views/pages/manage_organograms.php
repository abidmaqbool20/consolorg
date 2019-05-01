
 
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Organogram","Deleted"=>0,"Status"=>1));
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
              <h3><i class="fa fa-cubes"></i> Manage Organograms</h3>
            </div> 
            
            <div class="col-lg-2 col-md-2 col-sm-2"> 
              <!--   -->
            </div>
            <div class="col-lg-1 col-md-2 col-sm-2">
              <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
              <button onclick="load_view(this,'form_manage_organogram')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
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

                // $this->db->where(array("organograms.Deleted"=>0,"organograms.Org_Id"=>$this->org_id));
                // $this->db->select("
                //                     organograms.Id,
                //                     organogram_childs.Name as Child_Name, 
                //                     parent_child.Name as Parent_Child_Name, 
                //                     addedby.First_Name as Addedby_FirstName,
                //                     addedby.Last_Name as Addedby_LastName,
                //                     updatedby.First_Name as Updatedby_FirstName,
                //                     updatedby.Last_Name as Updatedby_LastName
                //                   ");

                // $this->db->from("organograms");
                // $this->db->join("organogram_childs","organogram_childs.Organogram_Id = organograms.Id","left");
                // $this->db->join("organogram_childs as parent_child","parent_child.Id = organogram_childs.Parent_Id","left");
                // $this->db->join("employees as addedby","addedby.Id = organograms.Added_By","left");
                // $this->db->join("employees as updatedby","updatedby.Id = organograms.Modified_By","left"); 
                // $this->db->order_by("Id","DESC");
                $organograms = $this->db->get_where("organograms",array("Deleted"=>0,"Org_Id"=>$this->org_id));

                if($organograms->num_rows() > 0)
                {
                  foreach ($organograms->result() as $key => $value) 
                  { 
                   
            ?>
            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12" id="row_<?= $value->Id; ?>">
              <div class="panel org_role">
                <div class="panel-heading">
                  <h4 class="panel-title" style="font-size: 18px;"><?= $value->Name; ?></h4>
                
                </div>
                <div class="panel-body"> 
                  <?php if(in_array($module_id."_view", $this->role_permissions)){ ?>
                  <button onclick="load_view(this,'view_orgcreated_organogram','<?= $value->Id; ?>')" class="btn btn-primary btn-stroke btn-icon tooltips mr5" ><i class="fa fa-search-plus"></i></button>
                  <?php }if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                  <button onclick="load_view(this,'form_edit_manage_organogram','<?= $value->Id; ?>')" class="btn btn-info btn-stroke btn-icon tooltips mr5" ><i class="fa fa-pencil"></i></button>
                  <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                  <button onclick="delete_record('organograms','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" ><i class="fa fa-trash"></i></button> 
                  <?php } ?>
                </div>
              </div>
            </div> 
          <?php }}else{ echo no_record_found(); }  ?>
          </div> 
        </div>
      </div>
    </div> 

   

  </div><!-- contentpanel -->
</div>

 