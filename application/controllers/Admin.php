<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
 
    public $date = null;
    public $just_date = null;
	public $time = null;
	public $modules = array();
    public $processed = array();
    public $counter = 0;
    public $CI = null;


	public function __construct() 
	{ 
        parent::__construct();      

        date_default_timezone_set("Asia/Karachi");
        $this->date = date('Y-m-d H:i:s'); 
        $this->just_date = date('Y-m-d'); 
        $this->time = date('H:i:s'); 
        $this->CI = $this;
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
        else{

            $this->db->delete("shift_employees",array("Org_Id"=>$this->org_id,"Shift_Id"=>$id));
        }

        echo json_encode($message);
    }

    public function save_week_work()
    {
        $message = array();

        $data = $this->input->post(); 
        // echo "<pre>";
        // print_r($data);

        $edit_rec = 0;
        if(isset($data['Edit_Recorde']) )
        {
            if($data['Edit_Recorde'] && $data['Edit_Recorde'] > 0)
            {
                $edit_rec = $data['Edit_Recorde'];
            }
        }

        $check = 0;
        if(isset($data['Location_Id']))
        {
           $check = $this->db->get_where("org_work_days",array("Location_Id"=>$data['Location_Id'],"Deleted"=>0,"Status"=>1))->num_rowS();
        }

        if($edit_rec < 1 && $check == 0)
        { 
            $i = 1;
            while($i < 6)
            {  
                if(!isset($data['Sunday_'.$i])){ $data['Sunday_'.$i] = 0; }
                if(!isset($data['Monday_'.$i])){ $data['Monday_'.$i] = 0; }
                if(!isset($data['Tuesday_'.$i])){ $data['Tuesday_'.$i] = 0; }
                if(!isset($data['Wednesday_'.$i])){ $data['Wednesday_'.$i] = 0; }
                if(!isset($data['Thursday_'.$i])){ $data['Thursday_'.$i] = 0; }
                if(!isset($data['Friday_'.$i])){ $data['Friday_'.$i] = 0; }
                if(!isset($data['Saturday_'.$i])){ $data['Saturday_'.$i] = 0; }

                $day = array(
                                "Org_Id" => $this->org_id,
                                "Location_Id" => $data['Location_Id'],
                                "Day_Repetition" => $i,
                                "Sunday" => $data['Sunday_'.$i],
                                "Monday" => $data['Monday_'.$i],
                                "Tuesday" => $data['Tuesday_'.$i],
                                "Wednesday" => $data['Wednesday_'.$i],
                                "Thursday" => $data['Thursday_'.$i],
                                "Friday" => $data['Friday_'.$i],
                                "Saturday" => $data['Saturday_'.$i]
                           );

                $this->db->insert("org_work_days",$day);

                $i++;
            } 

            $alert  = ' <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Success!</h4>
                             Work week is saved successfully.
                         </div>';  

            $message['Success'] = true;
            $message['Message'] = $alert;  
             
        }
        else
        {
            $location_id = $data['Location_Id'];

            $this->db->select("Id");
            $location_week = $this->db->get_where("org_work_days",array("Deleted"=>0,"Status"=>1,"Location_Id"=>$location_id,"Org_Id"=>$this->org_id));

            $i = 1;
            if($location_week->num_rows() > 0)
            {
                foreach ($location_week->result() as $key => $value) 
                {
                    if(!isset($data['Sunday_'.$i])){ $data['Sunday_'.$i] = 0; }
                    if(!isset($data['Monday_'.$i])){ $data['Monday_'.$i] = 0; }
                    if(!isset($data['Tuesday_'.$i])){ $data['Tuesday_'.$i] = 0; }
                    if(!isset($data['Wednesday_'.$i])){ $data['Wednesday_'.$i] = 0; }
                    if(!isset($data['Thursday_'.$i])){ $data['Thursday_'.$i] = 0; }
                    if(!isset($data['Friday_'.$i])){ $data['Friday_'.$i] = 0; }
                    if(!isset($data['Saturday_'.$i])){ $data['Saturday_'.$i] = 0; }

                    $day = array(
                                    "Org_Id" => $this->org_id,
                                    "Location_Id" => $data['Location_Id'],
                                    "Day_Repetition" => $i,
                                    "Sunday" => $data['Sunday_'.$i],
                                    "Monday" => $data['Monday_'.$i],
                                    "Tuesday" => $data['Tuesday_'.$i],
                                    "Wednesday" => $data['Wednesday_'.$i],
                                    "Thursday" => $data['Thursday_'.$i],
                                    "Friday" => $data['Friday_'.$i],
                                    "Saturday" => $data['Saturday_'.$i]
                               );

                    $this->db->update("org_work_days",$day,array("Id"=>$value->Id));

                     $i++;
                }

                $alert  = ' <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-ban"></i> Success!</h4>
                                 Work week is updated successfully.
                             </div>';  

                $message['Success'] = true;
                $message['Message'] = $alert;  
                
            }
 
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
        $status = "start =";
        $action_status = false;
        $data = $this->input->post();
         
        if(sizeof($data['ids']) > 0 )
        {
            $this->db->where_in("Id",$data['ids']);
            $this->db->select("Id,Email");
            $applicants = $this->db->get_where("applicants",array("Deleted"=>0,"Status"=>1));
            if($applicants->num_rows() > 0)
            { 
                foreach ($applicants->result() as $key => $applicant) 
                {  
                    $status = "start = applicants = ";
                    $applicant_applications = $this->db->get_where("applicant_applications",array("Applicant_Id"=>$applicant->Id,"Deleted"=>0,"Status"=>1, "Application_Status !="=>"Rejected","Application_Status !="=>"Hired","Application_Status !="=>"Offered"));
                    if($applicant_applications->num_rows() > 0)
                    { 
                        foreach ($applicant_applications->result() as $appli_index => $application) 
                        { 
                            $status = "start = applicants = applications = ";
                             $interviewers = array();
                             $interview_categories = array();
                             $interview_date = $send_email = $email_template = $interviewer_email = $interviewer_email_template = ""; 
                             
                             foreach ($data['form_data'] as $i => $v) {
                                 if($v['name'] == "Interviewer"){ $interviewers[] = $v['value']; }
                                 if($v['name'] == "Interviewer_Category"){ $interview_categories[] = $v; }
                                 if($v['name'] == "Interview_Date"){ $interview_date = $v['value']; }
                                 if($v['name'] == "Send_Email"){ $send_email = $v['value']; }
                                 if($v['name'] == "Email_Templates"){ $email_template = $v['value']; }
                                 if($v['name'] == "Send_Interviewer_Email"){ $interviewer_email = $v['value']; }
                                 if($v['name'] == "Interviewer_Email_Templates"){ $interviewer_email_template = $v['value']; }
                             }

                             $this->db->select("Name");
                             $org_data = $this->db->get_where("organizations",array("Deleted"=>0,"Status"=>1,"Id"=>$this->org_id))->result_array();  
                             
                             $this->db->select("Subject");
                             $appli_template_data = $this->db->get_where("email_templates",array("Deleted"=>0,"Status"=>1,"Id"=>$email_template,"Org_Id"=>$this->org_id))->result_array();   
                             
                             $this->db->select("Subject");
                             $interviewer_template_data = $this->db->get_where("email_templates",array("Deleted"=>0,"Status"=>1,"Id"=>$email_template,"Org_Id"=>$this->org_id))->result_array();


                            foreach ($interviewers as $inter => $interviewer) 
                            {  
                                $status = "start = applicants = applications = interviewers = ";
                                $interview_category = $interview_categories[$inter]['value'];
                                $check_interviewer = $this->db->get_where('applicant_interviews',array("Application_Id"=>$application->Id,"Applicant_Id"=>$applicant->Id,"Org_Id"=>$this->org_id,"Interviewer"=>$interviewer,"Job_Id"=>$application->Job_Id,"Deleted"=>0,"Status"=>1));
                                if($check_interviewer->num_rows() < 1)
                                { 
                                       
                                     $action_status = true;
                                    
                                     $interveiw_data['Application_Id'] = $application->Id;
                                     $interveiw_data['Applicant_Id'] = $applicant->Id;
                                     $interveiw_data['Org_Id'] = $this->org_id;
                                     $interveiw_data['Interviewer'] = $interviewer;
                                     $interveiw_data['Job_Id'] = $application->Job_Id;
                                     $interveiw_data['Interview_Date'] = $interview_date;
                                     $interveiw_data['Question_Category'] = $interview_category; 

                                     // echo "<pre>";
                                     // print_r($interveiw_data);
                                     // echo "</pre>";
                                     // die();

                                     $this->db->insert('applicant_interviews',$interveiw_data);    

                                     if($send_email != "" && $send_email == "on")
                                     {
                                        
                                        $message = $this->get_email_content($email_template,$applicant->Id,'applicants');
                                        $apli_email_data['Message'] = $message;
                                        $apli_email_data = array(
                                                                    "Org" => $org_data[0]['Name'], 
                                                                    "To" => $applicant->Email,
                                                                    "Subject" => $appli_template_data[0]['Subject'],
                                                                    "Message" => $message,
                                                                );
                                        $this->send_email($apli_email_data);

                                     }

                                     if($interviewer_email != "" && $interviewer_email == "on")
                                     {
                                        $this->db->select("Email");
                                        $interviewer_data = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Id"=>$interviewer))->result_array();   
                              
                                        $message = $this->get_email_content($email_template,$interviewer,'employees');
                                        $apli_email_data['Message'] = $message;
                                        $interviewer_email_data = array(
                                                                    "Org" => $org_data[0]['Name'], 
                                                                    "To" => $interviewer_data[0]['Email'],
                                                                    "Subject" => $interviewer_email_template[0]['Subject'],
                                                                    "Message" => $message,
                                                                );
                                        $this->send_email($interviewer_email_data);
                                     }
                                      

                                } 
                            }
                             
                        }
                    }
                } 
            } 
        }
        
        
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        echo $status; 
    }


    // public function assign_interviewers($value='')
    // {
    //     $action_status = false;
    //     $data = $this->input->post();
    //     if(isset($data['ids']) && isset($data['interviewers']))
    //     {
    //         if(sizeof($data['ids']) > 0 && sizeof($data['interviewers']) > 0)
    //         {
    //             $this->db->where_in("Id",$data['ids']);
    //             $this->db->select("Id");
    //             $applicants = $this->db->get_where("applicants",array("Deleted"=>0,"Status"=>1));
    //             if($applicants->num_rows() > 0)
    //             { 
    //                 foreach ($applicants->result() as $key => $applicant) 
    //                 {  
    //                     $applicant_applications = $this->db->get_where("applicant_applications",array("Applicant_Id"=>$applicant->Id,"Deleted"=>0,"Status"=>1,"Application_Status !="=>"Rejected","Application_Status !="=>"Rejected","Application_Status !="=>"Hired","Application_Status !="=>"Offered"));
    //                     if($applicant_applications->num_rows() > 0)
    //                     { 
    //                         foreach ($applicant_applications->result() as $appli_index => $application) 
    //                         {
                                 
    //                             foreach ($data['interviewers'] as $index => $interviewer) 
    //                             { 
    //                                 $check_interviewer = $this->db->get_where('applicant_interviews',array("Application_Id"=>$application->Id,"Applicant_Id"=>$applicant->Id,"Org_Id"=>$this->org_id,"Interviewer"=>$interviewer,"Job_Id"=>$application->Job_Id));
    //                                 if($check_interviewer->num_rows() < 1)
    //                                 {
    //                                      $action_status = true;
    //                                      $this->db->insert('applicant_interviews',array("Application_Id"=>$application->Id,"Applicant_Id"=>$applicant->Id,"Org_Id"=>$this->org_id,"Interviewer"=>$interviewer,"Job_Id"=>$application->Job_Id));    
    //                                 } 
    //                             } 
    //                         }
    //                     }
    //                 } 
    //             } 
    //         }
    //     }
        

    //     echo $action_status; 
    // }

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
                        $applicant_info['Joining_Date'] = date("Y-m-d");
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

                        $this->db->select("job_posts.*");
                        $this->db->where(array("job_posts.Deleted"=>0,"applicant_applications.Applicant_Id"=>$value->Id));
                        $this->db->from("applicant_applications");
                        $this->db->join("job_posts","job_posts.Id = applicant_applications.Job_Id","left");
                        $this->db->order_by("job_posts.Id","DESC");
                        $this->db->group_by("applicant_applications.Applicant_Id","DESC");
                        $jobpost_record = $this->db->get();
                        if($jobpost_record->num_rows() > 0)
                        {
                            $jobpost_data = $jobpost_record->row();
                            $work_record_data = array(
                                                    "Employee_Type" => $jobpost_data->Job_Type,
                                                    "Designation_Id" => $jobpost_data->Designation_Id,
                                                    "Org_Id" => $jobpost_data->Org_Id,
                                                    "Employee_Id" => $employee_id,
                                                    "Department_Id" => $jobpost_data->Department_Id,
                                                    "Start_Date" => date("Y-m-d H:s:i"),
                                                    "Location_Id" => $jobpost_data->Location_Id
                                                 );
                            $this->db->insert("employee_work_record",$work_record_data);
                        }
                        
                        if(isset($data['Shift_Id'])){  
                            $shift_data = array(
                                                     
                                                    "Org_Id" => $this->org_id,
                                                    "Employee_Id" => $employee_id, 
                                                    "Shift_Id" => $data['Shift_Id']
                                                 );
                            $this->db->insert("shift_employees",$shift_data);
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

    public function delete_shift_settings() 
    { 
        $id  = $this->input->post("id");  

        $this->db->update('shift_settings',array("Deleted"=>1),array("Shift_Id"=>$id));           

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

    public function get_department_designations()
    {
        $data = $this->input->post();
        $options = '<option value="0">Select Designation</option>';
        if($data['id'] != "")
        {
            $designations = $this->db->get_where("designations",array("Department_Id"=>$data['id'], "Org_Id" => $this->org_id,"Deleted"=>0,"Status"=>1));
            if($designations->num_rows() > 0)
            {
                foreach ($designations->result() as $key => $value) 
                {
                    $selected = "";  
                    $options .= '<option '.$selected.' value="'.$value->Id.'">'.$value->Name.'</option>';
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
            $page = $data['page'];
            $per_page = $data['per_page']; 
            $offset = ($page - 1) * $per_page;
            $limit =  $per_page; 
            $i = $offset;

            $module_id = 0000;
            $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Applicants","Deleted"=>0,"Status"=>1));
            if($module_data->num_rows() > 0)
            {
                $module_info = $module_data->result_array();
                $module_id = $module_info[0]['Id'];
            } 

            foreach ($result['result'] as $key => $value) 
            {
                $i++;
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
                 
              $appli_status = "";
              if( $value->Application_Status == "New"){ $appli_status = '<span class="label label-default">New</span>'; }
              if( $value->Application_Status == "Shortlisted"){ $appli_status = '<span class="label label-md1">Shortlisted</span>'; }
              if( $value->Application_Status == "Screened"){ $appli_status = '<span class="label label-primary">Screened</span>'; }
              if( $value->Application_Status == "Interview"){ $appli_status = '<span class="label label-info">Interview</span>'; }
              if( $value->Application_Status == "Offered"){ $appli_status = '<span class="label label-warning">Offered</span>'; }
              if( $value->Application_Status == "Hired"){ $appli_status = '<span class="label label-success">Hired</span>'; }
              if( $value->Application_Status == "Rejected"){ $appli_status = '<span class="label label-danger">Rejected</span>'; }

              $campus = "Campus Not Mentioned";
              if($value->Institute_Campus && $value->Institute_Campus != ""){ $campus = $value->Institute_Campus; }

              $records .= '<tr id="row_'.$value->Id.'">
                            <td>'.$i.'</td>
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
                                  <span>'.$value->First_Name.' '.$value->Last_Name.' '.$appli_status.' &nbsp;<span class="label label-md3">'.$value->applicant_total_applications.'</span></span>
                                  <span class="emp-table-email">'.$value->Email.'</span>  
                              </div>
                            </td>
                            <td><span>'.$value->Job_Title.'</span><br><span class="label label-md2">'.$value->Location_Name.'</span></td>   
                            <td>'.$value->Degree_Type.'</td>   
                            <td>'.$value->Institute_Name.' ('.$campus.') </td>
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
    
        $config = array();
        $config['useragent']           = "CodeIgniter";
        $config['mailpath']            = "/usr/bin/sendmail"; 
        $config['smtp_user']           = $email_setting_data[0]['Smtp_User'];
        $config['smtp_pass']           = $email_setting_data[0]['Smtp_Password'];
        $config['protocol']            = "smtp";
        $config['smtp_host']           = $email_setting_data[0]['Smtp_Host'];
        $config['smtp_port']           = $email_setting_data[0]['Smtp_Port'];
        $config['mailtype']            = 'html';
        $config['charset']             = 'iso-8859-1'; 
        $config['wordwrap']            = TRUE; 
        $config['validate']            = TRUE; 
        

        $this->load->library('email');
        $this->email->initialize($config); 
        $this->email->set_newline("\r\n");  
        $this->email->from($email_setting_data[0]['Email_From'], $email_data['Org']);
        $this->email->to($email_data['To']);
        $this->email->reply_to($email_setting_data[0]['Email_From'] );
        $this->email->subject($email_data['Subject']);
        $this->email->message($email_data['Message']);
        $result =  $this->email->send();
        // echo $this->email->print_debugger();


          if($result)
          {
            $email_data['Sent_By'] = $this->user_id;
            $email_data['Added_By'] = $this->user_id;
            $email_data['Date_Added'] = $this->date;
            $email_data['Sent_To'] = $email_data['To'];
            $email_data['Org_Id'] = $this->org_id;

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

    public function delete_week_work_days()
    {
        $data = $this->input->post();
        if($data['id'] != "" && $data['id'] > 0)
        {
            $this->db->update("org_work_days",array("Deleted"=>1),array("Location_Id"=>$data['id']));
        }

        echo true;
    }


    public function employee_signout($value='')
    {
        $message = array();
        $message['Status'] = false;
        $data = $this->input->post();
        if(isset($data['id']))
        {
            $att_rec = $this->db->get_where("attendance",array("Id"=>$data['id']));
            if($att_rec->num_rows() > 0)
            {
                $signout_time = date("Y-m-d H:i:s");
                $this->db->update("attendance",array("Signout"=>$signout_time),array("Id"=>$data['id']));
                $message['signout_time'] = $signout_time;
                $message['Status'] = true;
            }
        }

        echo json_encode($message); 
    }

    public function get_attendance_history($value='')
    {
        $data = $this->input->post();
        $attendance_records = "";
        $this->db->select("employees.First_Name,employees.Last_Name,employees.Id,employees.Photo,shifts.Name as Shift_Name,shifts.Start_Time as Shift_Start_Time,departments.Name as Department_Name,locations.Name as Location_Name");
        $employees = $this->db->where( array("employees.Deleted"=>0,"employees.Status"=>1,"employees.Org_Id"=>$this->org_id,"employees.Employee_Status"=>"Active","employees.Id"=>$data['Employee_Id'] ));
        $this->db->from("employees"); 
        $this->db->join("employee_work_record","employee_work_record.Employee_Id = employees.Id","left");
        $this->db->join("departments","departments.Id = employee_work_record.Department_Id","left");
        $this->db->join("shift_employees","shift_employees.Employee_Id = employees.Id","left");
        $this->db->join("shifts","shifts.Id = shift_employees.Shift_Id","left");
        $this->db->join("locations","locations.Id = employees.Location_Id","left");  
        $this->db->group_by("employees.Id");
        $employees = $this->db->get();
        //echo $this->db->last_query();

        if($employees->num_rows() > 0)
        {
            foreach ($employees->result() as $key => $value) 
            {
                $department_name = "Not Saved!";

                if($value->Department_Name != ""){ $department_name = $value->Department_Name; } 
                if($data['To_Date'] == ""){ $data['To_Date'] = $data['From_Date']; }

                if(strtotime($data['From_Date']) <= strtotime($data['To_Date']))
                {
                    $date_difference = strtotime($data['To_Date']) - strtotime($data['From_Date']);
                    $days = date("d",$date_difference); 

                    for ($i = 0; $i < $days; $i++) 
                    { 
                        $signin_time = $signout_time = "0000-00-00 00:00:00";
                        $Late_Reason = ""; 

                        $att_date = date('Y-m-d', strtotime($data['From_Date'] . ' +'.$i.' day'));
                        $attendance = $this->db->get_where("attendance",array("Deleted"=>0,"Status"=>1,"Employee_Id"=>$data['Employee_Id'],"Date"=>$att_date));
                         
                        $attendance_date = date("D - d M, Y",strtotime($att_date));

                        $class = "panel-absent";
                        $time_sepnt = "00:00:00";

                        $Attendance_Id = 0;

                        if($attendance->num_rows() > 0)
                        {
                            foreach ($attendance->result() as $index => $attendance_data) 
                            { 
                                $signin_time = $attendance_data->Signin;
                                $signout_time = $attendance_data->Signout;
                                $Late_Reason = $attendance_data->Late_Reason;
                                $Attendance_Id = $attendance_data->Id;

                                if($signout_time == "0000-00-00 00:00:00"){ $signout_time == date("Y-m-d H:i:s"); }
                                $emp_start_time = date("Y-m-d H:i:s", strtotime($signin_time));
                                $emp_end_time = date("Y-m-d H:i:s", strtotime($signout_time));

                                $time_difference = date_difference($emp_start_time,$emp_end_time);
                                if( $time_difference['Hour'] > 0){ $time_sepnt =  $time_difference['Hour']." Hour "; }
                                if( $time_difference['Minuts'] > 0 && $time_sepnt != "00:00:00"){ $time_sepnt .=  $time_difference['Minuts']." Minutes "; }else{ $time_sepnt =  $time_difference['Minuts']." Minutes ";}
                                //$time_sepnt = $time_difference['Hour'].":".$time_difference['Minuts'].":".$time_difference['Seconds'];


                                if(strtotime($value->Shift_Start_Time) >= strtotime($signin_time))
                                {
                                    $class = "panel-present";
                                    $attendance_status = "present"; 
                                }
                                else
                                {
                                    $class = "panel-late";
                                    $attendance_status = "late"; 
                                }
                            }
                        }
                        else
                        {
                            $class = "panel-absent";
                            $attendance_status = "absent";
                        } 

                        if(!$Late_Reason || $Late_Reason == "")
                        {
                            $Late_Reason = "Not Saved!";
                        }

                        $src =  "assets/images/default-user.png";
                        if($value->Photo && $value->Photo != "")
                        {
                            $src =  "assets/panel/userassets/employees/".$value->Id."/".$value->Photo;
                            if(!file_exists($src))
                            {
                               $src =  "assets/images/default-user.png";
                            } 
                        }

                        $attendance_records .= '<div class="col-md-3 col-lg-2 col-sm-4 col-xs-12 attendance_rec" id="row_'.$value->Id.'" name="'.$value->First_Name." ".$value->Last_Name.'" location="'.$value->Location_Name.'" shift="'.$value->Shift_Name.'" department="'.$department_name.'" attendance="'.$attendance_status.'">
                                                    <div class="panel panel-profile grid-view '.$class.'">
                                                      <div class="panel-heading">
                                                        <div class="text-center">
                                                            <div style="width: 80px; margin:0 auto;">
                                                              <a href="#" onclick="open_modal_window(this,\'employee_view\','.$value->Id.')" class="panel-profile-photo">
                                                                <img class="img-circle" src="'.$src.'" style="width: 100%;" alt="">
                                                              </a>
                                                            </div>
                                                          <a href="javascript:;"  onclick="open_modal_window(this,\'employee_view\','.$value->Id.')">
                                                            <h4 class="panel-profile-name" >'.$value->First_Name." ".$value->Last_Name.'</h4>
                                                          </a>
                                                          <p class="media-usermeta" style="color: #fff;"> '.$department_name.' | <strong>'.$attendance_date.'</strong></p>
                                                        </div>
                                                        <ul class="panel-options">
                                                          <li>
                                                            <div class="btn-group">
                                                              <button type="button" class="dropdown-toggle action-dropdown" style="font-size: 8px;" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                                                              <ul class="dropdown-menu" role="menu">
                                                                 
                                                                <li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,\'employee_view\','.$value->Id.')"> <i class="fa fa-eye"></i> Employee Profile </a></li>
                                                              
                                                                <li><a href="javascript:;" style="color: blue;" onclick="load_tab(this,\'update_attendance\','.$value->Id.',\'attendance_tabs_body\','.$Attendance_Id.')"> <i class="fa fa-pencil"></i> Edit </a></li>
                                                                 
                                                              </ul>
                                                            </div>
                                                          </li>
                                                        </ul>
                                                      </div><!-- panel-heading -->
                                                      <div class="panel-body people-info">

                                                        <div class="info-group">
                                                          <table class="table table-bordered table-inverse nomargin att-table">
                                                            <tr>
                                                                <th>Signin Time:</th>
                                                                <td>'.$signin_time.'</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Signout Time:</th>
                                                                <td id="employee_signout_'.$Attendance_Id.'">'.$signout_time.'</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Time Spent:</th>
                                                                <td>
                                                                   '.$time_sepnt.' 
                                                                </td>
                                                            </tr>
                                                          </table>
                                                        </div>
                                                        <div class="info-group">
                                                            <table class="table table-bordered table-inverse nomargin att-table">
                                                                <tr>
                                                                    <th>Shift: </th>
                                                                    <td>'.$value->Shift_Name.'</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Location:</th>
                                                                    <td>'.$value->Location_Name.'</td>
                                                                </tr> 
                                                            </table> 
                                                        </div>
                                                        <div class="info-group late-reason">

                                                          <label>Late Reason</label>
                                                          <p class="late_reason_p" >'.$Late_Reason.'</p>
                                                        </div> 
                                                      </div> 
                                                    </div> 
                                                </div>';

                    }
                }
            }
        }
        

        if($attendance_records == ""){ $attendace_records = no_record_found(); }

        echo $attendance_records;
    }


    public function reject_leave_application($value='')
    {
       $data = $this->input->post(); 
       $this->db->update("leave_applications",array("Application_Status"=>"Rejected"),array("Id"=>$data['id']));
       echo true;
    }

    public function approve_leave_application()
    {
        $data = $this->input->post();
        if(isset($data['emp_id']))
        { 
            $year = date("Y");
            $emp_application = $this->db->get_where("leave_applications",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Id"=>$data['id']));
            $emp_leaves = $this->db->get_where("employee_leaves",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Id"=>$data['emp_id'],"Year"=>$year));
            

            $required_leaves = 0;

            if($emp_application->num_rows() > 0)
            {
                $emp_application_data = $emp_application->result_array();
                $emp_application_data = $emp_application_data[0]; 

                if($emp_application_data['To_Date'] == "0000-00-00")
                {
                    $required_leaves = 1;
                }
                else
                {
                    $date1=date_create($emp_application_data['From_Date']);
                    $date2=date_create($emp_application_data['To_Date']);
                    $diff=date_diff($date1,$date2);
                    $required_leaves = $diff->format("%a") + 1;
                }
            }

            $available_paid_leaves = 0;
            $available_unpaid_leaves = 0;
            $consumed_paid_leaves = 0;
            $consumed_unpaid_leaves = 0;

            if($emp_leaves->num_rows() > 0)
            {
                $emp_leave_data = $emp_leaves->result_array();
                $emp_leave_data = $emp_leave_data[0]; 
                $available_paid_leaves = $emp_leave_data['Paid_Leaves'] - $emp_leave_data['Consumed_Paid_Leaves'];
                $available_unpaid_leaves = $emp_leave_data['Unpaid_Leaves'] - $emp_leave_data['Consumed_Unpaid_Leaves'];
                $consumed_paid_leaves = $emp_leave_data['Consumed_Paid_Leaves'];
                $consumed_unpaid_leaves = $emp_leave_data['Consumed_Unpaid_Leaves'];
            }

            if($available_paid_leaves >= $required_leaves)
            {
                $consumed_paid_leaves = $consumed_paid_leaves + $required_leaves;
                $this->db->update("employee_leaves",array("Consumed_Paid_Leaves"=>$consumed_paid_leaves),array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Id"=>$data['emp_id'],"Year"=>$year));
            }
            else
            {
                $paid_leaves = $available_paid_leaves;
                $unpaid_leaves = $required_leaves - $available_paid_leaves; 
                $consumed_paid_leaves = $consumed_paid_leaves + $paid_leaves;
                $consumed_unpaid_leaves = $consumed_unpaid_leaves + $unpaid_leaves; 
                $this->db->update("employee_leaves",array("Consumed_Paid_Leaves"=>$consumed_paid_leaves,"Consumed_Unpaid_Leaves"=>$consumed_unpaid_leaves),array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Id"=>$data['emp_id'],"Year"=>$year)); 
            }

           //$this->db->last_query();
        }

        $this->db->update("leave_applications",array("Application_Status"=>"Approved","Status_Changed_By"=>$this->user_id,"Modified_By"=>$this->user_id,"Date_Modification"=>date("Y-m-d H:i:s")),array("Id"=>$data['id']));
        
        echo true;

    }

    public function change_leave_application_status()
    {
        $data = $this->input->post();
        if(isset($data['emp_id']))
        { 

            $year = date("Y"); 
            $emp_application = $this->db->get_where("leave_applications",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Id"=>$data['id']));
            $emp_leaves = $this->db->get_where("employee_leaves",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Id"=>$data['emp_id'],"Year"=>$year));
             
            $required_leaves = 0; 
            $previous_status = $new_status = "";
            if($emp_application->num_rows() > 0)
            {
                $emp_application_data = $emp_application->result_array();
                $emp_application_data = $emp_application_data[0];  
                $previous_status = $emp_application_data['Application_Status'];
                if($emp_application_data['To_Date'] == "0000-00-00")
                {
                    $required_leaves = 1;
                }
                else
                {
                    $date1=date_create($emp_application_data['From_Date']);
                    $date2=date_create($emp_application_data['To_Date']);
                    $diff=date_diff($date1,$date2);
                    $required_leaves = $diff->format("%a") + 1;
                }
            }

            $available_paid_leaves = 0;
            $available_unpaid_leaves = 0;
            $consumed_paid_leaves = 0;
            $consumed_unpaid_leaves = 0;

            if($emp_leaves->num_rows() > 0)
            {
                $emp_leave_data = $emp_leaves->result_array();
                $emp_leave_data = $emp_leave_data[0]; 
                $available_paid_leaves = $emp_leave_data['Paid_Leaves'] - $emp_leave_data['Consumed_Paid_Leaves'];
                $available_unpaid_leaves = $emp_leave_data['Unpaid_Leaves'] - $emp_leave_data['Consumed_Unpaid_Leaves'];
                $consumed_paid_leaves = $emp_leave_data['Consumed_Paid_Leaves'];
                $consumed_unpaid_leaves = $emp_leave_data['Consumed_Unpaid_Leaves'];
            }

            if($data['status'] == "Approved")
            { 
                if($available_paid_leaves >= $required_leaves)
                {
                    $consumed_paid_leaves = $consumed_paid_leaves + $required_leaves;
                    $this->db->update("employee_leaves",array("Consumed_Paid_Leaves"=>$consumed_paid_leaves),array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Id"=>$data['emp_id'],"Year"=>$year));
                }
                else
                {
                    $paid_leaves = $available_paid_leaves;
                    $unpaid_leaves = $required_leaves - $available_paid_leaves; 
                    $consumed_paid_leaves = $consumed_paid_leaves + $paid_leaves;
                    $consumed_unpaid_leaves = $consumed_unpaid_leaves + $unpaid_leaves; 
                    $this->db->update("employee_leaves",array("Consumed_Paid_Leaves"=>$consumed_paid_leaves,"Consumed_Unpaid_Leaves"=>$consumed_unpaid_leaves),array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Id"=>$data['emp_id'],"Year"=>$year)); 
                }
            }
            elseif($previous_status == "Approved" && ( $data['status'] == "Rejected" || $data['status'] == "Pending" ))
            {
                if($consumed_unpaid_leaves >= $required_leaves)
                {
                    $consumed_unpaid_leaves = $consumed_unpaid_leaves - $required_leaves;
                    $this->db->update("employee_leaves",array("Consumed_Unpaid_Leaves"=>$consumed_unpaid_leaves),array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Id"=>$data['emp_id'],"Year"=>$year));
                }
                else
                {

                    $deducted_paid_leaves = $required_leaves - $consumed_unpaid_leaves;
                    $deducted_unpaid_leaves = $consumed_unpaid_leaves;
                    // $available_paid_leaves = $available_paid_leaves + $deducted_paid_leaves;
                    // $available_unpaid_leaves = $available_unpaid_leaves + $consumed_unpaid_leaves;

                    //$unpaid_leaves = $required_leaves - $available_paid_leaves; 
                    $consumed_paid_leaves = $consumed_paid_leaves - $deducted_paid_leaves;
                    $consumed_unpaid_leaves = 0; 
                    $this->db->update("employee_leaves",array("Consumed_Paid_Leaves"=>$consumed_paid_leaves,"Consumed_Unpaid_Leaves"=>$consumed_unpaid_leaves),array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Employee_Id"=>$data['emp_id'],"Year"=>$year)); 
                }
                 
            } 
        }
 
        $this->db->update("leave_applications",array("Application_Status"=>$data['status'],"Status_Changed_By"=>$this->user_id,"Modified_By"=>$this->user_id,"Date_Modification"=>date("Y-m-d H:i:s")),array("Id"=>$data['id']));
        
        echo true;

    }

    public function get_employee_leaves($value='')
    {
        $data = $this->input->post();

        $message = array();
        $message['Success'] = true;
        $message['Paid_Leaves'] = 0;
        $message['Unpaid_Leaves'] = 0;
        $message['Consumed_Paid_Leaves'] = 0;
        $message['Consumed_Unpaid_Leaves'] = 0;

        if(isset($data['emp_id']))
        {
            $year = date("Y");
            
            $employee_leaves = $this->db->get_where("employee_leaves",array("Deleted"=>0,"Status"=>1,"Employee_Id"=>$data['emp_id'],"Year"=>$year,"Org_Id"=>$this->org_id));
          
            if($employee_leaves->num_rows() > 0)
            {
                $employee_leave_data = $employee_leaves->result_array();
                $message['Success'] = true;
                $message['Edit_Recorde'] = $employee_leave_data[0]['Id'];
                $message['Paid_Leaves'] = $employee_leave_data[0]['Paid_Leaves'];
                $message['Unpaid_Leaves'] = $employee_leave_data[0]['Unpaid_Leaves'];
                $message['Consumed_Paid_Leaves'] = $employee_leave_data[0]['Consumed_Paid_Leaves'];
                $message['Consumed_Unpaid_Leaves'] = $employee_leave_data[0]['Consumed_Unpaid_Leaves'];
            }
            else
            {
                $employee_leave_data['Date_Added'] = date("Y-m-d H:i:s");
                $employee_leave_data['Added_By'] = $this->user_id;
                $employee_leave_data['Org_Id'] = $this->org_id; 
                $employee_leave_data['Employee_Id'] = $data['emp_id']; 
                $employee_leave_data['Year'] = $year;
                $employee_leave_data['Paid_Leaves'] = 0;
                $employee_leave_data['Unpaid_Leaves'] = 0;
                $employee_leave_data['Consumed_Paid_Leaves'] = 0;
                $employee_leave_data['Consumed_Unpaid_Leaves'] = 0;

                $this->db->insert("employee_leaves",$employee_leave_data);
                $message['Edit_Recorde'] = $this->db->insert_id();
            }
        }

        echo json_encode($message);
    }

    public function get_leaves_history()
    {


        $module_id = 0000;
        $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Leaves History","Deleted"=>0,"Status"=>1));
        if($module_data->num_rows() > 0)
        {
            $module_info = $module_data->result_array();
            $module_id = $module_info[0]['Id'];
        }


        $html = "";
        $data = $this->input->post(); 

        if(isset($data['Employee_Id']) && isset($data['Year']))
        { 
            $this->db->select("employees.Id,employees.First_Name,employees.Last_Name,employees.Id,employees.Photo,shifts.Name as Shift_Name,shifts.Start_Time as Shift_Start_Time,departments.Name as Department_Name,locations.Name as Location_Name");
            $employees = $this->db->where( array("employees.Id"=>$data['Employee_Id'],"employees.Deleted"=>0,"employees.Status"=>1,"employees.Org_Id"=>$this->org_id,"employees.Employee_Status"=>"Active"  ));
            $this->db->from("employees"); 
            $this->db->join("employee_work_record","employee_work_record.Employee_Id = employees.Id","left");
            $this->db->join("departments","departments.Id = employee_work_record.Department_Id","left");
            $this->db->join("shift_employees","shift_employees.Employee_Id = employees.Id","left");
            $this->db->join("shifts","shifts.Id = shift_employees.Shift_Id","left");
            $this->db->join("locations","locations.Id = employees.Location_Id","left");  
            $this->db->group_by("employees.Id");
            $employees = $this->db->get();
            //echo $this->db->last_query();

            if($employees->num_rows() > 0)
            {
                foreach ($employees->result() as $key => $value) 
                {
                    $class = "approved";
                    $department_name = "Not Saved!";
                    if($value->Department_Name != ""){ $department_name = $value->Department_Name; }
                    
                    $appli_date = $data['Year']."-".$data['Month'];
                    
                    $this->db->where(array("leave_applications.Deleted"=>0,"leave_applications.Status"=>1,"leave_applications.Employee_Id"=>$value->Id,"leave_applications.Org_Id"=>$this->org_id));
                    $this->db->like("leave_applications.From_Date",$appli_date,"after");
                    $this->db->select("leave_applications.*,employees.First_Name as Checked_By_First_Name, employees.Last_Name as Checked_By_Last_Name");
                    $this->db->from("leave_applications");
                    $this->db->join("employees","employees.Id = leave_applications.Status_Changed_By","left");

                    $leave_applications = $this->db->get(); 

                    $leave_application_data = array();

                    if($leave_applications->num_rows() > 0)
                    {
                        foreach ($leave_applications->result() as $index => $application_data) 
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
                            
                            if($application_data->Application_Status == "Approved"){ $class = "approved"; }
                            elseif($application_data->Application_Status == "Rejected"){ $class = "rejected";}
                            elseif($application_data->Application_Status == "Pending"){ $class = "pending";}


                            $date1=date_create($application_data->From_Date);
                            $date2=date_create($application_data->To_Date);
                            $diff=date_diff($date1,$date2);
                            $leaves = $diff->format("%a") + 1;

                            if(in_array($module_id."_view", $this->role_permissions)){ $view = '<li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,\'employee_view\','.$value->Id.')"> <i class="fa fa-eye"></i> Employee Profile </a></li>'; }else{ $view = ""; }
                            if(in_array($module_id."_edit", $this->role_permissions)){ $edit = '<li><a href="javascript:;" style="color: blue;" onclick="load_tab(this,\'add_leave_application\','.$value->Id.',\'leave_tabs_body\','.$application_data->Id.')"> <i class="fa fa-pencil"></i> Edit </a></li>'; }else{ $edit = ""; }

                            $html .= '<div class="col-md-3 col-lg-2 col-sm-4 col-xs-12 attendance_rec" id="row_'.$value->Id.'" >
                                        <div class="panel panel-profile grid-view '.$class.'">
                                          <div class="panel-heading">
                                            <div class="text-center">
                                                <div style="width: 80px; margin:0 auto;">
                                                  <a href="#" onclick="open_modal_window(this,\'employee_view\','.$value->Id.')" class="panel-profile-photo">
                                                    <img class="img-circle" src="'.$src.'" style="width: 100%;" alt="">
                                                  </a>
                                                </div>
                                              <a href="javascript:;"  onclick="open_modal_window(this,\'employee_view\','.$value->Id.')">
                                                <h4 class="panel-profile-name" >'.$value->First_Name." ".$value->Last_Name.'</h4>
                                              </a>
                                              <p class="media-usermeta" style="color: #fff;"> '.$department_name.' </p> 
                                            </div>
                                            <ul class="panel-options">
                                              <li>
                                                <div class="btn-group">
                                                  <button type="button" class="dropdown-toggle action-dropdown" style="font-size: 8px;" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                                                  <ul class="dropdown-menu" role="menu">
                                                    '.$view.''.$edit.'  
                                                  </ul>
                                                </div>
                                              </li>
                                            </ul>
                                          </div><!-- panel-heading -->
                                          <div class="panel-body people-info">

                                            <div class="info-group">
                                              <table class="table table-bordered table-inverse nomargin att-table">
                                                <tr>
                                                    <th>From Date:</th>
                                                    <td>'.date("D M d, Y",strtotime($application_data->From_Date)).'</td>
                                                </tr>
                                                <tr>
                                                    <th>To Date:</th>
                                                    <td>'.date("D M d, Y",strtotime($application_data->To_Date)).'</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Leaves</th>
                                                    <td> '.$leaves.' </td>
                                                </tr>
                                              </table>
                                            </div> 
                                            <div class="info-group">
                                                <table class="table table-bordered table-inverse nomargin att-table">
                                                    <tr>
                                                        <th>Approved By:</th>
                                                        <td>'.$application_data->Checked_By_First_Name." ".$application_data->Checked_By_Last_Name.'</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Modified on:</th>
                                                        <td>'.date("D M d, Y",strtotime($application_data->Date_Modification)).'</td>
                                                    </tr>  
                                                </table> 
                                            </div>
                                            <div class="info-group late-reason"> 
                                              <label>Leave Reason</label>
                                              <p class="late_reason_p" >'.strip_tags( substr($application_data->Reason,0,100) ).'</p>
                                            </div> 
                                          </div> 
                                        </div> 
                                    </div>';
                        }
                    }
                }
            }
        }

        if($html == ""){ $html = no_record_found(); }
        echo $html;
    }

    public function get_employee_activities()
    {


        $module_id = 0000;
        $module_data = $this->db->get_where("application_modules",array("M_Name"=>"Manage Activities","Deleted"=>0,"Status"=>1));
        if($module_data->num_rows() > 0)
        {
            $module_info = $module_data->result_array();
            $module_id = $module_info[0]['Id'];
        }


        $html = "";
        $data = $this->input->post(); 

        if(isset($data['Employee_Id']) && isset($data['From_Date']))
        { 
            $employee_activities = $this->db->select(" employee_activities.*,employees.First_Name,employees.Last_Name ,employees.Photo,activity_types.Name as  Activity_Type_Name,departments.Name as Department_Name  ");
            $this->db->where( array("employee_activities.Deleted"=>0,"employee_activities.Status"=>1,"employee_activities.Org_Id"=>$this->org_id, "employee_activities.Activity_Date >="=>$data['From_Date']));
            if(isset($data['To_Date'])){ $this->db->where(  array("employee_activities.Activity_Date <=" => $data['To_Date'] )); }
            if($data['Employee_Id'] > 0){ $this->db->where(  array("employee_activities.Employee_Id" => $data['Employee_Id'] )); }
            $this->db->from("employee_activities");  
            $this->db->join("employee_work_record","employee_work_record.Employee_Id = employee_activities.Employee_Id","left");
            $this->db->join("departments","departments.Id = employee_work_record.Department_Id","left");
            $this->db->join("activity_types","activity_types.Id = employee_activities.Activity_Type","left");
            $this->db->join("employees","employees.Id = employee_activities.Employee_Id","left"); 
            $this->db->group_by("employee_activities.Id");
            $employee_activities = $this->db->get();
            //echo $this->db->last_query();

            if($employee_activities->num_rows() > 0)
            {
                foreach ($employee_activities->result() as $key => $value) 
                {
                    $src =  "assets/images/default-user.png";
                    if($value->Photo && $value->Photo != "")
                    {
                        $src =  "assets/panel/userassets/employees/".$value->Employee_Id."/".$value->Photo;
                        if(!file_exists($src))
                        {
                           $src  = "assets/images/default-user.png";
                        } 
                    }

                    $time_spent = "";
                    $start_time = date("Y-m-d H:i:s", strtotime($value->Start_Time));
                    $end_time = date("Y-m-d H:i:s", strtotime($value->End_Time));
                    $difference = date_difference($start_time,$end_time);
                    if( $difference['Hour'] > 0){ $time_spent =  $difference['Hour']." Hour "; }
                    if( $difference['Minuts'] > 0 && $time_spent != ""){ $time_spent .=  $difference['Minuts']." Minutes "; }
                    else{ $time_spent =  $difference['Minuts']." Minutes ";}  

                    if(in_array($module_id."_view", $this->role_permissions)){ $view = '<li><a href="javascript:;" style="color: green;" onclick="open_modal_window(this,\'employee_view\','.$value->Employee_Id.')"> <i class="fa fa-eye"></i> Employee Profile </a></li>'; }else{ $view = ""; }
                    if(in_array($module_id."_edit", $this->role_permissions)){ $edit = '<li><a href="javascript:;" style="color: blue;" onclick="load_view(this,\'form_employee_activity\','.$value->Id.')"> <i class="fa fa-pencil"></i> Edit </a></li>'; }else{ $edit = ""; }
                    if(in_array($module_id."_delete", $this->role_permissions)){ $delete = '<li><a href="javascript:;" style="color: red;" onclick="delete_record(\'employee_activities\','.$value->Id.',this)"> <i class="fa fa-trash"></i> Delete </a></li>'; }else{ $delete = ""; }

                            $html .= '<div class="col-md-3 col-lg-2 col-sm-4 col-xs-12 activity_rec" id="row_'.$value->Id.'"   >
                                        <div class="panel panel-profile grid-view " style="background-color:#75c9f1;">
                                          <div class="panel-heading">
                                            <div class="text-center">
                                                <div style="width: 80px; margin:0 auto;">
                                                  <a href="#" onclick="open_modal_window(this,\'employee_view\','.$value->Employee_Id.')" class="panel-profile-photo">
                                                    <img class="img-circle" src="'.$src.'" style="width: 100%;" alt="">
                                                  </a>
                                                </div>
                                                <a href="javascript:;"  onclick="open_modal_window(this,\'employee_view\','.$value->Employee_Id.')">
                                                <h4 class="panel-profile-name" >'.$value->First_Name." ".$value->Last_Name.'</h4>
                                              </a>
                                              <p class="media-usermeta" style="color: #fff;"> '.$value->Department_Name.' </p>
                                            </div>
                                            <ul class="panel-options">
                                              <li>
                                                <div class="btn-group">
                                                  <button type="button" class="dropdown-toggle action-dropdown" style="font-size: 8px;" data-toggle="dropdown"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i></button>
                                                  <ul class="dropdown-menu" role="menu">
                                                    '.$view.''.$edit.''.$delete.'
                                                  </ul>
                                                </div>
                                              </li>
                                            </ul>
                                          </div> 
                                          <div class="panel-body people-info" style="min-height: 150px;"> 
                                                <div class="info-group">
                                                    <table class="table table-bordered table-inverse nomargin att-table">
                                                        <tr>
                                                            <th class="" style="color: #a01a90 !important;">'.$value->Activity_Type_Name.'</th> 
                                                        </tr> 
                                                        <tr>
                                                            <td style="text-align: left;">'.$value->Activity.'</td>
                                                        </tr>
                                                    </table> 
                                                </div>
                                                <div class="info-group">
                                                    <table class="table table-bordered table-inverse nomargin att-table">
                                                        <tr>
                                                            <th >Date: '.date("l M d, Y",strtotime($value->Activity_Date)).'</th> 
                                                        </tr>
                                                        <tr>
                                                            <th ><span style="color: #a01a90;">Time Spent:</span> 
                                                                <span style="color: #1206ca;">'.$time_spent.'</span>
                                                            </th> 
                                                        </tr> 
                                                    </table>
                                                </div> 
                                          </div> 
                                        </div> 
                                    </div>';
                         
                }
            }
        }

        if($html == ""){ $html = no_record_found(); }
        echo $html;
    }

    public function rotate_shifts()
    {
       $message = array();
       $data = $this->input->post();
       if(isset($data['Shift_A']) && isset($data['Shift_B']))
       {  
            $shift_rotation_data['Org_Id'] = $this->org_id;
            $shift_rotation_data['Shift_A'] = $data['Shift_A'];
            $shift_rotation_data['Shift_B'] = $data['Shift_B'];
            $shift_rotation_data['Date_Added'] = $this->date;
            $shift_rotation_data['Date_Modification'] = $this->date;
            $shift_rotation_data['Added_By'] = $this->user_id;

            $this->db->insert("shift_rotations",$shift_rotation_data);

            $alert  = '<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Successful!</h4> 
                            Shifts are rotated successfully...
                         </div>';
            $message['Success'] = true;
            $message['Message'] = $alert;
       }
       else
       {
            $alert  = '<div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-times"></i> Error!</h4>
                              Sorry! some form fields are missings. 
                         </div>';
            $message['Success'] = false;
            $message['Message'] = $alert;
            
       }

       echo json_encode($message);
    }

    public function change_annual_leave_quota_setting($value='')
    {
        $data = $this->input->post();
        if(isset($data['add_leave_status']))
        {
            $check_rec = $this->db->get_where("holiday_period",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
            if($check_rec->num_rows() > 0)
            {
                $this->db->update("holiday_period",array("Add_Remaining_Leaves_Into_Next_Year"=>$data['add_leave_status']),array("Org_Id"=>$this->org_id));
            }
            else
            {
                $this->db->insert("holiday_period",array("Add_Remaining_Leaves_Into_Next_Year"=>$data['add_leave_status'],"Added_By"=>$this->org_id,"Modified_By"=>$this->org_id,"Date_Added"=>$this->date,"Date_Modification"=>$this->date));
            }
        }

        echo true;
    }


    public function get_organogram_data()
    {
        $organogram_data = array();
        $data = $this->input->post();
        if($data['id'] > 0)
        {
            $organogram_rec = $this->db->get_where("organograms",array("Deleted"=>0,"Status"=>1,"Id"=>$data['id'],"Org_Id"=>$this->org_id));
            if($organogram_rec->num_rows() > 0)
            {
                $organogram_info = $organogram_rec->result_array();
                $organogram_data = $organogram_info[0]['Data'];
            }
        }

        echo $organogram_data = json_encode(json_decode($organogram_data));
        //echo json_decode($organogram_data);
        //print_r($organogram_data);  
    }

    public function save_announcement($value='')
    {
        $id = 0;
        $shifts = 0;
        $shifts_data = array();
        $message = array();
        $data = $this->input->post();
        if(isset($data['Edit_Recorde'])){ $id = $data['Edit_Recorde']; }
 
        if($id == 0)
        { 
            if(isset($data['Shifts'])){ $shifts = sizeof($data['Shifts']); }
            $announcement_data['Org_Id'] = $this->org_id;
            $announcement_data['Title'] = $data['Title'];
            $announcement_data['Description'] = $data['Description'];
            $announcement_data['Date_Added'] = $this->date;
            $announcement_data['Date_Modification'] = $this->date;
            $announcement_data['Added_By'] = $this->user_id;

            $this->db->insert("announcements",$announcement_data);
            $id = $this->db->insert_id(); 

            $alert  = '<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                              Success! Announcement is saved successfully... 
                         </div>';
            $message['Success'] = true;
            $message['Message'] = $alert;
        }
        else
        {
            if(isset($data['Shifts'])){ $shifts = sizeof($data['Shifts']); }
            $announcement_data['Org_Id'] = $this->org_id;
            $announcement_data['Title'] = $data['Title'];
            $announcement_data['Description'] = $data['Description'];
            $announcement_data['Date_Added'] = $this->date;
            $announcement_data['Date_Modification'] = $this->date;
            $announcement_data['Added_By'] = $this->user_id;

            $this->db->update("announcements",$announcement_data,array("Id"=>$id)); 
            $this->db->delete("announcement_shifts",array("Announcement_Id"=>$id)); 

            $alert  = '<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                              Success! Announcement is updated successfully... 
                         </div>';
            $message['Success'] = true;
            $message['Message'] = $alert;
        }

        if($shifts != 0)
        {
            for ($i=0; $i < $shifts; $i++) 
            {  
                if(isset($data['Shifts'][$i]))
                {
                    $announcement_shift_data['Org_Id'] = $this->org_id;
                    $announcement_shift_data['Announcement_Id'] = $id;
                    $announcement_shift_data['Shift_Id'] = $data['Shifts'][$i];
                    $announcement_shift_data['Date_Added'] = $this->date;
                    $announcement_shift_data['Date_Modification'] = $this->date;
                    $announcement_shift_data['Added_By'] = $this->user_id;

                    $this->db->insert("announcement_shifts",$announcement_shift_data);
                }
            }
        }


 
        echo json_encode($message);
    }

    public function save_report_points($value='')
    {
        $message = array();
        $alert  = '<div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Error!</h4>
                              Error! Some error occured. 
                         </div>';
        $message['Success'] = false;
        $message['Message'] = $alert;

        $data = $this->input->post();
        if(isset($data['Edit_Recorde']))
        {
            $this->db->update("employee_daily_reports",array("Points"=>$data['Points'],"Report_Status"=>"Reviewed","Modified_By"=>$this->user_id),array("Id"=>$data['Edit_Recorde']));

            $alert  = '<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                              Success! Report is viewed successfully... 
                         </div>';
            $message['Success'] = true;
            $message['Message'] = $alert;
        }

        echo json_encode($message);

    }

    public function get_daily_reports($value='')
    {
        $html = "";
        $data = $this->input->post();
        $from_date = "";
        $to_date = "";
        if(isset($data['From_Date']))
        {
            $from_date = date("l d F, Y",strtotime($data['From_Date']));
        }

        if(isset($data['To_Date']))
        {
            $to_date = date("l d F, Y",strtotime($data['To_Date']));
        }
        else
        {
           $to_date = $from_date;
        }

        

        $employee_daily_reports = $this->db->select("employee_daily_reports.*,employees.First_Name,employees.Last_Name, employees.Photo, departments.Name as Department_Name,designations.Name as Designation_Name  ");
        if($from_date != ""){ $this->db->where(array("employee_daily_reports.Report_Date >="=>$data['From_Date'])); }
        if($to_date != "" || $to_date != $from_date){ $this->db->where(array("employee_daily_reports.Report_Date <="=>$data['To_Date'])); }
        if($data['Employee_Id'] > 0){ $this->db->where(array("employee_daily_reports.Employee_Id"=>$data['Employee_Id'])); }
        
        $this->db->where( array("employee_daily_reports.Deleted"=>0,"employee_daily_reports.Status"=>1,"employee_daily_reports.Org_Id"=>$this->org_id ));
        $this->db->from("employee_daily_reports");  
        $this->db->join("employee_work_record","employee_work_record.Employee_Id = employee_daily_reports.Employee_Id","left");
        $this->db->join("departments","departments.Id = employee_work_record.Department_Id","left"); 
        $this->db->join("designations","designations.Id = employee_work_record.Designation_Id","left"); 
        $this->db->join("employees","employees.Id = employee_daily_reports.Employee_Id","left"); 
        $this->db->group_by("employee_daily_reports.Id");
        $this->db->order_by("employee_daily_reports.Report_Date","DESC");
        $employee_daily_reports = $this->db->get();
        //echo $this->db->last_query();

        if($employee_daily_reports->num_rows() > 0)
        {
            $report_date = "";
            foreach ($employee_daily_reports->result() as $key => $value) 
            { 
                $src =  "assets/images/default-user.png";
                if($value->Photo && $value->Photo != "")
                {
                    $src =  "assets/panel/userassets/employees/".$value->Employee_Id."/".$value->Photo;
                    if(!file_exists($src))
                    {
                       $src  = "assets/images/default-user.png";
                    } 
                }

                $html_head  = "";
                if($report_date != date("Y-m-d",strtotime($value->Report_Date)))
                {  
                    $report_date = date("Y-m-d",strtotime($value->Report_Date));
                    $html_head  =  '<div class="well warning mb10">
                                      <div class="row"> 
                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <h3>'.date("l d F, Y",strtotime($report_date)).'</h3> 
                                          </div>
                                      </div>
                                    </div>';  
                }
                
                $report_date = date("Y-m-d",strtotime($value->Report_Date));

                if($value->Report_Status == "New")
                {
                    $give_points = '<form id="common_form" method="post" action="'.base_url("admin/save_report_points").'" onsubmit="return save_report_points(this);" class="form-horizontal" >
                                        <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="'.$value->Id.'">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                          <hr>
                                          <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12" style="padding-left: 0px;">
                                            <select class="form-control select2 required" name="Points" id="Points" >
                                              <option value="0">Report Satisfaction Level</option>
                                              <option value="0">0 ( Not Satisfied )</option>
                                              <option value="1">1</option>
                                              <option value="2">2</option>
                                              <option value="3">3</option>
                                              <option value="4">4</option>
                                              <option value="5">5</option>
                                              <option value="6">6</option>
                                              <option value="7">7</option>
                                              <option value="8">8</option>
                                              <option value="9">9</option>
                                              <option value="10">10 ( Fully Satisfied )</option>
                                            </select>
                                          </div>
                                          <div class="col-md-10 col-lg-10 col-sm-10 col-xs-12" >
                                            <button type="submit" class="btn btn-warning pull-left"><i class="fa fa-check"></i> Save</button>
                                          </div>
                                        </div>
                                      </form>';
                }
                else
                {
                    $give_points = "";
                }

                $html .= $html_head.'<div class="well primary mb10" id="row_'.$value->Id.'">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                    <div style="width: 50px; float: left;">
                                      <a href="javascript:;"><img src="'.$src.'" class="pro-img"> </a>
                                    </div>
                                    <div style="width: 80%; float: left;">
                                      <a href="javascript:;">
                                        <h3 style="padding-left: 10px;"> 
                                          '.$value->First_Name." ".$value->Last_Name." ( ".$value->Designation_Name." )".' 
                                          <br>
                                          <span style="font-size: 13px; color: #f8be0f">'.$value->Department_Name .' Department</span>
                                        </h3>
                                      </a>
                                    </div> 
                                  </div>
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
                                    <p style="font-size: 17px;font-family: monospace;">'.$value->Report.'</p>
                                  </div>
                                  '.$give_points.'
                                </div>
                              </div> 
                            </div><script>'.'$(".select2").select2()'.'</script>';



            }

            echo $html;
        }
        else
        { 
            $html  = '<div class="well warning mb10">
                          <div class="row"> 
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h3>'.$from_date.' - '.$to_date.'</h3> 
                              </div>
                          </div>
                      </div>';
            echo $html.no_record_found(); 
        } 

    }


    public function save_shift_settings()
    {
       $message = array();
       $data = $this->input->post();
       // echo "<pre>";
       // print_r($data);
       // die();
        if(isset($data['Edit_Recorde']))
        {
            $edit_rec_id = $data['Edit_Recorde'];
        }
        else
        {
           $edit_rec_id = 0;     
        }

       if(isset($data['Shift_Id']))
       {
            if($edit_rec_id > 0)
            { 
                
                $shift_date_added = $this->db->get_where("shift_settings",array("Id"=>$edit_rec_id))->row()->Date_Added;
                $shift_added_date = date("Y-m-d",strtotime($shift_date_added));
                $today_date = date("Y-m-d");
                if($today_date == $shift_added_date){
                    //update shift
                    foreach ($data['Day'] as $key => $value)
                    { 
                        $setting_data['Day_Type']   = $data['Day_Type'][$key];
                        $setting_data['Day_Status'] = $data['Day_Status'][$key];
                        $setting_data['Start_Time'] = $data['Start_Time'][$key];
                        $setting_data['End_Time']   = $data['End_Time'][$key]; 
                        $setting_data['Date_Modification'] = $this->date; 
                        $setting_data['Modified_By']= $this->user_id; 

                        $this->db->update("shift_setting_details",$setting_data,array("Shift_Setting_Id"=>$edit_rec_id,"Day"=>$value));
                    }
                }
                else{ 

                        $shift_sitting_rec['Added_By']   = $this->user_id;
                        $shift_sitting_rec['Modified_By']= $this->user_id;
                        $shift_sitting_rec['Org_Id']     = $this->org_id;
                        $shift_sitting_rec['Shift_Id']     = $data['Shift_Id'];
                        $this->db->insert("shift_settings",$shift_sitting_rec);
                        $shift_setting_id = $this->db->insert_id();

                        foreach ($data['Day'] as $key => $value)
                        {
                            $setting_data['Day']   = $value;
                            $setting_data['Day_Type']   = $data['Day_Type'][$key];
                            $setting_data['Day_Status'] = $data['Day_Status'][$key];
                            $setting_data['Start_Time'] = $data['Start_Time'][$key];
                            $setting_data['End_Time']   = $data['End_Time'][$key];
                            $setting_data['Date_Added'] = $this->date;
                            $setting_data['Date_Modification'] = $this->date;
                            $setting_data['Added_By']   = $this->user_id;
                            $setting_data['Modified_By']= $this->user_id;
                            $setting_data['Org_Id']     = $this->org_id;
                            $setting_data['Shift_Setting_Id'] = $shift_setting_id;
                            $setting_data['Shift_Id']   = $data['Shift_Id'];

                            $this->db->insert("shift_setting_details",$setting_data);
                        }
                    } 

                $alert  =   '<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                  Success! shift settings are updated successfully... 
                             </div>';

                $message['Success'] = true;
                $message['Message'] = $alert;
            }
            else
            {     
                $shift_sitting_rec['Added_By']   = $this->user_id;
                $shift_sitting_rec['Modified_By']= $this->user_id;
                $shift_sitting_rec['Org_Id']     = $this->org_id;
                $shift_sitting_rec['Shift_Id']     = $data['Shift_Id'];
                $this->db->insert("shift_settings",$shift_sitting_rec);
                $shift_setting_id = $this->db->insert_id();

                foreach ($data['Day'] as $key => $value)
                {
                    $setting_data['Day']   = $value;
                    $setting_data['Day_Type']   = $data['Day_Type'][$key];
                    $setting_data['Day_Status'] = $data['Day_Status'][$key];
                    $setting_data['Start_Time'] = $data['Start_Time'][$key];
                    $setting_data['End_Time']   = $data['End_Time'][$key];
                    $setting_data['Date_Added'] = $this->date;
                    $setting_data['Date_Modification'] = $this->date;
                    $setting_data['Added_By']   = $this->user_id;
                    $setting_data['Modified_By']= $this->user_id;
                    $setting_data['Org_Id']     = $this->org_id;
                    $setting_data['Shift_Setting_Id'] = $shift_setting_id;
                    $setting_data['Shift_Id']   = $data['Shift_Id'];

                    $this->db->insert("shift_setting_details",$setting_data);
                }

                $alert  =   '<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                  Success! shift settings are saved successfully... 
                             </div>';

                $message['Success'] = true;
                $message['Message'] = $alert;
            }
       }

       echo json_encode($message);

    }


    public function get_holidays_for_calendar($value='')
    {
        $calendar_data = array();
        $data = $this->input->post();
        if($data['shift_id'])
        {
            $off_days = array();
            $on_days = array();
            $alternative_days = array();
            $date_added = $this->date;

            $shift_rec = $this->db->get_where("shift_settings",array("Deleted"=>0,"Status"=>1,"Shift_Id"=>$data['shift_id'],"Org_Id"=>$this->org_id));
            if($shift_rec->num_rows() > 0)
            {
                foreach ($shift_rec->result() as $key => $value) 
                {
                    if($value->Day_Status == "OFF"){ $off_days[] = $value->Day; }
                    if($value->Day_Status == "ON"){ $on_days[] = $value->Day; }
                    if($value->Day_Type == "Alternative"){ $alternative_days[] = $value->Day; }
                    $date_added = date("Y-m-d",strtotime($value->Date_Added));
                }

                $org_holidays_array = array();
                $org_holidays_names = array();

                $org_holidays = $this->db->get_where("organization_holidays",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                if($org_holidays->num_rows() > 0){  
                    foreach ($org_holidays->result() as $key => $value) {
                        $date_difference = strtotime($value->To_Date) - strtotime($value->From_Date);
                        $holiday_days = date("d",$date_difference);
                        $holiday_start_year = date("Y",strtotime($value->From_Date));
                        $holiday_start_month = date("m",strtotime($value->From_Date));
                        $holiday_start_day = date("d",strtotime($value->From_Date));
                        for($h=1; $h <= $holiday_days; $h++){ 
                            $holiday_date = date("Y-m-d",strtotime($holiday_start_year."-".$holiday_start_month."-".$holiday_start_day));
                            $org_holidays_array[] = $holiday_date;
                            $org_holidays_names[$holiday_date] = $value->Title;  
                            $holiday_start_day = $holiday_start_day + 1;
                        }
                    }
                }


                if(in_array("Friday", $off_days)){ $friday_flag = false; }elseif(in_array("Friday", $on_days)){ $friday_flag = true;}
                if(in_array("Saturday", $off_days)){ $saturday_flag = false; }elseif(in_array("Saturday", $on_days)){ $saturday_flag = true;}
                if(in_array("Sunday", $off_days)){ $sunday_flag = false; }elseif(in_array("Sunday", $on_days)){ $sunday_flag = true;}
                if(in_array("Monday", $off_days)){ $monday_flag = false; }elseif(in_array("Monday", $on_days)){ $monday_flag = true;}
                if(in_array("Tuesday", $off_days)){ $tuesday_flag = false; }elseif(in_array("Tuesday", $on_days)){ $tuesday_flag = true;}
                if(in_array("Wednesday", $off_days)){ $wednesday_flag = false; }elseif(in_array("Wednesday", $on_days)){ $wednesday_flag = true;}
                if(in_array("Thursday", $off_days)){ $thursday_flag = false; }elseif(in_array("Thursday", $on_days)){ $thursday_flag = true;}
                 
                    
                $target_date = date("Y-m-d", strtotime("+3 years", strtotime($date_added))); 
                $shift_start_date  = $calendar_date = $date_added; 

                $date1=date_create($shift_start_date);
                $date2=date_create($target_date);
                $diff=date_diff($date1,$date2);
                $calendar_days = $diff->format("%a"); 

                for ($i=1; $i <= $calendar_days ; $i++) 
                { 
                    $description = "";
                    $calendar_date = date('Y-m-d', strtotime($calendar_date . ' +1 day'));

                
                    $day_name = date("l",strtotime($calendar_date));

                    $title = 'Not Set';
                    $class = 'working_day'; 

                    if(in_array($day_name, $off_days)){ $title = 'OFF'; $class = 'leave_day'; }
                    elseif(in_array($day_name, $on_days)){ $title = 'ON'; $class = 'working_day'; }

                    if($day_name == "Friday" && in_array("Friday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true; $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Saturday" && in_array("Saturday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Sunday" && in_array("Sunday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Monday" && in_array("Monday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Tuesday" && in_array("Tuesday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Wednesday" && in_array("Wednesday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Thursday" && in_array("Thursday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    } 
                    
                    if(in_array($calendar_date, $org_holidays_array))
                    {  
                        $title = 'OFF'; $class = 'org_holiday'; 
                        $description = $org_holidays_names[$calendar_date];
                    }

                    

                        $calendar_data[] = array(
                                                    'id' => $i,
                                                    'title' => $title,
                                                    'className' => $class,
                                                    'start' => $calendar_date,
                                                    'description' => $description
                                                );
                        if($description != "")
                        {
                            $calendar_data[] = array(
                                                        'id' => $i,
                                                        'title' => $description,
                                                        'className' => $class,
                                                        'start' => $calendar_date, 
                                                    );
                        }
                         
                } 
            }  
        }

        
        //echo $this->db->last_query();
        echo json_encode($calendar_data);
    }

    public function find_day_repititions($start='',$end='')
    { 
        $current = $start;
        $count = 0;

        while($current != $end)
        {
            if(date('l', strtotime($current)) == 'Saturday'){
                $count++;
            }

            $current = date('Y-m-d', strtotime($current.' +1 day'));
        }

        return $count;
    }

    public function employee_attendance()
    {
        $attendance_status = "signout";
        $attendance = $this->session->userdata("attendance");
        if($attendance == "present")
        { 
            $attendance_id = $this->session->userdata("attendance_id");
            if($attendance_id > 0)
            {
                $this->db->update("attendance",array("Signout"=>$this->date),array("Id"=>$attendance_id));
                $this->session->set_userdata(array("attendance"=>"abscent","attendance_id"=>0));
            }

            $attendance_status = "signout";
        }
        else
        { 
            $this->db->update("attendance",array("Signout"=>$this->date),array("Org_Id"=>$this->org_id,"Employee_Id"=>$this->user_id,"Signout"=>"0000-00-00 00:00:00","Signin !="=>"0000-00-00 00:00:00"));

            $attendance_data = array(
                                        "Employee_Id" => $this->user_id,
                                        "Signin" => $this->date,
                                        "Date" => $this->just_date,
                                        "Org_Id" => $this->org_id,
                                    );

            $this->db->insert("attendance",$attendance_data);
            $attendance_id = $this->db->insert_id();
            $this->session->set_userdata(array("attendance"=>"present","attendance_id"=>$attendance_id));
            $attendance_status = "signin";
        }

        echo $attendance_status;
    }

    public function get_employee_logedintime()
    {
        $attendance = $this->session->userdata("attendance");
        if($attendance == "present")
        { 
            $attendance_id = $this->session->userdata("attendance_id");
            $emp_attendance_rec = $this->db->get_where("attendance",array("Id"=>$attendance_id));
            if($emp_attendance_rec->num_rows() > 0)
            {
                $emp_attendance_data = $emp_attendance_rec->result_array();
                echo $logedin_time = date("Y-m-d H:i:s", strtotime($emp_attendance_data[0]['Signin'])); 
            }
            else
            {
                echo "00:00:00";
            }
        }
        else
        {
            echo "00:00:00";    
        }
    }


    public function get_employee_attendance()
    {
       $calendar_data = array();
       $working_days = array();
       $data = $this->input->post();
       $employee_data = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Id"=>$this->user_id,"Employee_Status"=>"Active","Org_Id"=>$this->org_id));
       if($employee_data->num_rows() > 0)
       {
         $emp_rec = $employee_data->result_array();
         $emp_data = $emp_rec[0];
         $joining_date = $emp_data['Joining_Date']; 
       }
 
        if($data['shift_id'])
        {
            $off_days = array();
            $on_days = array();
            $alternative_days = array();
            $date_added = $this->date;

            $shift_rec = $this->db->get_where("shift_settings",array("Deleted"=>0,"Status"=>1,"Shift_Id"=>$data['shift_id'],"Org_Id"=>$this->org_id));
            if($shift_rec->num_rows() > 0)
            {
                foreach ($shift_rec->result() as $key => $value) 
                {
                    if($value->Day_Status == "OFF"){ $off_days[] = $value->Day; }
                    if($value->Day_Status == "ON"){ $on_days[] = $value->Day; }
                    if($value->Day_Type == "Alternative"){ $alternative_days[] = $value->Day; }
                    $date_added = date("Y-m-d",strtotime($value->Date_Added));
                }

                $org_holidays_array = array();
                $org_holidays_names = array();

                $org_holidays = $this->db->get_where("organization_holidays",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
                if($org_holidays->num_rows() > 0){  
                    foreach ($org_holidays->result() as $key => $value) {
                        $date_difference = strtotime($value->To_Date) - strtotime($value->From_Date);
                        $holiday_days = date("d",$date_difference);
                        $holiday_start_year = date("Y",strtotime($value->From_Date));
                        $holiday_start_month = date("m",strtotime($value->From_Date));
                        $holiday_start_day = date("d",strtotime($value->From_Date));
                        for($h=1; $h <= $holiday_days; $h++){ 
                            $holiday_date = date("Y-m-d",strtotime($holiday_start_year."-".$holiday_start_month."-".$holiday_start_day));
                            $org_holidays_array[] = $holiday_date;
                            $org_holidays_names[$holiday_date] = $value->Title;  
                            $holiday_start_day = $holiday_start_day + 1;
                        }
                    }
                }


                if(in_array("Friday", $off_days)){ $friday_flag = false; }elseif(in_array("Friday", $on_days)){ $friday_flag = true;}
                if(in_array("Saturday", $off_days)){ $saturday_flag = false; }elseif(in_array("Saturday", $on_days)){ $saturday_flag = true;}
                if(in_array("Sunday", $off_days)){ $sunday_flag = false; }elseif(in_array("Sunday", $on_days)){ $sunday_flag = true;}
                if(in_array("Monday", $off_days)){ $monday_flag = false; }elseif(in_array("Monday", $on_days)){ $monday_flag = true;}
                if(in_array("Tuesday", $off_days)){ $tuesday_flag = false; }elseif(in_array("Tuesday", $on_days)){ $tuesday_flag = true;}
                if(in_array("Wednesday", $off_days)){ $wednesday_flag = false; }elseif(in_array("Wednesday", $on_days)){ $wednesday_flag = true;}
                if(in_array("Thursday", $off_days)){ $thursday_flag = false; }elseif(in_array("Thursday", $on_days)){ $thursday_flag = true;}
                 
                    
                $target_date = date("Y-m-d", strtotime("+3 years", strtotime($date_added))); 
                $shift_start_date  = $calendar_date = $date_added; 

                $date1=date_create($shift_start_date);
                $date2=date_create($target_date);
                $diff=date_diff($date1,$date2);
                $calendar_days = $diff->format("%a"); 

                for ($i=1; $i <= $calendar_days ; $i++) 
                { 
                    $description = "";
                    $calendar_date = date('Y-m-d', strtotime($calendar_date . ' +1 day'));

                
                    $day_name = date("l",strtotime($calendar_date));

                    $title = 'Not Set';
                    $class = 'working_day'; 

                    if(in_array($day_name, $off_days)){ $title = 'OFF'; $class = 'leave_day'; }
                    elseif(in_array($day_name, $on_days)){ $title = 'ON'; $class = 'working_day'; }

                    if($day_name == "Friday" && in_array("Friday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true; $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Saturday" && in_array("Saturday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Sunday" && in_array("Sunday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Monday" && in_array("Monday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Tuesday" && in_array("Tuesday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Wednesday" && in_array("Wednesday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    }

                    if($day_name == "Thursday" && in_array("Thursday", $alternative_days))
                    { 
                        if($friday_flag) { $friday_flag = false; $title = 'ON'; $class = 'working_day';} else { $friday_flag = true;  $title = 'OFF'; $class = 'leave_day';}
                    } 
                    
                    if(in_array($calendar_date, $org_holidays_array))
                    {  
                        $title = 'OFF'; $class = 'org_holiday'; 
                        $description = $org_holidays_names[$calendar_date];
                    }

                    

                        $calendar_data[] = array(
                                                    'id' => $i,
                                                    'title' => $title,
                                                    'className' => $class,
                                                    'start' => $calendar_date,
                                                    'description' => $description
                                                );
                        if($description != "")
                        {
                            $calendar_data[] = array(
                                                        'id' => $i,
                                                        'title' => $description,
                                                        'className' => $class,
                                                        'start' => $calendar_date, 
                                                    );
                        }
                         
                } 
            }  
        } 

    }

    public function save_question()
    {
        $edit = 0;
        $message = array();
        $data = $this->input->post();
        if(isset($data['Edit_Recorde'])){ $edit = $data['Edit_Recorde']; unset($data['Edit_Recorde']); }
        if($edit == 0)
        {
            $question_values = $data['Question_Values']; 
            unset($data['Question_Values'],$data['Table_Name']);

            $data['Org_Id'] = $this->org_id;
            $data['Date_Added'] = $this->date;
            $data['Date_Modification'] = $this->date;
            $data['Added_By'] = $this->user_id;
            $data['Modified_By'] = $this->user_id;
            $this->db->insert("questions",$data);
            $question_id = $this->db->insert_id();

            if($question_id > 0)
            {
                if(sizeof($question_values) > 0)
                {
                    foreach ($question_values as $key => $value) {
                        if(!is_null($value) && !empty($value))
                        {
                            $question_data['Org_Id'] = $this->org_id;
                            $question_data['Question_Id'] = $question_id;
                            $question_data['Value'] = $value;
                            $question_data['Date_Added'] = $this->date;
                            $question_data['Date_Modification'] = $this->date;
                            $question_data['Added_By'] = $this->user_id;
                            $question_data['Modified_By'] = $this->user_id;
                            $this->db->insert("question_values",$question_data);
                        }
                    }
                }
            }

            $alert  = ' <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                             Question is saved successfully.
                         </div>';  

            $message['Success'] = true;
            $message['Message'] = $alert;  
        }
        else
        {
            $question_values = $data['Question_Values']; 
            unset($data['Question_Values'],$data['Table_Name']);

            
            $data['Date_Modification'] = $this->date; 
            $data['Modified_By'] = $this->user_id;
            $this->db->update("questions",$data,array("Id"=>$edit));  

            if(sizeof($question_values) > 0)
            { 
                $this->db->delete("question_values",array("Question_Id"=>$edit,"Org_Id"=>$this->org_id));

                foreach ($question_values as $key => $value) {
                    if(!is_null($value) && !empty($value))
                    {
                        $question_data['Org_Id'] = $this->org_id;
                        $question_data['Question_Id'] = $edit;
                        $question_data['Value'] = $value;
                        $question_data['Date_Added'] = $this->date;
                        $question_data['Date_Modification'] = $this->date;
                        $question_data['Added_By'] = $this->user_id;
                        $question_data['Modified_By'] = $this->user_id;
                        $this->db->insert("question_values",$question_data);
                    }
                }
            }
            
            $alert  = ' <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                             Question is updated successfully.
                         </div>';  

            $message['Success'] = true;
            $message['Message'] = $alert;
        }

        echo json_encode($message);
    }




    public function cities_names()
    {
        $cities_list = 'Islamabad';
 
        $array = explode(",",$cities_list);
        foreach ($array as $key => $value) 
        { 
          $city = strip_tags(trim($value));
          $this->db->insert("cities",array("name"=>$city,"state_id"=>'2724') );
        
        }

    }



    public function get_email_content($email_template,$id,$type)
    {

        $message = "";
        if($email_template > 0)
        {
            $email_template = $this->db->get_where("email_templates",array("Deleted"=>0,"Status"=>1,"Id"=>$email_template));
            if($email_template->num_rows() > 0)
            { 
               $template_data = $email_template->result_array();
                    
               if($type==='employees')
               {
                    $this->db->where("Id",$id);
                    $this->db->select("Email,Id");
                    $rec = $this->db->get_where("employees",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
               }

               if($type === "applicants")
               {
                    $this->db->where("Id",$id);
                    $this->db->select("Email,Id");
                    $rec = $this->db->get_where("applicants",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));
               }

                foreach ($rec->result() as $key => $value) 
                {
                  
                    $variables = $this->getInbetweenStrings("@@" , "@@", $template_data[0]['Message']); 
                       
                    $message = $template_data[0]['Message'];
                    $table_data = "";
                    foreach ($variables as $index => $field) 
                    {
                       $field_parts = explode(".", $field);
                       if($field_parts[0] != "")
                       {
                             $table_name = $field_parts[0];
                             if(isset($field_parts[1])){ $field_name = $field_parts[1]; }else{ $field_name = ""; }
                             
                             if($type === "employees")
                             {

                                 if($table_name == "employees")
                                 {
                                    $this->db->select($field_name);
                                    $table_data = $this->db->get_where($table_name,array("Id"=>$value->Id));
                                 } 
                                 else
                                 {
                                    $message = $this->check_fields_in_message($field,$message);
                                 }
                            }
                            elseif($type === "applicants")
                            {
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
                }        
            }
        }

        return $message;
    }

    public function save_interview_question()
    {
        $saved = 0;
        $data = $this->input->post(); 
        if(isset($data['Answer']))
        {
            if(is_array($data['Answer'])){ $data['Answer'] = json_encode($data['Answer']); }
            $check_rec = $this->db->get_where("interview_evaluations",array("Org_Id"=>$this->org_id,"Interview_Id"=>$data['Interview_Id'],"Interviewer"=>$data['Interviewer'],"Question_Id"=>$data['Question_Id']));
            if($check_rec->num_rows() > 0)
            {
                $update_data['Answer'] = $data['Answer'];
                $update_data['Date_Modification'] = $this->date;
                $update_data['Modified_By'] = $this->user_id; 
                $question_answer_data = $check_rec->result_array();
                $this->db->update("interview_evaluations",$update_data,array("Id"=>$question_answer_data[0]['Id']));
                $saved = 1;
                 
            }
            else
            {
                $question_rec = $this->db->get_where("questions",array("Id"=>$data['Question_Id']));
                if($question_rec->num_rows() > 0)
                {
                    $question_data = $question_rec->result_array(); 
                    $data['Question'] = $question_data[0]['Statement'];
                    $data['Date_Added'] = $this->date;
                    $data['Added_By']  = $this->user_id;
                    $data['Org_Id'] = $this->org_id; 
                    $this->db->insert("interview_evaluations",$data);
                    $saved = 1; 
                }
            }
        }

        // echo "<pre>";
        // print_r($data);
        echo $saved;

    }

    public function save_interviewer_opinions()
    {
        $data = $this->input->post();
        if($data['Interviewer'] > 0)
        {
            $data['Date_Modification'] = $this->date;
            $data['Modified_By'] = $this->user_id;
            $interview_id = $data['Interview_Id'];
            unset($data['Interview_Id']);
            $this->db->update("applicant_interviews",$data,array("Id"=>$interview_id));
        }
    }

    public function save_organization_holiday()
    {
        $saved = 0;
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
            if(isset($data['Repeat_Yearly'])){ 
                $data['From_Day'] = $data['From_Month']."-".$data['From_Day']; unset($data['From_Month'],$data['From_Date']); 
                $data['To_Day'] = $data['To_Month']."-".$data['To_Day']; unset($data['To_Month'],$data['To_Date']);
            }
            else{  
               unset($data['From_Month'],$data['From_Day'],$data['To_Month'],$data['To_Day']);  
            }
             
            
            $data = $this->JsonEncode($data); 
            $this->db->insert($table,$data); 
            $id = $this->db->insert_id(); 
            $saved = 1;
        }
        else
        {
            $data['Date_Modification'] = $this->date; 
            $data['Modified_By'] = $this->user_data['Id'];  
            $table = $data['Table_Name']; 
            unset($data['Edit_Recorde'],$data['Table_Name']); 
           if(isset($data['Repeat_Yearly'])){ 
                $data['From_Day'] = $data['From_Month']."-".$data['From_Day']; unset($data['From_Month'],$data['From_Date']); 
                $data['To_Day'] = $data['To_Month']."-".$data['To_Day']; unset($data['To_Month'],$data['To_Date']);
            }
            else{  
               unset($data['From_Month'],$data['From_Day'],$data['To_Month'],$data['To_Day']);  
            }

            $data = $this->JsonEncode($data); 
            $this->db->update($table,$data,array("Id"=>$Edit_Recorde,"Org_Id"=> $this->org_id));  
            $saved = 1;
        }

        if($saved)
        {
            $save = true;  
            $alert  = '<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Successful!</h4>
                             Record is saved successfully.  
                         </div>';
            $message['Success'] = true;
            $message['Message'] = $alert;
            $message['Id'] = $id;
        }
        else
        {
            $save = true;  
            $alert  = '<div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Error!</h4>
                             Record is not saved or updated.
                         </div>';
            $message['Success'] = true;
            $message['Message'] = $alert;
            $message['Id'] = $id;
        }

        echo json_encode($message);
    }

    public function save_organization_holiday_replacement()
    {
        $saved = 0;
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

            if(isset($data['From_Month'])){ 
                $data['From_Day'] = $data['From_Month']."-".$data['From_Day']; unset($data['From_Month']);
                $data['To_Day'] = $data['To_Month']."-".$data['To_Day']; unset($data['To_Month']);  
            }
            
            
            $data = $this->JsonEncode($data); 
            $this->db->insert($table,$data); 
            $id = $this->db->insert_id(); 
            $saved = 1;
        }
        else
        {
            $data['Date_Modification'] = $this->date; 
            $data['Modified_By'] = $this->user_data['Id'];  
            $table = $data['Table_Name']; 
            unset($data['Edit_Recorde'],$data['Table_Name']); 
            if(isset($data['From_Month'])){ 
                $data['From_Day'] = $data['From_Month']."-".$data['From_Day']; unset($data['From_Month']);
                $data['To_Day'] = $data['To_Month']."-".$data['To_Day']; unset($data['To_Month']);  
            }

            $data = $this->JsonEncode($data); 
            $this->db->update($table,$data,array("Id"=>$Edit_Recorde,"Org_Id"=> $this->org_id));  
            $saved = 1;
        }

        if($saved)
        {
            $save = true;  
            $alert  = '<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Successful!</h4>
                             Record is saved successfully.  
                         </div>';
            $message['Success'] = true;
            $message['Message'] = $alert;
            $message['Id'] = $id;
        }
        else
        {
            $save = true;  
            $alert  = '<div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Error!</h4>
                             Record is not saved or updated.
                         </div>';
            $message['Success'] = true;
            $message['Message'] = $alert;
            $message['Id'] = $id;
        }

        echo json_encode($message);
    }

    public function get_holiday_month_and_days()
    {
        $data = $this->input->post();
        $month_options = $month_day_options = "";
        if(isset($data['id']) && is_numeric($data['id']))
        {
            $holiday_id = $data['id'];
            $holiday_data = $this->db->get_where("organization_holidays",array("Id"=>$holiday_id,"Org_Id"=>$this->org_id,"Deleted"=>0,"Status"=>1));
            if($holiday_data->num_rows() > 0)
            {
                $record_data = $holiday_data->result_array();
                $record_data = $record_data[0];

                $to_month = $to_month_day = 0;
                if(isset($record_data['Repeat_Yearly']) && $record_data['Repeat_Yearly'] == "on")
                { 
                  $my_date =  date("Y")."-".$record_data['To_Day'];  
                  $date=date_create($my_date);
                  date_add($date,date_interval_create_from_date_string("1 days"));
                  $applicable_date =  date_format($date,"m-d"); 
                  
                  $date_parts = explode("-", $applicable_date);

                  $to_month = $date_parts[0];
                  $to_month_day = $date_parts[1]; 
                }
                elseif(isset($record_data['Repeat_Yearly']) && $record_data['Repeat_Yearly'] == "off")
                {
                  $date_parts = explode("-", $record_data['To_Date']);
                  $to_month = $date_parts[1];
                  $to_month_day = $date_parts[2]; 
                }

                if($to_month > 0 )
                {
                   for ($i=$to_month; $i < 13; $i++) { 
                    $selected = "";
                    $month_name = date("F",strtotime(date("Y")."-".$i));
                    if($i==$to_month) { $selected = 'selected="selected"'; }
                    $month_options .='<option '.$selected.' value="'.$i.'">'.$month_name.'</option>';
                   }
                }

                if($to_month_day > 0 )
                {
                   $month_total_days = date("t", strtotime(date("Y")."-".$record_data['To_Day']));
                   
                   for ($i=$to_month_day; $i < $month_total_days; $i++) { 
                    $selected = ""; 
                    if($i==$to_month_day) { $selected = 'selected="selected"'; }
                    $month_day_options .= '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
                   }
                }
            }
        }

        $return_data['months'] = $month_options;
        $return_data['days'] = $month_day_options;
        echo json_encode($return_data);
    }

    public function get_employees_list()
    {
        $this->db->select("departments.Name as Department_Name, departments.Id as Dep_Id,employees.Id, employees.First_Name, employees.Last_Name,employees.Joining_Date");
        $this->db->where(array("departments.Deleted"=>0,"departments.Status"=>1,"employees.Deleted"=>0,"employees.Status"=>1,"employees.Employee_Status"=>"Active"));
        $this->db->from("employees");
        $this->db->join("employee_work_record","employee_work_record.Employee_Id = employees.Id","left");
        $this->db->join("departments","departments.Id = employee_work_record.Department_Id","left");
        $this->db->order_by("departments.Name","asc");
        $this->db->group_by("employees.Id");
        $records = $this->db->get(); 
        if($records->num_rows() > 0){
            return $records->result();
        }
    }




}   

