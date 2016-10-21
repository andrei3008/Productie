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
                $(this).find('input').on({
                    focusout: function() {
                        contori.reset_td(valoare, type, row);
                    }
                });
            } else {
               
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
    /**
     * [save_reset resetare contori zi curenta]
     * @param  {[string]}   zi              [ziua curenta d-m-Y]
     * @param  {[int]}      idAparat        [id aparat]
     * @param  {[int]}      azi_idxInM      [idxInM zi curenta]
     * @param  {[int]}      azi_idxOutM     [idxOutM zi curenta]
     */
    save_reset: function(zi, idAparat, azi_idxInM, azi_idxOutM) {
        $("#reset_response").html('');
        var idxInE = $("#reset_indexIn").val();
        var idxOutE = $("#reset_indexOut").val();
        var idlocatie = $("#idlocatie").val();
        $.ajax({
            type: "POST",
            url: 'ajax/contori_resetel.php',
            data: {
                'zi': zi,
                'idxInE': idxInE,
                'idxOutE': idxOutE,
                'tip': 'azi',
                'idxInM': azi_idxInM,
                'idxOutM': azi_idxOutM,
                'idAparat': idAparat,
                'idlocatie': idlocatie
            },
            success: function (result) {
                $("#reset_response").html('<span style="color: '+result.color+'">'+result.mesaj+'</span>');
                contori.reset_tabel($('#luna').val(), $('#an').val(), idAparat)
            }
        });
    },

    /**
     * [save_set setare contori zi precedenta]
     * @param  {[string]} zi    [ziua precedenta d-m-Y]
     * @param  {[int]}      idAparat        [id aparat]
     * @param  {[int]}      ieri_idxInM     [idxInM zi precedenta]
     * @param  {[int]}      ieri_idxOutM    [idxOutM zi precedenta]
     */
    save_set: function(zi, idAparat, ieri_idxInM, ieri_idxOutM) {
        $("#set_response").html('');
        var idxInE = $("#set_indexIn_ieri").val();
        var idxOutE = $("#set_indexOut_ieri").val();
        var idlocatie = $("#idlocatie").val();
        $.ajax({
            type: "POST",
            url: 'ajax/contori_resetel.php',
            data: {
                'zi': zi,
                'idxInE': idxInE,
                'idxOutE': idxOutE,
                'tip': 'ieri',
                'idxInM': ieri_idxInM,
                'idxOutM': ieri_idxOutM,
                'idAparat': idAparat,
                'idlocatie': idlocatie
            },
            success: function (result) {
                $("#reset_response").html('<span style="color: '+result.color+'">'+result.mesaj+'</span>');
                contori.reset_tabel($('#luna').val(), $('#an').val(), idAparat);
            }
        });

    },

    /**
     * [show_modal_reset_electronic afisare modala si preluare contori din tabel]
     * @param  {[int]} idAparat
     * @param  {[int]} an
     * @param  {[int]} luna
     */
    show_modal_reset_electronic: function(idAparat, an, luna) {
        $(document).on('click', '#reset', function () {
            $('#modal_contori_reset').modal();
            if (screen.width > 1200) {
                $('#modal_contori_reset .modal-dialog').css({width: '1200px'});
            } else {
                $('#modal_contori_reset .modal-dialog').css({width: '100%'});
            }
            /*-----------------------------------------------------------------------------------
            |       Completare campuri din modala cu valorile din tabel                         |
            -----------------------------------------------------------------------------------*/
                var azi_idxInE = $('table tr.current').find("td[id*='idxInE']").attr('data-val');
                var azi_idxOutE = $('table tr.current').find("td[id*='idxOutE']").attr('data-val');
                    var azi_idxInM = $('table tr.current').find("td[id*='idxInM']").attr('data-val');
                    var azi_idxOutM = $('table tr.current').find("td[id*='idxOutM']").attr('data-val');
                $('#reset_indexIn').val(azi_idxInE);
                $('#reset_indexOut').val(azi_idxOutE);
                var ieri_idxInE = $('table tr.yesterday').find("td[id*='idxInE']").attr('data-val');
                var ieri_idxOutE = $('table tr.yesterday').find("td[id*='idxOutE']").attr('data-val');
                    var ieri_idxInM = $('table tr.yesterday').find("td[id*='idxInM']").attr('data-val');
                    var ieri_idxOutM = $('table tr.yesterday').find("td[id*='idxOutM']").attr('data-val');
                $('#set_indexIn_ieri').val(ieri_idxInE);
                $('#set_indexOut_ieri').val(ieri_idxOutE);

            /*-----------------------------------------------------------------------------------
            |       RESETARE / MODIFICARE indecsi electronici - salvare date din modala         |
            -----------------------------------------------------------------------------------*/
                var idAparat = $("#idAparat").val();
                $(document).on('click', '#save_reset', function () {
                    var zi = $("#reset_zi").val();
                    contori.save_reset(zi, idAparat, azi_idxInM, azi_idxOutM);
                });
                $(document).on('click', '#save_set', function () {
                    var zi = $("#set_ieri").val();
                    contori.save_set(zi, idAparat, ieri_idxInM, ieri_idxOutM);
                });
            /*---------------------------------------------------------------------------------*/
        });
    }
}
