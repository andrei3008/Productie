var audit = {
	get_audit: function(idAparat) {
		 $('.loading').show();
		 $('#timp_audit, #response').html('');
     audit.get_date(idAparat);
		 var timer = Date.now();
		 $.ajax({
            url: "ajax_audit.php",
            type: "POST",
            data: {
                'audit': "audit",
                'idAparat': idAparat
            },
            success: function (result) {
            	var err = result.err;
              var mesaj = result.mesaj;
            	var timp = result.timp;
              if (err == '0') {
                  $('#timp_audit').html(timp);
                  // $('#timp_audit').html(secs[0].replace(/\*/g, ''));
              }
              $('#response').html(mesaj);
              
            }
        });
	},
	get_date: function(idAparat, timer, mesaj, timp, err) {
		$.ajax({
        url: "ajax_date.php",
        type: "POST",
        async: true,
        data: {
            'audit': "audit",
            'idAparat': idAparat
        },
        success: function (result) {
            $('#stareAparatBazaDeDate #tabel').html(result);
            // timer2 = Date.now()
            
            // trecut = parseInt((timer2 - timer)/1000);
            
            $('.loading').hide();
        }
    });
	},
  get_audit_all: function(idAparat) {
     var timer = Date.now();
     $('.loading').show();
     $('#timp_audit, #response').html('');
     $.ajax({
            url: "ajax_audit_all.php",
            type: "POST",
            data: {
                'audit': "audit",
                'idAparat': idAparat
            },
            success: function (result) {
              trecut = parseInt((Date.now() - timer)/1000);
              $('#timp_audit').html(trecut+'s');
              //$('#stareAparatBazaDeDate #tabel').html(result);
              
              if (result.err == '0') {
                var date = result.date;
                $.each(date, function(index, element) {
                    $('#aparat-'+index+' td:last-child').html(element);
                });
                
              }
              $("#audit-response").html(result.mesaj);
              $('.loading').hide();
            }
        });
  },
  updateIpRetur: function(idAparat, serieAparat) {
     $.ajax({
            url: "ajax_updateIpRetur.php",
            type: "POST",
            data: {
                'idAparat': idAparat,
                'serieAparat': serieAparat
            },
            success: function (result) {
              alert(result);
              audit.get_audit_all(idAparat);
            }
        });
  },
}
$(document).ready(function () {
   var idAparat = $("#idAparat").val();
	 var serieAparat = $("#serieAparat").val();
    $("#audit").click(function (event) {
        event.preventDefault();
        // audit.get_audit(idAparat);
       	audit.get_audit_all(idAparat);
        // setTimeout(function(){audit.get_date(idAparat)}, 1000);
    });
    $("#updateIpRetur").click(function () {
        audit.updateIpRetur(idAparat, serieAparat);
    })
});