<div class="mainpanel">



  <div class="contentpanel">



   <div class="panel">

       

      <div class="panel-body">

          <div class="col-md-12">

            <div class="col-lg-7 col-md-6 col-sm-6">

              <h3><i class="fa fa-users"></i> Manage Organization Role's</h3>

            </div>

            <div class="col-lg-2 col-md-2 col-sm-3"> </div>

            <div class="col-lg-2 col-md-2 col-sm-2"> </div>

            <div class="col-lg-1 col-md-2 col-sm-2">

              <button onclick="load_view(this,'form_organization_role')" style="float: right;" class="btn btn-primary btn-quirk"><i class="fa fa-plus"></i>&nbsp; Add New</button> 

            </div>

          </div>

      </div>

    </div>



    <div class="panel"> 

      <div class="panel-body">

        <div class=""> 

          <div class="row">

            <?php

              $get_roles = $this->db->get_where("organization_roles",array("Deleted"=>0));

              if($get_roles->num_rows() > 0)

              {

                foreach ($get_roles->result() as $key => $value) {

                   

            ?>

            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12" id="row_<?= $value->Id; ?>">

              <div class="panel org_role">

                <div class="panel-heading">

                  <h4 class="panel-title" style="font-size: 18px;"><?= $value->Name; ?></h4>

                  <p><?= $value->Description; ?></p>

                </div>

                <div class="panel-body">

                  <button onclick="load_view(this,'view_role','<?= $value->Id; ?>')" class="btn btn-primary btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="View Role"><i class="fa fa-search-plus"></i></button>

                  <button onclick="load_view(this,'form_organization_role','<?= $value->Id; ?>')" class="btn btn-info btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Edit Role"><i class="fa fa-pencil"></i></button>

                  <button onclick="delete_record('organization_roles','<?= $value->Id; ?>',this)" class="btn btn-danger btn-stroke btn-icon tooltips mr5" data-toggle="toggle" title="" data-original-title="Delete Role"><i class="fa fa-trash"></i></button> 

                </div>

              </div>

            </div> 

          <?php }} ?>

          </div> 

        </div>

      </div>

    </div> 



   



  </div><!-- contentpanel -->

</div>



 