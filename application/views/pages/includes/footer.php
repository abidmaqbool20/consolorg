</section>

<script src="<?= ASSETSPATH; ?>lib/jquery-ui/jquery-ui.js"></script>
<script src="<?= ASSETSPATH; ?>lib/bootstrap/js/bootstrap.js"></script>
<script src="<?= ASSETSPATH; ?>lib/jquery-toggles/toggles.js"></script>

<script src="<?= ASSETSPATH; ?>lib/morrisjs/morris.js"></script>
<script src="<?= ASSETSPATH; ?>lib/raphael/raphael.js"></script>

<script src="<?= ASSETSPATH; ?>lib/flot/jquery.flot.js"></script>
<script src="<?= ASSETSPATH; ?>lib/flot/jquery.flot.resize.js"></script>
<script src="<?= ASSETSPATH; ?>lib/flot-spline/jquery.flot.spline.js"></script>

<script src="<?= ASSETSPATH; ?>lib/jquery-knob/jquery.knob.js"></script>

<script src="<?= ASSETSPATH; ?>js/quirk.js"></script>
<script src="<?= ASSETSPATH; ?>js/dashboard.js"></script>

<script src="<?= ASSETSPATH; ?>/lib/jquery-autosize/autosize.js"></script>
<script src="<?= ASSETSPATH; ?>/lib/timepicker/jquery.timepicker.js"></script>
<script src="<?= ASSETSPATH; ?>/lib/dropzone/dropzone.js"></script>
<script src="<?= ASSETSPATH; ?>/lib/bootstrapcolorpicker/js/bootstrap-colorpicker.js"></script>


<script src="<?= ASSETSPATH; ?>lib/datatables/jquery.dataTables.js"></script>
<script src="<?= ASSETSPATH; ?>lib/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="<?= ASSETSPATH; ?>lib/select2/select2.js"></script>

<script src="<?= ASSETSPATH; ?>js/jquery.orgchart.js"></script>

<script src="<?= ASSETSPATH; ?>/lib/summernote/summernote.js"></script>
<script src="<?= ASSETSPATH; ?>/lib/bootstrap3-wysihtml5-bower/bootstrap3-wysihtml5.all.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js"></script>
<script>

$(document).ready(function() { 
  $('#dataTable1').DataTable(); 
  $('select').select2({ minimumResultsForSearch: Infinity });
  $('#colorpicker1').colorpicker();
  $('.toggle').toggles();
});

</script>


</body>
 </html>