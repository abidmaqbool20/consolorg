<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
 

	public $date = null;

	public function __construct() {

		date_default_timezone_set("Asia/Karachi");
        $this->date = date('Y-m-d H:i:s');
        parent::__construct();     

    }

	public function index()
	{
		$this->load->view('login');
	}

	public function validation()
	{ 
		$this->form_validation->set_rules('Username', 'Username', 'required');
		$this->form_validation->set_rules('Password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE)
        {  
            $this->index();
        }
        else
        {  
        	$username = $this->input->post("Username");
			$password = $this->input->post("Password");

			if($username != "" && $password != "")
			{
				$validation = array(
										"Email" => $username,
										"Password" => $password,
										"Deleted" => 0,
										"Status" => 1,
										"Employee_Status" => 'Active'
									);

				$application_users = $this->db->get_where("employees",$validation);

				

				if($application_users->num_rows() > 0)
				{ 
					$user_data = $application_users->result_array();
					$user_info = $user_data[0]; 

					$organization_info = $this->db->get_where("organizations",array("Deleted"=>0,"Status"=>1,"Id"=>$user_info['Org_Id']));
					
					if($organization_info->num_rows() > 0)
					{  
						$get_org_subscription = $this->db->get_where("organization_subscriptions",array( "Subscription_Status"=>1,"Org_Id"=>$user_info['Org_Id']));
						if($get_org_subscription->num_rows() > 0)
						{
							
							$subscription_data = $get_org_subscription->result_array();
							$subscription_data = $subscription_data[0]; 

							$subscription_start_date = date("Y-m-d",strtotime($subscription_data['Subscription_Start_Date']));

							$get_org_plan = $this->db->get_where("app_plans",array( "Deleted"=>0,"Status"=>1,"Id"=>$subscription_data['Plan_Id']));
							if($get_org_plan->num_rows() > 0)
							{
								$get_org_plan_data = $get_org_plan->result_array();
								$get_org_plan_data = $get_org_plan_data[0]; 

								$plan_end_date = "";

								if($get_org_plan_data['Duration_Unit'] == "Month")
								{
									$plan_start_date = strtotime($subscription_start_date);
									$plan_end_date = date("Y-m-d", strtotime("+".$get_org_plan_data['Duration']." month", $plan_start_date));
								}
								elseif($get_org_plan_data['Duration_Unit'] == "Year")
								{
									$plan_start_date = strtotime($subscription_start_date);
									$plan_end_date = date("Y-m-d", strtotime("+".$get_org_plan_data['Duration']." year", $plan_start_date));
								}
								else
								{
									$message = '<div class="alert alert-danger">
												  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
												  <strong>Error!</strong> Subscription period is not valid. Please contact admin.
												</div>';
									$this->session->set_flashdata("message",$message);
				            		$this->index();
								}

								$today = date("Y-m-d");
								

								if(strtotime($today) < strtotime($subscription_start_date))
								{
									$message = '<div class="alert alert-danger">
												  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
												  <strong>Error!</strong> Subscription period is not yet started. Please contact admin.
												</div>';
									$this->session->set_flashdata("message",$message);
				            		$this->index();
								}
								elseif(strtotime($today) > strtotime($plan_end_date))
								{
									$message = '<div class="alert alert-danger">
												  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
												  <strong>Error!</strong> Subscription has been expired. Please contact admin.
												</div>';
									$this->session->set_flashdata("message",$message);
				            		$this->index();

								}
								elseif(strtotime($today) >= strtotime($subscription_start_date) && strtotime($today) <= strtotime($plan_end_date))
								{
									$userdata = array( 
														"user_type" => $user_info['User_Type'],
														"user_id" => $user_info['Id'],
														"org_id" => $user_info['Org_Id'],
														"role_id" => $user_info['Role_Id'],
														"plan_id" => $get_org_plan_data['Id'], 
														"org_role" => $get_org_plan_data['Role_Id'],
														"org_permission" => $get_org_plan_data['Permission_Id'],
														"loged_in" => true 
													  ); 

									$userdata['restrictions'] = array(
																		"Storage_Limit" => $get_org_plan_data['Storage_Limit'],
																		"Employees" => $get_org_plan_data['Employees'],
																		"Job_Posts" => $get_org_plan_data['Job_Posts'],
																	 );

 									
									$this->session->set_userdata($userdata);
									redirect("account");
								}
								else
								{
									$message = '<div class="alert alert-danger">
												  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
												  <strong>Error!</strong> Subscription Plan has been expired. Please contact admin.
												</div>';
									$this->session->set_flashdata("message",$message);
				            		$this->index();
								}


							}
							else
							{
								$message = '<div class="alert alert-danger">
											  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											  <strong>Error!</strong> Subscription plan is not found. Please contact admin.
											</div>';
								$this->session->set_flashdata("message",$message);
			            		$this->index();
							}
						}
						else
						{
							$message = '<div class="alert alert-danger">
										  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										  <strong>Error!</strong> Subscription has been expired. Please contact admin.
										</div>';
							$this->session->set_flashdata("message",$message);
		            		$this->index();
						} 

					}
					else
					{
						$message = '<div class="alert alert-danger">
									  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									  <strong>Error!</strong> Your organization is not registered or temporarily blocked.
									</div>';
						$this->session->set_flashdata("message",$message);
	            		$this->index();
					} 
					
				}
				else
				{ 
					$message = '<div class="alert alert-danger">
								  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								  <strong>Error!</strong> Invalid Credentials.
								</div>';
					$this->session->set_flashdata("message",$message);
            		$this->index();
				} 
				
			}
        }

		
		 
	}
}
