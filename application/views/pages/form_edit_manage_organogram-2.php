
 
<?php
  
  $organogram_data = array();
  if($data != "")
  {
    $check_organogram = $this->db->get_where("organograms",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_organogram->num_rows() > 0)
    {
      $organogram_data = $check_organogram->result_array();
      $organogram_data = $organogram_data[0];

      $check_organogram_childs = $this->db->get_where("organogram_childs",array("Deleted"=>0,"Organogram_Id"=>$data,"Org_Id"=>$this->org_id));
      if($check_organogram_childs->num_rows() < 1)
      {
        $this->db->insert("organogram_childs",array("Organogram_Id"=>$data,"Name"=>"Organization","Org_Id"=>$this->org_id));
      }
    }

  }
?>
<div class="mainpanel">

    <div class="contentpanel">
     <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-list"></i> Save / Update Organogram</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'manage_organograms')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
        </div>
      </div>
    </div>
        <form id="common_form" method="post" action="<?= base_url("save") ?>" onsubmit="return save_record(this);" class="form-horizontal" >
       <input type="hidden" name="Edit_Recorde" id="Edit_Recorde" value="<?php if(isset($organogram_data['Id'])){ echo $organogram_data['Id']; } ?>">
       <input type="hidden" name="Table_Name" id="Table_Name" value="organograms">
       <input type="hidden" name="Org_Id" id="Org_Id" value="<?= $this->org_id; ?>">
      <div class="panel">
        <div class="panel-heading nopaddingbottom">
          <h4 class="panel-title">Department Information</h4>
           
        </div>
        <div class="panel-body nopaddingtop">
          <hr>
          <div class="error"></div>
          <div class="form-group">
            <div class="col-sm-6 col-xs-12">
              <label class="control-label">Organogram Name <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <input type="text" name="Name" id="Name" value="<?php if(isset($organogram_data['Name'])){ echo $organogram_data['Name']; } ?>" class="form-control required" placeholder="Type module name..." required="" aria-required="true">
            </div>
            <!-- <div class="col-sm-3  col-xs-12">
              <label class="control-label">Select Table <span class="text-danger">*</span><span class="text-danger error"></span></label>
              <select id="Table" class="form-control select2" style="width: 100%" data-placeholder="Select Parent Module">
                  <option value="">&nbsp;</option>
                  <option value="0" selected="selected">Select Table</option>
                  <option value="employees">Employees</option>
                  <option value="designations">Designations</option>
                  <option value="departments">Departments</option>
                  <option value="locations">Locations</option>  
              </select>
            </div>  -->
          </div>
        </div>
      </div>
      <div class="panel"> 
        <div class="panel-body">
            <div id="orgChartContainer">
              <div id="orgChart"></div>
            </div>
            <div id="consoleOutput"> </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="">
            <button style="float: left;" class="btn btn-wide btn-success btn-quirk mr5"><i class="fa fa-check"></i> Submit</button>
            <button style="float: right; margin-left: 5px;"  onclick="load_view(this,'manage_organograms');" type="reset" class="btn btn-wide btn-danger btn-quirk"><i class="fa fa-times"></i> Cancel</button>
            <button  style="float: right;"  onclick="load_view(this,'form_edit_manage_organogram')" type="reset" class="btn btn-wide btn-default btn-quirk"><i class="fa fa-refresh"></i> Reset</button>
          </div>
        </div>
      </div>
    </form>
      <!-- panel -->

      <!-- panel -->

    </div><!-- contentpanel -->
  </div>
 
  <script type="text/javascript">
    
    $('.select2').select2();


      var testData = [];
  
      jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url('admin/get_organogram_childs'); ?>",
                    success: function (data) 
                    { 

                       testData = $.parseJSON(data); 
                        
                    }, 
                    async: false,  
                  }); 
    $(function(){ 
        //console.log(testData);
        org_chart = $('#orgChart').orgChart({
            data: testData,
            showControls: true,
            allowEdit: true,
            onAddNode: function(node){ 

                $.post("<?php echo base_url("admin/save_organogram_child"); ?>",{Parent_Id:node.data.id,Organogram_Id:<?= $data ?>},function(id){
                  org_chart.newNode(node.data.id,id); 
                }); 
                
            },
            onDeleteNode: function(node){ 
                org_chart.deleteNode(node.data.id); 
                $.post("<?php echo base_url("admin/delete_organogram_child"); ?>",{Id:node.data.id},function(id){ });
            },
            onClickNode: function(node){
                log('Clicked node '+node.data.id);
            }

        });
    });

    // just for example purpose
    function log(text){
        //$('#consoleOutput').append('<p>'+text+'</p>')
    }


 






    </script>