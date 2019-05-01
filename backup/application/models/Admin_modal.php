<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_modal extends CI_Model {

 	public function __construct()
 	{
 		parent::__construct();
 	}

 	public function get_employees($data=array())
 	{
 		$page = $data['page'];
 		$per_page = $data['per_page']; 
 	 
 		$org_id = $this->session->userdata('org_id');
 		foreach ($data['filter_data'] as $key => $value) 
 		{
 			$filter_data[$value['name']] = $value['value'];
 		}
 
		$offset = ($page - 1) * $per_page;
		$limit =  $per_page;
 		 	

 			   $this->db->select('
 			   					  employees.*, 
                                  roles.name as Role_Name, 
                                  locations.Name as Location_Name,  
                                  addedby.First_Name as Addedby_FirstName,
                                  addedby.Last_Name as Addedby_LastName,
                                  updatedby.First_Name as Updatedby_FirstName,
                                  updatedby.Last_Name as Updatedby_LastName
                                ')
 							->from('employees')
							->join("roles","roles.Id = employees.Role_Id","left")
							->join("locations","locations.Id = employees.Location_Id","left") 
            	->join("employees as addedby","addedby.Id = employees.Added_By","left")
            	->join("employees as updatedby","updatedby.Id = employees.Modified_By","left");

			            	$where = array(
			                                        "employees.Deleted"=> 0,  
			                                        "employees.Org_Id" => $org_id
			                              );
			            	if($filter_data['Role_Id'] > 0) { $where['roles.Deleted'] = 0; }
			                if($filter_data['Location_Id'] > 0) { $where['locations.Deleted'] = 0; }
			                
				        	$this->db->group_start()->where( $where );
				                

			                $or_where = "";
			                if($filter_data['Role_Id'] > 0) { $or_where['employees.Role_Id'] = $filter_data['Role_Id']; }
			                if($filter_data['Location_Id'] > 0) { $or_where['employees.Location_Id'] = $filter_data['Location_Id']; }
			                if($filter_data['Employee_Status'] != '0') { $or_where['employees.Employee_Status'] = $filter_data['Employee_Status']; }

			                if(!empty($or_where))
			                {	
			                	$this->db->group_start();
			                	$this->db->or_where($or_where);
			                	$this->db->group_end(); 
			                }

				                    
				               
				        	$this->db->group_end();
				        	

			if($filter_data['Text'] != "")
 		 	{
 		 		$like_query = $this->db->group_start()
 		 						->or_like(
				        			array(
	                                        "employees.First_Name"=> $filter_data["Text"], 
	                                        "employees.Last_Name"=> $filter_data["Text"], 
	                                        "employees.Email"=> $filter_data["Text"], 
	                                        "employees.Phone_Number"=> $filter_data["Text"], 
	                                        "employees.Mobile_Number_1"=> $filter_data["Text"], 
	                                        "employees.Mobile_Number_2"=> $filter_data["Text"], 
	                                        "employees.Employee_Status"=> $filter_data["Text"], 
	                                        "employees.Gender"=> $filter_data["Text"], 
	                                        "employees.CNIC"=> $filter_data["Text"], 
	                                        "employees.Religion"=> $filter_data["Text"], 
	                                        "employees.Blood_Group"=> $filter_data["Text"], 
	                                        "employees.Source_Of_Hire"=> $filter_data["Text"], 
	                                        "employees.Work_Phone"=> $filter_data["Text"], 
	                                        "employees.Phone_Extension"=> $filter_data["Text"], 
	                                        "employees.Joining_Date"=> $filter_data["Text"] 
	                                         
	                                     ), "both"
				        		);

				$this->db->group_end();        		
 		 	}  

		$this->db->limit($limit,$offset);	
		$this->db->order_by("Id","DESC");	        		 
	    $result = $this->db->get();

	    //echo $this->db->last_query(); 

 		$total_records =  $this->db->get_where("employees",array("Deleted"=>0,"Org_Id"=>$this->org_id))->num_rows(); 

 		$rows =  $result->result();
 		 
 		$response['result'] = $rows;
 		$response['total_records'] = $total_records;

 		return  $response;	
 	}

 	public function get_applicants($data=array())
 	{
 		$page = $data['page'];
 		$per_page = $data['per_page']; 
 	 
 		$org_id = $this->session->userdata('org_id');
 		
 		foreach ($data['filter_data'] as $key => $value) 
 		{
 			if(isset($data['filter_data'][$key+1]))
 			{
 				if($value['name'] == $data['filter_data'][$key+1]['name'])
	 			{
	 				$i = 0;
	 				foreach ($data['filter_data'] as $index => $content) 
	 				{
	 					if($value['name'] == $content['name'])
	 					{
	 						$filter_data[$content['name']][$i] = $content['value'];
	 						$i = $i + 1;
	 					} 
	 				}
	 			}
	 			elseif(!isset($filter_data[$value['name']]))
	 			{
	 				$filter_data[$value['name']] = $value['value'];
	 			}

 			} 
 			elseif(!isset($filter_data[$value['name']]))
 			{
 				$filter_data[$value['name']] = $value['value'];
 			}
 			
 		}
 		
 		 

		$offset = ($page - 1) * $per_page;
		$limit =  $per_page;
 		 	

 			$this->db->select('
 			   					  applicants.*, 
                                  job_posts.Title as Job_Title, 
                                  job_posts.Location_Id, 
                                  applicant_applications.Applicant_Id,
                                  applicant_applications.Job_Id,
                                  applicant_applications.Application_Status,
                                  applicant_applications.Applied_Date,
                                  locations.Name as Location_Name,  
                                  countries.name as Country_Name,
                                  states.name as State_Name,
                                  cities.name as City_Name,
                                  addedby.First_Name as Addedby_FirstName,
                                  addedby.Last_Name as Addedby_LastName,
                                  updatedby.First_Name as Updatedby_FirstName,
                                  updatedby.Last_Name as Updatedby_LastName,
                                  applicant_education.Institute,
                                  applicant_education.Marks_Obtained,
                                  universities.Name as Institute_Name,
                                ')
    			->from('applicants')
    			->join("applicant_applications","applicant_applications.Applicant_Id = applicants.Id","left")
    			->join("job_posts","job_posts.Id = applicant_applications.Job_Id","left")
    			->join("locations","locations.Id = job_posts.Location_Id","left") 
        	->join("employees as addedby","addedby.Id = applicants.Added_By","left")
        	->join("employees as updatedby","updatedby.Id = applicants.Modified_By","left")
        	->join("countries","countries.id = applicants.Country","left")
        	->join("states","states.id = applicants.State","left")
        	->join("cities","cities.id = applicants.City","left");

        	$where = array(
                                    "applicants.Deleted"=> 0,  
                                    "applicants.Org_Id" => $org_id
                          );

        	  if($filter_data['Job_Id'] > 0) { $where['applicant_applications.Job_Id'] = $filter_data['Job_Id']; }
            if($filter_data['Location_Id'] > 0) { $where['locations.Id'] = $filter_data['Location_Id']; }
            if($filter_data['Nationality'] > 0) { $where['applicants.Nationality'] = $filter_data['Nationality']; }
            if($filter_data['Country'] > 0) { $where['applicants.Country'] = $filter_data['Country']; }
            if($filter_data['State'] > 0) { $where['applicants.State'] = $filter_data['State']; }
            if($filter_data['City'] > 0) { $where['applicants.City'] = $filter_data['City']; }
            if($filter_data['Gender'] > 0) { $where['applicants.Gender'] = $filter_data['Gender']; }
            if($filter_data['Degree_Type'] > 0){

            	 $this->db->join("applicant_education","applicant_education.Applicant_Id = applicants.Id AND  applicant_education.Degree_Type = '$filter_data[Degree_Type]'","left");
            	 $where['applicant_education.Degree_Type'] = $filter_data['Degree_Type']; 
            }
            else{ $this->db->join("applicant_education","applicant_education.Applicant_Id = applicants.Id AND  applicant_education.Degree_Type = 'Bachelors'","left"); }
            if(sizeof($filter_data['Institute']) > 0) {

            	if( $filter_data['Institute'][0]  > 0){ 
            		$this->db->where_in("applicant_education.Institute",$filter_data['Institute']); 
            	} 
            }
            if($filter_data['Marks_Obtained'] > 0) {

            	if($filter_data['Marks_Value'] == "Equal_To")
            	{ $where['applicant_education.Marks_Obtained'] = $filter_data['Marks_Obtained'];  }
            	elseif($filter_data['Marks_Value'] == "Greater_Than")
            	{ $where['applicant_education.Marks_Obtained >'] = $filter_data['Marks_Obtained'];  } 
            	elseif($filter_data['Marks_Value'] == "Less_Than")
            	{ $where['applicant_education.Marks_Obtained <'] = $filter_data['Marks_Obtained'];  } 
            	elseif($filter_data['Marks_Value'] == "Greater_Than_Equal_To")
            	{ $where['applicant_education.Marks_Obtained >='] = $filter_data['Marks_Obtained'];  } 
            	elseif($filter_data['Marks_Value'] == "Less_Than_Equal_To")
            	{ $where['applicant_education.Marks_Obtained <='] = $filter_data['Marks_Obtained'];  } 
            	
            }
            
            $this->db->join("universities","universities.Id = applicant_education.Institute","left");

        	$this->db->group_start()->where( $where );
                

            $or_where = "";
            if($filter_data['Job_Id'] > 0) { $or_where['applicant_applications.Job_Id'] = $filter_data['Job_Id']; }
            if($filter_data['Location_Id'] > 0) { $or_where['applicants.Location_Id'] = $filter_data['Location_Id']; }
            if($filter_data['Application_Status'] != '0') { $or_where['applicant_applications.Application_Status'] = $filter_data['Application_Status']; }

            if(!empty($or_where))
            {	
            	$this->db->group_start();
            	$this->db->or_where($or_where);
            	$this->db->group_end(); 
            }

        	$this->db->group_end();
				        	

			if($filter_data['Text'] != "")
 		 	{
 		 		$like_query = $this->db->group_start()
 		 						->or_like(
				        			array(
	                                        "applicants.First_Name"=> $filter_data["Text"], 
	                                        "applicants.Last_Name"=> $filter_data["Text"], 
	                                        "applicants.Email"=> $filter_data["Text"], 
	                                        "applicants.Phone_Number"=> $filter_data["Text"], 
	                                        "applicants.Mobile_Number_1"=> $filter_data["Text"], 
	                                        "applicants.Mobile_Number_2"=> $filter_data["Text"],  
	                                        "applicants.Gender"=> $filter_data["Text"], 
	                                        "applicants.CNIC"=> $filter_data["Text"], 
	                                        "applicants.Religion"=> $filter_data["Text"], 
	                                        "applicants.Blood_Group"=> $filter_data["Text"], 
	                                        "applicants.Source_Of_Hire"=> $filter_data["Text"]  
	                                     ), "both"
				        		);

				$this->db->group_end();        		
 		 	}  

	 		$this->db->order_by("applicants.Id","DESC");   
			$this->db->order_by("Id","DESC");	   
			$this->db->group_by("applicants.Id");     		 
		    $total_records = $this->db->get()->num_rows();




 


		    $this->db->select('
 			   					  applicants.*, 
                                  job_posts.Title as Job_Title, 
                                  job_posts.Location_Id, 
                                  applicant_applications.Applicant_Id,
                                  applicant_applications.Job_Id,
                                  applicant_applications.Application_Status,
                                  applicant_applications.Applied_Date,
                                  locations.Name as Location_Name,  
                                  countries.name as Country_Name,
                                  states.name as State_Name,
                                  cities.name as City_Name,
                                  addedby.First_Name as Addedby_FirstName,
                                  addedby.Last_Name as Addedby_LastName,
                                  updatedby.First_Name as Updatedby_FirstName,
                                  updatedby.Last_Name as Updatedby_LastName,
                                  applicant_education.Institute,
                                  applicant_education.Marks_Obtained,
                                  applicant_education.Degree_Type,
                                  universities.Name as Institute_Name,
                                ')
    			->from('applicants')
    			->join("applicant_applications","applicant_applications.Applicant_Id = applicants.Id","left")
    			->join("job_posts","job_posts.Id = applicant_applications.Job_Id","left")
    			->join("locations","locations.Id = job_posts.Location_Id","left") 
        	->join("employees as addedby","addedby.Id = applicants.Added_By","left")
        	->join("employees as updatedby","updatedby.Id = applicants.Modified_By","left")
        	->join("countries","countries.id = applicants.Country","left")
        	->join("states","states.id = applicants.State","left")
        	->join("cities","cities.id = applicants.City","left");
        	

        	$where = array(
                                    "applicants.Deleted"=> 0,  
                                    "applicants.Org_Id" => $org_id
                          );

        	  if($filter_data['Job_Id'] > 0) { $where['applicant_applications.Job_Id'] = $filter_data['Job_Id']; }
            if($filter_data['Location_Id'] > 0) { $where['locations.Id'] = $filter_data['Location_Id']; }
            if($filter_data['Nationality'] > 0) { $where['applicants.Nationality'] = $filter_data['Nationality']; }
            if($filter_data['Country'] > 0) { $where['applicants.Country'] = $filter_data['Country']; }
            if($filter_data['State'] > 0) { $where['applicants.State'] = $filter_data['State']; }
            if($filter_data['City'] > 0) { $where['applicants.City'] = $filter_data['City']; }
            if($filter_data['Gender'] > 0) { $where['applicants.Gender'] = $filter_data['Gender']; }
            if($filter_data['Degree_Type'] > 0){

            	 $this->db->join("applicant_education","applicant_education.Applicant_Id = applicants.Id AND  applicant_education.Degree_Type = '$filter_data[Degree_Type]'","left");
            	 $where['applicant_education.Degree_Type'] = $filter_data['Degree_Type']; 
            }
            else{ $this->db->join("applicant_education","applicant_education.Applicant_Id = applicants.Id AND  applicant_education.Degree_Type = 'Bachelors'","left"); }
            if(sizeof($filter_data['Institute']) > 0) {

            	if( $filter_data['Institute'][0]  > 0){ 
            		$this->db->where_in("applicant_education.Institute",$filter_data['Institute']); 
            	} 
            }
            if($filter_data['Marks_Obtained'] > 0) {

            	if($filter_data['Marks_Value'] == "Equal_To")
            	{ $where['applicant_education.Marks_Obtained'] = $filter_data['Marks_Obtained'];  }
            	elseif($filter_data['Marks_Value'] == "Greater_Than")
            	{ $where['applicant_education.Marks_Obtained >'] = $filter_data['Marks_Obtained'];  } 
            	elseif($filter_data['Marks_Value'] == "Less_Than")
            	{ $where['applicant_education.Marks_Obtained <'] = $filter_data['Marks_Obtained'];  } 
            	elseif($filter_data['Marks_Value'] == "Greater_Than_Equal_To")
            	{ $where['applicant_education.Marks_Obtained >='] = $filter_data['Marks_Obtained'];  } 
            	elseif($filter_data['Marks_Value'] == "Less_Than_Equal_To")
            	{ $where['applicant_education.Marks_Obtained <='] = $filter_data['Marks_Obtained'];  } 
            	
            }
            
            $this->db->join("universities","universities.Id = applicant_education.Institute","left");



        	$this->db->group_start()->where( $where );
                

            $or_where = "";
            if($filter_data['Job_Id'] > 0) { $or_where['applicant_applications.Job_Id'] = $filter_data['Job_Id']; }
            if($filter_data['Location_Id'] > 0) { $or_where['applicants.Location_Id'] = $filter_data['Location_Id']; }
            if($filter_data['Application_Status'] != '0') { $or_where['applicant_applications.Application_Status'] = $filter_data['Application_Status']; }

            if(!empty($or_where))
            {	
            	$this->db->group_start();
            	$this->db->or_where($or_where);
            	$this->db->group_end(); 
            }

        	$this->db->group_end();
				        	

			if($filter_data['Text'] != "")
 		 	{
 		 		$like_query = $this->db->group_start()
 		 						->or_like(
				        			array(
	                                        "applicants.First_Name"=> $filter_data["Text"], 
	                                        "applicants.Last_Name"=> $filter_data["Text"], 
	                                        "applicants.Email"=> $filter_data["Text"], 
	                                        "applicants.Phone_Number"=> $filter_data["Text"], 
	                                        "applicants.Mobile_Number_1"=> $filter_data["Text"], 
	                                        "applicants.Mobile_Number_2"=> $filter_data["Text"],  
	                                        "applicants.Gender"=> $filter_data["Text"], 
	                                        "applicants.CNIC"=> $filter_data["Text"], 
	                                        "applicants.Religion"=> $filter_data["Text"], 
	                                        "applicants.Blood_Group"=> $filter_data["Text"], 
	                                        "applicants.Source_Of_Hire"=> $filter_data["Text"],  
	                                     ), "both"
				        		);

				$this->db->group_end();        		
 		 	}  

	 		$this->db->order_by("applicants.Id","DESC");  
			$this->db->limit($limit,$offset);	
			$this->db->order_by("Id","DESC");	        		 
		    $this->db->group_by("applicants.Id");
		    $result = $this->db->get();

		    //echo $this->db->last_query();  

	 		$rows =  $result->result();
	 		 
	 		$response['result'] = $rows;
	 		$response['total_records'] = $total_records;

	 		return  $response;	
 	}

  public function get_applicants_for_excel($records)
  { 
    
      $org_id = $this->session->userdata('org_id');
     
      $this->db->where_in("applicants.Id",$records);
      $this->db->where(array("applicants.Deleted" =>0,"applicants.Org_Id"=>$org_id));
      $this->db->select('
                    applicants.First_Name, 
                    applicants.Last_Name, 
                    applicants.Email, 
                    applicants.Phone_Number, 
                    applicants.Mobile_Number_1, 
                    applicants.Mobile_Number_2, 
                    applicants.DOB, 
                    applicants.Gender, 
                    applicants.Martial_Status, 
                    applicants.CNIC, 
                    religions.Religion as Applicant_Religion, 
                    applicants.Blood_Group, 
                    applicants.Source_Of_Hire, 
                    
                    job_posts.Title as Applied_Job_Title,  
 
                    applicant_applications.Application_Status,
                    applicant_applications.Applied_Date,

                    locations.Name as Location_Name,  

                    countries.name as Current_Country_Name, 
                    states.name as Current_State_Name, 
                    cities.name as Current_City_Name,
                    applicants.Zipcode as Current_Zipcode,
                    applicants.Address as Current_Address,

                    per_country.name as Permanent_Country_Name,
                    per_states.name as Permanent_State_Name,
                    per_cities.name as Permanent_City_Name,
                    applicants.P_Zipcode as Permanent_Zipcode,
                    applicants.P_Address as Permanent_Address,
                     
                     
                    
                    applicant_education.Degree_Type, 
                    applicant_education.Degree_Name,  
                    universities.Name as Institute_Name,
                    applicant_education.Result_Date, 
                    applicant_education.Marks_Obtained,
                    applicant_education.Total_Marks,

              
                    applicant_experience.ORG_Name,
                    applicant_experience.Designation,
                    applicant_experience.Job_Field,
                    applicant_experience.Job_Start_Date,
                    applicant_experience.Job_End_Date,

                    applicant_socialmedia_account.Facebook,
                    applicant_socialmedia_account.Linkedin,
                    applicant_socialmedia_account.Twitter,
                    applicant_socialmedia_account.Instagram,
                    applicant_socialmedia_account.Youtube,
                  ');

      $this->db->from("applicants"); 
      $this->db->join("applicant_applications","applicant_applications.Applicant_Id = applicants.Id","left"); 
      $this->db->join("job_posts","job_posts.Id = applicant_applications.Job_Id","left"); 
      $this->db->join("locations","locations.Id = job_posts.Location_Id","left"); 
      $this->db->join("employees as addedby","addedby.Id = applicants.Added_By","left");
      $this->db->join("employees as updatedby","updatedby.Id = applicants.Modified_By","left"); 
      $this->db->join("countries","countries.id = applicants.Country","left");
      $this->db->join("states","states.id = applicants.State","left");
      $this->db->join("cities","cities.id = applicants.City","left");
      $this->db->join("countries as per_country","per_country.id = applicants.P_Country","left");
      $this->db->join("states as per_states","per_states.id = applicants.P_State","left");
      $this->db->join("cities as per_cities","per_cities.id = applicants.P_City","left");
      $this->db->join("applicant_socialmedia_account","applicant_socialmedia_account.Applicant_Id = applicants.Id","left");
      $this->db->join("applicant_experience","applicant_experience.Applicant_Id = applicants.Id","left");
      $this->db->join("religions","religions.Id = applicants.Religion","left");

      $this->db->join("applicant_education","applicant_education.Applicant_Id = applicants.Id  AND applicant_education.Degree_Type='Bachelors'","left");
      $this->db->join("universities","universities.Id = applicant_education.Institute","left");

      $this->db->order_by("applicant_applications.Id","DESC");  
      $this->db->group_by("applicants.Id");
      $get_records = $this->db->get();
 
      return  $get_records;  
  }



}

?>