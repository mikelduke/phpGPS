/**
 * 
 */

function downloadUrl(url, callback) {
  //prompt("",url);
  var request = window.ActiveXObject ?
    new ActiveXObject('Microsoft.XMLHTTP') :
    new XMLHttpRequest;

  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      request.onreadystatechange = doNothing;
      callback(request, request.status);
    }
  };

  request.open('GET', url, true);
  request.send(null);
}

function doNothing() {}


function updateRecord(table, column, newValue, where) {
  var url = "updateRecord.php?update=true&table=" + table + "&where=" + where + "&" + column + "=" + newValue;
  //url += "&viewQuery=true";
  downloadUrl(url, function(data) {
    //prompt('', data.responseText);
    location.reload();
  });
}

function edit(table, column, oldVal, where) {
  var newValue = prompt("Enter New Value: ", oldVal);
  if (newValue != null) {
    updateRecord(table, column, newValue, where);
  }
}

function deleteRecord(table, where, showPrompt) {
  if (showPrompt)
    if (!confirm('Are you sure you want to delete this item?')) return;
  
  var url = "deleteRecord.php?delete=true&table=" + table + "&where=" + where;
  downloadUrl(url, function(data) {
    location.reload();
  });
}

function downloadAndRefresh(url) {
  downloadUrl(url, function(data) {
    //prompt('', data.responseText);
    location.reload();
  });
}

function updateDelete(upTable, upColumn, upNewValue, delTable, where, showPrompt) {
  if (showPrompt)
    if (!confirm('Are you sure you want to delete this item?')) return;
  
  var url = "updateRecord.php?update=true&table=" + upTable + "&where=" + where + "&" + upColumn + "=" + upNewValue;
  url += "&viewQuery=true";
  //prompt('', url);
  downloadUrl(url, function(data) {
    var delUrl = "deleteRecord.php?delete=true&table=" + delTable + "&where=" + where;
    //prompt('', delUrl);
    downloadUrl(delUrl, function(data) {
      location.reload();
    });
  });
}

function newPass(userId) {
	var newPass = prompt("Enter New Password: ", "password");
	
	//onclick='downloadAndRefresh(\"adminChangePass.php?user=" . $row['user_id'] . "&newpass=test" . "\")'
	var url = "adminChangePass.php?user=" + userId + "&newpass=" + newPass;
	
	downloadUrl(url, function(data) {
		location.reload();
	});
}