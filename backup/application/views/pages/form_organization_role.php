 
<?php
  
  $role_data = array();
  if($data != "")
  {
    $check_role = $this->db->get_where("roles",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_role->num_rows() > 0)
    {
      $role_data = $check_role->result_array();
      $role_data = $role_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update  Role</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'roles')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($role_data['Id'])){ echo $role_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="roles">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title"> Role Information</h4> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Role Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($role_data['Name'])){ echo $role_data['Name']; } ?>" class="form-control required" placeholder="Type role name..." required="" aria-required="true">
            </div>
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Role Description <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Description" id="Description" value="<?php if(isset($role_data['Description'])){ echo $role_data['Description']; } ?>" class="form-control required" placeholder="Type role description..." required="" aria-required="true">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-xs-12">
              <div class="tree well">
                <ul>
                  <?php  

                    $role_modules = array();
                    $org_role = $this->session->userdata("role_id");
                    $org_role_data = $this->db->get_where("roles",array("Id"=>$org_role));
                    if($org_role_data->num_rows() > 0)
                    {
                      $org_role_data = $org_role_data->result_array();
                      if(!empty($org_role_data[0]['Permissions']))
                      {
                        $role_modules = (array) json_decode($org_role_data[0]['Permissions']); 
                      }
                      
                    }

                    echo $this->db->last_query();

                    $permissions = array();

                    if(!empty($role_data['Permissions']))
                    {
                      $permissions = (array) json_decode($role_data['Permissions']); 
                    } 
                   
                   

                    $this->db->where_in("Id",$role_modules);    
                    $this->db->order_by("Parent_Module","ASC");
                    $modules =  $this->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1));
                    $processed = array();
                    if($modules->num_rows() > 0)
                    {
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

                            echo '<li> <span><i class="fa fa-plus"></i> </span>
                                    <label class="ckbox ckbox-primary">
                                      <input type="checkbox" onclick="check_modules(this)"  '.$checkbox_checked.' id="module_'.$value->Id.'" value="'.$value->Id.'" name="Permissions[]" class="modules_checkbox"><span class="module_title">'.$value->Name.'</span>
                                    </label>
                                   ';
                            $returned_info = create_childs($value->Id,$processed,$permissions);
                            $processed = $returned_info['ids'];

                            echo $returned_info['html'];
                            echo '</li>';

                           
                        }
                      }
                    }
                  ?>
                   
                </ul>
              </div> 
            </div> 
          </div> 
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'roles');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_organization_role')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
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
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass(' fa-plus').removeClass(' fa-minus');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass(' fa-minus').removeClass(' fa-plus');
        }
        e.stopPropagation();
    });
});

 
</script>