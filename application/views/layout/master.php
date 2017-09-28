<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ReticManager - Client Access</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/spatialcss/bootstrap3.3.7.css') ?>">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>
<body>
<div id="header" style="width:100%;left:0;position:fixed;margin-top:0px;padding:0;z-index:2000;height:42px;background:url(<?php echo base_url('/assets/images/rmheaderimage1440x42.png') ?>) no-repeat;background-repeat:no-repeat;background-position:center top;-webkit-background-size:100% 100%;-moz-background-size:100% 100%;-o-background-size:100% 100%;background-size:100% 100%;text-align:left;">
	<div class="container-fluid">
		<div class="navbar-header">
			<ul class="nav navbar-nav">
			</ul>
		</div>					
	</div>
</div>
<div class="container" >
    <div class="row">
        <div class="col-md-6 col-md-offset-3" style="margin-top:45px;">
            <?php echo $this->session->flashdata('message'); ?>
        </div>
    </div>

    <div class="row">
        <?php $this->load->view($content); ?>
    </div>
</div>

</body>
</html>