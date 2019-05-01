
<?php
  
  $record_data = array();
  if($data != "")
  {
    $check_rec = $this->db->get_where("applicant_instructions",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_rec->num_rows() > 0)
    {
      $record_data = $check_rec->result_array();
      $record_data = $record_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> Save / Update Applicant Instructions</h3>
          </div>
          <div class="col-md-6">
            
          </div>
        </div>
      </div>
    </div>
    <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($record_data['Id'])){ echo $record_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="applicant_instructions">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Applicant Instrucitons</h4> 
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group"> 
            <div class="col-sm-12 col-xs-12">
              <label class="control-label">Applicant Instructions <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <textarea id="Instructions" name="Instructions" class="form-control required" required=""><?php if(isset($record_data['Instructions'])){ echo $record_data['Instructions']; } ?></textarea> 
              <script> CKEDITOR.replace( 'Instructions' );</script> 
            </div> 
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_sidebar_view(this,'applicant_instructions');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            
          </div>
        </div>
      </div>
    </form>
    <div class="panel">
      <div class="panel-body"> 
        <div class="form-group">
          <div class="row">
            <?php

               
              $applicant_instructions = $this->db->get_where("applicant_instructions",array("Deleted"=>0,"Status"=>1,"Org_Id"=>$this->org_id));

              if($applicant_instructions->num_rows() > 0)
              {  
                foreach ($applicant_instructions->result() as $key => $value){ 

                  

                  echo '<div class="col-md-12 col-sm-12" id="row_'.$value->Id.'">
                          <div class="panel panel-inverse">
                            <div class="panel-heading" style="padding:0px;">
                                <div class="row">  
                                  <div class="col-md-12">
                                    <div class="col-md-6">
                                      <h3 style="color:#fff;"><i class="fa fa-bullhorn"></i> Applicant Instructions</h3>
                                    </div>
                                    <div class="col-md-6">
                                      <a href="javascript:;" class="btn btn-sm" onclick="load_view(this,\'applicant_instructions\','.$value->Id.')" style=" font-size:18px;float: right; color:#fff;" ><i class="fa fa-pencil"></i></a> 
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="panel-body" style="background: #dcdcdc;">
                               <div class="table-responsive">
                                   '.$value->Instructions.'
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
          
             
      </div>
    </div>
  </div>
 


<script type="text/javascript">
  $('.select2').select2();
  
</script>