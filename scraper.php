<?php
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.' . urldecode($_GET['u']) . '-download',
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_RETURNTRANSFER => true,
));

$response = curl_exec($curl);

curl_close($curl);

$dom = new DomDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($response);
libxml_clear_errors();

$xpath = new DomXPath($dom);
$page = $xpath->query("//div[@id='SITE_PAGES']")->item(0);
$hrefs = $xpath->query('.//a', $page);
$out = [];
foreach ($hrefs as $h) {
    $href = $h->getAttribute('href');
    if (strpos($href, '.zip') !== false) {
        $out[] = $h->getAttribute('href');
    }
}

$u = array_values(array_unique($out));
header('Content-type:application/json');
echo json_encode($u);

