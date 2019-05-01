 
 <div class="mainpanel">
  <div class="contentpanel">
      <div class="panel">
         
        <div class="panel-body">
            <div class="col-md-12">
              <div class="col-lg-11 col-md-10 col-sm-10">
                <h3><i class="fa fa-cubes"></i> Manage Plans</h3>
              </div> 
              <div class="col-lg-1 col-md-2 col-sm-2">
                <button onclick="load_view(this,'form_plan')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
              </div>
            </div>
        </div>
      </div>
      <div class="panel"> 
        <div class="panel-body">
           <div class="row">
               
         <?php 

            $this->db->where(array("app_plans.Deleted"=>0));
            $this->db->select("
                                app_plans.*,
                                organization_roles.Permissions as Org_Role,
                                addedby.First_Name as Addedby_FirstName,
                                addedby.Last_Name as Addedby_LastName,
                                updatedby.First_Name as Updatedby_FirstName,
                                updatedby.Last_Name as Updatedby_LastName
                              ");

            $this->db->from("app_plans");
            $this->db->join("users as addedby","app_plans.Added_By = addedby.Id","left");
            $this->db->join("users as updatedby","app_plans.Modified_By = updatedby.Id","left");
            $this->db->join("organization_roles","app_plans.Role_Id = organization_roles.Id","left");
            $this->db->order_by("Id","DESC");
            $app_plans = $this->db->get();

            if($app_plans->num_rows() > 0)
            {
              foreach ($app_plans->result() as $key => $value) 
              { 
                if($value->Show_In_Customer_Panel){ $panel_color = "#262b36"; }else{ $panel_color = ""; }
        ?>
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-success">
              <?php 

                  if($value->Default_Plan)
                  {
              ?>
                <div class="cnrflash">
                    <div class="cnrflash-inner">
                        <span class="cnrflash-label">MOST
                            <br>
                            POPULR</span>
                    </div>
                </div>
              <?php } ?>
                <div class="panel-heading"  style="background-color: <?= $panel_color; ?>">
                    <h3 class="panel-title">
                        <?= $value->Name; ?></h3>
                </div>
                <div class="panel-body plans-panel">
                    <div class="the-price">
                        <h1>
                            <?= $value->Currency." ".$value->Price; ?><span class="subscript">/<?= $value->Duration_Unit; ?></span></h1> 
                    </div>
                    <table class="table">
                        <?php
                          $org_roles = (array) json_decode($value->Org_Role);

                          $this->db->where_in("Id",$org_roles);
                          $modules = $this->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1));
                          if($modules->num_rows() > 0)
                          { 
                            foreach ($modules->result() as $index => $info) 
                            {
                                
                        ?>
                        <tr>
                            <td>
                                <i class="fa fa-check"></i> <?= $info->Name; ?>
                            </td>
                        </tr>
                      <?php }} ?>
                        <tr>
                            <td>
                                <i class="fa fa-check"></i> <?= $value->Storage_Limit." MB's Data Storage"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-check"></i> <?=  $value->Employees." Employees "; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-check"></i> <?=  $value->Job_Posts." Job Posts"; ?>
                            </td>
                        </tr> 
                    </table>
                </div>
                <div class="panel-footer" style="text-align: center;">
                    <a href="javascript:;" onclick="load_view(this,'form_plan','<?= $value->Id; ?>')" class="btn btn-success" style="background-color: <?= $panel_color; ?>" role="button"><i class="fa fa-edit"></i> Edit Plan</a>
                </div>
            </div>
        </div>
      <?php }}else{ echo no_record_found(); } ?>
        
      </div>
        </div>
      </div><!-- panel -->

 </div>
      </div><!-- panel -->
 