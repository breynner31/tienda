<?php

 define("KEY_TOKEN","APR.wqc-354*");
 define("MONEDA","$");
 session_start();
$num_cart ='';
if(isset($_SESSION['carrito']['productos'])) {
    $num_Cart = count($_SESSION['carrito']['productos']);
}
?>