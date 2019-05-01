
<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Benefit Types","Deleted"=>0,"Status"=>1));
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

            <div class="col-lg-7 col-md-6 col-sm-6">

              <h3><i class="fa fa-users"></i> Manage Benefit Types</h3>

            </div>

            <div class="col-lg-2 col-md-2 col-sm-3"> </div>

            <div class="col-lg-2 col-md-2 col-sm-2"> </div>

            <div class="col-lg-1 col-md-2 col-sm-2">
              <?php if(in_array($module_id."_add", $this->role_permissions)){ ?>
              <button onclick="load_view(this,'form_benefit_types')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
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

              $benefit_types = $this->db->get_where("benefit_types",array("Deleted"=>0,"Org_Id"=>$this->org_id));

              if($benefit_types->num_rows() > 0)

              {

                foreach ($benefit_types->result() as $key => $value) {

                   

            ?>

            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12" id="row_<?= $value->Id; ?>">

              <div class="panel org_role">

                <div class="panel-heading">

                  <h4 class="panel-title" style="font-size: 16px;"><?= $value->Name; ?></h4>  
                </div>

                <div class="panel-body"> 
                  <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                  <button onclick="load_view(this,'form_benefit_types','<?= $value->Id; ?>')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Edit Role"><i class="fa fa-pencil"></i></button>
                  <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                  <button onclick="delete_record('benefit_types','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Delete Role"><i class="fa fa-trash"></i></button> 
                  <?php } ?>
                </div>

              </div>

            </div> 

          <?php }}else{ echo no_record_found(); } ?>

          </div> 

        </div>

      </div>

    </div> 



   



  </div><!-- contentpanel -->

</div>



 