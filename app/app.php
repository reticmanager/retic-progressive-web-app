<!DOCTYPE html>
<html>

<head>
    <meta name="robots" content="noindex">
    <meta http-equiv="cache-control" content="max-age=259200" />
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

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/spatialcss/dependenciesUpdated2017_review.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/spatialcss/main-mimiUpdated2017_review.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/loaders.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('/assets/spatialcss/styledLayerControlUpdated2017.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/spatialcss/bootstrap-datepicker.min.css') ?>" />

    <script src="<?php echo base_url('/assets/spatialjs/bundleUpdate2017_review.js') ?>"></script>

    <script>
        var data_end_point = 'https://reticmanager.carto.com/api/v2/sql?q=';
        //Dynamic Column Headers
        var survey_table_name = '#survey-header-table',
            code = '<?php echo $council->code  ?>',
            codecenter = [ <?php echo $council->center_latitude ?> , <?php echo $council->center_longitude ?> ],
            columns,
            colObj,
            str;
        var googleSatbaseLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxNativeZoom: 20,
            maxZoom: 23,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });
        var esribaseLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxNativeZoom: 19,
            maxZoom: 24,
            subdomains: ['server', 'services']
        });

        var defaultMapLayer = <?php echo $council->default_map_layer ?> ,
            defaultMapName = '<?php echo $council->default_map_name ?>';
    </script>
    <link rel="stylesheet" href="../assets/css/webapp.css">
</head>

