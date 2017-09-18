<?php

/**
 * PHP Server-Side Example for Fine Uploader (traditional endpoint handler).
 * Maintained by Widen Enterprises.
 *
 * This example:
 *  - handles chunked and non-chunked requests
 *  - supports the concurrent chunking feature
 *  - assumes all upload requests are multipart encoded
 *  - supports the delete file feature
 *
 * Follow these steps to get up and running with Fine Uploader in a PHP environment:
 *
 * 1. Setup your client-side code, as documented on http://docs.fineuploader.com.
 *
 * 2. Copy this file and handler.php to your server.
 *
 * 3. Ensure your php.ini file contains appropriate values for
 *    max_input_time, upload_max_filesize and post_max_size.
 *
 * 4. Ensure your "chunks" and "files" folders exist and are writable.
 *    "chunks" is only needed if you have enabled the chunking feature client-side.
 *
 * 5. If you have chunking enabled in Fine Uploader, you MUST set a value for the `chunking.success.endpoint` option.
 *    This will be called by Fine Uploader when all chunks for a file have been successfully uploaded, triggering the
 *    PHP server to combine all parts into one file. This is particularly useful for the concurrent chunking feature,
 *    but is now required in all cases if you are making use of this PHP example.
 */

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

 } catch (Exception $e) {
     print "An error occurred: " . $e->getMessage();
 }
}

?>
