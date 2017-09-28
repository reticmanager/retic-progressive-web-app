<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ReticManager Mobile Field Capture</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url('/assets/spatialcss/main-mimiUpdated2017_review.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('/assets/css/webapp.css')?>">
</head>

<body>
  <?php echo $error;?>

<?php echo form_open_multipart('app/do_upload');?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="upload" />

</form>
  <div id="app">
    <div class="row cont-center">
      <div class="col-md-12 col-margins">
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
            <div class="form-group center">
              <img alt="reticmanager logo" src="images/RMLogo215x40.png" style="padding: 5px; width: 215px; height: auto;">
              <table class="table table-sm table-hover table-striped table-bordered">
                <tbody>
                  <tr class="custom-height">
                    <th scope="row">
                      <h5>Client Code:</h5>
                    </th>
                    <td>
                      <h5><?php echo $council->code?></h5>
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
              <h4>Inspection Type</h4>
              <select v-model="selected.type" v-on:change="optionSelect" class="form-control custom-height">
  								<option disabled value="">Select</option>
  								<option v-for="type in types"> {{ type }} </option>
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
  													<span class="">Date/Time</span>
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
  													<span class="">GPS</span>
  												</p>
  											</button>
                    </th>
                    <td class=" custom-td-width">
                      <h5>Latitude: {{ gps.lat }}</h5>
                      <h5>Longitude: {{ gps.long }}</h5>
                    </td>
                  </tr>
                  <tr class="custom-height">
                    <th scope="row">
                      <div class="input-group">
                        <label class="input-group-btn">
  													<span class="btn btn-primary webapp-buttons" style="border-radius: 4px;">
  														<i class="fa fa-camera fa-size image-input-icons" aria-hidden="true"></i>
  														<p>
  															<span class="">Image 1</span>
  														</p>
  														<input id="inputOne" ref="inputOne" v-on:change="getImage" style="display: none;" type="file" accept="image/*">
  														</span>
  													</label>
                      </div>
                    </th>
                    <td class=" custom-td-width">
                      <!-- Image container -->
                        <div v-for="(image, index) in images">
                          <image-container v-bind:src="images[index]"></image-container>
                        </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div id="mh-details-table" class="form-group center">
            <div class="col-md-6 col-md-offset-3">
              <div v-if="manHole">
                <table class="table table-sm table-hover table-striped table-bordered">
                  <tbody>
                    <tr class="custom-height">
                      <th scope="row">
                        <h5>Survey ID</h5>
                      </th>
                      <td class=" custom-td-width">
                        <input class="form-control custom-height custom-td-width" type="text" placeholder="  Survey ID">
                      </td>
                    </tr>
                    <tr class="custom-height">
                      <th scope="row">
                        <h5>Asset ID</h5>
                      </th>
                      <td class=" custom-td-width">
                        <input class="form-control custom-height custom-td-width" type="text" placeholder="  Asset ID">
                      </td>
                    </tr>
                    <tr class="custom-height">
                      <th scope="row">
                        <h5>Network</h5>
                      </th>
                      <td class=" custom-td-width">
                        <select placeholder="Select" v-model="selected.network" class="form-control custom-height custom-td-width">
  																	<option disabled value="">Select</option>
  																	<option v-for="network in networks"> {{ network }} </option>
  																</select>
                      </td>
                    </tr>
                    <tr class="custom-height">
                      <th scope="row">
                        <h5>Located Status</h5>
                      </th>
                      <td class=" custom-td-width">
                        <select v-model="selected.status" class="form-control custom-height custom-td-width">
  																	<option disabled value="">Select</option>
  																	<option v-for="status in statuses"> {{ status }} </option>
  																</select>
                      </td>
                    </tr>
                    <tr class="custom-height">
                      <th scope="row">
                        <h5>Street No</h5>
                      </th>
                      <td class=" custom-td-width">
                        <input class=" form-control custom-height custom-td-width" type="text" placeholder="  Street #">
                      </td>
                    </tr>
                    <tr class="custom-height">
                      <th scope="row">
                        <h5>Street Name</h5>
                      </th>
                      <td class=" custom-td-width">
                        <input class=" form-control custom-height custom-td-width" type="text" placeholder="  Street">
                      </td>
                    </tr>
                    <tr class="custom-height">
                      <th scope="row">
                        <h5>Node Diameter</h5>
                      </th>
                      <td class=" custom-td-width">
                        <select placeholder="Select" v-model="selected.diameter" class="form-control custom-height custom-td-width">
  																			<option disabled value="">Select</option>
  																			<option v-for="diameter in diameters"> {{ diameter }} </option>
  																		</select>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-md-offset-3">
          <button id="submit" name="submit" class="submit-buttons custom-height btn btn-primary webapp-buttons final-buttons" value="1">Submit</button>
          <a href="https://reticmanager.com/app/view/wdc160" id="cancel" name="cancel" class="submit-buttons custom-height btn btn-primary webapp-buttons final-buttons">Cancel</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Templates -->
  <script type="text/x-template" id="image-container">
    <img class="img-thumbnail"/>
  </script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.js"></script>
  <script type="text/javascript" src="<?php echo base_url('/assets/js/webapp.js')?>"></script>
</body>

</html>