<body>

    <div class="loading" style="display:none;">
        <div class="loader-inner ball-pulse">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <b>Adjusting map...</b>
        <p>Please wait while map and data load.</p>
    </div>
    <div class="loadingnoline" style="display:none;">
        <div class="loader-inner ball-pulse">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <b>Your selection may not be able to be plotted,</b>
        <p> please see if your selection has a plan ...</p>
    </div>
    <div class="input-daterange input-group" id="datepicker" style="display:none">
        <input type="text" class="input-sm form-control" name="start" />
        <span class="input-group-addon">to</span>
        <input type="text" class="input-sm form-control" name="end" />
    </div>

    <div class="container-fluid no-padding">
        <div id="wrapper">
            <div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">�</a>
            </div>
            <div id="header">
                <div class="container-fluid no-padding">
					<nav class="navbar navbar-default" role="navigation">
					  <div class="container-fluid">
						<div class="navbar-header">
						  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#retic-navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						  </button>
						  <a class="navbar-brand" href="https://www.reticmanager.com"><img alt="reticmanager logo" src="<?php echo base_url('/assets/images/RMLogo215x40.png') ?>" ></a>
						</div>

						<div class="collapse navbar-collapse" id="retic-navbar-collapse">
						  <ul class="navigation nav navbar-nav navbar-right">
							<li class="navigation-padding navigation-text" >
								<h5 title="Start again" id="home-button"><span onclick="resetPage()">home</span></h5>
							</li>
							<li class="navigation-padding navigation-text">
								<h5 title="Field data capture" id="webnav" v-on:click="hideUI"><span >webapp</span></h5>
							</li>
							<li class="navigation-padding navigation-text">
								<a href="mailto:info@reticmanager.com"></a>
								<h5 title="For support via email"  id="support-button"><a href="mailto:info@reticmanager.com"></a><a href="mailto:info@reticmanager.com" >support</a></h5>
							</li>
							<li class="navigation-padding navigation-text">
								<h5 ><span  id="upload-button" title="Upload data to ReticManager" onclick="logout()">upload</span></h5>
							</li>
							<li class="navigation-padding navigation-text dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><h5  id="view-button">view<b class="caret"></b></h5></a>
								<ul class="dropdown-menu" style="display: none; width: 140px; right: 0px;">
									<div class="navigation-text-simple menu-item-radio" id="lblac-split-5050">
										<input type="checkbox" name="split-5050-view" class="regular-checkbox split-5050" id="split-5050" style="margin-left: 15px;">
										<label for="split-5050"><span style="display: inline-block;"> Split 50/50</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-split-7525">
										<input type="checkbox" name="split-7525-view" class="regular-checkbox split-7525" id="split-7525" style="margin-left: 15px;">
										<label for="split-7525"><span style="display: inline-block;"> Split 75/25</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-map-only">
										<input type="checkbox" name="map-only-view" class="regular-checkbox map-only" id="map-only" style="margin-left: 15px;">
										<label for="map-only"><span style="display: inline-block;"> Map</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-table-only">
										<input type="checkbox" name="table-only-view" class="regular-checkbox table-only" id="table-only" style="margin-left: 15px;">
										<label for="table-only"><span style="display: inline-block;"> Table</span>
										</label>
									</div>
								</ul>
							</li>
							<li class="navigation-padding navigation-text dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><h5 title="Show Legend" id="legend-button">legend<b class="caret"></b></h5></a>
								<ul class="dropdown-menu" style="display: none; width: 275px; right: 0px;">
									<li>
										<h5 class="modal-title wastecolor" style="text-align: center;" id="myModalLabel">ReticManager - Legend</h5>
									</li>
									<li>
										<div> <img src="<?php echo base_url('/assets/images/legend_v3.1.png') ?>" alt="online solution legend" style="width: auto; height: auto; padding: 2px">
										</div>
									</li>

								</ul>
							</li>
							<li class="navigation-padding navigation-text dropdown">
								<a href="<?php echo base_url('/account/logout') ?>"></a>
								<h5  title="Logout of ReticManager" id="logout-button1"><a href="<?php echo base_url('/account/logout') ?>"></a><a href="<?php echo base_url('/account/logout') ?>">logout</a></h5>
							</li>
							<li id="menu-dropdown" class="navigation-padding navigation-text dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><h5  title="ReticManager Menu - Select a network to start" id="menu-button">menu<b class="caret"></b></h5></a>
								<ul class="Retic-menu dropdown-menu " style="display: none;">
									<li class="no-padding">
										<h5 class="modal-title wastecolor" style="text-align: center;font-size: 1.0em;">ReticManager v3.1 Menu</h5>
									</li>
									<li class="no-padding">
										<hr>
									</li>
									<li class="no-padding">
										<h5 class="wastecolor" style="text-align: center;font-size: 0.875em;">SURVEY DATA</h5>
									</li>
									<li class="no-padding divider"></li>
									<span><h5 style="text-align:center;"><b class="wastecolor">Sewer</b> &nbsp;&nbsp; <b class="stormcolor">Storm</b></h5></span>
									<li class="no-padding"><button id="sewer-button1" title="CCTV Inspections" <?php if ($council->sewer1 == '1') { ?> disabled<?php } ?> class="btn btn-default One" style="margin-left: 35px;">CCTV</button><button id="storm-button1" title="CCTV Inspections" <?php if ($council->storm1 == '1') { ?> disabled<?php } ?> class="btn btn-default Two">CCTV</button></li>
									<li class="no-padding"><button id="sewer-button2" title="Manhole/Node Inspections" <?php if ($council->sewer2 == '1') { ?> disabled<?php } ?> class="btn btn-default Three" style="margin-left: 35px;">MANHOLE</button><button id="storm-button2" title="Manhole/Node Inspections" <?php if ($council->storm2 == '1') { ?> disabled<?php } ?> class="btn btn-default Four">MANHOLE</button></li>
									<li class="no-padding"><button id="sewer-button3" title="Infiltration/Inflow Inspections" <?php if ($council->sewer3 == '1') { ?> disabled<?php } ?> class="btn btn-default Five" style="margin-left: 35px;">I / I</button><button id="storm-button3" title="Outlet Node Inspections" <?php if ($council->storm3 == '1') { ?> disabled<?php } ?> class="btn btn-default Six">OUTLET</button></li>
									<li class="no-padding">
										<input type="checkbox" name="networkcheck" class="regular-checkbox networkcheck" value="sw" id="network" style="display: none;">
									</li>
									<li class="no-padding divider"></li>
									<li class="no-padding">
										<button id="evaluation-reports-button" class="Retic-menu-buttons btn btn-default"  >Evaluation Reports</button>
									</li>
									<li class="no-padding navigation-text-simple divider"></li>
									<div class="navigation-text-simple menu-item-radio" id="lblac-Evaluation1">
										<input type="checkbox" name="gis_evaluation" class="regular-checkbox giseval" id="gis" style="margin-left: 15px;">
										<label for="gis"><span style="display: inline-block;"> GIS Amendments</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-Evaluation2">
										<input type="checkbox" name="ip_evaluation" class="regular-checkbox ipeval" id="ip" style="margin-left: 15px;">
										<label for="ip"><span style="display: inline-block;"> Infiltration Observations</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-Evaluation3">
										<input type="checkbox" name="prolat_evaluation" class="regular-checkbox prolateval" id="prolat" style="margin-left: 15px;">
										<label for="prolat"><span style="display: inline-block;"> Protruding Laterals</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-Evaluation4">
										<input type="checkbox" name="root_evaluation" class="regular-checkbox rooteval" id="root" style="margin-left: 15px;">
										<label for="root"><span style="display: inline-block;"> Root Removal</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-Evaluation5">
										<input type="checkbox" name="mhaccess_evaluation" class="regular-checkbox mhaccesseval" id="mhaccess" style="margin-left: 15px;">
										<label for="mhaccess"><span style="display: inline-block;"> Manhole Improvements</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-Evaluation6">
										<input type="checkbox" name="remedial_evaluation" class="regular-checkbox remedialeval" id="remedial" style="margin-left: 15px;">
										<label for="remedial"><span style="display: inline-block;"> Remedial Works</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-Evaluation7">
										<input type="checkbox" name="cctv_evaluation" class="regular-checkbox cctveval" id="cctv" style="margin-left: 15px;">
										<label for="cctv"><span style="display: inline-block;"> CCTV Re-survey</span>
										</label>
									</div>
									<div class="navigation-text-simple menu-item-radio" id="lblac-Evaluation8">
										<input type="checkbox" name="renewal_evaluation" class="regular-checkbox renewaleval" id="renewal" style="margin-left: 15px;">
										<label for="renewal"><span style="display: inline-block;"> Renewal Works</span>
										</label>
									</div>


									<li class="no-padding divider"></li>
									<!-- <li><h5  style="display: inline-block;margin-left: 15px;">Reports</h5></li> -->
									<li class="no-padding">
										<button id="subdivision-reports-button" class="Retic-menu-buttons btn btn-default" >subdivision compliance reports</button>
									</li>
									<li class="no-padding divider"></li>
									<div class="navigation-text-simple menu-item-radio" id="lblac-Subdivision">
										<input type="checkbox" name="subdivisions" class="regular-checkbox subdivisions" id="subdivisions" style="margin-left: 15px;">
										<label for="subdivisions"><span style="display: inline-block;"> Subdivisions</span>
										</label>
									</div>
									<!-- 	<li><button id="evaluation-reports-button" class="btn btn-default" style=" padding-left: 4px; padding-right: 4px; padding-top: 2px; padding-bottom: 2px; margin-left: 15px;">Evaluation Reports</button></li>
									<li><button id="subdivision-reports-button" class="btn btn-default" style=" padding-left: 4px; padding-right: 4px; padding-top: 2px; padding-bottom: 2px; margin-left: 15px;">subdivision compliance reports</button></li> -->
								</ul>
							</li>
						  </ul>
						</div><!-- /.navbar-collapse -->
					  </div><!-- /.container-fluid -->
					</nav>
				</div>
            </div>
            <!-- #header -->

            <!-- /.navbar.navbar-default -->
            <!-- /.well -->
              <div class="well search">
                  <!-- /.col-md-13 -->
                  <div class="col-md-12">
                    <div id="vue-map">
                      <div v-if="map" id="map" class="leaflet-container leaflet-fade-anim" tabindex="0" >
                          <div class="leaflet-map-pane" style="transform: translate3d(0px, 9px, 0px);">
                          </div>
                          <!-- /leaflet-map-pane> -->
                          <div class="leaflet-control-container">
                              <div class="leaflet-top leaflet-left"></div>
                              <div class="leaflet-top leaflet-right"></div>
                              <div class="leaflet-bottom leaflet-left"></div>
                              <div class="leaflet-bottom leaflet-right"></div>
                          </div>
                          <!-- /leaflet-control-container> -->
                          <div class="cartodb-infowindow" style="display: none;">
                              <div class="cartodb-popup v2" style="width: 260px; max-width: 300px;"><a href="https://www.reticmanager.com" class="cartodb-popup-close-button close">x</a>
                                  <div class="cartodb-popup-content-wrapper">
                                      <div class="cartodb-popup-content" style="height: 300px; max-height: 400px;"></div>
                                  </div>
                                  <div class="cartodb-popup-tip-container"></div>
                              </div>
                          </div>
                          <!-- /cartodb-infowindow -->
                      </div>
                    </div>

                      <!-- leaflet-container leaflet-fade-anim -->
                  </div>
                  <!-- /.col-md-13 -->

              </div>
              <!-- /.well -->

              <div id="table-container" class="table-responsive">
                  <table>
                  </table>
                  <div id="footer"></div>
              </div>
              <!-- /.wrapper -->
              <div id="vue-help">
                <div v-if="help" style="max-width:1200px; margin:0 auto;" class="inner"><img id="instructions" src="<?php echo base_url('/assets/images/basic_instructions.gif') ?>" alt="basic instrctions gif image" style="width: 95%;max-width: 1100px;  display: none">
                </div>
              </div>

              <div id="vue-cont">
                <div v-if="layout">
                  <layout-cont></layout-cont>
                </div>
              </div>
        </div>
      </div>
        <!-- /.container -->

        <!-- Modals -->
        <!-- Start Submit Form -->
        <div class="modal fade" id="contact_dialog" role="dialog" style="top: 30px; left: 10px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Upload Details</h4>
                    </div>
                    <div class="modal-body">
                        <form id="contact_form" method="POST">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label class="control-label" style="margin-left: 5px;">Name</label>
                                        <input type="text" class="form-control" required="required" placeholder="first & last name" id="form_name" name="name" style="margin-left: 5px;">
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label" style="margin-left: 15px;">Email</label>
                                        <input type="email" class="form-control" required="required" placeholder="email@company" id="form_email" name="email" style="margin-left: 15px;">
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label" style="margin-left: 25px;">Company</label>
                                        <input type="text" class="form-control" required="required" placeholder="company name" id="form_company" name="company" style="margin-left: 25px;">
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label"></label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label class="control-label" style="margin-left: 5px;">Project Name</label>
                                        <input type="text" class="form-control" rows="1" required="required" placeholder="project name / area" id="form_project_name" name="project_name" style="margin-left: 5px;">
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label" style="margin-left: 15px;">Project Reference</label>
                                        <input type="text" class="form-control" rows="1" placeholder="project reference" id="form_project_reference" name="project_reference" style="margin-left: 15px;">
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label" style="margin-left: 25px;">Expected Project Mtrs</label>
                                        <input type="text" class="form-control" rows="1" required="required" placeholder="0 mtrs" id="form_project_meterage" name="project_meterage" style="margin-left: 25px;">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label class="control-label" style="margin-left: 5px;">Council Contact Name</label>
                                        <input type="text" class="form-control" rows="1" placeholder="contact name" id="form_council_contact" name="council_contact" style="margin-left: 5px;">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="inspection_purpose" style="margin-left: 15px;">Inspection Purpose</label>
                                        <select class="form-control" id="inspection_purpose" name="inspection_purpose" style="margin-left: 15px;">
                                            <option>Planned Project</option>
                                            <option>Subdivision</option>
                                            <option>Operations & Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-11">
                                        <label class="control-label" style="margin-left: 5px;">Upload Description</label>
                                        <textarea class="form-control" rows="2" style="margin-left: 5px;resize: vertical;" required="required" placeholder="please provide a short decription of what is being uploaded, &#10; &#42;note if uploading from a media disc/device this must remain available till upload has completed" id="form_description" name="upload_description"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-1">
                                        <label class="control-label" style="margin-left: 5px;">Upload Mtrs</label>
                                        <input type="text" class="form-control" rows="1" placeholder="0 mtrs" id="form_upload_meterage" name="upload_meterage" style="margin-left: 5px;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-1">
                                        <label class="control-label" style="margin-left: 5px;">No. of Video files</label>
                                        <input type="text" class="form-control" rows="1" placeholder="0" id="no_of_video_files" name="no_of_video_files" style="margin-left: 5px;">
                                    </div>
                                    <div class="col-sm-1">
                                        <label class="control-label" style="margin-left: 5px;">No. of Data files</label>
                                        <input type="text" class="form-control" rows="1" placeholder="0" id="no_of_data_files" name="no_of_data_files" style="margin-left: 5px;">
                                    </div>
                                    <div class="col-sm-1">
                                        <label class="control-label" style="margin-left: 5px;">No of Image files</label>
                                        <input type="text" class="form-control" rows="1" placeholder="0" id="no_of_image_files" name="no_of_image_files" style="margin-left: 5px;">
                                    </div>
                                    <div class="col-sm-1">
                                        <label class="control-label" style="margin-left: 5px;">No. of Plan files</label>
                                        <input type="text" class="form-control" rows="1" placeholder="0" id="no_of_plan_files" name="no_of_plan_files" style="margin-left: 5px;">
                                    </div>
                                </div>
                            </div>


                            <!--                         <form id="contact_form" action="process.php" method="POST">
								Name: <input type="text" name="name"><br/>
								Email: <input type="text" name="email"><br/>
								Upload Description: <input type="text" name="upload_description"><br/>
							</form> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="submitForm" class="btn btn-default">Send</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Submit Form -->
        <!--Evaluation Report Window -->
        <div class="modal fade" style="z-index:100000;" id="evalReportsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Evaluation Reports</h4>
                    </div>
                    <div class="modal-body">
                        <table align="center" id="evaluation-report-table" class="display table-hover table-bordered table-striped table-responsive dataTable" cellspacing="0" width="95%">
                            <thead>
                                <tr>
                                    <th>report_number</th>
                                    <th>workblock</th>
                                    <th>network</th>
                                    <th>reference</th>
                                    <th>report_date</th>
                                    <th>evaluation_report</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="eval-close-button-1" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Subdivision Compliance Report Window -->
        <div class="modal fade" style="z-index:100000;" id="subdivisionReportsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Subdivision CCTV IDC Compliance Audit Reports</h4>
                    </div>
                    <div class="modal-body">
                        <table align="center" id="subdivision-report-table" class="display table-hover table-bordered table-striped table-responsive dataTable" cellspacing="0" width="95%">
                            <thead>
                                <tr>
                                    <th>report_number</th>
                                    <th>workblock</th>
                                    <th>network</th>
                                    <th>reference</th>
                                    <th>report_date</th>
                                    <th>compliance_report</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="subdivision-close-button-1" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
		<!--Data Files Window -->
        <div class="modal fade" style="z-index:100000;" id="dataFilesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">AMIS Data Files</h4>
                    </div>
                    <div class="modal-body">
                        <table align="center" id="data-files-table" class="display table-hover table-bordered table-striped table-responsive dataTable" cellspacing="0" width="95%">
                            <thead>
                                <tr>
                                    <th>data_file_number</th>
                                    <th>workblock</th>
                                    <th>network</th>
                                    <th>reference</th>
                                    <th>data_file_date</th>
                                    <th>data_file</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="datafiles-close-button-1" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



        <!--Initial Start Window -->
        <div class="modal fade" style="width: 100%; z-index:100000;" id="startModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h4 class="modal-title" id="myModalLabel">ReticManager - Online Solution</h4>
                        <div class="module workflow-form active">

                            <form style="max-width: 100%;">
                                <div class="form-container bound-visible" style="margin-top: 5px;">
                                    <p class="form-item form-desc bound-invisible"></p>
                                    <div class="form label-left" style="max-width: 100%;">
                                        <div class="form-item required">
                                            <div class="image bound-visible">
                                                <div class="inner"> <img src="<?php echo base_url('/assets/images/RMLogo215x40.png') ?>" alt="reticmanager logo" style="width: auto; height: 30px;"> &nbsp; &nbsp;&nbsp;<img src="<?php echo base_url($council->logo_url) ?>" alt=" client logo" style="width: auto; height: 50px;"> </div>
                                            </div>
                                        </div>
                                        <div class="form-item required">
                                            <div class="container bound-visible" style="margin-top: 5px;">
                                                <div class="form-container bound-visible">
                                                    <h5 class="form-title bound-invisible">ReticMananger Disclaimer</h5>
                                                    <p class="form-item form-desc bound-visible">Terms and Conditions
                                                        <br>By proceeding and using the ReticManager Online Solution, you are agreeing to ReticManager terms and conditions as stated here <a href="https://reticmanager.com/termsandconditions/" target="_blank" title="ReticManager terms and conditions">reticmanager.com/termsandconditions</a>
                                                        <br>
                                                        <br>GIS and Spatial Locations
                                                        <br>ReticManager accepts no responsibility for any location and information inaccuracy shown through online solution, Information shown on plans is indicative only. Users of this system are responsible to ensure that the data accessed and used is appropriate and applicable to the end use intended.
                                                        <br>GIS data is sourced from the client and updated periodically. GIS updates are welcome and if you would like to provide us with an update, send an email to <a href="mailto:info@reticmanager.com?subject=GIS Update">info@reticmanager.com</a>
                                                    </p>
                                                    <div class="form label-left" style=""></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <br>
                                <button type="button" id="startup-close-button-1" class="btn btn-default" onclick="Agree()" data-dismiss="modal">Agree and Proceed</button>

                                <div class="form-item required">
                                    <div class="container bound-visible" style="margin-top: 5px;">
                                        <div class="form-container bound-visible">
                                            <h5 class="form-title bound-invisible">Basic Instructions</h5>
                                            <p class="form-item form-desc bound-visible">To Start using ReticManager Online Solution you must select a network via the menu
                                                <br>This is shown below, it should be located at the top right of your screen when this window is closed. Please see reference images below.
                                                <br>

                                            </p>
                                            <div class="image bound-visible">
                                                <div class="inner"><img src="<?php echo base_url('/assets/images/basic_instructions.gif') ?>" alt="basic instructions gif image" style="width: 95%;max-width: 1100px;">
                                                </div>
                                            </div>
                                            <div class="form label-left" style=""></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script src="../assets/js/webapp.js" type="text/javascript"></script>

