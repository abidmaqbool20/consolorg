 
<?php 
 
  $record_data = array();
  $src =  "assets/images/default-user.png";



  $this->db->where(array("applicants.Deleted" =>0,"applicants.Org_Id"=>$this->org_id,"applicants.Id"=>$data));
  $this->db->select('
                applicants.*, 
                job_posts.Title as Job_Title, 
                 
                applicant_applications.Applicant_Id,
                applicant_applications.Job_Id,
                applicant_applications.Application_Status,
                applicant_applications.Applied_Date,
                locations.Name as Location_Name,  
                countries.name as Country_Name,
                states.name as State_Name,
                cities.name as City_Name,
                per_country.name as Per_Country_Name,
                per_states.name as Per_State_Name,
                per_cities.name as Per_City_Name,
                addedby.First_Name as Addedby_FirstName,
                addedby.Last_Name as Addedby_LastName,
                updatedby.First_Name as Updatedby_FirstName,
                updatedby.Last_Name as Updatedby_LastName,
                applicant_education.Institute,
                applicant_education.Marks_Obtained,
                applicant_education.Degree_Type,
                universities.Name as Institute_Name,
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

  $this->db->join("applicant_education","applicant_education.Applicant_Id = applicants.Id  AND applicant_education.Degree_Type='Bachelors'","left");
  $this->db->join("universities","universities.Id = applicant_education.Institute","left");

  $this->db->order_by("applicant_applications.Job_Id","DESC");  
  $this->db->group_by("applicants.Id");
  $get_record = $this->db->get();

 
  if($get_record->num_rows() > 0)
  {
    $record_data = $get_record->result_array();
    $record_data = $record_data[0];

    $pic_src =  "assets/images/default-user.png";
    if($record_data['Photo'] && $record_data['Photo'] != "")
    {
      $pic_src =  "assets/panel/userassets/applicants/".$record_data['Id']."/".$record_data['Photo'];
      if(!file_exists($src))
      {
         $pic_src =  "assets/images/default-user.png";
      } 
    }

    $resume_src = "Not Uploaded";
    if(isset($record_data['Resume']) && $record_data['Resume'] != "")
    {
      $src =  "assets/panel/userassets/applicants/".$record_data['Id']."/".$record_data['Resume'];
      if(file_exists($src))
      {
        $resume_src = $src;
      } 
    }


    $this->db->order_by("Id","DESC");
    $get_application_rec = $this->db->get_where("applicant_applications",array("Deleted"=>"0","Status"=>1,"Applicant_Id"=>$data,"Org_Id"=>$this->org_id));
    $total_applications = $get_application_rec->num_rows();
     
  }
 
 ?>

<style type="text/css">
  .list-group-item
  {
    text-align: left;
  }
</style>

<div class="mainpanel view-panle-class">

    <div class="contentpanel view-panle-class">

     <div class="panel view-panle-class">
         
        <div class="panel-body view-panle-class">
          <div class="col-xs-12 col-md-3 col-lg-2 profile-left">
            <div class="profile-left-heading">
             
              <a href="<?= $pic_src; ?>" target="_blank" class="profile-photo"><img class="img-circle img-responsive" src="<?= $pic_src; ?>" alt=""></a>
              <h2 class="profile-name"><?php if(isset($record_data['First_Name'])){ echo $record_data['First_Name']." ".$record_data['Last_Name']; } ?></h2>
              <h4 class="profile-designation"><?php if(isset($record_data['Job_Title'])){ echo $record_data['Job_Title']; } ?><br>
                ( <?php if(isset($record_data['Location_Name'])){ echo $record_data['Location_Name']; } ?> )
              </h4>

              <ul class="list-group">
                <li class="list-group-item">Apply Date <a href="javascript:;"><?php echo date("d M, Y", strtotime($record_data['Applied_Date'])); ?></a></li>
                <li class="list-group-item">Total Applications <a href="javascript:;"><?= $total_applications; ?></a></li> 
              </ul>


              <a href="<?= $resume_src; ?>" target="_blank"><button class="btn btn-danger btn-quirk btn-block profile-btn-follow">View Resume</button></a>
            </div>
            <div class="profile-left-body">
              <h4 class="panel-title">About Me</h4>
              <p style="text-align: justify;"><?php if(isset($record_data['About_Info'])){ echo $record_data['About_Info']; } ?></p>

              <hr class="fadeout">

              <h4 class="panel-title"><i class="fa fa-flag"></i> Country </h4>
              <p><?php if(isset($record_data['Per_Country_Name'])){ echo $record_data['Per_Country_Name']; } ?></p>

              <hr class="fadeout">

              <h4 class="panel-title"><i class="fa fa-globe"></i> State / City </h4>
              <p> <?php if(isset($record_data['Per_State_Name'])){ echo $record_data['Per_State_Name']; } ?>, <?php if(isset($record_data['Per_City_Name'])){ echo $record_data['Per_City_Name']; } ?></p>

              <hr class="fadeout">

              <h4 class="panel-title"><i class="fa fa-map-marker"></i> Address</h4>
              <p><?php if(isset($record_data['Address'])){ echo $record_data['Address']; } ?></p>

              <hr class="fadeout">

              <h4 class="panel-title">Social</h4>
              <ul class="list-inline profile-social">
                <?php if(isset($record_data['Facebook'])){ echo '<li><a href="'.$record_data['Facebook'].'" target="_blank"><i class="fa fa-facebook-official"></i></a></li>'; } ?>
                <?php if(isset($record_data['Facebook'])){ echo '<li><a href="'.$record_data['Twitter'].'" target="_blank"><i class="fa fa-twitter"></i></a></li>'; } ?>
                <?php if(isset($record_data['Facebook'])){ echo '<li><a href="'.$record_data['Linkedin'].'" target="_blank"><i class="fa fa-linkedin"></i></a></li>'; } ?>
                <?php if(isset($record_data['Facebook'])){ echo '<li><a href="'.$record_data['Youtube'].'" target="_blank"><i class="fa fa-youtube"></i></a></li>'; } ?> 
              </ul>

            </div>
          </div>
          <div class="col-md-9 col-lg-10 profile-right">
                <div class="col-md-12 col-sm-12">
                  <div class="panel panel-inverse">
                    <div class="panel-heading">
                      <h3 class="panel-title">Acount Information</h3>
                    </div>
                    <div class="panel-body view-panle-body">
                     <div class="table-responsive">
                        <table class="table table-bordered table-inverse nomargin view_applicant_table">
                           
                            <tr> 
                              <th>Name</th>
                              <td class="text-left"><?php if(isset($record_data['First_Name'])){ echo $record_data['First_Name']." ".$record_data['Last_Name']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Email</th>
                              <td class="text-left"><?php if(isset($record_data['Email'])){ echo $record_data['Email']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Password</th>
                              <td class="text-left"><?php if(isset($record_data['Password'])){ echo $record_data['Password']; } ?></td> 
                            </tr> 
                          
                        </table>
                      </div>
                    </div>
                  </div> 
                </div>
                <div class="col-md-12 col-sm-12">
                  <div class="panel panel-inverse">
                    <div class="panel-heading">
                      <h3 class="panel-title">Contact Information</h3>
                    </div>
                    <div class="panel-body view-panle-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-inverse nomargin view_applicant_table">
                           
                            <tr> 
                              <th>Phone Number</th>
                              <td class="text-left"><?php if(isset($record_data['Phone_Number'])){ echo $record_data['Phone_Number']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Mobile Number 1</th>
                              <td class="text-left"><?php if(isset($record_data['Mobile_Number_1'])){ echo $record_data['Mobile_Number_1']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Mobile Number 2</th>
                              <td class="text-left"><?php if(isset($record_data['Mobile_Number_2'])){ echo $record_data['Mobile_Number_2']; } ?></td> 
                            </tr> 
                            <tr> 
                              <th colspan="2" style="font-size: 15px;">Current Address</th> 
                            </tr> 
                            <tr> 
                              <th>Country</th>
                              <td class="text-left"><?php if(isset($record_data['Country_Name'])){ echo $record_data['Country_Name']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>State</th>
                              <td class="text-left"><?php if(isset($record_data['State_Name'])){ echo $record_data['State_Name']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>City</th>
                              <td class="text-left"><?php if(isset($record_data['City_Name'])){ echo $record_data['City_Name']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Zipcode</th>
                              <td class="text-left"><?php if(isset($record_data['Zipcode'])){ echo $record_data['Zipcode']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Address</th>
                              <td class="text-left"><?php if(isset($record_data['Address'])){ echo $record_data['Address']; } ?></td> 
                            </tr>
                            <tr> 
                              <th colspan="2" style="font-size: 15px;">Permanent Address</th> 
                            </tr> 
                            <tr> 
                              <th>Country</th>
                              <td class="text-left"><?php if(isset($record_data['Per_Country_Name'])){ echo $record_data['Per_Country_Name']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>State</th>
                              <td class="text-left"><?php if(isset($record_data['Per_State_Name'])){ echo $record_data['Per_State_Name']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>City</th>
                              <td class="text-left"><?php if(isset($record_data['Per_City_Name'])){ echo $record_data['Per_City_Name']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Zipcode</th>
                              <td class="text-left"><?php if(isset($record_data['P_Zipcode'])){ echo $record_data['P_Zipcode']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Address</th>
                              <td class="text-left"><?php if(isset($record_data['P_Address'])){ echo $record_data['P_Address']; } ?></td> 
                            </tr>
                        </table>
                      </div>
                    </div>
                  </div> 
                </div>
                <div class="col-md-12 col-sm-12">
                  <div class="panel panel-inverse">
                    <div class="panel-heading">
                      <h3 class="panel-title">Other Information</h3>
                    </div>
                    <div class="panel-body view-panle-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-inverse nomargin view_applicant_table">
                           
                            <tr> 
                              <th>CNIC</th>
                              <td class="text-left"><?php if(isset($record_data['CNIC'])){ echo $record_data['CNIC']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>DOB</th>
                              <td class="text-left"><?php if(isset($record_data['DOB'])){ echo $record_data['DOB']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Gender</th>
                              <td class="text-left"><?php if(isset($record_data['Gemder'])){ echo $record_data['Gemder']; } ?></td> 
                            </tr> 
                            <tr> 
                              <th>Martial Status</th>
                              <td class="text-left"><?php if(isset($record_data['Martial_Status'])){ echo $record_data['Martial_Status']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Blood Group</th>
                              <td class="text-left"><?php if(isset($record_data['Blood_Group'])){ echo $record_data['Blood_Group']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Religion</th>
                              <td class="text-left"><?php if(isset($record_data['Religion'])){ echo $record_data['Religion']; } ?></td> 
                            </tr>
                            <tr> 
                              <th>Source Of Apply</th>
                              <td class="text-left"><?php if(isset($record_data['Source_Of_Hire'])){ echo $record_data['Source_Of_Hire']; } ?></td> 
                            </tr>
                            <tr> 
                              <th colspan="2" style="font-size: 15px;">About Applicant</th> 
                            </tr>
                            <tr>  
                              <td colspan="2" class="text-left"><?php if(isset($record_data['About_Info'])){ echo $record_data['About_Info']; } ?></td> 
                            </tr>
                        </table>
                      </div>
                    </div>
                  </div> 
                </div>
                
          </div>
          
                
            
        </div>
      </div>

      
    </div> 
  </div>

   