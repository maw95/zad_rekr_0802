<?php

require getcwd() . "/db_model.php";
$model = new db_model();
$con = $model->connect('localhost:3306', 'root', '', 'products');
$model->prepareTables($con);


$strContents = file_get_contents('products_1927a13ce63d227pl.xml');
$strDatas = $model->Xml2Array($strContents);

foreach ($strDatas['products']['item'] as $prod) {
    $model->insert_product($con, $prod);
}
printf('<h1>' . count($strDatas['products']['item']) . ' products added to database.');
?>