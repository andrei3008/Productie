var situatie = {
	base_url_ajax: "includes/rapoarte",
	loading: function() {
		$("#loading").toggle();
	},
//
// @param      btn     butonul click-uit
// @param      {<char>} tip      [siz / sil]
// @return     {Object}  { se completeaza input-urile <raport-firma> si <raport-locatie> si le paseaza mai departe}
//
	set_params: function(btn, tip) {
		var firma = $(btn).attr('data-firma');
	   	var locatie = $(btn).attr('data-locatie');
	   	$("#raport-firma").val(firma);
	   	$("#raport-locatie").val(locatie);
	   	$("#raport-tip").val(tip);
	   	// situatie.get_nume_firma(firma);
	   	// situatie.get_nume_locatie(locatie);
	   	return {firma: firma, locatie: locatie, tip: tip }
	},

/**
 * 	Se activeaza data click-uita si se apeleaza functia
 * 	RAPORT pentru generarea situatiei
 */
	activate: function() {
		$(".month-days").on("click", function() {
			if ($(this).hasClass('active')) {
				$(this).removeClass('active');
			} else {
				$(this).addClass('active');
			}
			firma = $('#raport-firma').val();
	   		locatie = $('#raport-locatie').val();
	   		tip = $('#raport-tip').val();
			situatie.raport(locatie, firma, tip);
		})
	},
	get_nume_firma: function(id) {
		$.ajax({
          	type: "POST",
          	url: situatie.base_url_ajax+"/ajax.php",
          	data: {
          		type: 'nume_firma',
          		id: id,
          	},
          	success: function(result){
          		out = jQuery.parseJSON(result);
          	}
      	});
	},
	get_nume_locatie: function(id) {
		$.ajax({
          	type: "POST",
          	url: situatie.base_url_ajax+"/ajax.php",
          	data: {
          		type: 'nume_locatie',
          		id: id,
          	},
          	success: function(result){
          		
          	}
      	});
	},
	downloadURI: function (url) {
		window.open(url);
	},
/**
 * Generare ZILE din luna-an selectate
 *
 * @param       an      anul selectat (2016)
 * @param       luna    luna selectata (08 - august)
 * @param      {<int>}  locatie
 * @param      {<int>}  firma
 * @param      {<char>} tip      [siz / sil]
 */
	load_zile_luna: function(an, luna, locatie, firma, tip) {
		$.ajax({
          	type: "POST",
          	url: situatie.base_url_ajax+"/ajax.php",
          	data: {
          		type: 'load_zile_luna',
          		an: an,
          		luna: luna,
          		firma: firma,
          		locatie: locatie,
          		tip: tip
          	},
          	success: function(result){
          		data = jQuery.parseJSON(result);
          		$("#zile-luna").html(data.out);
          		situatie.activate();
          		situatie.raport(locatie, firma, tip);
          	}
      });
	},

//
// preluare zi-luna pentru generare zile luna (SIZ)
// 
//
// @param      {<int>}  	locatie  id-ul locatiei
// @param      {<int>}  	firma    id-ul firmai
// @param      {<string>}  	tip  	 SIZ/SIL
//
	change_raport: function(locatie, firma, tip) {
		var an = $("#input-an").val();
		var luna = $("#input-luna").val();
		situatie.load_zile_luna(an, luna, locatie, firma, tip);
	},

/**
 * Se genereaza RAPORTUL 
 *
 * @param      {<int>}  	locatie  id-ul locatiei
 * @param      {<int>}  	firma    id-ul firmai
 * @param      {<string>}  	tip  	 SIZ/SIL
 */
	raport: function(locatie, firma, tip) {
		var operator = $("#raport-operator").val();
		var zile = [];
		if (tip == 'siz') {
			$('.month-days.active').each(function() {
	       		zile.push("'" + $(this).attr('data-zi') + "'");
	     	});
		} else {
			var an = $("#input-an").val();
			var luna = $("#input-luna").val();
			zile.push(an+'-'+luna);
		}
		$.ajax({
          	type: "POST",
          	url: situatie.base_url_ajax+"/ajax.php",
          	data: {
          		type: 'generare-raport',
          		data_select: zile,
          		firma_select: firma,
          		locatie: locatie,
          		tip: tip,
          		operator: operator          		
          	},
          	success: function(result){
          		dataa = jQuery.parseJSON(result);
          		$("#raport2").html(dataa.out);
          		situatie.export();
          	}
      });
	},
/**
 * Se preiau datele pentru EXPORT la click oe buton
 *
 */
	export: function() {
		$(".btn-print-group-current ul li a").on('click', function() {
			parinte = $(this).parent().parent().prev('button');
			var zile = [];
			$('.month-days.active').each(function() {
	       		zile.push("'" + $(this).attr('data-zi') + "'");
	     	});
			var ext = $(this).attr("data-ext"); 				// .pdf sau .xls
			var locatie = $(parinte).attr("data-locatie");		// id locatie
			var firma = $(parinte).attr("data-firma");			// id firma
			var tip = $(parinte).attr("data-tip");				// SIZ / SIL
			var data = zile;									// data selectata (2016-07-02)
			var perioada = $(parinte).attr("data-perioada");	// zilnic / lunar
			var operator = $("#raport-operator").val();			// id operator
			if (perioada == 'zilnic') {
				data = [];
				data.push("'" + $(parinte).attr('data-dataa') + "'");
			}
			situatie.genereaza_export({
				ext: ext,
				locatie: locatie,
				firma: firma,
				tip: tip,
				data: data,
				perioada: perioada,
				operator: operator,
				type: 'genereaza_export',
			});
		})
	},
/**
 *  Export pdf/xlsx luna selectata
 */
	export_lunar:  function() {
		$(".btn-print-group-luna ul li a").on('click', function() {
			parinte = $(this).parent().parent().prev('button');
			var an = $("#input-an").val();
			var luna = $("#input-luna").val();
			var ext = $(this).attr("data-ext"); 				// .pdf sau .xls
			var locatie = $("#raport-locatie").val();			// id locatie
			var firma = $("#raport-firma").val();				// id firma
			var tip = $("#raport-tip").val();					// SIZ / SIL
			var data = an+"-"+luna;									// data selectata (2016-07-02)
			var perioada = $(parinte).attr("data-perioada");	// zilnic / lunar
			var operator = $("#raport-operator").val();			// id operator
			situatie.genereaza_export({
				ext: ext,
				locatie: locatie,
				firma: firma,
				tip: tip,
				data: data,
				an: an,
				luna: luna,
				perioada: perioada,
				operator: operator,
				type: 'genereaza_export',
			});
		})
	},
/**
 * generare EXPORT 
 *
 * @param     date  = datele preluate de export() 
 *
 */
	genereaza_export: function(date) {
		situatie.loading();
		$.ajax({
          	type: "POST",
          	url: situatie.base_url_ajax+"/ajax.php",
          	data: date,
          	success: function(result){
          		situatie.downloadURI(result);
          		situatie.loading();
          	}
      	});
	}

}


