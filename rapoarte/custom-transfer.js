$(document).on('click', '.luna', function () {
    var luna = $(this).attr('data-luna');
    var an = $(this).attr('data-an');
    var op = ($(this).attr('data-op') != 'undefined' ) ? $(this).attr('data-op') : '';
    console.log(an, luna);
    $.ajax({
        type: "POST",
        url: '../ajax/tabelTrasfer.php',
        data: {
            'luna': luna,
            'an': an,
            'op' : op
        },
        success: function (result) {
            $('.modal').modal();
            $('.modal-body').html(result);
            //                console.log(result);
        }
    })
})
$(document).on('click', '.btn-pdf', function () {
    var an = $("input[name='input-an']").val();
    var luna = $("input[name='input-luna']").val();
    var op = ($("input[name='input-op']").val() != 'undefined' ) ? $("input[name='input-op']").val() : '';
    var ext = $(this).attr("data-ext"); // .pdf sau .xls
    console.log(an, luna, ext);
    $.ajax({
        type: "POST",
        url: '../rapoarte/ajax_rapoarte.php',
        data: {
            'luna': luna,
            'an': an,
            'ext': ext,
            'idOperator': op
        },
        success: function (result) {
            window.open(result);
        }
    })
})
$(document).on('click', '.btn-xls', function () {
    var an = $("input[name='input-an']").val();
    var luna = $("input[name='input-luna']").val();
    var op = ($("input[name='input-op']").val() != 'undefined' ) ? $("input[name='input-op']").val() : '';
    var ext = $(this).attr("data-ext"); // .pdf sau .xls
    //console.log(an, luna, ext);
    $.ajax({
        type: "POST",
        url: '../rapoarte/ajax_rapoarte.php',
        data: {
            'luna': luna,
            'an': an,
            'ext': ext,
            'idOperator': op
        },
        success: function (result) {
            window.open(result);
        }
    })
})
