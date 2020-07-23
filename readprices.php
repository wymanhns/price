<?php

#$url="https://www.price.com.hk/category.php?c=100027&sort=2"; //ram
$url="https://www.price.com.hk/category.php?c=100014&sort=2"; //cpu

ReadData("cpu");

function ReadData($name) {

    switch ($name)
    {
    case "cpu":
        $url="https://www.price.com.hk/category.php?c=100014&sort=2"; //cpu
        break;
    case "ram":
        $url="https://www.price.com.hk/category.php?c=100027&sort=2"; //ram
        break;
    default:
    $url="https://www.price.com.hk/category.php?c=100014&sort=2"; //cpu
    }


    $response = getHTTPS($url);
    $result1=strstr($response,"共");
    $result2=strstr($result1,"種項目" ,1);
    $total=substr($result2,3);

    $pagecount=15;
    $pages=$total/$pagecount;
    $lastpagecount=$total%$pagecount;
    if ($lastpagecount==0){ $lastpagecount=$pagecount; }

    echo "總數 ".$total." 個 , 共有 ".$pages." 頁,尾頁 " .$lastpagecount." 個<bar>";
    $resultx=$response;
    $writeresult;
    for ($i=1; $i<=$pages; $i++)
    {
        $url1=$url."&page=".$i;
        echo $url1;
        $resultx = getHTTPS($url1);

        echo "******************************* Page ".$i." *************************************<br>";
        for ($x=1; $x<=$pagecount; $x++)
        {
            
            $result1=strstr($resultx,"li id="); //</li>
            $result2=strstr($result1,'</li>',1);
            $resultx=strstr($result1,'</li>');
            #$result4=strstr($result3,'li id="footer-wechat"',1);
            #echo $result2;
            $writeresult=$writeresult . "<br>" . $result2;
            
        }
        

    }


    $myfile = fopen( $name.".html", "w") or die("Unable to open file!");
    $txt = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . $writeresult;
    #$txt = $result2;
    fwrite($myfile, $txt);

    fclose($myfile);

}



function getHTTPS($url) {
    $curl = curl_init(); 

    if (!$curl) {
        die("Is not working"); 
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    #curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:64.0) Gecko/20100101 Firefox/64.0'); 
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:64.0) Gecko/20100101 Firefox/64.0'); 
    
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
    curl_setopt($curl, CURLOPT_FAILONERROR, true); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 50);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
    $html = curl_exec($curl); 


    curl_close($curl);
    return $html;
}

?>
