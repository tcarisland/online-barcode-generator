<?php

    require_once  "EAN13.php";
    require_once  "CODE128.php";

    $number = $_GET["barcode_value"];
    $field = $_GET["barcode_type"];

    $retval = "";
    if(!strcmp($field, "EAN13")) {
        $my_ean = new EAN13($number);
        $retval = $my_ean->generate_ean();
    } elseif (!strcmp($field, "CODE128")) {
        $my_code_128 = new CODE128($number);
        $retval = $my_code_128->generate_barcode();
    } else {
        $retval = "Illegal radio button value.<br>";
    }
    echo $retval;

?>