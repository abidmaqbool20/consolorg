
<script src="<?= ASSETSPATH; ?>js/organogram.js"></script> 
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
              <button onClick="save_organogram(<?php if(isset($organogram_data['Id'])){ echo $organogram_data['Id']; }else{ echo 0;} ?>,'organograms',<?= $this->org_id; ?>)" style="float: right;" class="btn btn-success btn-quirk"><i class="fa fa-check"></i>&nbsp;Save</button>
              <button onClick="load_view(this,'manage_organograms')" style="float: right; margin-right: 10px;" class="btn btn-warning btn-quirk"><i class="fa fa-arrow-left"></i>&nbsp; GO BACK</button>
            </div>
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div id="tree" style="overflow: auto !important;
    position: relative !important;
    height: 750px !important;
    padding-bottom: 100px !important;"> </div>
            </div>
          </div>
        </div>
      </div> 
  </div>
</div>
  <script type="text/javascript">
  
 
    $organogram_data = new Array({ id: 1, tags: ["ceo"], name: "XYZ", title: "CEO", img: "<?= base_url("assets/images/default-user.png"); ?>" });  
    
    if(0 < <?php if(isset($organogram_data['Id'])){ echo $organogram_data['Id']; }else{ echo 0;} ?>)
    {
      $id = <?php if(isset($organogram_data['Id'])){ echo $organogram_data['Id']; } ?>;
      jQuery.ajax({
                    type: "POST",
                    data: {"id":$id},
                    url: "<?php echo base_url("admin/get_organogram_data"); ?>",
                    success: function (data) 
                    {  
                      if(data && data != 'null')
                      {
                         
                        $organogram_data = JSON.parse(data); 

                      }
                        
                    }, 
                    async: false,  
                  });   
    }



    console.log($organogram_data);
    $template = "rony";
    var chart = new OrgChart(document.getElementById("tree"), {
        scaleInitial: BALKANGraph.match.boundary,
        enableDragDrop: true,
        template: "ula",
        menu: {
            svg: { text: "Export SVG" },
            csv: { text: "Export CSV" }
        },
        tags: {
            "assistant": {
                template: "ula"
            }
        },
        nodeMenu: {
            details: { text: "Details" },
            edit: { text: "Edit" },
            add: { text: "Add" },
            remove: { text: "Remove" }
        },
        nodeBinding: {
            field_0: "name",
            field_1: "title",
            img_0: "img"
        },
        nodes: $organogram_data

    });
 


    function save_organogram($id,$table,$org_id)
    {
      if($id > 0)
      {
          // var chart = new OrgChart(document.getElementById("tree"));
          $nodes = chart.getNodes();
          $.post("<?= base_url("admin/save_record") ?>",{"Edit_Recorde":$id,"Table_Name":$table,"Org_Id":$org_id,"Data":$nodes},
          function(response)
          {
            swal("Success","Organogram is saved successfully...","success");
          });
      }
    }
</script>

