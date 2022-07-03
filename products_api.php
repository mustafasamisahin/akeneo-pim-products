<?php
require_once './vendor/autoload.php';

$client_id = '';
$secret = '';
$username = '';
$password = '';
$url = '';

$clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder($url);
$client = $clientBuilder->buildAuthenticatedByPassword($client_id, $secret, $username, $password);

$values = array();
$output = array();


$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('completeness', '>', 70, ['scope' => 'ecommerce']);
$searchFilters = $searchBuilder->getFilters();


$products = $client->getProductApi()->listPerPage(50, false, ['search' => $searchFilters, 'scope' => 'ecommerce']);
$assetClient = $client->getProductMediaFileApi();
$attributeClient = $client->getAttributeApi();

$attributePages = $attributeClient->listPerPage(100);
$allAttributes = $attributePages->getItems();
while ($attributePages->hasNextPage()) {
    $attributePages = $attributePages->getNextPage();
    $allAttributes = array_merge($allAttributes, $attributePages->getItems());
}
foreach ($allAttributes as $attribute) {
    $values[] = $attribute["code"];
}

$allProducts = $products->getItems();

while ($products->hasNextPage()) {
    $products = $products->getNextPage();
    $allProducts = array_merge($allProducts, $products->getItems());
}

$i = 0;
foreach ($allProducts as $product) {
    $output[$i]["identifier"] = $product["identifier"];
    $output[$i]["categories"] = $product["categories"] ?? '';
    foreach ($values as $value) {
        if($value == "price"){
            print_r($product["values"]["price"]);
        }
        $output[$i][$value] = $product["values"][$value][0]["data"] ?? '';
    }

    if (array_key_exists("picture", $product["values"]) && array_key_exists("data", $product["values"]["picture"][0])) {
        $data = $product["values"]["picture"][0]["data"];
        $begin = strripos($data, "/") + 1;
        $code = substr($data, $begin);
        $down = $assetClient->download($data);
        $fileLocation = './products/' . basename($code);
        file_put_contents($fileLocation, $down->getBody());
        $output[$i]["image"] = $fileLocation;
    }
    $i++;
}
$fp = fopen('results.json', 'w');
fwrite($fp, json_encode($output, JSON_PRETTY_PRINT));
fclose($fp);
