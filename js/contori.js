var contori= {
    reset_td: function(valoare, type, row) {
        $('#'+type+'_'+row).html(valoare);
        $('#'+type+'_'+row).attr('data-val', valoare);
        $('#'+type+'_'+row).removeClass('input');
    },
    editare_celule: function(idAparat) {
        $('tr.deblocat td.editable').on('click', function () {
            var id = $(this).prop('id');
            var e = id.split('_');
            var type = e[0];
            var row = e[1];
            var valoare = $(this).attr('data-val');
            var id_tabel = $(this).attr('data-idtabel');
            var val_w = $(this).width();
            if (!$(this).hasClass('input')) {
                $(this).html('<input type="text" value="'+valoare+'" id="input_'+type+'_'+row+'" data-type="'+type+'" style="width:'+val_w+'px"/>');
                $(this).addClass('input');
                contori.salvare_valori('input_'+type+'_'+row, idAparat, id_tabel);
            } else {
                $('tr.deblocat td.editable').blur(function () {
                     contori.reset_td(valoare, type, row);
                });
            }
            
        }); 
        
    },
    salvare_valori: function(id, idAparat, id_tabel) {
        $('#'+id).on('change', function() {
            var valoare = $('#'+id).val();
            var e = id.split('_');
            var type = e[1];
            var row = e[2];
            var luna = $("#luna").val();
            var an = $("#an").val();
            $.ajax({
                type: "POST",
                url: 'ajax/contori_update.php',
                data: {
                    'valoare': valoare,
                    'zi': row,
                    'type': type,
                    'luna': luna,
                    'an': an,
                    'idAparat': idAparat,
                    'id_tabel': id_tabel
                },
                success: function (result) {
                    contori.reset_td(valoare, type, row);
                    contori.reset_tabel(luna, an, idAparat)
                }
            });
        })
        
    },
    reset_tabel: function(luna, an, idAparat) {
        $('.loading').show();
        $.ajax({
            type: "POST",
            url: 'ajax/contori_tabel.php',
            data: {
                'luna': luna,
                'an': an,
                'idAparat': idAparat
            },
            success: function (result) {
                $("#table_content").html(result);
                $('.loading').hide();
                contori.editare_celule(idAparat);
                contori.show_modal_reset_electronic(idAparat, an, luna);
            }
        });
    },
    show_modal_reset_electronic: function(idAparat, an, luna) {
        $(document).on('click', '#reset', function () {
            $('#modal_contori_reset').modal();
            if (screen.width > 1200) {
                $('#modal_contori_reset .modal-dialog').css({width: '1200px'});
            } else {
                $('#modal_contori_reset .modal-dialog').css({width: '100%'});
            }
            var today = new Date();
            var dd = today.getDate();
            console.log(dd);
            $.ajax({
                type: "POST",
                url: 'ajax/contori_resetel.php',
                data: {
                    'test': 'test'
                },
                success: function (result) {
                    
                }
            });
        });
    }
}
