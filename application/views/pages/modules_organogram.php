
 
<div class="mainpanel">

    <div class="contentpanel">

     <div class="panel">
         
        <div class="panel-body">
            <div class="col-md-12">
              <div class="col-lg-7 col-md-6 col-sm-6">
                <h3><i class="fa fa-cubes"></i> Manage Modules</h3>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-3">
                 <!--  <select class="form-control selected_action"  onchange=" perform_group_action('application_modules',this)">
                      <option selected="selected">With Selected</option>
                      <option value="Delete">Delete</option>
                      <option value="Disable">Disable</option>
                      <option value="Enable">Enable</option>
                      <option value="Change_Status">Change Status</option> 
                  </select> -->
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2"> 
                <div class="btn-group pull-right people-pager">
                  <button class="btn btn-primary-active"  onclick="load_view(this,'modules');"><i class="fa fa-th"></i></button>
                  <a href="javascript:;" class="btn btn-default"  onclick="load_view(this,'modules_organogram');"><i class="fa fa-th-list"></i></a>
                </div>
              </div>
              <div class="col-lg-1 col-md-2 col-sm-2">
                <button onclick="load_view(this,'form_module')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 
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
      </div><!-- panel -->

      <!-- panel -->

    </div><!-- contentpanel -->
  </div>
 
  <script type="text/javascript">

      var testData = [];
  
      jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url('get_modules_array'); ?>",
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
                $.post("<?php echo base_url("save_module"); ?>",{Parent_Module:node.data.id},function(id){
                  org_chart.newNode(node.data.id,id); 
                }); 
                
            },
            onDeleteNode: function(node){ 
                org_chart.deleteNode(node.data.id); 
                $.post("<?php echo base_url("delete_module"); ?>",{Id:node.data.id},function(id){ });
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