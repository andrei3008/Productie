var macuri = {
	domain: domain,
	reset_content: function() {
		$("#modals #modal-content .row").html('<div class="alert alert-info" role="alert">Se incarca continutul...</div>');
		$("#modals #modal-title").html('Macuri');
	},
	show_tabel: function() {
		$('#modals').modal();
		macuri.reset_content();
		if (screen.width > 1200) {
            $('#modals .modal-dialog').css({width: '1200px'});
        }
        $.ajax({
            type: "POST",
            url: macuri.domain + '/ajax/macuri-table.php',
            success: function (result) {
                $("#modals #modal-content .row").html(result);
                $('#table-macuri').dataTable();
                $('#table-macuri tbody').on('click', 'td a.seriiMacuri', function (){
                    // console.log('intra');
                    macuri.atribuiePic(this);
                });
          //       $('.seriiMacuri').on('click', function () {
          //            console.log('intra');
          //       	macuri.atribuiePic(this);
		        // });
            }
        });
	},
	atribuiePic: function(dom) {
		var mac = $(dom).attr("data-mac");
        var macNou = $(dom).attr("data-macNou");
        var seria = $(dom).attr("data-seria");
        var idAparat = $(dom).attr("data-idAparat");
        var macDeAsociat = $(dom).attr("data-macDeAsociat");
        if (mac == "") {
            $.ajax({
                url: macuri.domain+"/ajax/atribuiePic.php",
                type: "POST",
                data: {
                    'seria': seria,
                    'idAparat': idAparat,
                    'idmac': macNou,
                    'macDeAsociat': macDeAsociat
                },
                success: function (result) {
                    alert(result);
                    // noty({text: result, type: 'success'});
                    // location.reload();
                }
            });
        } else {
            if (window.confirm("Aparatul cu id " + idAparat + " si seria " + seria + " are deja mac-ul " + mac + ". Sunteti sigur ca doriti sa suprascrieti ?")) {
                $.ajax({
                    url: macuri.domain+"/ajax/atribuiePic.php",
                    type: "POST",
                    data: {
                        'seria': seria,
                        'idAparat': idAparat,
                        'idmac': macNou,
                        'macDeAsociat': macDeAsociat
                    },
                    success: function (result) {
                        alert(result);
                        // noty({text: result, type: 'success'});
                        // location.reload();
                    }
                });
            }
        }
	},
	sterge_macuri_test: function(dom) {
		$(dom).attr("disabled",true);
        $(dom).text("Loading ....");
        $.ajax({
            url: macuri.domain+"/ajax/stergeMac.php",
            type: "POST",
            success: function (response) {
            	noty({text: 'Macurile de test au fost sterse!', type: 'success'});
            },
            complete : function(){
            	 $(dom).text("Sterge toate macurile de test");
            	 $(dom).attr("disabled",false);
            }
        });
	}
}
$(document).ready(function () {  
    $("#button-vezi-tabel").click(function (e) {
        macuri.show_tabel();
        
    });
    $('#buton-sters').on('click', function () {
    	 macuri.sterge_macuri_test(this);;
    });
});