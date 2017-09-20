<?php

// Include the upload handler class
require_once "handler.php";

$uploader = new UploadHandler();

// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
$uploader->allowedExtensions = array(); // all files types allowed by default

// Specify max file size in bytes.
$uploader->sizeLimit = null;

// Specify the input name set in the javascript.
$uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
$uploader->chunksFolder = "file-uploads";

$method = get_request_method();

// This will retrieve the "intended" request method.  Normally, this is the
// actual method of the request.  Sometimes, though, the intended request method
// must be hidden in the parameters of the request.  For example, when attempting to
// delete a file using a POST request. In that case, "DELETE" will be sent along with
// the request in a "_method" parameter.
function get_request_method() {
    global $HTTP_RAW_POST_DATA;

    if(isset($HTTP_RAW_POST_DATA)) {
    	parse_str($HTTP_RAW_POST_DATA, $_POST);
    }

    if (isset($_POST["_method"]) && $_POST["_method"] != null) {
        return $_POST["_method"];
    }

    return $_SERVER["REQUEST_METHOD"];
}

if ($method == "POST") {
    header("Content-Type: text/plain");

    // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
    // For example: /myserver/handlers/endpoint.php?done
    if (isset($_GET["done"])) {
        $result = $uploader->combineChunks("file-uploads");
    }
    // Handles upload requests
    else {
        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
        $result = $uploader->handleUpload("file-uploads");

        // To return a name used for uploaded file you can use the following line.
        $result["uploadName"] = $uploader->getUploadName();
        // Return target file path
        $result["uploadFile"] = $uploader->getTargetFile();
        // Return target file path
        $result["uploadFileSize"] = $uploader->getTargetFileSize();
        // Push to Google Drive
        set_time_limit(600); //File upload should not take longer than 10 minutes
        uploadGoogleDrive($result["uploadName"], $result["uploadFile"], $result["uploadFileSize"]);
        set_time_limit(30); //Reset to default
    }

    echo json_encode($result);
}
// for delete file requests
else if ($method == "DELETE") {
    $result = $uploader->handleDelete("file-uploads");
    echo json_encode($result);
}
else {
    header("HTTP/1.0 405 Method Not Allowed");
}


function uploadGoogleDrive($file_name, $targetFile, $file_size)
{
  include_once __DIR__ . '../../../../autoload.php';
  include_once "templates/base.php";

  $client = new Google_Client();
  $client->useApplicationDefaultCredentials();

  $client->setSubject("cgnetwork.nz@gmail.com");

  if ($credentials_file = checkServiceAccountCredentialsFile()) {
    $client->setAuthConfig($credentials_file);
  } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
    $client->useApplicationDefaultCredentials();
  } else {
    echo missingServiceAccountDetailsWarning();
    return;
  }

  $client->setApplicationName("Client_Library_Examples");
  $client->setScopes(['https://www.googleapis.com/auth/drive']);
  $service = new Google_Service_Drive($client);
  $folderId = '0By4zLqW7y7obZFRvcG9fN25IeXc';
  $fileId = '';

  $fileMetadata = new Google_Service_Drive_DriveFile(array(
      'name' =>  $file_name,
      'parents' => array($folderId)
  ));

  try {
    $client->setDefer(true);
    $request = $service->files->create($fileMetadata);

    $chunkSizeBytes = 50 * 1024 * 1024;

    $media = new Google_Http_MediaFileUpload(
        $client,
        $request,
        '',
        $targetFile,
        true,
        $chunkSizeBytes
    );

$media->setFileSize(filesize($targetFile));

$status = false;
$handle = fopen($targetFile, "rb");
while (!$status && !feof($handle)) {
$chunk = fread($handle, $chunkSizeBytes);
$status = $media->nextChunk($chunk);
}

// The final value of $status will be the data from the API for the object
// that has been uploaded.
$result = false;
if($status != false) {
  $result = $status;
}

fclose($handle);
// Reset to the client to execute requests immediately in the future.
$client->setDefer(false);

$optParams = array(
  'pageSize' => 1,
  'fields' => 'files(id)'
);

$results = $service->files->listFiles($optParams);

foreach ($results->getFiles() as $file) {
  $fileId = $file->getId();
}

// $service->getClient()->setUseBatch(true);
//
// $batch = $service->createBatch();
//
// $userPermission = new Google_Service_Drive_Permission(array(
//     'type' => 'domain',
//     'role' => 'owner',
//     'emailAddress' => 'cgnetwork.nz@gmail.com'
// ));
//
// $request = $service->permissions->update(
//     $fileId, $userPermission, array('transferOwnership' => 'true'));
//     $batch->add($request, 'user');

 } catch (Exception $e) {
     print "An error occurred: " . $e->getMessage();
 }
}

?>
