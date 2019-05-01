<div class="mainpanel">

    <div class="contentpanel">

     <div class="panel">
         
        <div class="panel-body">
            <div class="col-md-12">
              <div class="col-lg-7 col-md-6 col-sm-6">
                <h3><i class="fa fa-users"></i> Manage Users</h3>
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
              <div class="col-lg-2 col-md-2 col-sm-2">
                 <div class="btn-group pull-right people-pager">
                  <button class="btn btn-primary-active"  onclick="load_view(this,'users-table');"><i class="fa fa-th"></i></button>
                  <a href="javascript:;" class="btn btn-default"  onclick="load_view(this,'users-list');"><i class="fa fa-th-list"></i></a>
                </div>
              </div>
              <div class="col-lg-1 col-md-2 col-sm-2">
                <button onclick="load_view(this,'form_user')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
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
                      <input type="checkbox" onclick="selectallrecords(this,'users_table')" ><span></span>
                    </label>
                  </th> 
                  <th>Name</th> 
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Mobile</th>
                  <th>Joining Date</th>
                  <th>Last Updated</th>
                  <th>Added By</th>
                  <th>Last Updated By</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tfoot>
                <tr>
                 
                  <th>
                    <label class="ckbox ckbox-primary">
                      <input type="checkbox" onclick="selectallrecords(this,'users_table')" ><span></span>
                    </label>
                  </th> 
                  <th>Name</th> 
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Mobile</th>
                  <th>Joining Date</th>
                  <th>Last Updated</th>
                  <th>Added By</th>
                  <th>Last Updated By</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </tfoot>

              <tbody id="users_table">
                <?php 

                  $this->db->where(array("users.Deleted"=>0));
                  $this->db->select("
                                      users.*,
                                      addedby.First_Name as Addedby_FirstName,
                                      addedby.Last_Name as Addedby_LastName,
                                      updatedby.First_Name as Updatedby_FirstName,
                                      updatedby.Last_Name as Updatedby_LastName
                                    ");

                  $this->db->from("users");
                  $this->db->join("users as addedby","users.Added_By = addedby.Id","left");
                  $this->db->join("users as updatedby","users.Modified_By = updatedby.Id","left");
                  $this->db->order_by("Id","DESC");
                  $users = $this->db->get();

                  if($users->num_rows() > 0)
                  {
                    foreach ($users->result() as $key => $value) 
                    { 
                      if($value->Image && $value->Image != "")
                      {
                        $src = ASSETSPATH."panel/userassets/users/".$value->Id."/".$value->Image;
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
                  <td>
                    <img src="<?= $src; ?>" style="width: 30px;"> &nbsp; 
                    <?= $value->First_Name; ?> 
                    <?= $value->Last_Name; ?>
                  </td>
                  <td><?= $value->Email; ?></td>
                  <td><?= $value->Phone; ?></td>
                  <td><?= $value->Mobile; ?></td>
                  <td><?= $value->Date_Added; ?></td>
                  <td><?= $value->Date_Modification; ?></td>
                  <td><?= $value->Addedby_FirstName." ".$value->Addedby_LastName; ; ?></td>
                  <td><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ; ?></td>
                  <td>
                    <label class="switch">
                      <input type="checkbox" <?php if($value->Status == 1){ echo "checked='checked'"; } ?> class="status" table="users" table_id="<?= $value->Id; ?>">
                      <span class="slider"></span>
                    </label>
                  </td>
                  <td>
                    <a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_user','<?= $value->Id; ?>')"> <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                    <a href="javascript:;" onclick="delete_record('users','<?= $value->Id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
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