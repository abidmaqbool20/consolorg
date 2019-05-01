
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

 	public $userdata = null;
 	public $date = null;
 	public $org_id = null;
 	public $user_id = null;

    public function __construct() {

        parent::__construct();
        date_default_timezone_set("Asia/Karachi");
        //$this->userdata = $this->session->all_userdata();
	     
        $this->date = date("Y-m-d H:i:s");
        // $this->org_id = $this->userdata['org_id'];
        // $this->user_id = $this->userdata['user_id'];
    }

    public function index()
    {  
    	$email_data =  array(
	                            "Org" => 'Construction Solutions', 
	                            "To" => 'abidmaqbool20@gmail.com',
	                            "Subject" => 'Cron Job Test',
	                            "Message" => 'It is test email for cron job from consol.pk',
	                        );
      $this->rotate_shifts();
    	//$this->send_email($email_data);
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
        $this->email->message("hello");  //$email_data['Message']
        echo $result = $this->email->send();  

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
 
  public function rotate_shifts()
  {
    $shifts = $this->db->get_where("shift_rotations",array("Deleted"=>0,"Status"=>1,"Rotation_Status"=>"Pending"));
    if($shifts->num_rows() > 0){
      foreach ($shifts->result() as $key => $value) {
        $implentation_time =  date("Y-m-d H:i:s", strtotime('+10 hours',strtotime($value->Date_Added)));
        $time_now = date("Y-m-d H:i:s");

        $d_start    = new DateTime($time_now); 
        $d_end      = new DateTime($implentation_time); 
        $diff = $d_start->diff($d_end);  
        
        $hours = $diff->format('%h'); 
        $minutes = $diff->format('%i'); 

        if($hours <= 0 && $minutes <= 0){
         
          $shift_a = $value->Shift_A;
          $shift_b = $value->Shift_B;

          $query = "UPDATE shift_employees as shift_a_employees, shift_employees as shift_b_employees SET shift_a_employees.Shift_Id = $shift_b, shift_b_employees.Shift_Id = $shift_a WHERE shift_a_employees.Shift_Id = $shift_a AND shift_b_employees.Shift_Id = $shift_b;"; 
          $this->db->query($query);

          $query = "UPDATE shifts as shift_a, shifts as shift_b SET shift_a.Shift_Incharge = shift_b.Shift_Incharge, shift_b.Shift_Incharge = shift_a.Shift_Incharge WHERE shift_a.Id = $shift_a AND shift_b.Id = $shift_b;"; 
          $this->db->query($query);

          $this->db->update("shift_rotations",array("Rotation_Status"=>"Success","Date_Modification"=>date("Y-m-d H:i:s")),array("Id"=>$value->Id));
        }
      }
    }
  }





}

?>