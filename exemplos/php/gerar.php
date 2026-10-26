<?php
// Monta a URL da imagem para usar num <img> (ex.: página de produto).
// Uso: php gerar.php

function geracode_barcode_url(string $format, string $data, array $extra = []): string {
    $query = http_build_query(array_merge(['format' => $format], $extra, ['data' => $data]));
    return 'https://www.geracodigo.com.br/api/v1/barcode?' . $query;
}

function geracode_qr_url(string $data, array $extra = []): string {
    $query = http_build_query(array_merge($extra, ['data' => $data]));
    return 'https://www.geracodigo.com.br/api/v1/qr?' . $query;
}

$sku = 'SKU-00042';
$url = geracode_barcode_url('code128', $sku);
echo '<img src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($sku) . '">' . PHP_EOL;

// Salvar o PNG em disco:
file_put_contents('qrcode.png', file_get_contents(geracode_qr_url('https://exemplo.com.br', ['size' => 400])));
