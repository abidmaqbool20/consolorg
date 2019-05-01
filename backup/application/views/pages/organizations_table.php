<div class="mainpanel">

    <div class="contentpanel">

     <div class="panel">
         
        <div class="panel-body">
            <div class="col-md-12">
              <div class="col-lg-9 col-md-8 col-sm-8">
                <h3><i class="fa fa-users"></i> Manage Organizations</h3>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-3">
                  <select class="form-control selected_action"  onchange=" perform_group_action('users',this)">
                      <option selected="selected">With Selected</option>
                      <option value="Delete">Delete</option>
                      <option value="Disable">Disable</option>
                      <option value="Enable">Enable</option>
                      <option value="Change_Status">Change Status</option> 
                  </select>
              </div> 
              <div class="col-lg-1 col-md-2 col-sm-2">
                <button onclick="load_view(this,'form_organization')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
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
                      <input type="checkbox" onclick="selectallrecords(this,'organizations_table')" ><span></span>
                    </label>
                  </th> 
                  <th>Org Name</th> 
                  <th>Org Email</th>
                  <th>Org Phone</th>
                  <th>Org Mobile</th>
                  <th>Org Location</th>
                  
                  <th>Admin Name</th> 
                  <th>Admin Email</th> 
                  <th>Admin Mobile</th>

                  <th>Subscription Start Date</th>
                  <th>Subscription End Date</th>
                  
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tfoot>
                <tr>
                 
                  <th>
                    <label class="ckbox ckbox-primary">
                      <input type="checkbox" onclick="selectallrecords(this,'organizations_table')" ><span></span>
                    </label>
                  </th> 
                  <th>Org Name</th> 
                  <th>Org Email</th>
                  <th>Org Phone</th>
                  <th>Org Mobile</th>
                  <th>Org Location</th>
                  
                  <th>Admin Name</th> 
                  <th>Admin Email</th> 
                  <th>Admin Mobile</th>

                  <th>Subscription Start Date</th>
                  <th>Subscription End Date</th>
                  
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </tfoot>

              <tbody id="organizations_table">
                <?php 

                  $this->db->where(array("organizations.Deleted"=>0));
                  $this->db->select("
                                      organizations.*,
                                      countries.name as Country_Name,
                                      states.name as State_Name,
                                      cities.name as City_Name,
                                      addedby.First_Name as Addedby_FirstName,
                                      addedby.Last_Name as Addedby_LastName,
                                      updatedby.First_Name as Updatedby_FirstName,
                                      updatedby.Last_Name as Updatedby_LastName
                                    ");

                  $this->db->from("organizations");
                  $this->db->join("countries","organizations.Country = countries.id","left");
                  $this->db->join("states","organizations.State = states.id","left");
                  $this->db->join("cities","organizations.City = cities.id","left");
                  $this->db->join("users as addedby","organizations.Added_By = addedby.Id","left");
                  $this->db->join("users as updatedby","organizations.Modified_By = updatedby.Id","left");
                  $this->db->order_by("Id","DESC");
                  $organizations = $this->db->get();

                  if($organizations->num_rows() > 0)
                  {
                    foreach ($organizations->result() as $key => $value) 
                    { 
                      if($value->Logo && $value->Logo != "")
                      {
                        $src = ASSETSPATH."panel/userassets/organizations/".$value->Id."/".$value->Logo;
                      }
                      else
                      {
                        $src = ASSETSPATH."images/default-user.png";
                      }
                ?>
                <tr id="row_<?= $value->Id; ?>">
                  <td>
                    <label class="ckbox ckbox-primary">
                      <input class="table_record_checkbox" value="<?= $value->Id; ?>" type="checkbox" id="record_<?= $value->Id; ?>" ><span></span>
                    </label>
                  </td>
                  <td style="min-width: 180px;">
                    <a href="javascript:;"  onclick="load_view(this,'organization_view','<?= $value->Id; ?>')">
                      <img src="<?= $src; ?>" style="width: 30px;"> &nbsp; 
                      <?= $value->Name; ?>  
                    </a>
                  </td>
                  <td><?= $value->Primary_Email; ?></td>
                  <td><?= $value->Primary_Phone; ?></td>
                  <td><?= $value->Primary_Mobile; ?></td>
                  <td><?= $value->Address.", ".$value->City_Name.", ".$value->State_Name.", ".$value->Country_Name; ?></td>

                  <td><?= $value->CP_Name; ?></td>
                  <td><?= $value->CP_Email; ?></td>
                  <td><?= $value->CP_Mobile_1; ?></td>
                 

                  <td><?= $value->Subscription_Start_Date; ?></td>
                  <td><?= $value->Subscription_End_Date; ?></td>

           
                  <td>
                    <label class="switch">
                      <input type="checkbox" <?php if($value->Status == 1){ echo "checked='checked'"; } ?> class="status" table="organizations" table_id="<?= $value->Id; ?>">
                      <span class="slider"></span>
                    </label>
                  </td>
                  <td>
                    <a href="javascript:;" style="color: black;" onclick="load_view(this,'organization_view','<?= $value->Id; ?>')"> <i class="fa fa-search-plus"></i> </a>&nbsp;&nbsp;
                    <a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_organization','<?= $value->Id; ?>')"> <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                    <a href="javascript:;" onclick="delete_record('organizations','<?= $value->Id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
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