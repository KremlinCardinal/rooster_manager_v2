function addNote() {
	var formdata = $("#addnote_form").serialize();
	formdata = "addnote[userid]="+userid+"&addnote[class]=" + $('#addnote_class').val() + "&" + formdata;
	formdata = formdata.replace(/%5B/g, '[').replace(/%5D/g, ']');

	$.ajax({
		url: "ajax/ajax.php",
		data: formdata,
		type: "POST",
		dataType: "html",
		success: function(result) {
			console.log(result);
		},
		error: function(xhr, status, errorThrown) {
			alert( "Sorry, there was a problem!" );
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );
		}
	});
}

function getNotes(vak) {
	var sendData = "getNotes[userid]="+userid;

	$.ajax({
		url: "ajax/ajax.php",
		data: sendData,
		type: "POST",
		dataType: "html",
		success: function(result) {
			console.log(result);
			var jsonvars = JSON.parse(result);
			console.log(jsonvars);
			$("#modal-getnote .class").html(vak);
			$("#modal-getnote .note").html(jsonvars[vak]);
			$("#modal-editnote #editnote_class").val(vak);
			$("#modal-editnote #editnote_note").val(jsonvars[vak]);
			$("#modal-getnote").openModal();
		},
		error: function(xhr, status, errorThrown) {
			alert( "Sorry, there was a problem!" );
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );
		}
	});
}

function editNotes() {
	var formdata = $("#editnote_form").serialize();
	formdata = "editnote[userid]="+userid+"&editnote[class]=" + $('#editnote_class').val() + "&" + formdata;
	formdata = formdata.replace(/%5B/g, '[').replace(/%5D/g, ']');
	console.log(formdata);
	$.ajax({
		url: "ajax/ajax.php",
		data: formdata,
		type: "POST",
		dataType: "html",
		success: function(result) {
			console.log(result);
		},
		error: function(xhr, status, errorThrown) {
			alert( "Sorry, there was a problem!" );
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );
		}
	});
}