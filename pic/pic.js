var pic = {
	/**
	 * [update_idx - UPDATEAZA INDECSI VENITI DIN TABLE ERORIPK]
	 * @param  {[type]} string    		[IN/OUT]
     * @param  {[idAparat]} int         [id aparat]
	 * @param  {[tip]} char    		    [M/E]
	 * @return {[]}          			[]
	 */
	update_idx: function(type, idAparat, tip) { 
        $.ajax({
            type: "POST",
            url: 'pic_ajax.php',
            data: {
                'type': type,
                'idAparat': idAparat,
                'tip': tip
            },
            success: function (result) {
               alert(result);
           	}
        });
    },
}
$(document).ready(function() {
    $(document).on('click', '.mergeinout_mecanic', function (event) {
        event.preventDefault();
        var type = $(this).attr('data-type');
        var idAparat = $("#idAparat").val();
	    pic.update_idx(type, idAparat, 'M');
        
    })
    $(document).on('click', '.mergeinout_electronic', function (event) {
        event.preventDefault();
        var type = $(this).attr('data-type');
        var idAparat = $("#idAparat").val();
        pic.update_idx(type, idAparat, 'E');
        
    })
})