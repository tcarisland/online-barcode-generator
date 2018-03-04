<?php

    require_once  "EAN13.php";

    $number = $_GET["barcode_value"];
    $field = $_GET["barcode_type"];

    $retval = "";
    if(!strcmp($field, "EAN13")) {
        $my_ean = new EAN13($number);
        $retval = $my_ean->generate_ean();
    } elseif (!strcmp($field, "CODE128")) {
        $retval = $number . "<br>";
    } else {
        $retval = "Illegal radio button value.<br>";
    }
    echo $retval;

?>