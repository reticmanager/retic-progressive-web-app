// ENABLE PASSWORD

// Change this to 1 to ask for a password before uploading
// REMEMBER: Set your password in the file server.gs
var enablePassword = 0;


// ASK THE SENDER'S NAME

// Change this to 1 to ask the sender's name (a subfolder with the sender's name will be created within the main folder to receive the files)
var enableName = 1;

var totalFiles = current = 0;
var drop = document.querySelector('#drop');
var fInput = document.querySelector('#fileInput');
var barNumber = document.querySelector('#barNumber');

drop.onclick = function(){
	fInput.click();
}
drop.addEventListener("dragover", hover, false);
drop.addEventListener("dragleave", hover, false);
drop.addEventListener("drop", getFiles, false);
fInput.addEventListener("change", getFiles, false);

var pass, uName;
function askPass(){
	if(enablePassword){
		pass = prompt('\n\n\n\nPlease, insert the password to upload:');
		if(!pass){
			alert('\n\n\n\nInsert the password!');
			askPass();
			return false;
		}
	}
}

function askName(){
	if(enableName){
		uName = prompt('\n\n\n\nPlease, insert your name:');
		if(!uName){
			alert('\n\n\n\nInsert your name!');
			askName();
			return false;
		}
	}
}


function hover(e){
	e.stopPropagation();
	e.preventDefault();
	e.target.className = (e.type == "dragover" ? "hover" : "");
}
function getFiles(e){
	askPass();
	askName();
	var files = e.target.files || e.dataTransfer.files;
	hover(e); // stops the event
	if(files.length == 0) {
        alert('\n\n\n\nSelect a file!');
    } else {
		totalFiles = files.length;
		barNumber.innerHTML = 'Preparing files...';
		for(i=0;i<files.length;i++){ // send each file at a time
			encodeFile(files[i]);
		}
		drop.className = 'hide';
		document.querySelector('#window').className = '';
	}
}
function encodeFile(file){
	var r = new FileReader();
    r.onload = function(){
        var base64file = r.result;
        google.script.run.withSuccessHandler(progressBar).uploadFile(base64file, file.name, pass, uName);
    }
    r.readAsDataURL(file);
}
function progressBar(data){
	if(data.indexOf('Error:')>-1){ // something was wrong
		alert('\n\n\n\n'+data);
		if(data == 'Error: Password Incorrect!'){
			window.location.reload(); // to abort all uploads
		}
	} else {
		document.querySelector('#title').innerHTML = "Sent file: <em>"+data+"</em>";
		current++;
		if(current==totalFiles){
			document.querySelector('#complete').className = '';
			document.querySelector('#sending').className = 'hide';
		}
		barNumber.innerHTML = current+' of '+totalFiles;
		var percent = Math.ceil(current/totalFiles*100);
		insideBar.style.width = percent+'%';
	}
}
var c = document.createElement('div');
c.innerHTML = "Developed by Chaz Goodare";
c.style = "position: absolute;bottom: 15px;right: 15px;font-size: 12px;color: #666;"
document.body.appendChild(c);
