<?php
ini_set('max_execution_time', '0');
include('simple_html_dom.php');
// Read the JSON file
$filename='events/'.str_replace(" ","-",$_GET['date']).'.json';
$json = file_get_contents($filename);

// Decode the JSON file
$json_data = json_decode($json,true);


$data = [];
// Display data sporttitle
for($i=0;$i<count($json_data) ;$i++){
    $browsers = [];
$url=$json_data[$i]['Event Link'];

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

$page = curl_exec($curl);
curl_close($curl);
if(!empty($page)){
    $html = str_get_html($page);
    
    $evdesc = $html->find('span.sporttitle');
    $br = $html->find("a[onclick=window.open(this.href, this.target, 'width=920,height=710,'+' location=yes,toolbar=no,menubar=no,status=no');return false;]");
    

    foreach($br as $b){
    $browsers[] = $b->href;

    
}

    $json_data[$i]['Sports Type']=$evdesc[0]->plaintext;
    $data[]=["Sports Name"=> $json_data[$i]['Sports Name'],"Browsers"=>$browsers];

   
}}

if (!file_exists('browsers')) {
    mkdir('browsers', 0777, true);

}

$newJsonString = json_encode($json_data);
file_put_contents($filename, $newJsonString);
file_put_contents('browsers/'.str_replace(" ","-",$_GET['date']).'.json', json_encode($data));
#window.open(this.href, this.target, 'width=920,height=710,'+' location=yes,toolbar=no,menubar=no,status=no');return false;
#window.open(this.href, this.target, 'width=920,height=710,'+' location=yes,toolbar=no,menubar=no,status=no');return false;
#http://cdn.livetv474.me/webplayer2.php?t=youtube&c=iD92V3IpFgk&lang=en&eid=1116227&lid=1699722&ci=4173&si=39