$(document).ready(function() {
// se gereaza raportul la fiecare schimbare de an si luna
	$('#input-an, #input-luna').on('change', function() {
		$("#raport2").html('');
		firma = $('#raport-firma').val();
	   	locatie = $('#raport-locatie').val();
	   	tip = $('#raport-tip').val();
	   	// situatie.export_lunar();
		situatie.change_raport(locatie, firma, tip);
	});
// afisare modala RAPORT ZILNIC la click pe buton
	$('.btn-siz').on('click', function (e) {
	   	$('#modal-raport').modal();
	   	$("#zile-luna").show(); // se afiseaza zilele si butonul de print lunar (sunt doar pentru SIZ)
	   	$('#raport-modal-title').html('Generare rapoarte SITUATIE INCASARI ZILNICE');
	   	params = situatie.set_params(this, 'siz');
	   	
	   	situatie.change_raport(params.locatie, params.firma, params.tip);
	});
	situatie.export_lunar();
// afisare modala RAPORT LUNAR la click pe buton
	$('.btn-sil').on('click', function (e) {
	   	$('#modal-raport').modal();
	   	$('#raport-modal-title').html('Generare rapoarte SITUATIE INCASARI LUNARE');
	   	params = situatie.set_params(this, 'sil');
	   	$("#zile-luna").hide(); 	// se ascund zilele si butonul de print lunar (sunt doar pentru SIZ)
	   	firma = $('#raport-firma').val();
	   	locatie = $('#raport-locatie').val();
	   	tip = $('#raport-tip').val();
	   	// situatie.export_lunar();
	   	situatie.raport(locatie, firma, tip);			// se genereaza raport SIL
	});

})