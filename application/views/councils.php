<html>
<head>
    <title>ReticManager</title>
	<meta name="robots" content="noindex">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>ReticManager Solution</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/spatialcss/jquery.dataTables.yadcf.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/spatialcss/select2_review.css') ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css.map" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/fonts/fontawesome-webfont.eot" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/fonts/fontawesome-webfont.svg" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/fonts/fontawesome-webfont.ttf" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/fonts/fontawesome-webfont.woff" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/fonts/fontawesome-webfont.woff2" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/fonts/FontAwesome.otf" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/spatialcss/dependenciesUpdated2017_review.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/spatialcss/main-mimiUpdated2017_review.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/loaders.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('/assets/spatialcss/styledLayerControlUpdated2017.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/spatialcss/bootstrap-datepicker.min.css') ?>" />

    <script src="<?php echo base_url('/assets/spatialjs/bundleUpdate2017_review.js') ?>"></script>

	<script>
        var data_end_point = 'https://reticmanager.carto.com/api/v2/sql?q=';
    </script>
<style>
	td {word-wrap: break-word;
	 max-width: 120px;}
	 
	 .imageurl {
	 margin-bottom: 10px;
	 width: auto;
	 height:30px;
	 vertical-align:bottom
	 background:transparent url('imageurl.png') center top no-repeat;
	}

	.imageurl:hover {
		transform:scale(1.1,1.1);
	   background-image: url('twitterbird_hover.png');
	}
</style>
</head>
<body>

<h4>  <img  alt="reticmanager logo" src="<?php echo base_url('/assets/images/RMLogo215x40.png') ?>" style="width: 215px; height: 40px;"></h4>
<h3>Council List</h3>
<ul>
	<table id="council_list" class="display compact table-bordered table-striped table-responsive dataTable" role="grid" aria-describedby="council_list" cellspacing="0" width="95%"style="margin-left: 25px;">
		<thead>
			<tr>
				<td style="font-size:xx-small;">Council ID</td>
				<?php foreach ($councils as $council):?>
						<td><?php echo $council['id'];?></td>
				<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="font-size:xx-small;">Code</td>
				<?php foreach ($councils as $council):?>
						<td><a href="<?php echo base_url('view/'.$council['code']);?>" class="imageurl"><?php echo $council['code'];?></a></td>
				<?php endforeach;?>
			</tr>
			<tr>
				<td style="font-size:xx-small;">Org</td>
				<?php foreach ($councils as $council):?>
						<td><label style="font-size:xx-small;"><?php echo $council['name'];?></label></td>
				<?php endforeach;?>
			</tr>
			<tr>
				<td style="font-size:xx-small;">Logo</td>
				<?php foreach ($councils as $council):?>
						<td><a href="<?php echo base_url('view/'.$council['code']);?>" class="imageurl"><label><img class="imageurl" style="width: auto; height: 30px;padding-top:2px;padding-bottom:2px;vertical-align:bottom" src="<?php echo base_url(''.$council['logo_url']);?>"></label></a></td>
				<?php endforeach;?>
			</tr>
		</tbody>
	</table>
</ul>
<div class="form-group">
	<a href="<?php echo $this->config->base_url('/account/view_activity'); ?>">View Activity</a>
</div>
<h3>User List</h3>
<ul>
	<table id="user_list" class="display compact table-bordered table-striped table-responsive dataTable" role="grid" aria-describedby="user_list" cellspacing="0.5" width="95%"style="margin-left: 25px;">
		<thead>
			<tr>
					<td>ID</td>
					<td>First Name</td>
					<td>Last Name</td>
					<td>Email</td>
					<td>Organisation</td>
					<td>Position</td>
					<td>Phone</td>
					<td>Coucil ID</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user):?>
				<tr>
					<td><?php echo $user['id'];?></td>
					<td><?php echo $user['first_name'];?></td>
					<td><?php echo $user['last_name'];?></td>
					<td><?php echo $user['email'];?></td>
					<td><?php echo $user['organisation'];?></td>
					<td><?php echo $user['position'];?></td>
					<td><?php echo $user['phone'];?></td>
					<td><?php echo $user['council_id'];?></td>
				</tr>	
			<?php endforeach;?>
		</tbody>
	</table>
	<div id="user_list_footer"></div>			
	</div>
</ul>
<h3>Activity List</h3>
<ul>
	<table id="activity_list" class="display compact table-bordered table-striped table-responsive dataTable" role="grid" aria-describedby="activity_list" cellspacing="0.5" width="95%"style="margin-left: 25px;">
		<thead>
			<tr>
					<td>ID</td>
					<td>First Name</td>
					<td>Last Name</td>
					<td>Email</td>
					<td>URL</td>
					<td>IP Address</td>
					<td>timestamp</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($activities as $activity):?>
				<tr>
					<td><?php echo $activity['id'];?></td>
					<td><?php echo $activity['first_name'];?></td>
					<td><?php echo $activity['last_name'];?></td>
					<td><?php echo $activity['email'];?></td>
					<td><?php echo $activity['url'];?></td>
					<td><?php echo $activity['ip_address'];?></td>
					<td><?php echo $activity['timestamp'];?></td>
					
				</tr>	
			<?php endforeach;?>
		</tbody>
		<tfoot id="activity_list_footer">
			<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
			</tr>
		</tfoot>
	</table>
	<div id="activity_list_footer_ext"></div>			
	</div>
</ul>
		
        <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
		<script src="<?php echo base_url('/assets/spatialjs/select2_review.js') ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/jquery.dataTables.yadcf_review.js') ?>"></script>
		<script src="<?php echo base_url('/assets/spatialjs/councils.js') ?>"></script>

        <script type="text/javascript" src="<?php echo base_url('/assets/spatialjs/bootstrap-datepicker.min.js') ?>"></script>
</body>
</html>