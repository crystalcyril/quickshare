<?php

require_once('bootstrap.inc.php');


$max_upload_size = get_max_upload_size();

?>
<!DOCTYPE html>
<html>
<head>
	<title>File Sharing</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
  <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
  
  <!-- fine uploader -->
  <!--
  <script src="js/fineuploader/header.js"></script>
  <script src="js/fineuploader/util.js"></script>
  <script src="js/fineuploader/button.js"></script>
  <script src="js/fineuploader/handler.base.js"></script>
  <script src="js/fineuploader/handler.form.js"></script>
  <script src="js/fineuploader/handler.xhr.js"></script>
  <script src="js/fineuploader/uploader.basic.js"></script>
  <script src="js/fineuploader/dnd.js"></script>
  <script src="js/fineuploader/uploader.js"></script>
  -->
  
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
  
  <!--<link href="js/fineuploader/css/fineuploader.css" rel="stylesheet" type="text/css"/>-->
  <link href="js/fineuploader/fineuploader-3.3.0.css" rel="stylesheet" type="text/css"/>
	
</head>
<body>
	
	<div>
		
		<div class="container">
		
			<h1>Share Your File in a SendSpace Way!</h1>
		
			<form>
				
				<div id="upload">
					
					<div>You can drag and drop a file here as well!</div>
					<div id="fine-uploader"></div>
					<div id="trigger-upload"><a href="#">Upload!</a></div>
					
					<i>Maximum upload size: <?php echo $max_upload_size / 1024 / 1024 ?> MBytes</i>
				
				</div>				
				
				<div id="success">
					Your download link is available!
					<br/>
					
					<h3>Download Link</h3>
					
					<a id="download-link" href=""></a> <!--<a id="copy-download-link" href="#">Copy Link</a> -->
					<br/>
					This link will be valid for <span id="file-life"></span>.
				</div>
				
				<div id="failure">
					
					Uploaded failed!
					
					<br/>
					
					Server reports the following error:
					<br/>
					
					<div id="failure-reason"></div>
					
				</div>			
				
			</form>
			
		</div>
		
	</div>
	
  
  <script src="js/fineuploader/fineuploader-3.3.0.js"></script>
  <!-- Boostrap -->
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.zclip.min.js"></script>
	<script>
		
		var fileCount = 0;
    var uploader = new qq.FineUploader({
        element: $('#fine-uploader')[0],
        multiple: false,
        debug: true,
        autoUpload: false,
        sizeLimit: <?php echo $max_upload_size; ?>,
        request: {
            endpoint: "upload.php"
        },
				failedUploadTextDisplay: {
        	mode: 'custom',
        	maxChars: 40,
        	responseProperty: 'error',
	        enableTooltip: true
  	    },
  	    text: {
  	    	uploadButton: 'Add File...',
  	    },
        callbacks: {
        		onSubmit: function(id, fileName) {
        				fileCount++;
					    	$('#success').hide();
					    	$('#failure').hide();
					    	if (fileCount > 0) {
					    		$('#trigger-upload').show();
					    	}
        		},
            //onError: errorHandler,
            onCancel: function(id, fileName) {
            	fileCount--;
            	if (fileCount <= 0) {
            		$('#trigger-upload').hide();
            	}
            },
            onComplete: function(id, fileName, responseJSON) {
            	
            	// show the URL link
            	if (responseJSON.success) {
            		
            		fileCount = 0;
            		
	            	var downloadUrl = 'file.php?id=' + responseJSON.uploadName;
	            	downloadUrl = responseJSON.downloadUrl;
	            	$('#download-link').attr('href', downloadUrl).html(downloadUrl);
	            	
	            	// tell the user how long this file can stay
	            	var days = Math.floor(responseJSON.fileLife / 24 / 60 / 60);
	            	var hours = Math.floor((responseJSON.fileLife - days * 24 * 60 * 60) / 60 / 60);
	            	var minutes = Math.floor((responseJSON.fileLife - days * 24 * 60 * 60 - hours * 60 * 60) / 60);
	            	var seconds = Math.floor(responseJSON.fileLife % 60);
	            	//
	            	var l = '';
	            	if (days > 0) {
	            		l +=  ' ' + days + ' days';
	            	}
	            	if (hours > 0 || l != '') {
	            		l +=  ' ' + hours + ' hours';
	            	}
	            	if (hours > 0 || minutes > 0 || l != '') {
	            		l += ' ' + minutes + ' minutes';
	            	}
	            	l += ' ' +  seconds + ' seconds';
	            	// tell the deadline too
	            	var deadline = new Date();
	            	deadline = new Date(deadline.getTime() + parseInt(responseJSON.fileLife) * 1000);
	            	l += ' (expired on ' + deadline + ')';
	            	
	            	$('#file-life').html(l);
	            	
	            	// show the screen
					    	$('#success').show();
					    	$('#failure').hide();
					    	$('#trigger-upload').hide();
					    	
					    } else {
					    	
					    	// upload failed.
					    	
					    	$('#success').hide();
					    	//$('#failure').show();
					    	
					    	//$('#failure-reason').html(responseJSON.message);
					    	
					    }
					    
					    
					    
            }
        }
    });
    
    $(document).ready(function() {

			/*    	
			$('#copy-download-link').zclip({
				path: 'js/ZeroClipboard.swf',
				copy: $('p#fuck').val(),

			});
			*/

			$('#trigger-upload').click(function() {
				uploader.uploadStoredFiles();
			});
			
			$('#trigger-upload').hide();
    	$('#success').hide();
    	$('#failure').hide();
    	
    });
	</script>
</body>
</html>