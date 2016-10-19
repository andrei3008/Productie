<meta name="viewport" content="width=900">
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel = "stylesheet" type = "text/css" href = "<?php echo DOMAIN ?>/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN ?>/css/custom.css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type = "text/javascript" src = "<?php echo DOMAIN; ?>/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/instafilta.min.js"></script>
<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/jquery.printPage.js"></script>
<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/bootstrap-dialog.js"></script>
<script type='text/javascript' src='<?php echo DOMAIN; ?>/js/custom.js'></script>
<script type='text/javascript' src='<?php echo DOMAIN; ?>/js/jquery-ui.min.js'></script>
<script src="<?php echo DOMAIN; ?>/js/jquery-idleTimeout.js"></script>
<script src="<?php echo DOMAIN; ?>/js/noty/js/noty/packaged/jquery.noty.packaged.min.js"></script>
<link rel="stylesheet" href="<?php echo DOMAIN; ?>/js/dataTable/dataTable.css">
<script src="<?php echo DOMAIN; ?>/js/dataTable/dataTables.js"></script>
<script>
    $(document).tooltip({
        content: function () {
            var element = $(this);
            return element.attr("title");
        }
    })
    $.noty.defaults = {
        layout: 'top',
        theme: 'bootstrapTheme', // or 'relax'
        type: 'alert',
        text: '', // can be html or string
        dismissQueue: true, // If you want to use queue feature set this true
        template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
        animation: {
            open: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceInLeft'
            close: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceOutLeft'
            easing: 'swing',
            speed: 500 // opening & closing animation speed
        },
        timeout: false, // delay for closing event. Set false for sticky notifications
        force: false, // adds notification to the beginning of queue when set to true
        modal: false,
        maxVisible: 5, // you can set max visible notification for dismissQueue true option,
        killer: false, // for close all notifications before show
        closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
        callback: {
            onShow: function() {},
            afterShow: function() {},
            onClose: function() {},
            afterClose: function() {},
            onCloseClick: function() {},
        },
        buttons: false // an array of buttons
    };
    $.extend( true, $.fn.dataTable.defaults, {
        "bJQueryUI": true,
        "bAutoWidth": false,
        "sPaginationType": "full_numbers",
        "sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
        "oLanguage": {
            "sLengthMenu": "<span>Show entries:</span> _MENU_",
            "oPaginate": { "sFirst": "First", "sLast": "Last", "sNext": ">", "sPrevious": "<" }
        }
    });
</script>
<style>
    label {
        display: inline-block;
        width: 5em;
    }
</style>