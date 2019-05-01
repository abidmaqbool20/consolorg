<?php 
 
  $module_id = 0000;
  $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Manage Assets","Deleted"=>0,"Status"=>1));
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
                <h3><i class="fa fa-cubes"></i> Manage Assets</h3>
              </div> 
              <div class="col-lg-2 col-md-2 col-sm-3">
                  <select class="form-control selected_action"  onchange="perform_group_action('organization_assets',this)">
                      <option selected="selected">With Selected</option>
                      <?php if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                      <option value="Delete">Delete</option>
                      <?php } if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                      <option value="Disable">Disable</option>
                      <option value="Enable">Enable</option>
                      <option value="Change_Status">Change Status</option> 
                      <?php } ?>
                  </select>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2"> 
               <!--  <div class="btn-group pull-right people-pager">
                  <button class="btn btn-primary-active"  onclick="load_view(this,'organization_assets_table');"><i class="fa fa-th"></i></button>
                  <a href="javascript:;" class="btn btn-default"  onclick="load_view(this,'organization_assets_list');"><i class="fa fa-th-list"></i></a>
                </div> -->
              </div>
              <div class="col-lg-1 col-md-2 col-sm-2">
                <?php if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                <button onclick="load_view(this,'form_organization_assets')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
                <?php } ?>
              </div>
            </div>
        </div>
      </div>

      <div class="panel"> 
        <div class="panel-body">
          <div class="table-responsive">
            <table id="dataTable1" class="table table-bordered table-striped-col">
              <thead>
                <tr> 
                  <th>
                    <label class="ckbox ckbox-primary">
                      <input type="checkbox" onclick="selectallrecords(this,'organization_assets')" ><span></span>
                    </label>
                  </th> 
                  <th>Asset Name</th>  
                  <th>Total Quantity</th>  
                  <th>Purchase Date</th>  
                  <th>Expiry Date</th>  
                  <th>Created Date</th>
                  <th>Last Updated</th>
                  <th>Created By</th>
                  <th>Last Updated By</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tfoot>
                <tr>
                 
                  <th>
                    <label class="ckbox ckbox-primary">
                      <input type="checkbox" onclick="selectallrecords(this,'organization_assets')" ><span></span>
                    </label>
                  </th> 
                  <th>Asset Name</th>  
                  <th>Total Quantity</th>  
                  <th>Purchase Date</th>  
                  <th>Expiry Date</th>  
                  <th>Created Date</th>
                  <th>Last Updated</th>
                  <th>Created By</th>
                  <th>Last Updated By</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </tfoot>

              <tbody id="organization_assets">
                <?php 

                  $this->db->where(array("organization_assets.Deleted"=>0,"organization_assets.Org_Id"=>$this->org_id));
                  $this->db->select("
                                      organization_assets.*, 
                                      addedby.First_Name as Addedby_FirstName,
                                      addedby.Last_Name as Addedby_LastName,
                                      updatedby.First_Name as Updatedby_FirstName,
                                      updatedby.Last_Name as Updatedby_LastName
                                    ");

                  $this->db->from("organization_assets"); 
                  $this->db->join("users as addedby","addedby.Id = organization_assets.Added_By","left");
                  $this->db->join("users as updatedby","updatedby.Id = organization_assets.Modified_By","left"); 
                  $this->db->order_by("Id","DESC");
                  $organization_assets = $this->db->get();

                  if($organization_assets->num_rows() > 0)
                  {
                    foreach ($organization_assets->result() as $key => $value) 
                    { 
                       
                ?>
                <tr id="row_<?= $value->Id; ?>">
                  <td>
                    <label class="ckbox ckbox-primary">
                      <input class="table_record_checkbox" value="<?= $value->Id; ?>" type="checkbox" id="record_<?= $value->Id; ?>" ><span></span>
                    </label>
                  </td>
                  <td><?= $value->Name; ?>   </td>
                  <td><?= $value->Quantity; ?>   </td>
                  <td><?= $value->Purchase_Date; ?>   </td>
                  <td><?= $value->Expiry_Date; ?>   </td> 
                  <td><?= $value->Date_Added; ?></td>
                  <td><?= $value->Date_Modification; ?></td>
                  <td><?= $value->Addedby_FirstName." ".$value->Addedby_LastName;  ?></td>
                  <td><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ?></td>
                  <td>
                    <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <label class="switch">
                      <input type="checkbox" <?php if($value->Status == 1){ echo "checked='checked'"; } ?> class="status" table="organization_assets" table_id="<?= $value->Id; ?>">
                      <span class="slider"></span>
                    </label>
                    <?php } ?>
                  </td>
                  <td>
                    <?php if(in_array($module_id."_edit", $this->role_permissions)){ ?>
                    <a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_department','<?= $value->Id; ?>')"> <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                    <?php }if(in_array($module_id."_delete", $this->role_permissions)){ ?>
                    <a href="javascript:;" onclick="delete_record('organization_assets','<?= $value->Id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
                    <?php } ?>
                  </td>
                </tr>
                <?php }} ?>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- panel -->

      <!-- panel -->

    </div><!-- contentpanel -->
  </div>

  <script type="text/javascript">
    $('#dataTable1').DataTable();
  </script>