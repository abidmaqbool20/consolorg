 
<?php
  
  $role_data = array();
  if($data != "")
  {
    $this->db->where(array("roles.Deleted"=>0,"permissions.Deleted"=>0,"permissions.Org_Id"=>$this->org_id,"permissions.Id"=>$data));
    $this->db->select("roles.Name as Role_Name,roles.Permissions as Role_Permissions,permissions.*");
    $this->db->from("permissions");
    $this->db->join("roles","roles.Id = permissions.Role_Id","left");
    $check_record = $this->db->get();

    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update  Permission</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'permissions')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save"); ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id'])){ echo $record_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="permissions">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title"> Permission Information</h4> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-6  col-xs-12">
              <label class="control-label">Select Role <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select name="Role_Id" id="Role_Id" onchange="get_role_permissions(this<?php if(isset($record_data['Role_Id'])){ echo ", ".$record_data['Role_Id']; } ?><?php if(isset($record_data['Id'])){ echo ", ".$record_data['Id']; } ?>)" value="<?php if(isset($record_data['Role_Id'])){ echo $record_data['Role_Id']; } ?>" class="form-control select2 required">
                <?php 

                  $this->db->order_by("Id","asc");
                  $roles = $this->db->get_where("roles",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                  if($roles->num_rows() > 0)
                  {
                    foreach ($roles->result() as $key => $value) 
                    {
                      if(isset($record_data['Role_Id'])){ if($record_data['Role_Id'] == $value->id ){ $selected = "selected='selected'"; }else{ $selected = ""; }}
                       echo '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                    }
                  }
                 ?>
                
              </select>
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Permission Title <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Title" id="Title" value="<?php if(isset($record_data['Title'])){ echo $record_data['Title']; } ?>" class="form-control required" placeholder="Type permission title..." required="" aria-required="true">
            </div>
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Permission Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Description" id="Description" value="<?php if(isset($record_data['Description'])){ echo $record_data['Description']; } ?>" class="form-control required" placeholder="Type role description..." required="" aria-required="true">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-xs-12">
              <div class="tree well" id="permissions_data">
                <?php 

                  if(isset($record_data['Role_Id']) && $record_data['Role_Id'] > 0)
                  { 
                        $role_modules = array();
                        $org_role = $record_data['Role_Id'];
                        $org_role_data = $this->db->get_where("roles",array("Id"=>$org_role));
                        if($org_role_data->num_rows() > 0)
                        {
                          $org_role_data = $org_role_data->result_array();
                          if(!empty($org_role_data[0]['Permissions']))
                          {
                            $role_modules = (array) json_decode($org_role_data[0]['Permissions']); 
                          }
                          
                        }

                        $permissions = array();

                        if($record_data['Id'] != "" && $record_data['Id'] > 0)
                        {
                          $permissions_rec = $this->db->get_where("permissions",array("Id"=>$record_data['Id'],"Org_Id"=>$this->org_id,"Deleted"=>0)); 
                          if($permissions_rec->num_rows() > 0)
                          {
                            $permissions_data = $permissions_rec->result_array();
                            $permissions = (array) json_decode($permissions_data[0]['Permissions']); 
                          }
                          
                        } 
                       
                       
                        if(sizeof($role_modules) > 0)
                        { 

                            $this->db->where_in("Id",$role_modules);    
                            $this->db->order_by("Parent_Module","ASC");
                            $modules =  $this->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1));
                            $processed = array();
                            if($modules->num_rows() > 0)
                            {
                              $html = "<ul>";
                              foreach ($modules->result() as $key => $value) 
                              { 
                                if(!in_array($value->Id, $processed))
                                { 
                                    $processed[] = $value->Id;  

                                    if(in_array($value->Id, $permissions))
                                    {
                                      $checkbox_checked = "checked='checked'";
                                    }
                                    else
                                    {
                                      $checkbox_checked = "";
                                    }

                                    $html .= '<li> <span><i class="fa fa-plus"></i> </span>
                                                <label class="ckbox ckbox-primary">
                                                  <input type="checkbox" onclick="check_modules(this)"  '.$checkbox_checked.' id="module_'.$value->Id.'" value="'.$value->Id.'" name="Permissions[]" class="modules_checkbox"><span class="module_title">'.$value->Name.'</span>
                                                </label>
                                               ';

                                    $returned_info = create_permissions_childs($value->Id,$processed,$permissions);
                                    $processed = $returned_info['ids'];

                                    $html .=  $returned_info['html'];
                                    $html .=  '</li>';

                                   
                                }
                              }

                             echo $html .= "</ul>";
                            }

                        }
                  }

                ?>
              </div> 
            </div> 
          </div> 
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'permissions');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_permissions')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
$(function () {
    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(':visible')) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass(' fa-plus').removeClass(' fa-minus');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass(' fa-minus').removeClass(' fa-plus');
        }
        e.stopPropagation();
    });
});

function get_role_permissions($this,$role_id='',$permission_id='')
{
  if($role_id == "")
  {
    $role_id = $($this).val();
  }

  if($role_id != "")
  {  
    $.post("admin/get_role_permissions ",{'Role_Id':$role_id,'permission_id':$permission_id}, function(response)
    {
       if(response)
       {
        $("#permissions_data").html(response);
       }
    });
  }
}
 
</script>