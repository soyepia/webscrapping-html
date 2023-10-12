<?php
$html = file_get_contents('https://unasp.br/cursos/graduacao');

$dom = new DOMDocument();
@$dom->loadHTML($html);

$unaCardGridStageElements = $dom->getElementsByTagName('div');
$unaCardWrapperElements = [];

foreach ($unaCardGridStageElements as $element) {
    $class = $element->getAttribute('class');
    if (strpos($class, 'una-card-grid__stage') !== false) {
        $unaCardWrapperElementsInStage = $element->getElementsByTagName('div');
        foreach ($unaCardWrapperElementsInStage as $wrapperElement) {
            $wrapperClass = $wrapperElement->getAttribute('class');
            if (strpos($wrapperClass, 'una-card-wrapper') !== false) {
                $unaCardWrapperElements[] = $wrapperElement;
            }
        }
    }
}

$texto = [];
foreach ($unaCardWrapperElements as $element) {
    $text = $element->textContent;
    $text = iconv('UTF-8', 'UTF-8//IGNORE', $text);
    $texto[] = $text . "\n";
}

$csvFileName = 'dados.csv';
$csvFile = fopen($csvFileName, 'w');
fwrite($csvFile, "\xEF\xBB\xBF");
fputcsv($csvFile, array('LISTA DE CURSOS'));

foreach ($texto as $row) {
    fputcsv($csvFile, array($row));
}
fclose($csvFile);

echo 'Arquivo CSV gerado com sucesso (UTF-8).';
