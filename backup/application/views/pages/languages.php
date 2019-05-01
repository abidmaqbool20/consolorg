<div class="mainpanel">

    <div class="contentpanel">

     <div class="panel"> 
        <div class="panel-body">
            <div class="col-md-12">
              <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                <h3><i class="fa fa-cubes"></i> Manage Languages</h3>
              </div>  
              <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                <button onclick="load_view(this,'form_training_group')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
              </div>
            </div>
        </div>
      </div>

      <div class="panel" style="min-height: 75vh;">
        <div class="panel-heading">
          <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3">
                <select class="form-control selected_action"  onchange="perform_group_action('languages',this)">
                    <option selected="selected">With Selected</option>
                    <option value="Delete">Delete</option>
                    <option value="Disable">Disable</option>
                    <option value="Enable">Enable</option>
                    <option value="Change_Status">Change Status</option> 
                </select>
              </div>
              
            <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
               <input type="text" class="form-control" name="">
            </div>
            <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
               <button class="btn btn-warning btn-quirk"><i class="fa fa-filter"></i>&nbsp; Filter Table</button> 
            </div>
          </div> 
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered nomargin">
              <thead>
                 <tr> 
                  <th>
                    <label class="ckbox ckbox-primary">
                      <input type="checkbox" onclick="selectallrecords(this,'locations')" ><span></span>
                    </label>
                  </th> 
                  <th>Language Name</th>   
                  <th>Language Code</th>   
                  <th>Created Date</th>
                  <th>Last Updated</th>
                  <th>Created By</th>
                  <th>Last Updated By</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 

                  $this->db->where(array("languages.Deleted " =>0));
                  $this->db->select("
                                      languages.*, 
                                      addedby.First_Name as Addedby_FirstName,
                                      addedby.Last_Name as Addedby_LastName,
                                      updatedby.First_Name as Updatedby_FirstName,
                                      updatedby.Last_Name as Updatedby_LastName

                                    ");

                  $this->db->from("languages"); 
                  $this->db->join("users as addedby","addedby.Id = languages.Added_By","left");
                  $this->db->join("users as updatedby","updatedby.Id = languages.Modified_By","left"); 
                  $this->db->order_by("id","DESC");
                  $this->db->limit("10");
                  $languages = $this->db->get();

                  if($languages->num_rows() > 0)
                  {
                    foreach ($languages->result() as $key => $value) 
                    { 
                       
                ?>
                <tr id="row_<?= $value->id; ?>">
                  <td>
                    <label class="ckbox ckbox-primary">
                      <input class="table_record_checkbox" value="<?= $value->id; ?>" type="checkbox" id="record_<?= $value->id; ?>" ><span></span>
                    </label>
                  </td>
                  <td><?= $value->name; ?>   </td> 
                  <td><?= $value->code; ?>   </td> 
                  <td><?= $value->Date_Added; ?></td>
                  <td><?= $value->Date_Modification; ?></td>
                  <td><?= $value->Addedby_FirstName." ".$value->Addedby_LastName;  ?></td>
                  <td><?= $value->Updatedby_FirstName." ".$value->Updatedby_LastName; ?></td>
                  <td>
                    <label class="switch">
                      <input type="checkbox" <?php if($value->Status == 1){ echo "checked='checked'"; } ?> class="status" table="locations" table_id="<?= $value->id; ?>">
                      <span class="slider"></span>
                    </label>
                  </td>
                  <td>
                    <a href="javascript:;" style="color: blue;" onclick="load_view(this,'form_locations','<?= $value->id; ?>')"> <i class="fa fa-pencil"></i> </a>&nbsp;&nbsp;
                    <a href="javascript:;" onclick="delete_record('locations','<?= $value->id; ?>',this)" style="color: red;"> <i class="fa fa-trash"></i></a>
                  </td>
                </tr>
                <?php }} ?>
                
              </tbody>
            </table>
          </div><!-- table-responsive -->
        </div>
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2">
              <select>
                <option>10</option>
                <option>50</option>
                <option>100</option>
              </select>
            </div>
          </div> 
        </div>
    </div>
  </div> 
</div>
