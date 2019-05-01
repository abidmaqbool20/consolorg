 
<?php 

  $role_data = array();
  if($data != "")
  {
    $check_role = $this->db->get_where("Organogram",array("Deleted"=>0,"Id"=>$data));
    if($check_role->num_rows() > 0)
    {
      $role_data = $check_role->result_array();
      $role_data = $role_data[0];
    } 
  }

?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> View Organization Organogram</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'organization_organogram')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
          </div>
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


  </div>
</div>
<script type="text/javascript">

      var testData = [];
  
      jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url('admin/get_organization_organogram'); ?>",
                    data: {id:0},
                    success: function (data) 
                    {  
                       testData = $.parseJSON(data); 
                        
                    }, 
                    async: false,  
                  }); 

     
    $(function(){ 
        console.log(testData);
        org_chart = $('#orgChart').orgChart({
            data: testData,
            showControls: false,
            allowEdit: false,
            onAddNode: function(node){ 
               
                
            },
            onDeleteNode: function(node){ 
               
            },
            onClickNode: function(node){
               
            }

        });
    });

    // just for example purpose
    function log(text){
        //$('#consoleOutput').append('<p>'+text+'</p>')
    }
    </script>