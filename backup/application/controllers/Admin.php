<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
 
    public $date = null;
	public $time = null;
	public $modules = array();
    public $processed = array();
    public $counter = 0;



	public function __construct() 
	{ 
        parent::__construct();      

        date_default_timezone_set("Asia/Karachi");
        $this->date = date('Y-m-d H:i:s'); 
        $this->time = date('H:i:s'); 
    }

    public function index()
	{
		$data['view'] = "viewloader";
		$this->load->view('pages/template',$data);
	}

	public function load_view()
	{
		$view_data = $this->input->post();   
		echo $this->load->view('pages/'.$view_data['view'],$view_data, TRUE);
	}

	public function load_form()
	{
		$form_data = $this->input->post();  

		echo $this->load->view('pages/forms/'.$form_data['form'],$form_data, TRUE);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect("login");
	}


    public function save_multiple_files($path,$files=array(),$table,$id,$employee_id) 
    { 
        $filedata = array(); 
        if (!file_exists($path)) {  mkdir($path, 0777, true); } 
        $filesCount = count($files); 

        for($i = 0; $i < $filesCount; $i++) 
        { 
            $_FILES['userFile']['name'] = $files[$i]['name']; 
            $_FILES['userFile']['type'] = $files[$i]['type']; 
            $_FILES['userFile']['tmp_name'] = $files[$i]['tmp_name']; 
            $_FILES['userFile']['error'] = $files[$i]['error']; 
            $_FILES['userFile']['size'] = $files[$i]['size']; 

            $config = array(); 
            $config['upload_path']          = $path; 
            $config['allowed_types']        = '*'; 
            $config['max_size']             = 1024*1024*1024*1024*1024*1024; 
            $config['encrypt_name']         = true; 

            $this->load->library('upload', $config); 
            $this->upload->initialize($config); 
            if($this->upload->do_upload('userFile'))
            { 
                $fileData = $this->upload->data(); 
                $filedata = array();

                $filedata['Table_Id']   =    $id; 
                $filedata['Org_Id'] = $this->org_id; 
                $filedata['Employee_Id'] =  $employee_id; 
                $filedata['Table_Name'] =    $table; 
                $filedata["Original_Name"] = $fileData['file_name']; 
                $filedata['File_Name'] =     $_FILES['userFile']['name'];  
                $filedata['File_Type'] =     $_FILES['userFile']['type']; 
                $filedata['File_Size'] =     $_FILES['userFile']['size']; 
                $filedata['Date_Added']=     $this->date; 
                $filedata['Added_By']  =     $this->user_data['Id']; 
                $filedata['Modified_By'] =   $this->user_data['Id']; 

                $this->db->insert("files",$filedata);   
            }

        }
 
        return true; 
    } 

	public function save_file($pathh,$name) 
    { 
        if (!file_exists($pathh)) {  mkdir($pathh, 0777, true); } 

        $config = array(); 
        $config['upload_path']          = $pathh; 
        $config['allowed_types']        = '*'; 
        $config['max_size']             = 1024*1024*1024*1024; 
        $config['encrypt_name']         = true; 

        $this->load->library('upload', $config); 

        if ( ! $this->upload->do_upload($name)) 
        { 
             $error = array('error' => $this->upload->display_errors()); 
             print_r($error); 
        } 
        else 
        {  
          	$data =  $this->upload->data(); 
          	$files_name = $data['file_name']; 
         	return $files_name; 
        } 
    }


    public function reArrayFiles($file_post)  
    { 
        $file_ary = array(); 
        $file_count = count($file_post['name']); 
        $file_keys = array_keys($file_post); 

        for ($i=0; $i<$file_count; $i++) 
        { 
            foreach ($file_keys as $key) 
            { 
                $file_ary[$i][$key] = $file_post[$key][$i]; 
            } 
        } 

        return $file_ary; 
    } 

    public function JsonEncode($data='') 
    {

        foreach ($data as $key => $val)  
        { 
           if(is_array($val))  
           { 
               $data[$key] = json_encode($val); 
           } 
        } 

        return $data; 
    } 

	public function save_record() 
	{ 
		$save = false;  
		$message = array();  

        $data = $this->input->post(); 

        if(isset($data['Edit_Recorde'])) 
        { 
            $Edit_Recorde = $data['Edit_Recorde']; 
        } 
        else 
        { 
            $Edit_Recorde = ""; 
        }
  
		if($Edit_Recorde == "") 
        { 
           
        	$data['Date_Added'] = $this->date; 
        	$data['Added_By'] = $this->user_data['Id'];  
        	$table = $data['Table_Name']; 
        	unset($data['Edit_Recorde'],$data['Table_Name']); 
        	$data = $this->JsonEncode($data); 
        	$this->db->insert($table,$data); 
            $id = $this->db->insert_id();

            $employee_id = 0;
            if(isset($data['Employee_Id'])){ $employee_id = $data['Employee_Id']; }

            if($this->check_file_storage())
            {
                $files_allowed = true;
                $filedata = array(); 
                $path = "assets/panel/userassets/".$table."/".$id."/"; 
                
                if(isset($_FILES) && sizeof($_FILES) > 0) 
                { 
                    foreach ($_FILES as $key => $value)  
                    { 
                        if(is_array($_FILES[$key]['name'])) 
                        { 
                            if($_FILES[$key]['error'][0] == 0) 
                            { 
                                $files =  $this->reArrayFiles($_FILES[$key]); 
                                $this->save_multiple_files($path,$files,$table,$id,$employee_id); 
                            } 
                        } 
                        else 
                        { 
                            if($_FILES[$key]['error'] == 0) 
                            {

                                $filedata['Table_Id'] = $id; 
                                $filedata['Org_Id'] = $this->org_id; 
                                $filedata['Employee_Id'] =  $employee_id; 
                                $filedata['Table_Name'] = $table; 
                                $filedata["Original_Name"] = $this->save_file($path,$key); 
                                $filedata['File_Name'] = $_FILES[$key]['name']; 
                                $filedata['File_Type'] = $_FILES[$key]['type']; 
                                $filedata['File_Size'] = $_FILES[$key]['size']; 
                                $filedata['Date_Added'] = $this->date; 
                                $filedata['Added_By'] = $this->user_data['Id']; 
                                $filedata['Modified_By'] = $this->user_data['Id']; 

                                $this->db->insert("files",$filedata); 
                                $this->db->update($table,array($key => $filedata["Original_Name"]),array("Id"=>$id));

                            } 
                        }  
                    } 
                } 
            }
            else
            {
                $files_allowed = false;
            }

            if(!$files_allowed)
            {
                $save = true;  
                $alert  = '<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> Successful!</h4>
                                 Record is saved successfully. But files are not saved because storage limit has been reached out. Please upgrade your plan.
                             </div>';
                $message['Success'] = true;
                $message['Message'] = $alert;
                $message['Id'] = $id;
            }
            else
            {
                $save = true;  
                $alert  = '<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> Successful!</h4>
                                 Record is saved successfully...
                             </div>';
                $message['Success'] = true;
                $message['Message'] = $alert;
                $message['Id'] = $id;
            }
        	
        } 
        else 
        { 
        	$data['Date_Modification'] = $this->date; 
        	$data['Modified_By'] = $this->user_data['Id']; 
        	$id = $data['Edit_Recorde']; 
        	$table = $data['Table_Name']; 
        	unset($data['Edit_Recorde'],$data['Table_Name']); 
        	if(!isset($data['Permissions']) && $table == "roles"){ $data['Permissions'] = array(); }
        	$data = $this->JsonEncode($data); 

            $employee_id = 0;
            if(isset($data['Employee_Id'])){ $employee_id = $data['Employee_Id']; }

            if($this->check_file_storage())
            {
                $files_allowed = true;
                $path = "assets/panel/userassets/".$table."/".$id."/"; 
                if(isset($_FILES) && sizeof($_FILES) > 0) 
                { 
                    $filedata = array(); 
                    foreach ($_FILES as $key => $value)  
                    { 
                        if(is_array($_FILES[$key]['name'])) 
                        { 
                            if($_FILES[$key]['error'][0] == 0) 
                            { 
                                $files =  $this->reArrayFiles($_FILES[$key]); 
                                $this->save_multiple_files($path,$files,$table,$id); 
                            } 
                        } 
                        else 
                        {  
                            if($_FILES[$key]['error'] == 0) 
                            { 
                                $filedata['Table_Id'] = $id; 
                                $filedata['Org_Id'] = $this->org_id; 
                                $filedata['Employee_Id'] =  $employee_id; 
                                $filedata['Table_Name'] = $table; 
                                $filedata["Original_Name"] = $this->save_file($path,$key); 
                                $filedata['File_Name'] = $_FILES[$key]['name']; 
                                $filedata['File_Type'] = $_FILES[$key]['type']; 
                                $filedata['File_Size'] = $_FILES[$key]['size']; 
                                $filedata['Date_Added'] = $this->date; 
                                $filedata['Added_By'] = $this->user_data['Id']; 
                                $filedata['Modified_By'] = $this->user_data['Id'];  

                                $this->db->insert("files",$filedata); 
                                $this->db->update($table,array($key => $filedata["Original_Name"]),array("Id"=>$id)); 
                            } 
                        }  
                    } 

                } 
            }
            else
            {
                $files_allowed = false;
            }

            $this->db->update($table,$data,array("Id"=>$id)); 


            if(!$files_allowed)
            {
                $save = true;  
                $alert  = '<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> Successful!</h4>
                                 Record is updated successfully. But files are not saved because storage limit has been reached out. Please upgrade your plan.
                             </div>';
                $message['Success'] = true;
                $message['Message'] = $alert;
                $message['Id'] = $id;
            }
            else
            {
                $save = true;  
                $alert  = '<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> Successful!</h4>
                                 Record is saved successfully...
                             </div>';
                $message['Success'] = true;
                $message['Message'] = $alert;
                $message['Id'] = $id;
            }
  
        } 

        if(!$save) 
        {   
            $alert  = '<div class="alert alert-danger alert-dismissible">
			                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			                <h4><i class="icon fa fa-ban"></i> Successful!</h4>
			                 Record is updated successfully...
			             </div>';  

			$message['Success'] = true;
			$message['Message'] = $alert;  
			$message['Id'] = $id;          
        } 

        echo json_encode($message);
        //$this->session->set_flashdata("message",$message); 
        //redirect($_SERVER['HTTP_REFERER']);
	}  
    
    public function check_file_storage()
    {
        $allow = false;
        $allowed_storage = 0;
        $restrictions = $this->session->userdata('restrictions');
        $allowed_storage = $restrictions['Storage_Limit']; 
        
        
        $this->db->select("SUM(File_Size) as Used_Storage");
        $org_files = $this->db->get_where("files",array("Org_Id"=>$this->org_id,"Deleted"=>0));

        if($org_files->num_rows() > 0)
        {
            $files_data = $org_files->result_array();

            $total_used_storage = round($files_data[0]['Used_Storage'] / (1024*1024) ); // will return MB 

            if($total_used_storage <  $allowed_storage )
            {
                 $allow = true;
            }
            else
            {
                 $allow = false;
            }
        }
        else
        {
            $allow = true;
        }

        return $allow;

    } 

    public function save_employee()
    {
        $restrictions = array();
        $restrictions = $this->session->userdata("restrictions");
        if(isset($restrictions['Employees']))
        {
            $get_employees = $this->db->get_where("employees",array("Deleted"=>0,"Org_Id"=>$this->org_id));
            if($get_employees->num_rows() < $restrictions['Employees'])
            {
                $data = $this->input->post();

                $data['Date_Added'] = $this->date; 
                $data['Added_By'] = $this->user_data['Id'];  
                $table = $data['Table_Name']; 
                $data = $this->JsonEncode($data); 
                $this->db->insert($table,$data); 
                $id = $this->db->insert_id();

                $user_profile_data['Email'] = $data['Email'];
                $user_profile_data['Password'] = $data['Password'];


            }
            else
            {
                $alert  = ' <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                 You are not allowed to add more employees. Please upgrade your plan.
                             </div>';  

                $message['Success'] = true;
                $message['Message'] = $alert;  
                $message['Id'] = $id;  
            }
        }

        echo json_encode($message);
    }

    public function save_shift()
    {
        $message = array();
        $data = $this->input->post();
        $id = 0;
        if(isset($data['Edit_Recorde'])) 
        {  
            $id = $data['Edit_Recorde']; 
        } 
        else 
        { 
            $id = ""; 
        }
    
        $shift_employees = array();
        if(isset($data['Shift_Employee']))
        {
            if(sizeof($data['Shift_Employee']) > 0)
            {
                $shift_employees = (array) $data['Shift_Employee'];
            }
        }

        unset($data['Shift_Employee']);

        if($id == 0) 
        {  
            $data['Date_Added'] = $this->date; 
            $data['Added_By'] = $this->user_data['Id'];  
            $table = $data['Table_Name']; 
            unset($data['Edit_Recorde'],$data['Table_Name']); 
            $data = $this->JsonEncode($data); 
            $this->db->insert($table,$data); 
            $id = $this->db->insert_id(); 

            $alert  = ' <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Success!</h4>
                            Shift is saved successfully.
                         </div>';  

            $message['Success'] = true;
            $message['Message'] = $alert;  
            $message['Id'] = $id;  

        }
        else
        {
            $data['Date_Modification'] = $this->date; 
            $data['Modified_By'] = $this->user_data['Id'];  
            $table = $data['Table_Name']; 
            unset($data['Edit_Recorde'],$data['Table_Name']); 
            $data = $this->JsonEncode($data); 
            $this->db->update($table,$data,array("Id"=>$id)); 

            $alert  = ' <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Success!</h4>
                             Shift is updated successfully.
                         </div>';  

            $message['Success'] = true;
            $message['Message'] = $alert;  
            $message['Id'] = $id;  
            
        }


        if(sizeof($shift_employees) > 0)
        {
            $this->db->delete("shift_employees",array("Org_Id"=>$this->org_id,"Shift_Id"=>$id));

            $employee_data = array();
            foreach ($shift_employees as $key => $value) 
            {
                 $employee_data_rec['Org_Id'] = $this->org_id;
                 $employee_data_rec['Shift_Id'] = $id;
                 $employee_data_rec['Employee_Id'] = $value;
                 $employee_data_rec['Date_Added'] = $this->date;
                 $employee_data_rec['Added_By'] = $this->user_data['Id'];

                 $employee_data[] = $employee_data_rec;
            }

            $this->db->insert_batch('shift_employees', $employee_data); 
        }

        echo json_encode($message);
    }

    public function send_email_to_selected()
    {
        $data = $this->input->post();
        if(isset($data['EmailTemplates']) && $data['EmailTemplates'] != "" && $data['EmailTemplates'] > 0)
        {
            $email_template = $this->db->get_where("email_templates",array("Deleted"=>0,"Status"=>1,"Id"=>$data['EmailTemplates']));
            if($email_template->num_rows() > 0)
            {
                $template_data = $email_template->result_array();
                $applicants = explode(",",$data['ids']);

                $this->db->where_in("Id",$applicants);
                $this->db->select("Email,Id");
                $applicants_rec = $this->db->get_where("applicants",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));

                $this->db->select("Name");
                $org_data = $this->db->get_where("organizations",array("Deleted"=>0,"Status"=>1,"Id"=>$this->org_id))->result_array();
                    
                    
                    $variables = $this->getInbetweenStrings("@@" , "@@", $template_data[0]['Message']);
                    
                     
                    foreach ($applicants_rec->result() as $key => $value) {
                        
                        $message = $template_data[0]['Message'];
                        $table_data = "";
                        foreach ($variables as $index => $field) 
                        {
                           $field_parts = explode(".", $field);
                           if($field_parts[0] != "")
                           {
                                 $table_name = $field_parts[0];
                                 if(isset($field_parts[1])){ $field_name = $field_parts[1]; }else{ $field_name = ""; }
                                 if($table_name == "applicants")
                                 {
                                    $this->db->select($field_name);
                                    $table_data = $this->db->get_where($table_name,array("Id"=>$value->Id));
                                 }
                                 elseif($table_name == "job_posts")
                                 {
                                    $this->db->order_by("Id","DESC");
                                    $this->db->select("Job_Id");
                                    $applicant_application = $this->db->get_where("applicant_applications",array("Applicant_Id"=>$value->Id,"Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                                    if($applicant_application->num_rows() > 0)
                                    {
                                        $application_data = $applicant_application->result_array();
                                        $this->db->select($field_name);
                                        $table_data = $this->db->get_where($table_name,array("Id"=>$application_data[0]['Job_Id']));
                                    }
                                    
                                 }
                                 else
                                 {
                                    $message = $this->check_fields_in_message($field,$message);
                                 }

                                 if($table_data != "")
                                 {
                                     
                                    if(is_object($table_data))
                                    {
                                        if($table_data->num_rows() > 0)
                                        {
                                            $table_data = $table_data->result_array();
                                            $word = "@@".$field."@@";
                                            $replace_with = $table_data[0][$field_name];
                                            $message = str_replace($word,$replace_with,$message);
                                        }
                                    }
                                 }
                             
                           }
                        }

                        $email_data = array(
                                                "Org" => $org_data[0]['Name'], 
                                                "To" => $value->Email,
                                                "Subject" => $template_data[0]['Subject'],
                                                "Message" => $message,
                                            );

                        $this->send_email($email_data);
                    } 
            }
        }
    }

    public function assign_interviewers($value='')
    {
        $action_status = false;
        $data = $this->input->post();
        if(isset($data['ids']) && isset($data['interviewers']))
        {
            if(sizeof($data['ids']) > 0 && sizeof($data['interviewers']) > 0)
            {
                $this->db->where_in("Id",$data['ids']);
                $this->db->select("Id");
                $applicants = $this->db->get_where("applicants",array("Deleted"=>0,"Status"=>1));
                if($applicants->num_rows() > 0)
                { 
                    foreach ($applicants->result() as $key => $applicant) 
                    {  
                        $applicant_applications = $this->db->get_where("applicant_applications",array("Applicant_Id"=>$applicant->Id,"Deleted"=>0,"Status"=>1,"Application_Status !="=>"Rejected","Application_Status !="=>"Rejected","Application_Status !="=>"Hired","Application_Status !="=>"Offered"));
                        if($applicant_applications->num_rows() > 0)
                        { 
                            foreach ($applicant_applications->result() as $appli_index => $application) 
                            {
                                 
                                foreach ($data['interviewers'] as $index => $interviewer) 
                                { 
                                    $check_interviewer = $this->db->get_where('applicant_interviews',array("Application_Id"=>$application->Id,"Applicant_Id"=>$applicant->Id,"Org_Id"=>$this->org_id,"Interviewer"=>$interviewer,"Job_Id"=>$application->Job_Id));
                                    if($check_interviewer->num_rows() < 1)
                                    {
                                         $action_status = true;
                                         $this->db->insert('applicant_interviews',array("Application_Id"=>$application->Id,"Applicant_Id"=>$applicant->Id,"Org_Id"=>$this->org_id,"Interviewer"=>$interviewer,"Job_Id"=>$application->Job_Id));    
                                    } 
                                } 
                            }
                        }
                    } 
                } 
            }
        }
        

        echo $action_status; 
    }

    public function add_note_for_selected_applicants($value='')
    {
        $action_status = false;
        $data = $this->input->post();
        if(isset($data['ids']) && isset($data['note']))
        {
            if(sizeof($data['ids']) > 0 && $data['note'] != "")
            { 
                $this->db->where_in("Id",$data['ids']);
                $this->db->select("Id");
                $applicants = $this->db->get_where("applicants",array("Deleted"=>0,"Status"=>1));
                if($applicants->num_rows() > 0)
                { 
                    foreach ($applicants->result() as $key => $applicant) 
                    {  
                        $applicant_applications = $this->db->get_where("applicant_applications",array("Applicant_Id"=>$applicant->Id,"Deleted"=>0,"Status"=>1,"Application_Status !="=>"Rejected","Application_Status !="=>"Rejected","Application_Status !="=>"Hired","Application_Status !="=>"Offered"));
                        if($applicant_applications->num_rows() > 0)
                        { 
                            foreach ($applicant_applications->result() as $appli_index => $application) 
                            { 
                                $check_note = $this->db->get_where('application_notes',array("Application_Id"=>$application->Id,"Applicant_Id"=>$applicant->Id,"Org_Id"=>$this->org_id,"Notes"=>$data['note'],"Job_Id"=>$application->Job_Id));
                                if($check_note->num_rows() < 1)
                                {
                                     $action_status = true;
                                     $this->db->insert('application_notes',array("Application_Id"=>$application->Id,"Applicant_Id"=>$applicant->Id,"Org_Id"=>$this->org_id,"Notes"=>$data['note'],"Job_Id"=>$application->Job_Id));    
                                }  
                            }
                        }
                    } 
                }  
            } 
        }

        echo $action_status; 
    }

    public function move_applicants_into_employees($value='')
    {
        $action_status = false;
        $data = $this->input->post();
        if(isset($data['ids']))
        {
            if(sizeof($data['ids']) > 0)
            {
               $data['ids'] = explode(",", $data['ids']);
               $this->db->where_in("Id",$data['ids']);
               $applicants = $this->db->get_where("applicants",array("Deleted"=>0,"Status"=>1));
                
               if($applicants->num_rows() > 0)
               { 
                 foreach ($applicants->result() as $key => $value) 
                 {
                    $applicant_info = (array) $value;
                    $applicant_info['Org_Id'] = $this->org_id;
                    unset($applicant_info['Id'],$applicant_info['Accept_Terms'],$applicant_info['Date_Added'],$applicant_info['Date_Modification'],$applicant_info['Added_By'],$applicant_info['Modified_By'],$applicant_info['Deleted']);
                    if(isset($data['Role_Id'])){ $applicant_info['Role_Id'] = $data['Role_Id']; }
                    if(isset($data['Permission_Id'])){ $applicant_info['Permission_Id'] = $data['Permission_Id']; }
                    if(isset($data['Location_Id'])){ $applicant_info['Location_Id'] = $data['Location_Id']; }

                    $check_in_employees = $this->db->get_where("employees",array("Org_Id"=>$this->org_id,"Deleted"=>0,"Status"=>1,"CNIC"=>$value->CNIC));
                    if($check_in_employees->num_rows() < 1)
                    { 
                        $this->db->insert("employees",$applicant_info);
                        $employee_id = $this->db->insert_id();

                        $copy_src = "assets/panel/userassets/applicants/".$value->Id."/";
                        $target_src = "assets/panel/userassets/employees/".$employee_id."/";

                        if (!file_exists($target_src)) {  mkdir($target_src, 0777, true); }  
                        $files = scandir($copy_src);
                        
                        foreach ($files as $file) 
                        {
                          if (in_array($file, array(".",".."))) continue; 

                          if (!file_exists($target_src.$file) && file_exists($copy_src.$file))
                          {
                            copy($copy_src.$file, $target_src.$file);
                          }
                          
                        }

                        // move applicant education and experience into employee records

                        $this->move_applicant_education($employee_id,$value->Id);
                        $this->move_applicant_experience($employee_id,$value->Id);

                        $action_status = true;
                    }

                 }
               } 
            }
        }

         echo $action_status; 
    }

    public function move_applicant_education($employee_id='',$applicant_id='')
    {
        if($applicant_id != "" && $employee_id != "")
        {
            $get_applicant_education = $this->db->get_where("applicant_education",array("Deleted"=>0,"Status"=>1,"Applicant_Id"=>$applicant_id));
            if($get_applicant_education->num_rows() > 0)
            {
                foreach ($get_applicant_education->result() as $key => $value) 
                {
                     $education_rec = (array) $value;
                     $education_rec['Employee_Id'] = $employee_id;
                     $education_rec['Org_Id'] = $this->org_id;
                     unset($education_rec['Id'],$education_rec['Applicant_Id'],$education_rec['Date_Added'],$education_rec['Date_Modification'],$education_rec['Added_By'],$education_rec['Modified_By'],$education_rec['Deleted']);
                
                     $this->db->insert("employee_education",$education_rec);
                }
            }
        }
    }

    public function move_applicant_experience($employee_id='',$applicant_id='')
    {
        if($applicant_id != "" && $employee_id != "")
        {
            $get_applicant_experience = $this->db->get_where("applicant_experience",array("Deleted"=>0,"Status"=>1,"Applicant_Id"=>$applicant_id));
            if($get_applicant_experience->num_rows() > 0)
            {
                foreach ($get_applicant_experience->result() as $key => $value) 
                {
                     $experience_rec = (array) $value;
                     $experience_rec['Employee_Id'] = $employee_id;
                     $experience_rec['Org_Id'] = $this->org_id;
                     unset($experience_rec['Id'],$experience_rec['Applicant_Id'],$experience_rec['Date_Added'],$experience_rec['Date_Modification'],$experience_rec['Added_By'],$experience_rec['Modified_By'],$experience_rec['Deleted']);
                
                     $this->db->insert("employee_experience",$experience_rec);
                }
            }
        }
    } 
  
    public function add_applicant_application($applicant_id,$job_id)
    {
        $application_rec['Applicant_Id'] = $applicant_id;
        $application_rec['Org_Id'] = $this->org_id;
        $application_rec['Job_Id'] = $job_id;
        $application_rec['Applied_Date'] = $this->date;
        $application_rec['Application_Status'] = 'New'; 
        $this->db->insert("applicant_applications",$application_rec);
    }


	public function delete_record() 
    { 
        $ids  = $this->input->post("ids"); 
        $table  = $this->input->post("table");  

        if($table != "" && $ids != "") 
        { 
            foreach ($ids as $key => $value) 
            {  
                 $this->db->update($table,array("Deleted"=>1),array("Id"=>$value)); 
            } 
        } 

        echo true; 
    }  

    public function change_status() 
    { 
        $ids  = $this->input->post("ids"); 
        $table  = $this->input->post("table");  
        $action  = $this->input->post("status");  
        $ids = (array) $ids;

        if($table != "" && $ids != "") 
        { 
        	if($action == "Change_Status")
        	{
        		foreach ($ids as $key => $value)  
	            { 
	                $table_data = $this->db->get_where($table,array("Deleted"=>0,"Id"=>$value))->result_array(); 
	                if($table_data[0]['Status'] == 1){ $status = 0; }else{ $status = 1; } 
	                $this->db->update($table,array("Status"=>$status),array("Id"=>$value)); 
	            } 
        	}
        	elseif($action == "Enable")
        	{
        		$this->db->where_in("Id",$ids);
        		$this->db->update($table,array("Status"=>1)); 
        	}
        	elseif($action == "Disable")
        	{
        		$this->db->where_in("Id",$ids);
        		$this->db->update($table,array("Status"=>0)); 
        	}
            

            echo true; 
        }
        else
        {
        	echo false;
        } 
        
    }

    public function download_files() 
    { 
        $ids  = $this->input->post("ids"); 
        $table  = $this->input->post("table");  
       
        $ids = (array) $ids;

        if($table != "" && $ids != "" && sizeof($ids) > 0) 
        { 
            $this->db->where_in("Id",$ids);
            $files = $this->db->get_where("files",array("Deleted"=>0,"Org_Id"=>$this->org_id));
            foreach ($files->result() as $key => $value) 
            { 
                $path = "assets/panel/userassets/".$value->Table_Name."/".$value->Table_Id."/".$value->Original_Name;  
                $this->zip->read_file($path); 
            }

            $file_name = "compressed_files_".time().".zip";
            $this->zip->archive('assets/panel/userassets/temp/'.$file_name); 
            $this->zip->clear_data();
            echo 'assets/panel/userassets/temp/'.$file_name;
        }
        else
        {
            echo false;
        } 
        
    }
    public function create_subdomain($sub_domain_name, $cpanel_username, $cpanel_password, $main_domain) 
    {

        $build_request = "/frontend/x3/subdomain/doadddomain.html?rootdomain=" . $main_domain . "&amp;domain=" . $sub_domain_name . "&amp;dir=public_html/" . $sub_domain_name;

        $open_socket = fsockopen('localhost', 2082);
        if(!$open_socket) {
        return "Socket Error";
        exit();
        }

        $auth_string = $cpanel_username . ":" . $cpanel_password;
        $auth_pass = base64_encode($auth_string);
        $build_headers = "GET " . $build_request ."\r\n";
        $build_headers .= "HTTP/1.0\r\n";
        $build_headers .= "Host:localhost\r\n";
        $build_headers .= "Authorization: Basic " . $auth_pass . "\r\n";
        $build_headers .= "\r\n";

        fputs($open_socket, $build_headers);
        while(!feof($open_socket)) {
        fgets($open_socket, 128);
        }
        fclose($open_socket);

        $sub_domain_url = "http://" . $sub_domain_name . "." . $main_domain . "/";

        return $sub_domain_url;

    }

    public function get_modules_array()
    { 
    		 
        $application_modules = $this->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1)); 
        
        if($application_modules->num_rows() > 0)
        {
          foreach ($application_modules->result() as $key => $value) 
          {   
          	if(!in_array($value->Id, $this->processed))
          	{ 
              		if($value->Parent_Module > 0)
          			{
          				if(!in_array($value->Parent_Module,$this->processed))
          				{
          					 $this->check_module($value->Parent_Module); 
          				}  
          			} 

          			if(!in_array($value->Id, $this->processed))
          			{ 
          				$this->modules[] =  array( 
			                                    "id" => $value->Id, 
			                                    "name" => $value->Name, 
			                                    "parent" => $value->Parent_Module, 
		                                   );

          				$this->processed[] = $value->Id;
          			}
          			 
          	}

          }
        }

        

        echo json_encode($this->modules);
    }

    public function check_module($id)
    {
    	$this->counter = $this->counter + 1; 

    	if($this->counter > 500)
    	{
    		return false;
    	}

    	$parent_module = $this->db->get_where("application_modules",array("Id"=>$id));
		if($parent_module->num_rows() > 0)
		{  
			$parent_module_data = $parent_module->result_array();

			if($parent_module_data[0]['Parent_Module'] > 0)
			{ 
				if(!in_array($parent_module_data[0]['Parent_Module'], $this->processed))
				{
				 	$this->check_module($parent_module_data[0]['Parent_Module']); 
				}
				 
			} 
			 
			if(!in_array($id, $this->processed))
			{

				$array =  array( 
					                        "id" => $parent_module_data[0]['Id'], 
					                        "name" => $parent_module_data[0]['Name'], 
					                        "parent" => $parent_module_data[0]['Parent_Module'], 
					                     );

				$this->modules[] = $array;
				$this->processed[] = $id; 
				 
			}
			 
				 

		} 
  
    } 

    public function check_module_and_permissions($id,$permissions=array())
    {
        $this->counter = $this->counter + 1; 

        if($this->counter > 500)
        {
            return false;
        }

        $parent_module = $this->db->get_where("application_modules",array("Id"=>$id));
        if($parent_module->num_rows() > 0)
        {  
            $parent_module_data = $parent_module->result_array();

            if($parent_module_data[0]['Parent_Module'] > 0)
            { 
                if(!in_array($parent_module_data[0]['Parent_Module'], $this->processed))
                {
                    $this->check_module($parent_module_data[0]['Parent_Module']); 
                }
                 
            } 

             
            if(!in_array($id, $this->processed))
            {

                $array =  array( 
                                            "id" => $parent_module_data[0]['Id'], 
                                            "name" => $parent_module_data[0]['Name'], 
                                            "parent" => $parent_module_data[0]['Parent_Module'], 
                                         );

                $this->modules[] = $array;
                $this->processed[] = $id; 
                 
            }
             
            $this->attach_permissions($parent_module_data[0]['Id'],$permissions);     

        } 
        else
        {
            $this->attach_permissions($id,$permissions);
        }      
    }

    public function attach_permissions($id,$permissions=array())
    {
            if(in_array($id."_add", $permissions))
            {
                $this->modules[] =  array( 
                                            "id" => $id."_add", 
                                            "name" => "Add", 
                                            "parent" => $id, 
                                         );
            }

            if(in_array($id."_edit", $permissions))
            {
                $this->modules[] =  array( 
                                            "id" => $id."_edit", 
                                            "name" => "Edit", 
                                            "parent" => $id, 
                                         );
            }

            if(in_array($id."_delete", $permissions))
            {
                $this->modules[] =  array( 
                                            "id" => $id."_delete", 
                                            "name" => "Delete", 
                                            "parent" => $id, 
                                         );
            }

            if(in_array($id."_view", $permissions))
            {
                $this->modules[] =  array( 
                                            "id" => $id."_view", 
                                            "name" => "View", 
                                            "parent" => $id, 
                                         );
            }

            if(in_array($id."_export", $permissions))
            {
                $this->modules[] =  array( 
                                            "id" => $id."_export", 
                                            "name" => "Export", 
                                            "parent" => $id, 
                                         );
            }

            if(in_array($id."_import", $permissions))
            {
                $this->modules[] =  array( 
                                            "id" => $id."_import", 
                                            "name" => "Import", 
                                            "parent" => $id, 
                                         );
            }

            if(in_array($id."_filter", $permissions))
            {
                $this->modules[] =  array( 
                                            "id" => $id."_filter", 
                                            "name" => "Filter", 
                                            "parent" => $id, 
                                         );
            }
    }

    public function save_module()
    {
    	$data = $this->input->post();
    	$data['Date_Added'] = $this->date;
    	$data['Added_By'] = $this->user_data['Id'];  
    	$save = $this->db->insert("application_modules",$data);

    	echo $this->db->insert_id();
    }

    public function update_module()
    {
    	$data = $this->input->post();
    	$id = $data['Id'];
    	$name = $data['Name'];
    	 
    	$update = $this->db->update("application_modules",array("Name"=>$name),array("Id"=>$id));

    	echo $update;
    }
     
  	public function delete_module()
    {
    	$id = $this->input->post('Id'); 
    	$delete = $this->db->update("application_modules",array("Deleted"=>1),array("Id"=>$id)); 
    	$this->delete_childs($id); 
    	return $delete;
    }

    public function delete_childs($id)
    {
    	$childs = $this->db->get_where("application_modules",array("Parent_Module"=>$id,"Deleted"=>0));
    	if($childs->num_rows() > 0)
    	{
    		foreach ($childs->result() as $key => $value) 
    		{
    			$delete = $this->db->update("application_modules",array("Deleted"=>1),array("Id"=>$value->Id)); 
    			$this->delete_childs($value->Id);
    		}
    	}
    }

    public function get_role_array()
    {
    	$id = $this->input->post("id");
        
    	$roles = $this->db->get_where("roles",array("Deleted"=>0,"Status"=>1,"Id"=>$id)); 
            
        if($roles->num_rows() > 0)
        {
            $this->modules[] =  array( 
                                        "id" => "11111", 
                                        "name" => "Modules", 
                                        "parent" => "0", 
                                     );

        	$roles_data = $roles->result_array();
        	$role_permissions = (array) json_decode($roles_data[0]['Permissions']);

        	$this->db->where_in("Id",$role_permissions);
        	$get_modules = $this->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1));

        	if($get_modules->num_rows() > 0)
        	{
	          foreach ($get_modules->result() as $key => $value) 
	          {   
	          	if(!in_array($value->Id, $this->processed))
	          	{ 
	              		if($value->Parent_Module > 0)
	          			{
	          				if(!in_array($value->Parent_Module,$this->processed))
	          				{
	          					 $this->check_module($value->Parent_Module); 
	          				}  
	          			} 

	          			if(!in_array($value->Id, $this->processed))
	          			{ 
                            if($value->Parent_Module == 0){ $parent_module_id = '11111'; }else{ $parent_module_id = $value->Parent_Module; }
	          				$this->modules[] =  array( 
        				                                    "id" => $value->Id, 
        				                                    "name" => $value->Name, 
        				                                    "parent" => $parent_module_id, 
        			                                 );

	          				$this->processed[] = $value->Id;
	          			}
	          			 
	          	}

	          }
	      	}
        }
 
       echo json_encode($this->modules);
    }

    public function get_permissions_array()
    {
        $id = $this->input->post("id");
        
        $permissions = $this->db->get_where("permissions",array("Deleted"=>0,"Status"=>1,"Id"=>$id,"Org_Id"=>$this->org_id)); 
            
        if($permissions->num_rows() > 0)
        {
            $this->modules[] =  array( 
                                        "id" => "11111", 
                                        "name" => "Modules", 
                                        "parent" => "0", 
                                     );

            $permission_data = $permissions->result_array();
            if($permission_data[0]['Role_Id'] != "" && $permission_data[0]['Role_Id'] > 0)
            {
                $role_rec = $this->db->get_where("roles",array("Id"=>$permission_data[0]['Role_Id']));
                $role_data = $role_rec->result_array(); 
                $role_permissions = (array) json_decode($role_data[0]['Permissions']); 
                $permissions = (array) json_decode($permission_data[0]['Permissions']); 

                $this->db->where_in("Id",$role_permissions);
                $get_modules = $this->db->get_where("application_modules",array("Deleted"=>0,"Status"=>1));

                if($get_modules->num_rows() > 0)
                {
                  foreach ($get_modules->result() as $key => $value) 
                  {   
                    if(!in_array($value->Id, $this->processed))
                    { 
                            if($value->Parent_Module > 0)
                            {
                                if(!in_array($value->Parent_Module,$this->processed))
                                {
                                     $this->check_module_and_permissions($value->Parent_Module,$permissions); 
                                }  
                            } 

                            if(!in_array($value->Id, $this->processed))
                            { 
                                if($value->Parent_Module == 0){ $parent_module_id = '11111'; }else{ $parent_module_id = $value->Parent_Module; }
                                $this->modules[] =  array( 
                                                                "id" => $value->Id, 
                                                                "name" => $value->Name, 
                                                                "parent" => $parent_module_id, 
                                                         );

                                $this->processed[] = $value->Id;
                            }

                            $this->check_module_and_permissions($value->Id,$permissions);
                             
                    }

                  }
                }
            }
        }
 
       echo json_encode($this->modules);
    }

    public function get_states()
    {
    	$data = $this->input->post();
    	$options = '<option value="0">Select State</option>';
    	if($data['country_id'] != "")
    	{
	    	$states = $this->db->get_where("states",array("country_id"=>$data['country_id']));
	    	if($states->num_rows() > 0)
	    	{
	    		foreach ($states->result() as $key => $value) 
	    		{
	    			if($data['selected_id'] != "" && $data['selected_id'] = $value->id)
	    			{
	    				$selected = 'selected="selected"';
	    			}
	    			else
	    			{
	    				$selected = "";
	    			}

	    			$options .= '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
	    		}
	    	}
	    }

	    echo $options;
    }

    public function get_cities()
    {
    	$data = $this->input->post();
    	$options = '<option value="0">Select City</option>';
    	if($data['state_id'] != "")
    	{
	    	$cities = $this->db->get_where("cities",array("state_id"=>$data['state_id']));
	    	if($cities->num_rows() > 0)
	    	{
	    		foreach ($cities->result() as $key => $value) 
	    		{
	    			if($data['selected_id'] != "" && $data['selected_id'] = $value->id)
	    			{
	    				$selected = 'selected="selected"';
	    			}
	    			else
	    			{
	    				$selected = "";
	    			}

	    			$options .= '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
	    		}
	    	}
	    }

	    echo $options;
    }


    public function get_role_permissions()
    {

        $html = "";
        $data = $this->input->post();

        $role_modules = array();
        $org_role = $data['Role_Id'];
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

        if($data['permission_id'] != "" && $data['permission_id'] > 0)
        {
          $permissions_rec = $this->db->get_where("permissions",array("Id"=>$data['permission_id'],"Org_Id"=>$this->org_id,"Deleted"=>0)); 
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

              $html .= "</ul>";
            }

        }

        $script = "<script type='text/javascript'> 
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
                    </script>
                ";
        echo $html.$script;
    }

    public function get_table_fields()
    {
        $html = "<option value=''> Select Field</option>";
        $fields_array = array();
        $table = $this->input->post("table");
        $fields_array = $this->db->list_fields($table);
        

        $removeable_fields = array("Id","Role_Id","Org_Id","User_Type","Employee_Id","Permission_Id","Date_Added","Date_Modification","Deleted","Status","Added_By","Modified_By");
        $remaining_fields =  array_diff($fields_array, $removeable_fields);
        if(sizeof($remaining_fields) > 0)
        {
            foreach ($remaining_fields as $key => $value) 
            {
                $part = explode("_",$value);

                if(isset($part[1])){ 
                    if($part[1] == "Id"){ 
                        continue;
                    } 
                }

                $field_name = "";

                for($i=0; $i < sizeof($part); $i++)
                {
                    if($i == 0)
                    {
                        $field_name = $part[$i];
                    }
                    else
                    {
                        $field_name = $field_name." ".$part[$i];
                    }
                   
                }

                $html .= '<option value="@@'.$table.'.'.$value.'@@">'.$field_name.'</option>';
                
            }
        }

        echo $html;
    }

    public function get_institute()
    {
        $data = $this->input->post();
        $options = '<option value="0">Select Institute</option>';
        if($data['country_code'] != "")
        {
            $universities = $this->db->get_where("universities",array("Country_Code"=>$data['country_code']));
            if($universities->num_rows() > 0)
            {
                foreach ($universities->result() as $key => $value) 
                {
                    $selected = "";
                    // if($data['selected_id'] != "" && $data['selected_id'] = $value->id)
                    // {
                    //     $selected = 'selected="selected"';
                    // }
                    
                    $options .= '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
                }
            }
        }

        echo $options;
    }

    public function get_job_posts()
    {
        $data = $this->input->post();
        $options = '<option value="0">Select Job Post</option>';
        if($data['location'] != "")
        {
            $job_posts = $this->db->get_where("job_posts",array("Location_Id"=>$data['location'], "Org_Id" => $this->org_id));
            if($job_posts->num_rows() > 0)
            {
                foreach ($job_posts->result() as $key => $value) 
                {
                    $selected = "";  
                    $options .= '<option '.$selected.' value="'.$value->Id.'">'.$value->Title.'</option>';
                }
            }
        }

        echo $options;
    }

    public function get_employees($query_strings='')
    {
          $filter_data = array();
          $data = $this->input->post();   

          $result =   $this->Admin_modal->get_employees($data);
          $records = $links = "";

          if(sizeof($result['result']) > 0)
          {

            $module_id = 0000;
            $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Employees","Deleted"=>0,"Status"=>1));
            if($module_data->num_rows() > 0)
            {
                $module_info = $module_data->result_array();
                $module_id = $module_info[0]['Id'];
            }

            foreach ($result['result'] as $key => $value) 
            {
                $src =  "assets/images/default-user.png";
                if($value->Photo && $value->Photo != "")
                {
                    $src =  "assets/panel/userassets/employees/".$value->Id."/".$value->Photo;
                    if(!file_exists($src))
                    {
                       $src =  "assets/images/default-user.png";
                    } 
                }

                $view_btn = $edit_btn = $delete_btn = ""; 
                if(in_array($module_id."_view", $this->role_permissions)){
                    $view_btn = '<li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,\'employee_view\','.$value->Id.')"> <i class="fa fa-eye"></i> View</a></li>';
                }

                if(in_array($module_id."_edit", $this->role_permissions)){
                    $edit_btn = '<li><a href="javascript:;" style="color: blue;" onclick="load_view(this,\'form_employee_edit\','.$value->Id.')">  <i class="fa fa-pencil"></i> Edit </a></li>';
                }

                if(in_array($module_id."_delete", $this->role_permissions)){
                    $delete_btn = '<li><a href="javascript:;" onclick="delete_record(\'employees\','.$value->Id.',this)" style="color: red;">
                                     <i class="fa fa-trash"></i> Delete</a></li>';
                }

                $records .= '<tr id="row_'.$value->Id.'">
                            <td>
                                <label class="ckbox ckbox-primary">
                                  <input class="table_record_checkbox" value="'.$value->Id.'" type="checkbox" id="record_'.$value->Id.'" ><span></span>
                                </label>
                            </td> 
                            <td>
                                <div class="btn-group">
                                  <button type="button" class="dropdown-toggle action-dropdown" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                                  <ul class="dropdown-menu" role="menu">
                                     '.$view_btn.' '.$edit_btn.' '.$delete_btn.'
                                  </ul>
                                </div>
                            </td>
                            <td>
                            <div class="table-profile-img" >
                                <a  href="javascript:;" >
                                  <img src="'.$src.'" class="pro-img">
                                </a> 
                              </div> 
                              <div class="emp-tabe-name">
                                  '.$value->First_Name.' '.$value->Last_Name.'
                                  <span class="emp-table-email">'.$value->Email.'</span>  
                              </div>
                            </td>
                            <td>'.$value->Role_Name.'   </td> 
                            <td>'.$value->Location_Name.'   </td> 
                            <td>'.$value->Joining_Date.'   </td> 
                            <td>'.date("l, F d, Y H:i:s", strtotime($value->Date_Added)).'</td>
                            <td>'.date("l, F d, Y H:i:s", strtotime($value->Date_Added)).'</td>
                            <td>'.$value->Addedby_FirstName." ".$value->Addedby_LastName.'</td>
                            <td>'.$value->Updatedby_FirstName." ".$value->Updatedby_LastName.'</td> 
                            
                           

                          </tr>
                    ';
            }

            $config['base_url'] = base_url('admin/get_employees');
            $config['total_rows'] = $result['total_records'];
            $config['per_page'] = $data['per_page'];
            $config["uri_segment"] = 3; 
            $config['first_url'] = base_url('admin/get_employees/1');  
            $config['num_links'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;  
            $config['enable_query_strings']= TRUE;
            $config['first_link'] = 'First Page';
            $config['first_tag_open'] = '<li class="firstlink">';
            $config['first_tag_close'] = '</li>'; 
            $config['last_link'] = 'Last Page';
            $config['last_tag_open'] = '<li class="lastlink">';
            $config['last_tag_close'] = '</li>'; 
            $config['next_link'] = 'Next Page';
            $config['next_tag_open'] = '<li class="nextlink">';
            $config['next_tag_close'] = '</li>'; 
            $config['prev_link'] = 'Prev Page';
            $config['prev_tag_open'] = '<li class="prevlink">';
            $config['prev_tag_close'] = '</li>'; 
            $config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
            $config['cur_tag_close'] = '</a></li>'; 
            $config['num_tag_open'] = '<li class="numlink">';
            $config['num_tag_close'] = '</li>';
     
            
            $this->pagination->initialize($config);
            $links = $this->pagination->create_links();  
            
          }
          else
          { 
            $records .= '<tr> <td colspan="11">'.no_record_found().'</td> </tr>';
          }
          
            
            $result['links']  = $links;
            $result['records']  = $records;

             echo json_encode($result); 
    }

    

    public function get_applicants($query_strings='')
    {
          $filter_data = array();
          $data = $this->input->post();   

          $result =   $this->Admin_modal->get_applicants($data);
          $records = $links = "";

          if(sizeof($result['result']) > 0)
          {

            $module_id = 0000;
            $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Applicants","Deleted"=>0,"Status"=>1));
            if($module_data->num_rows() > 0)
            {
                $module_info = $module_data->result_array();
                $module_id = $module_info[0]['Id'];
            }


            foreach ($result['result'] as $key => $value) 
            {
                $src =  "assets/images/default-user.png";
                if($value->Photo && $value->Photo != "")
                {
                    $src =  "assets/panel/userassets/applicants/".$value->Id."/".$value->Photo;
                    if(!file_exists($src))
                    {
                       $src =  "assets/images/default-user.png";
                    } 
                }

                 $view_btn = $edit_btn = $delete_btn = ""; 
                if(in_array($module_id."_view", $this->role_permissions)){
                    $view_btn = '<li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,\'applicant_view\','.$value->Id.')"> <i class="fa fa-eye"></i> View</a></li>';
                }

                if(in_array($module_id."_edit", $this->role_permissions)){
                    $edit_btn = '<li><a href="javascript:;" style="color: blue;" onclick="load_view(this,\'form_applicant_edit\','.$value->Id.')">  <i class="fa fa-pencil"></i> Edit </a></li>';
                }

                if(in_array($module_id."_delete", $this->role_permissions)){
                    $delete_btn = '<li><a href="javascript:;" onclick="delete_record(\'applicants\','.$value->Id.',this)" style="color: red;">
                                     <i class="fa fa-trash"></i> Delete</a></li>';
                }

              $applicant_address = $value->Country_Name.", ".$value->State_Name.", ".$value->City_Name.", ".$value->Address;

              $records .= '<tr id="row_'.$value->Id.'">
                            <td>
                                <label class="ckbox ckbox-primary">
                                  <input class="table_record_checkbox" value="'.$value->Id.'" type="checkbox" id="record_'.$value->Id.'" ><span></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-group">
                                  <button type="button" class="dropdown-toggle action-dropdown" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                                  <ul class="dropdown-menu" role="menu">
                                     '.$view_btn.' '.$edit_btn.' '.$delete_btn.'
                                  </ul>
                                </div>
                            </td>

                            <td>
                            <div class="table-profile-img" >
                                <a  href="javascript:;" >
                                  <img src="'.$src.'" class="pro-img">
                                </a> 
                              </div> 
                              <div class="emp-tabe-name">
                                  '.$value->First_Name.' '.$value->Last_Name.'
                                  <span class="emp-table-email">'.$value->Email.'</span>  
                              </div>
                            </td>
                            <td>'.$value->Job_Title.'   </td> 
                            <td>'.$value->Location_Name.'   </td> 
                            <td>'.$value->Application_Status.'</td>
                            <td>'.$value->Degree_Type.'</td>   
                            <td>'.$value->Institute_Name.'</td>
                            <td>'.$value->Applied_Date.'</td>
                            <td>'.$applicant_address.'</td>
                             
                          </tr>
                    ';
            }

            $config['base_url'] = base_url('admin/get_applicants');
            $config['total_rows'] = $result['total_records'];
            $config['per_page'] = $data['per_page'];
            $config["uri_segment"] = 3; 
            $config['first_url'] = base_url('admin/get_applicants/1');  
            $config['num_links'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;  
            $config['enable_query_strings']= TRUE;
            $config['first_link'] = 'First Page';
            $config['first_tag_open'] = '<li class="firstlink">';
            $config['first_tag_close'] = '</li>'; 
            $config['last_link'] = 'Last Page';
            $config['last_tag_open'] = '<li class="lastlink">';
            $config['last_tag_close'] = '</li>'; 
            $config['next_link'] = 'Next Page';
            $config['next_tag_open'] = '<li class="nextlink">';
            $config['next_tag_close'] = '</li>'; 
            $config['prev_link'] = 'Prev Page';
            $config['prev_tag_open'] = '<li class="prevlink">';
            $config['prev_tag_close'] = '</li>'; 
            $config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
            $config['cur_tag_close'] = '</a></li>'; 
            $config['num_tag_open'] = '<li class="numlink">';
            $config['num_tag_close'] = '</li>';
     
            
            $this->pagination->initialize($config);
            $links = $this->pagination->create_links();  
            
          }
          else
          { 
            $records .= '<tr> <td colspan="11">'.no_record_found().'</td> </tr>';
          }
          
            
            $result['links']  = $links;
            $result['records']  = $records;
            $result['total_records']  = $result['total_records'];

             echo json_encode($result); 
    }

    public function get_organization_organogram()
    {
        $organization_organogram =  array();
        $organization_data = $this->db->get_where("organizations",array("Id"=>$this->org_id));
        if($organization_data->num_rows() > 0)
        {
            $organization_info = $organization_data->result_array();
            $org_name = $organization_info[0]['Name'];

            $org_id = time();

            $organization_organogram[] =  array( 
                                                "id" => $org_id, 
                                                "name" => $org_name, 
                                                "parent" => "0", 
                                             );
            $child_id = 1;
            $departments_rec = $this->db->get_where("departments",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
            if($departments_rec->num_rows() > 0)
            {
               foreach ($departments_rec->result() as $key => $value) {

                    $dep_id = $child_id++;
                    $organization_organogram[] =  array( 
                                                            "id" => $dep_id, 
                                                            "name" => $value->Name, 
                                                            "parent" => $org_id, 
                                                        );

                    $designations_rec = $this->db->get_where("designations",array("Department_Id"=>$value->Id,"Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                    if($designations_rec->num_rows() > 0)
                    {
                       foreach ($designations_rec->result() as $index => $designation) {

                            $designation_id = $child_id++;
                            $organization_organogram[] =  array( 
                                                                    "id" => $designation_id, 
                                                                    "name" => $designation->Name, 
                                                                    "parent" => $dep_id, 
                                                                );

                            $this->db->where(array("employees.Deleted"=>0,"employee_work_record.Designation_Id"=>$designation->Id,"employee_work_record.Status"=>1));
                            $this->db->select("employees.First_Name,employees.Last_Name,employees.Id,employee_work_record.Employee_Id,employee_work_record.Department_Id,employee_work_record.Designation_Id");
                            $this->db->from("employees");
                            $this->db->join("employee_work_record","employee_work_record.Employee_Id = employees.Id","left");
                            $employees_rec = $this->db->get();
                            if($employees_rec->num_rows() > 0)
                            {
                               foreach ($employees_rec->result() as $ikey => $employee) {

                                    $employee_id = $child_id++;
                                    $organization_organogram[] =  array( 
                                                                            "id" => $employee_id, 
                                                                            "name" => $employee->First_Name." ".$employee->Last_Name, 
                                                                            "parent" => $designation_id, 
                                                                        );
                               }
                            }

                        }
                    }
               }
            }
        }

        echo json_encode($organization_organogram);
    }

    
    public function get_organogram_childs()
    {

        // $this->modules[] =  array( 
        //                             "id" => '111', 
        //                             "name" => 'Organization', 
        //                             "parent" =>'0', 
        //                        );

        $organogram_childs = $this->db->get_where("organogram_childs",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id)); 
        
        if($organogram_childs->num_rows() > 0)
        {
          foreach ($organogram_childs->result() as $key => $value) 
          {   
            if(!in_array($value->Id, $this->processed))
            { 
                    if($value->Parent_Id > 0)
                    {
                        if(!in_array($value->Parent_Id,$this->processed))
                        {
                             $this->check_organogram_childs($value->Parent_Id); 
                        }  
                    } 

                    if(!in_array($value->Id, $this->processed))
                    { 
                        $this->modules[] =  array( 
                                                "id" => $value->Id, 
                                                "name" => $value->Name, 
                                                "parent" => $value->Parent_Id, 
                                           );

                        $this->processed[] = $value->Id;
                    }
                     
            }

          }
        }

        

        echo json_encode($this->modules);
    }

    public function check_organogram_childs($id)
    {
        $this->counter = $this->counter + 1; 

        if($this->counter > 500)
        {
            return false;
        }
 
        $parent_child = $this->db->get_where("organogram_childs",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Id"=>$id)); 
        if($parent_child->num_rows() > 0)
        {  
            $parent_child_data = $parent_child->result_array();

            if($parent_child_data[0]['Parent_Id'] > 0)
            { 
                if(!in_array($parent_child_data[0]['Parent_Id'], $this->processed))
                {
                    $this->check_organogram_childs($parent_child_data[0]['Parent_Id']); 
                }
                 
            } 
             
            if(!in_array($id, $this->processed))
            {

                $array =  array( 
                                            "id" => $parent_child_data[0]['Id'], 
                                            "name" => $parent_child_data[0]['Name'], 
                                            "parent" => $parent_child_data[0]['Parent_Id'], 
                                         );

                $this->modules[] = $array;
                $this->processed[] = $id; 
                 
            } 
        } 
  
    }



    public function save_organogram_child()
    {
        $data = $this->input->post();
        $data['Date_Added'] = $this->date;
        $data['Added_By'] = $this->user_data['Id'];  
        $data['Org_Id'] = $this->org_id;  
        $save = $this->db->insert("organogram_childs",$data);

        echo $this->db->insert_id();
    }

    public function update_organogram_child()
    {
        $data = $this->input->post();
        $id = $data['Id'];
        $name = $data['Name'];
         
        $update = $this->db->update("organogram_childs",array("Name"=>$name),array("Id"=>$id));

        echo $update;
    }
     
    public function delete_organogram_child()
    {
        $id = $this->input->post('Id'); 
        $delete = $this->db->update("organogram_childs",array("Deleted"=>1),array("Id"=>$id)); 
        $this->parent_delete_childs($id); 
        return $delete;
    }

    public function parent_delete_childs($id)
    {
        $childs = $this->db->get_where("organogram_childs",array("Parent_Id"=>$id,"Deleted"=>0));
        if($childs->num_rows() > 0)
        {
            foreach ($childs->result() as $key => $value) 
            {
                $delete = $this->db->update("organogram_childs",array("Deleted"=>1),array("Id"=>$value->Id)); 
                $this->parent_delete_childs($value->Id);
            }
        }
    }

    public function change_application_status()
    {
        $response = array();
        $data = $this->input->post();

        $applicants = explode(",",$data['Applicants']);

        $this->db->where_in("Applicant_Id",$applicants); 
        $applicants_rec = $this->db->update("applicant_applications",array("Application_Status"=>$data['Application_Status']),array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));

        //echo $this->db->last_query(); 

        if(isset($data['Send_Email']) && $data['Send_Email'] == "on")
        {
            $email_template = $this->db->get_where("email_templates",array("Deleted"=>0,"Status"=>1,"Id"=>$data['Email_Templates']));
            if($email_template->num_rows() > 0)
            {
                $template_data = $email_template->result_array();
                $applicants = explode(",",$data['Applicants']);

                $this->db->where_in("Id",$applicants);
                $this->db->select("Email,Id");
                $applicants_rec = $this->db->get_where("applicants",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));

                $this->db->select("Name");
                $org_data = $this->db->get_where("organizations",array("Deleted"=>0,"Status"=>1,"Id"=>$this->org_id))->result_array();
                    
                    
                    $variables = $this->getInbetweenStrings("@@" , "@@", $template_data[0]['Message']);
                    
                     
                    foreach ($applicants_rec->result() as $key => $value) {
                        
                        $message = $template_data[0]['Message'];
                        $table_data = "";
                        foreach ($variables as $index => $field) 
                        {
                           $field_parts = explode(".", $field);
                           if($field_parts[0] != "")
                           {
                                 $table_name = $field_parts[0];
                                 if(isset($field_parts[1])){ $field_name = $field_parts[1]; }else{ $field_name = ""; }
                                 if($table_name == "applicants")
                                 {
                                    $this->db->select($field_name);
                                    $table_data = $this->db->get_where($table_name,array("Id"=>$value->Id));
                                 }
                                 elseif($table_name == "job_posts")
                                 {
                                    $this->db->order_by("Id","DESC");
                                    $this->db->select("Job_Id");
                                    $applicant_application = $this->db->get_where("applicant_applications",array("Applicant_Id"=>$value->Id,"Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                                    if($applicant_application->num_rows() > 0)
                                    {
                                        $application_data = $applicant_application->result_array();
                                        $this->db->select($field_name);
                                        $table_data = $this->db->get_where($table_name,array("Id"=>$application_data[0]['Job_Id']));
                                    }
                                    
                                 }
                                 else
                                 {
                                    $message = $this->check_fields_in_message($field,$message);
                                 }

                                 if($table_data != "")
                                 {
                                     
                                    if(is_object($table_data))
                                    {
                                        if($table_data->num_rows() > 0)
                                        {
                                            $table_data = $table_data->result_array();
                                            $word = "@@".$field."@@";
                                            $replace_with = $table_data[0][$field_name];
                                            $message = str_replace($word,$replace_with,$message);
                                        }
                                    }
                                 }
                             
                           }
                        }

                        $email_data = array(
                                                "Org" => $org_data[0]['Name'], 
                                                "To" => $value->Email,
                                                "Subject" => $template_data[0]['Subject'],
                                                "Message" => $message,
                                            );

                        $this->send_email($email_data);
                    } 
            }
        }

        $alert  = '<div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Successful!</h4>
                         Applicants status is changed successfully...
                     </div>';
        $response['Success'] = true;
        $response['Message'] = $alert;
       

        echo json_encode($response);
    }

    public function check_fields_in_message($field,$message,$employee_id='')
    {
        if($field == "Person performing this action")
        {
            $employee = $this->db->get_where("employees",array("Id"=>$this->user_id))->result_array();
            $word = "@@".$field."@@";
            $replace_with = $employee[0]['First_Name']." ".$employee[0]['Last_Name'];
            $message = str_replace($word,$replace_with,$message);
        }
        elseif($field == "Reporting To")
        {
            if($employee_id != "")
            {
                $this->db->where(array("employee_report_to.Employee_Id"=>$employee_id) );
                $this->db->select("employees.First_Name, employees.Last_Name");
                $this->db->from("employee_report_to");
                $this->db->join("employees","employees.Id = employee_report_to.Employee_Id","left");
                $this->db->order_by("employee_report_to.Id","DESC");
                $reporting_to = $this->db->get('employees');

                if($reporting_to->num_rows() > 0)
                {
                    $reporting_to_data = $reporting_to->result_array();
                    $word = "@@".$field."@@";
                    $replace_with = $reporting_to_data[0]['First_Name']." ".$employee[0]['Last_Name'];
                    $message = str_replace($word,$replace_with,$message);
                } 
            }
        }
        elseif($field == "currenttime")
        {
            $time = $this->time;
            $word = "@@".$field."@@";
            $replace_with = $time;
            $message = str_replace($word,$replace_with,$message);
        }
        elseif($field == "currentdate")
        {
            $date = date('Y-m-d');
            $word = "@@".$field."@@";
            $replace_with = $date;
            $message = str_replace($word,$replace_with,$message);
        }
        elseif($field == "companyCity")
        {
            $this->db->where(array("organizations.Id"=>$this->org_id) );
            $this->db->select("cities.name as City_Name");
            $this->db->from("organizations");
            $this->db->join("cities","cities.id = organizations.City","left");
            $this->db->order_by("organizations.Id","DESC");
            $organizations = $this->db->get('organizations');
            if($organizations->num_rows() > 0)
            {
                $organization_data = $organizations->result_array();
                $word = "@@".$field."@@";
                $replace_with = $organization_data[0]['City_Name'];
                $message = str_replace($word,$replace_with,$message);
            }
            
        }
        elseif($field == "companyFirstAddress")
        {
            $organization = $this->db->get_where("organizations",array("Id"=>$this->org_id))->result_array();
            $word = "@@".$field."@@";
            $replace_with = $organization[0]['Address'];
            $message = str_replace($word,$replace_with,$message);
        }
        elseif($field == "companyLogo")
        {
            $this->db->select("Logo,Id");
            $organization = $this->db->get_where("organizations",array("Id"=>$this->org_id))->result_array();
           
            if($organization[0]['Logo'] && $organization[0]['Logo'] != "")
            {
                $src = ASSETSPATH."panel/userassets/organizations/".$organization[0]['Id']."/".$organization[0]['Logo'];
                $logo = '<div style="width: 100px; clear:both;"><img src="'.$src.'" style="width: 100%;"></div>';
            }
            else
            {
                $logo = "";
            }

            $word = "@@".$field."@@";
            $replace_with = $logo;
            $message = str_replace($word,$replace_with,$message);
        }
        elseif($field == "companyZipcode")
        {
            $this->db->select("Zipcode");
            $organization = $this->db->get_where("organizations",array("Id"=>$this->org_id))->result_array();
            $word = "@@".$field."@@";
            $replace_with = $organization[0]['Zipcode'];
            $message = str_replace($word,$replace_with,$message);
        }
        elseif($field == "companyContactPerson")
        {
            $this->db->select("First_Name,Last_Name");
            $employees = $this->db->get_where("employees",array("Org_Id"=>$this->org_id,"User_Type"=>"Super_Admin"))->result_array();
            $word = "@@".$field."@@";
            $replace_with = $employees[0]['First_Name']." ".$employees[0]['Last_Name'];
            $message = str_replace($word,$replace_with,$message);
        }
        elseif($field == "companyCountry")
        {
            $this->db->where(array("organizations.Id"=>$this->org_id) );
            $this->db->select("countries.name as Country_Name");
            $this->db->from("organizations");
            $this->db->join("countries","countries.id = organizations.Country","left");
            $this->db->order_by("organizations.Id","DESC");
            $organizations = $this->db->get('organizations');
            if($organizations->num_rows() > 0)
            {
                $organization_data = $organizations->result_array();
                $word = "@@".$field."@@";
                $replace_with = $organization_data[0]['Country_Name'];
                $message = str_replace($word,$replace_with,$message);
            }
            
        }
        elseif($field == "companyContactNumber")
        {
            $this->db->select("Primary_Phone");
            $organization = $this->db->get_where("organizations",array("Id"=>$this->org_id))->result_array();
            $word = "@@".$field."@@";
            $replace_with = $organization[0]['Primary_Phone'];
            $message = str_replace($word,$replace_with,$message);
        }
        elseif($field == "companyWebsite")
        {
            $this->db->select("Domain");
            $organization = $this->db->get_where("organizations",array("Id"=>$this->org_id))->result_array();
            $word = "@@".$field."@@";
            $replace_with = $organization[0]['Domain'];
            $message = str_replace($word,$replace_with,$message);
        }



        return $message;
    }
 
    public function getInbetweenStrings($start, $end, $str)
    {
        $matches = array();
        $regex = "/$start([a-zA-Z0-9_.]*)$end/";
        preg_match_all($regex, $str, $matches);
        return $matches[1];
    }


    public function send_email($email_data = array())
    {
      $email_settings = $this->db->get_where("email_settings",array("Deleted"=>0,"Status"=>1,"Id"=>$this->org_id)); 
      if($email_settings->num_rows() > 0)
      {
          $email_setting_data = $email_settings->result_array();
          //'ssl://smtp.consol.pk',
          $config = Array( 
              'protocol'  => 'smtp',
              'smtp_host' => $email_setting_data[0]['Smtp_Host'],
              'smtp_port' => $email_setting_data[0]['Smtp_Port'],
              'smtp_user' => $email_setting_data[0]['Smtp_User'],
              'smtp_pass' => $email_setting_data[0]['Smtp_Password'], 
              'newline'   =>  "\r\n",
              'validation'   => true,
              'mailtype'  => 'html', 
              'charset'   => 'iso-8859-1', 
              'wordwrap'  => TRUE
          );

          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->set_mailtype("html"); 
          $this->email->from($email_setting_data[0]['Email_From'], $email_data['Org']);
          $this->email->to($email_data['To']);
          $this->email->reply_to($email_setting_data[0]['Email_From'],$email_setting_data[0]['Email_From']);
          $this->email->subject($email_data['Subject']);
          $this->email->message($email_data['Message']);
          $result = $this->email->send();

          if($result)
          {
            $email_data['Sent_By'] = $this->user_id;
            $email_data['Added_By'] = $this->user_id;
            $email_data['Date_Added'] = $this->date;
            $email_data['Sent_To'] =$email_data['To'];
            $email_data['Org_Id'] =$email_data['Org'];

            unset($email_data['Org'],$email_data['To']);

            $this->db->insert("email_history",$email_data);
          }


          return $result; 
      }
    }

    public function generate_excel_file()
    {

        $filename = "applicants_".time();
        $file_title = "Applicants";
        $table = $this->input->post("table");
        $records = $this->input->post("ids");
        $response = 0;
        if(!empty($records))
        {
            $applicants = $this->Admin_modal->get_applicants_for_excel($records);
            $applicants_array = $applicants->result_array();

            
            $file_columns = array_keys($applicants_array[0]);
            $xls = generate_excel($filename,$file_title,$file_columns,$applicants_array);
            $response = $xls;
        }
        
        echo $response;
        
    }



}

