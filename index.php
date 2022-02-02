<?php
include('simple_html_dom.php');
$url= "http://livetv.sx/enx/allupcomingsports/";
$base_url="http://livetv.sx/";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

$page = curl_exec($curl);
curl_close($curl);
if(!empty($page)){
    #evdesc
    
    $dates=[];

    $html = str_get_html($page);
    $next_month =date('F',strtotime('first day of +1 month'));
    $evdesc = $html->find('span.evdesc');
    foreach($evdesc as $ev){
        if(str_contains($ev->plaintext, date("F")) || str_contains($ev->plaintext, $next_month))
    $dates[]=(explode("at",$ev->plaintext)[0]);
    #;
    }
    $dates = array_unique($dates);
    foreach($dates as $date){
        $json = [];
    for ($i=0;$i<count($evdesc);$i++){

        if(str_contains($evdesc[$i]->plaintext, $date))
        {
            $parent=$evdesc[$i]->parent ();
            $eve = $parent->find('a.live');

            $json[]=['Sports Name'=>$eve[0]->plaintext,"Sports Type"=>"","Competition Name"=>str_replace(')','',explode("(",$evdesc[$i]->plaintext)[1]),"Start Time"=>explode("  \t",$evdesc[$i]->plaintext)[0],"Event Link"=>strip_tags($base_url. $eve[0]->href)];
           
        }
        
    }
    if (!file_exists('events')) {
        mkdir('events', 0777, true);
    
    }
        $year = date('Y');

        if (date('m', strtotime($next_month))<date('m'))
        {
            $year = date('Y', strtotime('+1 year'));
        }

    $filename='events/'.str_replace(" ","-",$date.$year.'.json');
    $fp = fopen($filename, 'w');
    fwrite($fp, json_encode($json));
    fclose($fp);
}
    
    echo "all  events scrapped !<br>";
    echo "choose date to scrape : !<br>";
    foreach($dates as $date)
    echo "<a href='read.php?date=$date".$year."'>get $date's events browsers </a><br>";
    /*
    for($i=0;$i<count($eve);$i++){
        $json[]=['Sports Name'=>$eve[$i]->plaintext,"Sports Type"=>"","Start Time"=>date("d F Y")." at ".explode("(",$evdesc[$i]->textContent)[0],"Event Link"=>$base_url. $eve[$i]->href];
    }
    
    $fp = fopen('results.json', 'w');
    fwrite($fp, json_encode($json));
    fclose($fp);
    */
}