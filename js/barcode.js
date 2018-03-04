function generate_barcode() {
    var number = jQuery("#searchfield").val();
    var selectedField = "";
    var selected = jQuery("input[type='radio'][name='field']:checked");
    selectedField = selected.val();
    var append = "barcode_generator.php?barcode_value=" + number + "&barcode_type=" + selectedField;
    jQuery.ajax( {
        type : "GET",
        url : "http://www.tcarisland.com/wp-content/plugins/barcode/" + append,
        success : function( data ) {
            jQuery("#barcode_svg").html(data);
        }
    });
}
