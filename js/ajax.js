function addnote() {
	var formdata = $("#addnote_form").serialize();
	formdata = "addnote[class]=" + $('#addnote_class').val() + "&" + formdata;
	formdata = formdata.replace(/%5B/g, '[').replace(/%5D/g, ']');
	console.log(formdata);

	$.ajax({
		url: "ajax/addnote.php",
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