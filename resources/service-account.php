<?php
/*
 * Copyright 2013 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

include_once __DIR__ . '../../../../autoload.php';
include_once "templates/base.php";

echo pageHeader("Service Account Access");

/************************************************
  Make an API request authenticated with a service
  account.
 ************************************************/

$client = new Google_Client();

/************************************************
  ATTENTION: Fill in these values, or make sure you
  have set the GOOGLE_APPLICATION_CREDENTIALS
  environment variable. You can get these credentials
  by creating a new Service Account in the
  API console. Be sure to store the key file
  somewhere you can get to it - though in real
  operations you'd want to make sure it wasn't
  accessible from the webserver!
  Make sure the Books API is enabled on this
  account as well, or the call will fail.
 ************************************************/

if ($credentials_file = checkServiceAccountCredentialsFile()) {
  // set the location manually
  $client->setAuthConfig($credentials_file);
} elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
  // use the application default credentials
  $client->useApplicationDefaultCredentials();
} else {
  echo missingServiceAccountDetailsWarning();
  return;
}

$client->setApplicationName("Client_Library_Examples");
$client->setScopes(['https://www.googleapis.com/auth/drive']);
$service = new Google_Service_Drive($client);

/************************************************
  We're just going to make the same call as in the
  simple query as an example.
 ************************************************/
 // Print the names and IDs for up to 10 files.

$optParams = array(
  'pageSize' => 50,
  'fields' => 'nextPageToken, files(id, name)'
);
$results = $service->files->listFiles($optParams);

if (count($results->getFiles()) == 0) {
  print "No files found.\n";
} else {
  echo "<h2>Account Files:</h2>";
  foreach ($results->getFiles() as $file) {
    echo"<p>" . $file->getName() . " : " . $file->getId() . "</p>";
    // deleteFiles($service, $file->getId());
  }
}

function deleteFiles($service, $id)
{
return $delete = $service->files->delete($id);
}

 try {
   $user = $service->about->get(array('fields' => 'user'));
    $about = $service->about->get(array('fields' => 'storageQuota'));
    echo "<h2>Account Details:</h2>";
    print "Current user name: " . $user->user->getDisplayName();
    $usage = $about->storageQuota->getUsage();
    $quota = $about->storageQuota->getLimit();
    print "<p>Account Usage: " . formatBytes($usage) . "</p>";
    print "Account Limit: " . formatBytes($quota);
} catch (Exception $e) {
    print "An error occurred: " . $e->getMessage();
}


// Upload testing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $ds = DIRECTORY_SEPARATOR;

  $storeFolder = 'file-uploads';

  if (!empty($_FILES)) {

      $tempFile = $_FILES['file']['tmp_name'];
      $file_name = $_FILES['file']['name'];
      $file_type = $_FILES['retic']['type'];

      $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;

      $targetFile =  $targetPath. $_FILES['file']['name'];

      move_uploaded_file($tempFile,$targetFile);

      $file = new Google_Service_Drive_DriveFile();
      $folderId = '0By4zLqW7y7obZFRvcG9fN25IeXc';

      $fileMetadata = new Google_Service_Drive_DriveFile(array(
          'name' =>   $file_name,
          'parents' => array($folderId)
      ));

      $content = file_get_contents($targetFile);
      $file = $service->files->create($fileMetadata, array(
          'data' => $content,
          'mimeType' => $file_type,
          'uploadType' => 'resumable',
          'fields' => 'id'));
      printf("<p>File ID: %s\n</p>", $file->id);

  }

//   $file_name = $_FILES['retic']['name'];
//   $file_size = $_FILES['retic']['size'];
//   $file_tmp = $_FILES['retic']['tmp_name'];
//   $file_type = $_FILES['retic']['type'];
//
//   echo "<p>File Name: " . $file_name . "</p>";
//   echo "<p>File Size: " . $file_size . "</p>";
//   echo "<p>File Tmp: " . $file_tmp . "</p>";
//   echo "<p>File Type: " . $file_type . "</p>";
//
// // Now lets try and send the metadata as well using multipart!
// $file = new Google_Service_Drive_DriveFile();
// $folderId = '0By4zLqW7y7obZFRvcG9fN25IeXc';
//
// $fileMetadata = new Google_Service_Drive_DriveFile(array(
//     'name' =>   "Wildlife.wmv",
//     'parents' => array($folderId)
// ));
//
// $content = file_get_contents($targetFile);
// $file = $service->files->create($fileMetadata, array(
//     'data' => $content,
//     'mimeType' => 'video/wmv',
//     'uploadType' => 'multipart',
//     'fields' => 'id'));
// printf("<p>File ID: %s\n</p>", $file->id);

// $handle = fopen($_FILES["retic"]["tmp_name"], 'r');
// $file->setName("Retic File Upload");
// $result2 = $service->files->create(
//     $file,
//     array(
//       'data' => file_get_contents($handle),
//       'mimeType' => 'application/octet-stream',
//       'uploadType' => 'multipart'
//     )
// );

}

// Helper function to display bytes in readable format
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>

<div class="box">
<?php if (isset($authUrl)): ?>
  <div class="request">
    <a class='login' href='<?= $authUrl ?>'>Connect Me!</a>
  </div>

<?php else: ?>
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <form id="my-dropzone" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="dropzone"></form>
      </div>
    </div>
  </div>
<?php endif ?>
</div>
<script type="text/javascript" src="js/dropzone.min.js"></script>
<script>
  Dropzone.options.myDropzone = {
    maxFilesize: 500,
    acceptedFiles: '.3gp,.3gp2,.h261,.h263,.h264,.jpgv,.jpm,.jpgm,.mp4,.mp4v,.mpg4,.mpeg,.mpg,.mpe,.m1v,.m2v,.ogv,.qt,.mov,.fli,.flv,.mks,.mkv,.wmv,.avi,.movie,.smv,.g3,.jpeg,.jpg,.jpe,.png,.btif,.sgi,.svg,.tiff,.tif',
    init: function() {
      this.on("uploadprogress", function(file, progress) {
        console.log(progress);
      });
    }
  }
</script>
