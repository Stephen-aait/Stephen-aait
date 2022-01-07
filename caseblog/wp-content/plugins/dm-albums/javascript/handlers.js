/* Demo Note:  This demo uses a FileProgress class that handles the UI for displaying the file name and percent complete.
The FileProgress class is not part of SWFUpload.
*/


/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
function dm_fileQueued(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("Pending...");
		progress.toggleCancel(true, this);

	} catch (ex) {
		this.debug(ex);
	}

}

function dm_fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			return;
		}

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			progress.setStatus("File is too big.");
			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus("Cannot upload Zero Byte files.");
			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			progress.setStatus("Invalid File Type.");
			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		default:
			if (file !== null) {
				progress.setStatus("Unhandled Error");
			}
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function dm_fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		
		if (numFilesSelected > 0) 
		{
			document.getElementById(this.customSettings.cancelButtonId).disabled = false;	
			document.getElementById("btnStart").disabled = false;
		}
		
		/* I want auto start the upload and I can do that here */
		//this.startUpload();
		//document.getElementById("btnStart").disabled = false;
		
		//debug("<br>fileDialogComplete(numFilesSelected, numFilesQueued)...g_dm_UPLOAD_IN_PROGRESS: " + g_dm_UPLOAD_IN_PROGRESS);
		
	} catch (ex)  {
        this.debug(ex);
	}
}

function dm_uploadStart(file) {
	try {
		/* I don't want to do any file validation or anything,  I'll just update the UI and
		return true to indicate that the upload should start.
		It's important to update the UI here because in Linux no uploadProgress events are called. The best
		we can do is say we are uploading.
		 */
		 
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("Starting...");
		progress.toggleCancel(true, this);
	}
	catch (ex) {}
	
	return true;
}

function dm_uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
		progress.setStatus("Uploading...");
	} catch (ex) {
		this.debug(ex);
	}
}

function dm_uploadSuccess(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("Complete.");
		progress.toggleCancel(false);
		dm_albums_UpdateAlbumList();

	} catch (ex) {
		this.debug(ex);
	}
}

function dm_uploadError(file, errorCode, message) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);
		
		document.getElementById('divErrorQueueContainer').style.visibility = 'visible';

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			progress.setStatus("Upload Error: " + message);
			document.getElementById("divErrorQueue").innerHTML += "<p class='uploaderror'>Upload Error for " + file.name + ": " + message + "</p>";
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			progress.setStatus("Upload Failed.");
			document.getElementById("divErrorQueue").innerHTML += "<p class='uploaderror'>Upload Failed for " + file.name + ".</p>";
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			progress.setStatus("Server (IO) Error");
			document.getElementById("divErrorQueue").innerHTML += "<p class='uploaderror'>Server (IO) Error for " + file.name + "</p>";
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			progress.setStatus("Security Error");
			document.getElementById("divErrorQueue").innerHTML += "<p class='uploaderror'>Security Error for " + file.name + "</p>";
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			progress.setStatus("Upload limit exceeded.");
			document.getElementById("divErrorQueue").innerHTML += "<p class='uploaderror'>Upload limit exceeded.</p>";
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			progress.setStatus("Failed Validation.  Upload skipped.");
			document.getElementById("divErrorQueue").innerHTML += "<p class='uploaderror'>Failed Validation for " + file.name + ".  Upload skipped.</p>";
			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			// If there aren't any files left (they were all cancelled) disable the cancel button
			if (this.getStats().files_queued === 0) {
				document.getElementById(this.customSettings.cancelButtonId).disabled = true;
				document.getElementById("btnStart").disabled = true;
			}
			progress.setStatus("Cancelled");
			document.getElementById("divErrorQueue").innerHTML += "<p class='uploadcancel'>" + file.name + " Cancelled</p>";
			progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			progress.setStatus("Stopped");
			document.getElementById("divErrorQueue").innerHTML += "<p class='uploadcancel'>" + file.name + " Stopped</p>";
			break;
		default:
			progress.setStatus("Unhandled Error: " + errorCode);
			document.getElementById("divErrorQueue").innerHTML += "<p class='uploaderror'>Unhandled Error for " + file.name + ": " + errorCode + "</p>";
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function dm_uploadComplete(file) {

	if (this.getStats().files_queued === 0) {
		document.getElementById(this.customSettings.cancelButtonId).disabled = true;
		document.getElementById("btnStart").disabled = true;
		g_dm_UPLOAD_IN_PROGRESS = false;
		dm_FinishUpload();
	}
}

// This event comes from the Queue Plugin
function dm_queueComplete(numFilesUploaded) {
	g_dm_UPLOAD_IN_PROGRESS = false;
	
	setTimeout(function () {
		refreshQueue();
	}, 6000);
	
	//debug("<br>queueComplete(numFilesUploaded)...g_dm_UPLOAD_IN_PROGRESS: " + g_dm_UPLOAD_IN_PROGRESS);
}

function refreshQueue()
{
	document.getElementById("fsUploadProgress").innerHTML = "<span class=\"legend\">Upload Queue</span>";
	document.getElementById("uploadtarget").innerHTML = "";
}
