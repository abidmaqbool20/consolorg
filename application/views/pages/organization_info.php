 
<?php 
  


  $role_data = array();
  if($data != "")
  {
    $this->db->where(array("organizations.Deleted"=>0,"organizations.Id"=>$data));
    $this->db->select("
                        organizations.*, 
                        countries.name as Country_Name,
                        states.name as State_Name,
                        cities.name as City_Name, 
                        cp_countries.name as CP_Country_Name,
                        cp_states.name as CP_State_Name,
                        cp_cities.name as CP_City_Name, 
                        addedby.First_Name as Addedby_FirstName,
                        addedby.Last_Name as Addedby_LastName,
                        updatedby.First_Name as Updatedby_FirstName,
                        updatedby.Last_Name as Updatedby_LastName

                      ");

    $this->db->from("organizations"); 
    
    $this->db->join("countries","organizations.Country = countries.id","left");
    $this->db->join("states","organizations.State = states.id","left");
    $this->db->join("cities","organizations.City = cities.id","left");

    $this->db->join("countries as cp_countries","organizations.CP_Country = cp_countries.id","left");
    $this->db->join("states as cp_states","organizations.CP_State = cp_states.id","left");
    $this->db->join("cities as cp_cities","organizations.CP_City = cp_cities.id","left");

    $this->db->join("users as addedby","organizations.Added_By = addedby.Id","left");
    $this->db->join("users as updatedby","organizations.Modified_By = updatedby.Id","left");
    $this->db->order_by("Id","DESC");

    $organizations = $this->db->get();

    if($organizations->num_rows() > 0)
    {
      $org_data = $organizations->result_array();
      $org_data = $org_data[0];


      $get_subscription_rec = $this->db->get_where("organization_subscriptions",array("Org_Id"=>$data,"Subscription_Status"=>1));
      if($get_subscription_rec->num_rows() > 0)
      {
        $get_subscription_data = $get_subscription_rec->result_array(); 
        $org_data['Subscription_Start_Date'] = $get_subscription_data[0]['Subscription_Start_Date'];
        $org_data['Subscription_End_Date'] = $get_subscription_data[0]['Subscription_End_Date'];
      }
      else
      {
        $org_data['Subscription_Start_Date'] = "";
        $org_data['Subscription_End_Date'] = "";
      }


      $org_logo = "";
      if($org_data['Logo'] && $org_data['Logo'] != "")
      {
        $org_logo = base_url("assets/panel/userassets/organizations")."/".$org_data['Id']."/".$org_data['Logo'];
        $org_logo = '<img class="media-object img-circle" src="'.$org_logo.'" alt="">';
      }
      else
      {
        if($org_data['Name'] && $org_data['Name'] != "")
        {
          $org_name = explode(" ", $org_data['Name']);
          $org_logo_letters = substr($org_name[0], 0,1);
          $org_logo_letters .= substr(array_pop($org_name), 0,1);
          $org_logo = '<div class="logo_letters">'.$org_logo_letters.'</div>';
        }
        else
        {
          $org_logo = '<div class="logo_letters"><i class="fa fa-times"></i></div>';
        }
      }


    } 

  }
?>
 
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="logo-container">
            <div class="logo-div">
              <a href="#">
                <?= $org_logo; ?>
              </a>
            </div>
          </div>
          <div class="col-md-12">
            <div class="org-name">
              <h3><?php if($org_data['Name'] && $org_data['Name'] != ""){ echo $org_data['Name']; }else{ echo "Not Set"; } ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-profile list-view">
      <div class="panel-heading"> 
         <h3><i class="fa fa-bars"></i> Summery</h3>
      </div> 

      <div class="panel-body people-info">
        <div class="row">
          <?php if($org_data['Summery'] && $org_data['Summery'] != ""){ echo $org_data['Summery']; }else{ echo "Not Saved"; } ?>
        </div>
      </div>
    </div>
    <div class="panel panel-profile list-view">
      <div class="panel-heading"> 
         <h3><i class="fa fa-warning"></i> Note </h3>
      </div> 

      <div class="panel-body people-info">
        <div class="row">
          <?php if($org_data['Note'] && $org_data['Note'] != ""){ echo $org_data['Note']; }else{ echo "Not Saved"; } ?>
        </div>
      </div>
    </div>
    <div class="panel panel-profile list-view">
      <div class="panel-heading"> 
         <h3><i class="fa fa-info-circle"></i> Organization Information</h3>
      </div> 

      <div class="panel-body people-info">
        <div class="row">
          <div class="col-sm-4">
            <div class="info-group">
              <label>Location</label>
              <?php 

                if($org_data['CP_Address'] && $org_data['Address'] != ""){ echo $org_data['Address'].", "; }  
                if($org_data['City_Name'] && $org_data['City_Name'] != ""){ echo $org_data['City_Name'].", "; }  
                if($org_data['State_Name'] && $org_data['State_Name'] != ""){ echo $org_data['State_Name'].", "; }  
                if($org_data['Country_Name'] && $org_data['Country_Name'] != ""){ echo $org_data['Country_Name'].", "; }  
                if($org_data['Zipcode'] && $org_data['Zipcode'] != ""){ echo $org_data['Zipcode']; }  

              ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Primary Email</label>
               <?php if($org_data['Primary_Email'] && $org_data['Primary_Email'] != ""){ echo $org_data['Primary_Email']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Secondary Email</label>
               <?php if($org_data['Secondary_Email'] && $org_data['Secondary_Email'] != ""){ echo $org_data['Secondary_Email']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          
        
          <div class="col-sm-4">
            <div class="info-group">
              <label>Primery Phone</label>
              <?php if($org_data['Primary_Phone'] && $org_data['Primary_Phone'] != ""){ echo $org_data['Primary_Phone']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Secondary Phone</label>
              <?php if($org_data['Secondary_Phone'] && $org_data['Secondary_Phone'] != ""){ echo $org_data['Secondary_Phone']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Primary Mobile</label>
              <?php if($org_data['Primary_Mobile'] && $org_data['Primary_Mobile'] != ""){ echo $org_data['Primary_Mobile']; }else{echo "Not saved!"; }   ?>
            </div>
          </div> 

        
          <div class="col-sm-4">
            <div class="info-group">
              <label>Secondary Mobile</label>
              <?php if($org_data['Secondary_Mobile'] && $org_data['Secondary_Mobile'] != ""){ echo $org_data['Secondary_Mobile']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Domain</label>
              <a href="<?php if($org_data['Domain'] && $org_data['Domain'] != ""){ echo $org_data['Domain']; }else{echo "javascript:;"; }   ?>" target="_blank"><?php if($org_data['Domain'] && $org_data['Domain'] != ""){ echo $org_data['Domain']; }else{echo "Not saved!"; }   ?></a>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>NTN</label>
              <?php if($org_data['NTN'] && $org_data['NTN'] != ""){ echo $org_data['NTN']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
         
        
          <div class="col-sm-4">
            <div class="info-group">
              <label>Subscription Start Date</label>
              <?php if($org_data['Subscription_Start_Date'] && $org_data['Subscription_Start_Date'] != ""){ echo date("l ,d F Y", strtotime( $org_data['Subscription_Start_Date']));   }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Subscription End Date</label>
              <?php if($org_data['Subscription_End_Date'] && $org_data['Subscription_End_Date'] != ""){ echo date("l ,d F Y", strtotime( $org_data['Subscription_End_Date']));  }else{echo "Not saved!"; }   ?>
            </div>
          </div>
         

           <div class="col-sm-4">
            <div class="info-group">
              <label>Added By</label>
              <?php if($org_data['Addedby_FirstName'] && $org_data['Addedby_FirstName'] != ""){ echo $org_data['Addedby_FirstName']." ".$org_data['Addedby_LastName']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Last Modified By</label>
              <?php if($org_data['Updatedby_FirstName'] && $org_data['Updatedby_FirstName'] != ""){ echo $org_data['Updatedby_FirstName']." ".$org_data['Updatedby_LastName']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Date Added</label>
              <?php if($org_data['Date_Added'] && $org_data['Date_Added'] != ""){ echo date("l ,d F Y", strtotime( $org_data['Date_Added'])); }else{echo "Not saved!"; }   ?>
            </div>
          </div>
           <div class="col-sm-4">
            <div class="info-group">
              <label>Last Modification Date</label>
              <?php if($org_data['Date_Modification'] && $org_data['Date_Modification'] != ""){ echo date("l ,d F Y", strtotime( $org_data['Date_Modification'])); }else{echo "Not saved!"; }   ?>
            </div>
          </div>

        </div>
      </div> 
    </div>
 
    <div class="panel panel-profile list-view">
      <div class="panel-heading"> 
         <h3><i class="fa fa-info-circle"></i>  Contact Person Information</h3>
      </div> 

      <div class="panel-body people-info">
        <div class="row">
          <div class="col-sm-4">
            <div class="info-group">
              <label>Name</label>
               <?php if($org_data['CP_Name'] && $org_data['CP_Name'] != ""){ echo $org_data['CP_Name']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Location</label>
              <?php 

                if($org_data['Address'] && $org_data['CP_Address'] != ""){ echo $org_data['CP_Address'].", "; }  
                if($org_data['CP_City_Name'] && $org_data['CP_City_Name'] != ""){ echo $org_data['CP_City_Name'].", "; }  
                if($org_data['CP_State_Name'] && $org_data['CP_State_Name'] != ""){ echo $org_data['CP_State_Name'].", "; }  
                if($org_data['CP_Country_Name'] && $org_data['CP_Country_Name'] != ""){ echo $org_data['CP_Country_Name'].", "; }  
                if($org_data['CP_Zipcode'] && $org_data['CP_Zipcode'] != ""){ echo $org_data['CP_Zipcode']; }  

              ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Email</label>
               <?php if($org_data['CP_Email'] && $org_data['CP_Email'] != ""){ echo $org_data['CP_Email']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="info-group">
              <label>Primary Mobile</label>
              <?php if($org_data['CP_Mobile_1'] && $org_data['CP_Mobile_1'] != ""){ echo $org_data['CP_Mobile_1']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
         
          <div class="col-sm-4">
            <div class="info-group">
              <label>Secondary Mobile</label>
              <?php if($org_data['CP_Mobile_2'] && $org_data['CP_Mobile_2'] != ""){ echo $org_data['CP_Mobile_2']; }else{echo "Not saved!"; }   ?>
            </div>
          </div>
          
          
        </div> 
      </div>
    </div> 
 
 