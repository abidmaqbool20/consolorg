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

      <div class="row"> 
        
          
            
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

                     


                <div class="col-lg-3 col-md-4 col-sm-6 " id="row_<?= $value->Id; ?>">
                   
                  <div class="panel panel-profile grid-view">
                    <div class="panel-heading">
                      <div class="text-center">
                        <a href="#" class="panel-profile-photo">
                          <img class="img-circle" src="<?= $src; ?>" style="width: 75px;" alt="">
                        </a>
                        <h4 class="panel-profile-name"><?= $value->First_Name." ".$value->Last_Name; ?> </h4>
                        <p class="media-usermeta"><i class="fa fa-user"></i> Admin</p>
                      </div>
                      <ul class="panel-options" style="width: 100%; right: 0px" >
                        <li style="float: left; margin-left: 25px;"> 
                          <label class="ckbox ckbox-primary">
                            <input type="checkbox" onclick="selectallrecords(this,'users_table')" ><span></span>
                          </label>
                        </li>
                        <li style="float: right; margin-right: 40px;">
                          <div class="btn-group fm-group" style="display: block;">
                              <button type="button" class="dropdown_options dropdown-toggle fm-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu pull-right fm-menu" role="menu"> 
                                <li onclick="load_view(this,'form_user','<?= $value->Id; ?>')"><a href="#"><i class="fa fa-pencil"></i> Edit</a></li> 
                                <li onclick="delete_record('users','<?= $value->Id; ?>',this)"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
                                
                              </ul>
                            </div>
                          </li>
                        
                      </ul>
                    </div><!-- panel-heading -->
                    <div class="panel-body people-info">

                      <div class="info-group">
                        <label>Location</label>
                        <?php if($value->Address && $value->Address != ""){  echo $value->Address; } else{ echo "Not Saved!"; } ?>
                      </div>
                      <div class="row">
                        <div class="col-xs-6">
                          <div class="info-group">
                            <label>Email</label>
                            <?php if($value->Email && $value->Email != ""){  echo $value->Email; } else{ echo "Not Saved!"; } ?>
                          </div>
                        </div>
                         <div class="col-xs-6">
                          <div class="info-group">
                              <label class="switch">
                                <input type="checkbox" <?php if($value->Status == 1){ echo "checked='checked'"; } ?> class="status" table="users" table_id="<?= $value->Id; ?>">
                                <span class="slider"></span>
                              </label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-6">
                          <div class="info-group">
                            <label>Phone</label>
                            <?php if($value->Phone && $value->Phone != ""){  echo $value->Phone; } else{ echo "Not Saved!"; } ?>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <div class="info-group">
                            <label>Mobile</label>
                            <?php if($value->Mobile && $value->Mobile != ""){  echo $value->Mobile; } else{ echo "Not Saved!"; } ?>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-xs-6">
                          <div class="info-group">
                            <label>Date Added</label>
                            <h4><?= $value->Date_Added; ?></h4>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <div class="info-group">
                            <label>Added By</label>
                            <h4><?= $value->Addedby_FirstName." ".$value->Addedby_LastName; ; ?></h4>
                          </div>
                        </div>
                      </div>

                       <div class="row">
                        <div class="col-xs-6">
                          <div class="info-group">
                            <label>Date Updated</label>
                            <h4><?= $value->Date_Modification; ?></h4>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <div class="info-group">
                            <label>Modified By</label>
                            <h4><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ; ?></h4>
                          </div>
                        </div>
                      </div>

                     
                    </div><!-- panel-body -->
                  </div><!-- panel -->
                </div>


               
                <?php }} ?>
              
         
      </div>  
    </div> 
  </div>

  <script type="text/javascript">
    $('#dataTable1').DataTable();
  </script>