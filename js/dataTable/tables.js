$(document).ready(function() {
	$.fn.dataTable.ext.order['dom-noinput'] = function  ( settings, col ) {
	    return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	        // console.log (td+' '+i);
	        return $(td).html();
	    } );
	}
	$.fn.dataTable.ext.order['dom-text'] = function  ( settings, col ) {
	    return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	        return $('input', td).val();
	    } );
	}
	/* Create an array with the values of all the input boxes in a column, parsed as numbers */
	$.fn.dataTable.ext.order['dom-text-numeric'] = function  ( settings, col ) {
	    return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	        return $('input', td).val() * 1;
	    } );
	}	 
	/* Create an array with the values of all the select options in a column */
	$.fn.dataTable.ext.order['dom-select'] = function  ( settings, col ) {
	    return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	        return $('select', td).val();
	    } );
	} 
	/* Create an array with the values of all the checkboxes in a column */
	$.fn.dataTable.ext.order['dom-checkbox'] = function  ( settings, col ) {
	    return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	        return $('input', td).prop('checked') ? '1' : '0';
	    } );
	}
	$.extend( true, $.fn.dataTable.defaults, {
	    "bJQueryUI": true,
		"bAutoWidth": false,
		"sPaginationType": "full_numbers",
		"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
		"oLanguage": {
			"sLengthMenu": "<span>Show entries:</span> _MENU_",
			"oPaginate": { "sFirst": "First", "sLast": "Last", "sNext": ">", "sPrevious": "<" }
		},
		"aoColumnDefs": [
			{ "sSortDataType": "dom-text", "aTargets": [ "_all" ] },
			{ "sType": "numeric", "aTargets": [ -2 ] }
		]
	} );
	 
	oDataTable = $('#dataTables').dataTable();
});