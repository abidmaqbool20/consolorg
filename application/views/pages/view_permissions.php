 
<?php
  
  $permission_data = array();
  if($data != "")
  {
    $check_record = $this->db->get_where("roles",array("Deleted"=>0,"Id"=>$data,"Org_Id"=>$this->org_id));
    if($check_record->num_rows() > 0)
    {
      $permission_data = $check_record->result_array();
      $permission_data = $permission_data[0];
    }

  }
?>
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> View Organization Role</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'permissions')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
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
                    url: "<?php echo base_url('admin/get_permissions_array'); ?>",
                    data: {id:<?= $data; ?>},
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