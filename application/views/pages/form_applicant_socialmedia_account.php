 
<?php
 
  $record_data = array();
  if($edit_rec != "")
  {
    $this->db->order_by("Id","DESC");
    $check_record = $this->db->get_where("applicant_socialmedia_account",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Id"=>$edit_rec));

    if($check_record->num_rows() > 0)
    {
      $record_data = $check_record->result_array();
      $record_data = $record_data[0]; 
    }

  }
?>

    <div class="panel-body"> 
      <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
        <div class="error"></div>
        <div class="form-group">
           
            <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id']) && $record_data['Id'] != "" ){ echo $record_data['Id']; } ?>">
            <input type="hidden" name="Applicant_Id" id="Applicant_Id" value="<?= $data; ?>">
            <input type="hidden" name="Table_Name" id="Table_Name" value="applicant_socialmedia_account">
            <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>"> 

            <div class="col-sm-12  col-xs-12">
              <div class="">
                <label class="control-label">Facebook <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control" id="Facebook" name="Facebook" value="<?php if(isset($record_data['Facebook'])){ echo $record_data['Facebook']; } ?>">
                 
              </div>
            </div>
           <div class="col-sm-12  col-xs-12">
              <div class="">
                <label class="control-label">Linkedin <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control" id="Linkedin" name="Linkedin" value="<?php if(isset($record_data['Linkedin'])){ echo $record_data['Linkedin']; } ?>">
                 
              </div>
            </div>
           <div class="col-sm-12  col-xs-12">
              <div class="">
                <label class="control-label">Twitter <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control" id="Twitter" name="Twitter" value="<?php if(isset($record_data['Twitter'])){ echo $record_data['Twitter']; } ?>">
                 
              </div>
            </div>
           <div class="col-sm-12  col-xs-12">
              <div class="">
                <label class="control-label">Youtube <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control" id="Youtube" name="Youtube" value="<?php if(isset($record_data['Youtube'])){ echo $record_data['Youtube']; } ?>">
                 
              </div>
            </div>
           <div class="col-sm-12  col-xs-12">
              <div class="">
              <label class="control-label">Instagram <span class="text-danger">*</span><span class="text-danger error"></span></label>
                <input type="text" class="form-control" id="Instagram" name="Instagram" value="<?php if(isset($record_data['Instagram'])){ echo $record_data['Instagram']; } ?>">
                 
              </div>
            </div>
           
            
                  
        </div>
            
        <div class="form-group"> 
          <div class="col-sm-12">
            <br>
            <button type="submit" id="" class="btn btn-success pull-right"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Submit </button> 
          </div>
        </div>
      </form>
      <br><br>
      <div class="form-group">
        <div class="row">
        <?php

          $this->db->order_by("Id","DESC");
          $applicant_socialmedia_acc = $this->db->get_where("applicant_socialmedia_account",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id,"Applicant_Id"=>$data));

          if($applicant_socialmedia_acc->num_rows() > 0)
          {  
            foreach ($applicant_socialmedia_acc->result() as $key => $value)
            {
              
              echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'" >
                      <div class="panel panel-inverse">
                        <div class="panel-heading" style="padding:0px;">
                            <div class="row">  
                              <div class="col-md-12">
                                <div class="col-md-6">
                                  <h3 style="color:#fff;"><i class="fa fa-graduation-cap"></i> Applicant Social Media Accounts </h3>
                                </div>
                                <div class="col-md-6">
                                  <a href="javascript:;" class="btn btn-sm" onclick="load_tab(this,\'form_applicant_socialmedia_account\','.$data.',\'applicant_from_container\', '.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a>
                                  <a href="javascript:;" class="btn btn-sm"  onclick="delete_record(\'applicant_socialmedia_account\','.$value->Id.',this)" style="color: red;float: right; font-size:18px;"> <i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel-body" style="background: #dcdcdc;">
                           <div class="table-responsive">
                              <table class="table table-bordered table-primary nomargin">   <tr> 
                                  <th>Facebook</th>
                                  <td>'.$value->Facebook.'</td> 
                                </tr>
                                <tr> 
                                  <th>Linkedin</th>
                                  <td>'.$value->Linkedin.'</td> 
                                </tr>
                                <tr> 
                                  <th>Twitter</th>
                                  <td>'.$value->Twitter.'</td> 
                                </tr>
                                <tr> 
                                  <th>Youtube</th>
                                  <td>'.$value->Youtube.'</td> 
                                </tr>
                                <tr> 
                                  <th>Insatgram</th>
                                  <td>'.$value->Insatgram.'</td> 
                                </tr>  
                              </table> 
                            </div> 
                          </div> 
                        </div>  
                      </div>';
          
            }
          }
          else
          {
            echo no_record_found(); 
          }

        ?>
        </div>
      </div>
    </div> 
      
         

 


<script type="text/javascript">
  $('.select2').select2(); 
  $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
</script>