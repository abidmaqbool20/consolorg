<!DOCTYPE html>
<html lang="en">

 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
   

  <title>Quirk Responsive Admin Templates</title>

  <link rel="stylesheet" href="<?= ASSETSPATH; ?>/lib/fontawesome/css/font-awesome.css">
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
  <link rel="stylesheet" href="<?= ASSETSPATH; ?>/css/quirk.css">

  <script src="<?= ASSETSPATH; ?>/lib/modernizr/modernizr.js"></script>
 
</head>

<body class="signwrapper">

  <div class="sign-overlay"></div>
  <div class="signpanel"></div>

  <div class="panel signin">
    <div class="panel-heading">
      <h1>CONSOL-HR</h1>
      <h4 class="panel-title">Welcome! Please signin.</h4>
    </div>
    <div class="panel-body">
      <!-- <button class="btn btn-primary btn-quirk btn-fb btn-block">Connect with Facebook</button>
      <div class="or">or</div> -->
      <div>
        <?= $this->session->flashdata("message"); ?> 
        <?php echo validation_errors(); ?>
      </div>
    
       <form action="<?php echo base_url('signin') ?>" method="post">
          <div class="form-group mb10">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
              <input type="text" class="form-control" placeholder="Enter Username" name="Username" id="Username" required="required">
            </div>
          </div>
          <div class="form-group nomargin">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input type="text" class="form-control" placeholder="Enter Password" name="Password" id="Password" required="required">
            </div>
          </div>
          <div><a href="#" class="forgot">Forgot password?</a></div>
          <div class="form-group">
            <button class="btn btn-success btn-quirk btn-block" type="submit">Sign In</button>
          </div>
        </form>
     
     
    </div>
  </div> 

</body>

 </html>
