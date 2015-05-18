var arrMonths = [
    "januari",
    "februari",
    "maart",
    "april",
    "mei",
    "juni",
    "juli",
    "augustus",
    "september",
    "oktober",
    "november",
    "december"
];

var arrDays = [
    "zondag",
    "maandag",
    "dinsdag",
    "woensdag",
    "donderdag",
    "vrijdag",
    "zaterdag"
];

$(document).ready(function(){
	$('.button-collapse').sideNav({menuWidth: 240, activationWidth: 70});

	// the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
	$('.modal-trigger').leanModal();

	if($('#modal-registreer').hasClass("register_error_empty_field")) {
		$('#modal-registreer').openModal();
		toast('Een of meerdere velden zijn nog niet ingevuld!', 5000);
	}

	if($('#modal-registreer').hasClass("register_error_passwords_not_equal")) {
		$('#modal-registreer').openModal();
		toast('De opgegeven wachtwoorden komen niet met elkaar overeen.', 5000);
	}

	if($('#modal-registreer').hasClass("register_error_username_occupied")) {
		$('#modal-registreer').openModal();
		toast('De opgegeven gebruikersnaam is al in gebruik.', 5000);
	}

	if($('#modal-registreer').hasClass("register_error_succes")) {
		toast('U bent geregistreerd, u kunt nu inloggen.', 5000);
	}

	if($('#modal-registreer').hasClass("register_error_mysql")) {
		$('#modal-registreer').openModal();
		toast('Er is iets fout gegaan!', 5000);
	}

	if($('#modal-login').hasClass("login_error_empty_field")) {
		$('#modal-login').openModal();
		toast('Een of meerdere velden zijn nog niet ingevuld!', 5000);
	}

	if($('#modal-login').hasClass("login_error_wrong_credentials")) {
		$('#modal-login').openModal();
		toast('De opgegeven gebruikersnaam of wachtwoord is onjuist.', 5000);
	}

	if($('#modal-login').hasClass("login_error_succes")) {
		toast('U bent nu ingelogd.', 5000);
	}

	$('#logout_link1').click(function() {
		$('#logout_form1').submit();
	});

	$('#logout_link2').click(function() {
		$('#logout_form2').submit();
	});

	$("tr .mdi-content-add").click(function() {
		var jsonVars = $(this).parent().parent().parent().data('data');
		$("input#addnote_class").val(jsonVars.v);
		$("#modal-addnote").openModal();
		console.log(jsonVars);
	});

	$("tr .mdi-action-info").click(function(){
		var jsonVars = $(this).parent().parent().parent().data('data');
		getNotes(jsonVars.v);
	});



});

$("#addnote_submit").click(function() {
	addNote();
});

$("#editnote_open").click(function() {
	$("#modal-editnote").openModal();
});

$("#editnote_submit").click(function() {
	editNotes();
});