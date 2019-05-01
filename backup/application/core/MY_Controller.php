<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public $user_id = null;
  public $org_id = null; 
	public $user_data = null;
  public $role_permissions = array();
  public $restrictions = array();
  public $allowed_modules = array();

    public function __construct() {

        parent::__construct();
        
        $userdata = $this->session->all_userdata();
        
        if(isset($userdata['user_id']) && $userdata['user_id'] != "")
        { 
           $this->user_id = $userdata['user_id'];
           $this->org_id = $userdata['org_id']; 
           $u_data = $this->db->get_where("employees",array("Id"=>$this->user_id))->result_array();  
           $this->user_data = $u_data[0]; 
          
           $this->restrictions = $userdata['restrictions'];
           

          if($this->user_data['User_Type'] == "Employee")
          {
            $roles = $this->db->get_where("roles",array("Deleted"=>0,"Status"=>1,"Id"=>$this->user_data['Role_Id']));  
              
                if($roles->num_rows() > 0)
                {   
                  $role_data = $roles->result_array();
                  $role_allowed_modules = (array) json_decode($role_data[0]['Permissions']);
                  $this->allowed_modules = $role_allowed_modules;
                }

                $permissions = $this->db->get_where("permissions",array("Deleted"=>0,"Status"=>1,"Id"=>$this->user_data['Permission_Id']));  
              
                if($permissions->num_rows() > 0)
                {   
                  $permission_data = $permissions->result_array();
                  $role_permissions = (array) json_decode($permission_data[0]['Permissions']);
                  $this->role_permissions = $role_permissions;
                } 
          }
          elseif($this->user_data['User_Type'] == "Super_Admin")
          {
            $organization_role = $this->db->get_where("organization_roles",array("Deleted"=>0,"Status"=>1,"Id"=>$userdata['org_role']));  
              
                if($organization_role->num_rows() > 0)
                {   
                  $organization_role_data = $organization_role->result_array();
                  $role_allowed_modules = (array) json_decode($organization_role_data[0]['Permissions']);
                  $this->allowed_modules = $role_allowed_modules;
                }

                $organization_permissions = $this->db->get_where("organization_permissions",array("Deleted"=>0,"Status"=>1,"Id"=>$userdata['org_permission']));  
              
                if($organization_permissions->num_rows() > 0)
                {   
                  $organization_permissions_data = $organization_permissions->result_array();
                  $role_permissions = (array) json_decode($organization_permissions_data[0]['Permissions']);
                  $this->role_permissions = $role_permissions;
                }
          }
            

            
      	}
      	else
      	{
      		redirect("login");
      	}

    	
    }	


}

?>