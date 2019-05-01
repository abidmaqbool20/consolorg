 
<div class="mainpanel">
  <div class="contentpanel">
    <div class="panel">
      <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6">
            <h3><i class="fa fa-user"></i> View Organogram</h3>
          </div>
          <div class="col-md-6">
            <button onClick="load_view(this,'manage_organograms')" style="float: right;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
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
                    url: "<?php echo base_url('admin/get_organogram_childs'); ?>",
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