<script type="text/x-template" id="form-layout">
  <div class="row cont-center">
    <div class="col-md-12 col-margins">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div class="form-group center">
            <h4>Details</h4>
            <table class="table table-sm table-hover table-striped table-bordered">
              <tbody>
                <tr class="custom-height">
                  <th scope="row">
                    <h5>Client Number:</h5>
                  </th>
                  <td>
                    <h5># 2001</h5>
                  </td>
                </tr>
                <tr class="custom-height">
                  <th scope="row">
                    <h5>ID Number:</h5>
                  </th>
                  <td>
                    <h5># 5001</h5>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div class="form-group center">
            <h4>Inspection</h4>
            <select class="form-control custom-height">
            <option>Manhole</option>
            <option>Smoke</option>
            <option>Connections</option>
            <option>I / I</option>
          </select>
          </div>
        </div>
      </div>
      <div class="row table-margin-top">
        <div class="col-md-6 col-md-offset-3">
          <div class="form-group center">
            <table class="table table-sm table-hover table-striped table-bordered">
              <tbody>
                <tr class="custom-height">
                  <th scope="row">
                    <button @click="getDate" type="button" class="btn btn-primary webapp-buttons">
                    <i class="fa fa-clock-o fa-size" aria-hidden="true"></i>
                    <p>
                      <span class="">Date / Time</span>
                    </p>
                  </button>
                  </th>
                  <td class=" custom-td-width">
                    <h5>{{ date }}</h5>
                  </td>
                </tr>
                <tr class="custom-height">
                  <th scope="row">
                    <button @click="getLocation" type="button" class="btn btn-primary webapp-buttons">
                    <i class="fa fa-globe fa-size" aria-hidden="true"></i>
                    <p>
                      <span class="">GPS Co-ordinates</span>
                    </p>
                  </button>
                  </th>
                  <td class=" custom-td-width">
                    <h5>Latitude: {{ lat }}</h5>
                    <h5>Longitude: {{ long }}</h5>
                  </td>
                </tr>
                <tr class="custom-height">
                  <th scope="row">
                    <div class="input-group">
                      <label class="input-group-btn">
                      <span class="btn btn-primary webapp-buttons" style="border-radius: 4px;">
                            <i class="fa fa-camera fa-size image-input-icons" aria-hidden="true"></i><p><span class="">Image 1</span></p>
                        <input id="inputOne" ref="inputOne" v-on:change="imageOne" style="display: none;" type="file" accept="image/*">
                        </span>
                      </label>
                    </div>
                  </th>
                  <td class=" custom-td-width">
                    <h5>Name: {{ images[0] }}</h5>
                    <h5>Size: {{ images[1] }}</h5>
                  </td>
                </tr>
                <tr class="custom-height">
                  <th scope="row">
                    <div class="input-group">
                      <label class="input-group-btn">
                          <span class="btn btn-primary webapp-buttons" style="border-radius: 4px;">
                                <i class="fa fa-camera fa-size image-input-icons" aria-hidden="true"></i><p><span class="">Image 2</span></p>
                            <input id="inputTwo" ref="inputTwo" v-on:change="imageTwo" style="display: none;" type="file" accept="image/*">
                            </span>
                          </label>
                    </div>
                  </th>
                  <td class=" custom-td-width">
                    <h5>Name: {{ images[2] }}</h5>
                    <h5>Size: {{ images[3] }}</h5>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</script>

        <script>
            function splitview() {
                $("#map").height(450);
            }

            function mapview() {
                $("#map").height(700);
            }

            function tableview() {
                $("#map").height(0);
            }

            function resetPage() {
                location.reload();
            }

            function Agree() {
                document.getElementById("instructions").style.display = 'inline-block';
            }

            function openNav() {
                document.getElementById("mySidenav").focus();
            }

            function closeNav() {
                document.getElementById("mySidenav").blur();
            }

            function closecustomInfoWindow() {
                    document.getElementById("customInfoWindow").style.display = 'none';
                }

            function logout() {
                $("#contact_dialog").modal('show');
            }
        </script>
        <script>
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-74860057-1', 'auto');
            ga('send', 'pageview');
        </script>


        <!-- HTML template for custom infowindow -->
        <div id="customInfoWindow">
            <a href="" class="cartodb-popup-close-button close" onclick="closecustomInfoWindow(); return false;" style="font-size: 15px; margin-top: -2px; margin-right:5px">x</a>
            <ul class="nav nav-pills"></ul>

            <div class="tab-content clearfix">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#survey"> SURVEY </a>
                    </li>
                    <li><a data-toggle="tab" href="#start"> START </a>
                    </li>
                    <li><a data-toggle="tab" href="#finish"> FINISH </a>
                    </li>
                    <li><a data-toggle="tab" href="#gis"> GIS </a>
                    </li>
                </ul>
            </div>
        </div>

        <div id="hoverWindow" style="display: none; cursor: pointer;">obs</div>
        <div id="OutlethoverWindow" style="display: none; cursor: pointer;">outlet</div>
        <div id="CCTVhoverWindow" style="display: none; cursor: pointer;">cctv</div>

        <script type="infowindow/html" id="obs_infowindow_template">
            <div class="infowindow-custom">
                <a href="" class="cartodb-popup-close-button close" style="margin-right:5px">x</a>
                <div class="cartodb-popup-content">
                    <div class="content" style="padding:5px">
                        <h4 class="obs-title">OBSERVATION</h4>
                        <p>
                            <h5 class="obs-title">Code</h5>
                        </p>
                        <p>{{code}}, {{sev_code}}</p>
                        <p>
                            <h5 class="obs-title">Description</h5>
                        </p>
                        <p>{{description}}</p>
                        <p><img width="240px" height="180px" src="{{photo_link}}" />
                        </p>
                    </div>
                </div>
                <div class="cartodb-popup-tip-container"></div>
            </div>
        </script>
        <script type="infowindow/html" id="cctv_lines_infowindow_template">
            <div class="infowindow-custom" id="cctv_lines_infowindow_div" style="width:350px">
                <div class="cartodb-popup-header" style="text-align: center;">
                    <h4 class="obs-title" style="width: 100%;align-content: center;padding: 1px;padding-right: 0px;margin-top: 0px;margin-left: -10px;background-color: rgba(255,255,155,.8);text-align:center;font-weight: bold;">CCTV SURVEY</h4>
                    <br>
                </div>

                <div class="cartodb-popup-content" style=" cursor: pointer;">
                    <div class="content" style="padding:0px">

                        <table align="center" id="cctv_infowindow_table" class="display compact table-hover  dataTable" style="padding:0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Asset ID:</h5>
                                    </td>
                                    <td><b><u>{{asset_id}}</u></b>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Location:</h5>
                                    </td>
                                    <td>{{start_street_no}} {{start_street_name}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Workblock No:</h5>
                                    </td>
                                    <td>{{work_block_no}}</td>
                                    <td>
                                        <h5 class="obs-title">Survey ID:</h5>
                                    </td>
                                    <td><b><u>{{survey_id}}</u></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Start Node:</h5>
                                    </td>
                                    <td>{{start_node}}</td>
                                    <td>
                                        <h5 class="obs-title">Finish Node:</h5>
                                    </td>
                                    <td>{{finish_node}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Survey Date:</h5>
                                    </td>
                                    <td>{{survey_date}}</td>
                                    <td>
                                        <h5 class="obs-title">Survey Length:</h5>
                                    </td>
                                    <td>{{survey_length}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Material:</h5>
                                    </td>
                                    <td>{{survey_mat}}</td>
                                    <td>
                                        <h5 class="obs-title">Diameter:</h5>
                                    </td>
                                    <td>{{survey_dia}}</td>
                                </tr>
                            </thead>
                        </table>
                        <table align="center" id="cctv_infowindow_table_condition" class="display compact" style="padding:0px;text-align: center;margin-left: -7px;" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td style="border-bottom: 1px solid;"></td>
                                    <td style="border-bottom: 1px solid;">
                                        <h5 class="obs-title">Pipe Condition</h5>
                                    </td>
                                    <td style="border-bottom: 1px solid;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">ECR:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">STR Grd:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">SER Grd:</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ecr_grade}}</td>
                                    <td>{{str_grade}}</td>
                                    <td>{{ser_grade}}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid;"></td>
                                    <td style="border-bottom: 1px solid;">
                                        <h5 class="obs-title">Survey Files</h5>
                                    </td>
                                    <td style="border-bottom: 1px solid;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Report:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Video:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Plan:</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{#report_url}}<a href="{{report_url}}" target="_blank">report</a>{{/report_url}} {{^report_url}}no report{{/report_url}}</td>
                                    <td>{{#video_url}}<a href="{{video_url}}" target="_blank">video</a>{{/video_url}} {{^video_url}}no video{{/video_url}}</td>
                                    <td>{{#plan_url}}<a href="{{plan_url}}" target="_blank">plan</a>{{/plan_url}} {{^plan_url}}no plan{{/plan_url}}</td>
                                </tr>
                            </thead>
                        </table>
                        <table align="center" id="cctv_infowindow_evaluation" class="display compact" style="padding:0px;text-align: left;margin-left: -7px;" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    {{#evaluation_comments}}
                                    <tr>
                                        <td>
                                            <h5 class="obs-title">Evaluation Comments:</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{evaluation_comments}}</td>
                                    </tr>
                                    {{/evaluation_comments}} {{^evaluation_comments}} {{/evaluation_comments}} {{#ip_eval}}
                                    <tr>
                                        <td>
                                            <h5 class="obs-title">Infiltration Present:</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ip_eval}}</td>
                                    </tr>
                                    {{/ip_eval}} {{^ip_eval}} {{/ip_eval}} {{#gis_eval}}
                                    <tr>
                                        <td>
                                            <h5 class="obs-title">GIS Amendments:</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{gis_eval}}</td>
                                    </tr>
                                    {{/gis_eval}} {{^gis_eval}} {{/gis_eval}}
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
                <div class="cartodb-popup-tip-container"></div>
            </div>
        </script>
        <script type="infowindow/html" id="node_inspections_infowindow_template">
            <div class="infowindow-custom" id="node_inspections_infowindow_div" style="width:350px; height:500px;">
                <div class="cartodb-popup-header" style="text-align: center;">
                    <h4 class="obs-title" style="width: 100%;align-content: center;padding: 1px;padding-right: 0px;margin-top: 0px;margin-left: -10px;background-color: rgba(255,255,155,.8);text-align:center;font-weight: bold;">NODE INSPECTION</h4>
                    <br>
                </div>
                <div class="cartodb-popup-content" style=" cursor: pointer;">
                    <div class="content" style="padding:0px">

                        <table align="center" id="node_inspection_infowindow_table" class="display compact table-hover  dataTable" style="padding:0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Asset ID:</h5>
                                    </td>
                                    <td><b><u>{{asset_id}}</u></b>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Location:</h5>
                                    </td>
                                    <td>{{street_name}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Workblock No:</h5>
                                    </td>
                                    <td>{{work_block_no}}</td>
                                    <td>
                                        <h5 class="obs-title">Survey ID:</h5>
                                    </td>
                                    <td><b><u>{{survey_id}}</u></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Survey Date:</h5>
                                    </td>
                                    <td>{{survey_date}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Material:</h5>
                                    </td>
                                    <td>{{survey_mat}}</td>
                                    <td>
                                        <h5 class="obs-title">Diameter:</h5>
                                    </td>
                                    <td>{{survey_dia}}</td>
                                </tr>
                            </thead>
                        </table>
                        <table align="center" id="node_inspection_infowindow_table_condition" class="display compact" style="padding:0px;text-align: center;margin-left: -7px;" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td style="border-bottom: 1px solid;"></td>
                                    <td style="border-bottom: 1px solid;">
                                        <h5 class="obs-title">Node Condition</h5>
                                    </td>
                                    <td style="border-bottom: 1px solid;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">STR Grd:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title"></h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">SER Grd:</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{str_grade}}</td>
                                    <td></td>
                                    <td>{{ser_grade}}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid;"></td>
                                    <td style="border-bottom: 1px solid;">
                                        <h5 class="obs-title">Node Inspection Files</h5>
                                    </td>
                                    <td style="border-bottom: 1px solid;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Report:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Plan:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title"></h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{#report_url}}<a href="{{report_url}}" target="_blank">report</a>{{/report_url}} {{^report_url}}no report{{/report_url}}</td>
                                    <td>{{#plan_url}}<a href="{{plan_url}}" target="_blank">plan</a>{{/plan_url}} {{^plan_url}}no plan{{/plan_url}}</td>
                                    <td></td>
                                </tr>
                            </thead>
                        </table>
                        <table align="center" id="node_inspection_infowindow_observations" class="display compact table-hover  dataTable" style="padding:0" cellspacing="0" width="100%">
                            <tr>
                                <td style="border-bottom: 1px solid;"></td>
                                <td style="border-bottom: 1px solid;">
                                    <h5 class="obs-title">Protection</h5>
                                </td>
                                <td style="border-bottom: 1px solid;"></td>
                            </tr>
                            <tr>
                                <td>
                                    <h5 class="obs-title">Scour:</h5>
                                </td>
                                <td>
                                    <h5 class="obs-title">Debris:</h5>
                                </td>
                                <td>
                                    <h5 class="obs-title">Tide:</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>{{scour_protection}}</td>
                                <td>{{debris_protection}}</td>
                                <td>{{tide_protection}}</td>
                            </tr>
                            </thead>

                        </table>
                        {{#start_icon}}
                        <tr>
                            <td>
                                <h5 class="obs-title">Comments:</h5>
                            </td>
                        </tr>
                        <tr>
                            <td>{{comments}}</td>
                        </tr>
                        <tr>
                            <td>{{contractor_comments}}</td>
                        </tr>
                        {{/start_icon}} {{^start_icon}} {{/start_icon}}
                        <tr>
                            {{#photo_link}}
                            <!-- <img src="{{photo_link}}"> -->
                            <p><img alt="Image Loading... Please Wait" width="240px" height="180px" src="{{photo_link}}" />
                            </p>
                            {{/photo_link}}
                        </tr>
                        {{^photo_link}} {{/photo_link}}
                    </div>
                </div>
                <div class="cartodb-popup-tip-container"></div>
            </div>
        </script>
        <script type="infowindow/html" id="cctv_node_start_infowindow_template">
            <div class="infowindow-custom" id="cctv_node_start_infowindow_div" style="width:350px">
                <div class="cartodb-popup-header" style="text-align: center;">
                    <h4 class="obs-title" style="width: 100%;align-content: center;padding: 1px;padding-right: 0px;margin-top: 0px;margin-left: -10px;background-color: rgba(255,200,100,.8);text-align:center;font-weight: bold;">CCTV START NODE</h4>
                    <br>
                </div>
                <div class="cartodb-popup-content" style=" cursor: pointer;">
                    <div class="content" style="padding:0px">

                        <table align="left" id="startnode_infowindow_table" class="display compact table-hover  dataTable" style="padding:0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Start Node:&nbsp;&nbsp;</h5>
                                    </td>
                                    <td><b><u>{{start_node}}</u></b>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Survey ID:</h5>
                                    </td>
                                    <td><b><u>{{survey_id}}</u></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Location:</h5>
                                    </td>
                                    <td>{{start_street_no}} {{start_street_name}}</td>
                                </tr>
                        </table>
                        <table align="center" id="startnode_infowindow_condition" class="display compact" style="padding:0px;text-align: center;margin-left: -7px;" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td style="border-bottom: 1px solid;"></td>
                                    <td style="border-bottom: 1px solid;">
                                        <h5 class="obs-title">Survey Files</h5>
                                    </td>
                                    <td style="border-bottom: 1px solid;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Report:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Video:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Plan:</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{#report_url}}<a href="{{report_url}}" target="_blank">report</a>{{/report_url}} {{^report_url}}no report{{/report_url}}</td>
                                    <td>{{#video_url}}<a href="{{video_url}}" target="_blank">video</a>{{/video_url}} {{^video_url}}no video{{/video_url}}</td>
                                    <td>{{#plan_url}}<a href="{{plan_url}}" target="_blank">plan</a>{{/plan_url}} {{^plan_url}}no plan{{/plan_url}}</td>
                                </tr>
                            </thead>
                        </table>
                        <br> {{#start_icon}}
                        <p>{{build_comments}}</p>{{/start_icon}} {{^start_icon}} {{/start_icon}}
                    </div>
                </div>
                <div class="cartodb-popup-tip-container"></div>
            </div>
        </script>
        <script type="infowindow/html" id="cctv_node_finish_infowindow_template">
            <div class="infowindow-custom" id="cctv_node_finish_infowindow_div" style="width:350px">
                <div class="cartodb-popup-header" style="text-align: center;">
                    <h4 class="obs-title" style="width: 100%;align-content: center;padding: 1px;padding-right: 0px;margin-top: 0px;margin-left: -10px;background-color: rgba(255,200,100,.8);text-align:center;font-weight: bold;">CCTV FINISH NODE</h4>
                    <br>
                </div>
                <div class="cartodb-popup-content" style=" cursor: pointer;">
                    <div class="content" style="padding:0px">

                        <table align="left" id="finishnode_infowindow_table" class="display compact table-hover  dataTable" style="padding:0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Finish Node:</h5>
                                    </td>
                                    <td><b><u>{{finish_node}}</u></b>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Survey ID:</h5>
                                    </td>
                                    <td><b><u>{{survey_id}}</u></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Location:</h5>
                                    </td>
                                    <td>{{finish_street_no}} {{finish_street_name}}</td>
                                </tr>
                        </table>
                        <table align="center" id="finishnode_infowindow_table_condition" class="display compact" style="padding:0px;text-align: center;margin-left: -7px;" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td style="border-bottom: 1px solid;"></td>
                                    <td style="border-bottom: 1px solid;">
                                        <h5 class="obs-title">Survey Files</h5>
                                    </td>
                                    <td style="border-bottom: 1px solid;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Report:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Video:</h5>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Plan:</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{#report_url}}<a href="{{report_url}}" target="_blank">report</a>{{/report_url}} {{^report_url}}no report{{/report_url}}</td>
                                    <td>{{#video_url}}<a href="{{video_url}}" target="_blank">video</a>{{/video_url}} {{^video_url}}no video{{/video_url}}</td>
                                    <td>{{#plan_url}}<a href="{{plan_url}}" target="_blank">plan</a>{{/plan_url}} {{^plan_url}}no plan{{/plan_url}}</td>
                                </tr>
                            </thead>
                        </table>
                        <br> {{#finish_icon}}
                        <p>{{build_comments}}</p>{{/finish_icon}} {{^finish_icon}} {{/finish_icon}}
                    </div>
                    <div class="cartodb-popup-tip-container"></div>
                </div>
        </script>
        <script type="infowindow/html" id="gis_lines_infowindow_template">
            <div class="infowindow-custom" id="gis_lines_infowindow_div" style="width:350px">
                <div class="cartodb-popup-header" style="text-align: center;">
                    <h4 class="obs-title" style="width: 100%;align-content: center;padding: 1px;padding-right: 0px;margin-top: 0px;margin-left: -10px;background-color: rgba(200,200,200,.8);text-align:center;font-weight: bold;"> GIS ASSET DATA</h4>
                    <br>
                </div>
                <div class="cartodb-popup-content" style=" cursor: pointer;">
                    <div class="content" style="padding:0px">
                        <table align="center" id="gis_lines_infowindow_table" class="display compact table-hover  dataTable" style="padding:0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Asset ID:</h5>
                                    </td>
                                    <td><b><u>{{asset_id}}</u></b>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">From Node:</h5>
                                    </td>
                                    <td>{{from_node}}</td>
                                    <td>
                                        <h5 class="obs-title">To Node</h5>
                                    </td>
                                    <td>{{to_node}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Material:</h5>
                                    </td>
                                    <td>{{material}}</td>
                                    <td>
                                        <h5 class="obs-title">Diameter:</h5>
                                    </td>
                                    <td>{{diameter}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Install Date:</h5>
                                    </td>
                                    <td>{{install_date}}</td>
                                    <td>
                                        <h5 class="obs-title">Length:</h5>
                                    </td>
                                    <td>{{length}}</td>
                                </tr>
                        </table>
                    </div>
                    <div class="cartodb-popup-tip-container"></div>
                </div>
        </script>
        <script type="infowindow/html" id="gis_nodes_infowindow_template">
            <div class="infowindow-custom" id="gis_nodes_infowindow_div" style="width:350px">
                <div class="cartodb-popup-header" style="text-align: center;">
                    <h4 class="obs-title" style="width: 100%;align-content: center;padding: 1px;padding-right: 0px;margin-top: 0px;margin-left: -10px;background-color: rgba(200,200,200,.8);text-align:center;font-weight: bold;"> GIS ASSET DATA</h4>
                    <br>
                </div>
                <div class="cartodb-popup-content" style=" cursor: pointer;">
                    <div class="content" style="padding:0px">
                        <table align="center" id="gis_nodes_infowindow_table" class="display compact table-hover  dataTable" style="padding:0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Asset ID:</h5>
                                    </td>
                                    <td><b><u>{{asset_id}}</u></b>
                                    </td>
                                    <td>
                                        <h5 class="obs-title">Install Date:</h5>
                                    </td>
                                    <td>{{installdat}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="obs-title">Type:</h5>
                                    </td>
                                    <td>{{type}}</td>
                                    <td>
                                        <h5 class="obs-title">Diameter:</h5>
                                    </td>
                                    <td>{{diameter}}</td>
                                    <td>
                                        <h5 class="obs-title">Owner:</h5>
                                    </td>
                                    <td>{{owner}}</td>
                                </tr>
                        </table>
                    </div>
                    <div class="cartodb-popup-tip-container"></div>
                </div>
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.0/angular.min.js"></script>


        <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
        <script src="<?php echo base_url('/assets/spatialjs/app-mimi.js') ?>"></script>
		<script src="<?php echo base_url('/assets/spatialjs/select2_review.js') ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/jquery.dataTables.yadcf_review.js') ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/styledLayerControl.js') ?>"></script>
		<script src="<?php echo base_url($council->evaluation) ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/core.js') ?>"></script>
		<script src="<?php echo base_url($council->table_template) ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/map.js') ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/format.js') ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/load.js') ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/action.js') ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/buttonactions.js') ?>"></script>
        <script src="<?php echo base_url('/assets/spatialjs/uploadform.js') ?>"></script>

        <script type="text/javascript" src="<?php echo base_url('/assets/spatialjs/bootstrap-datepicker.min.js') ?>"></script>
</body>

</html>
