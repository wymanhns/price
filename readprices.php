<?php

$url="https://www.price.com.hk/category.php?c=100027&sort=2";

$response = getHTTPS($url);
$result1=strstr($response,"共");
$result2=strstr($result1,"種項目" ,1);
$total=substr($result2,3);

/*
for ($i=1,$x=1; $i<=$total; $i++, $x++)
{
    if ( $x > "15"){ //轉頁
        $x=1;
    }

    echo "The number is " . $i . "  x = ".$x."<br>";
 

}
*/
#echo $response;
echo $total;
/*
echo "*************************************************************************<br>";
$result3=strstr($result1,"li id="); //</li>
$result2=strstr($result3,'</li>',1);
$result41=strstr($result3,'</li>');
#$result4=strstr($result3,'li id="footer-wechat"',1);
#echo $result2;
echo "*************************************************************************<br>";

// CPU **************************
    // brand 
    if (preg_match("/intel/i", $result2)) {
        $brand="Intel";
    } elseif (preg_match("/amd/i", $result2)) {
        $brand="Amd";
    } elseif (preg_match("/via/i", $result2)) {
        $brand="VIA";
    } else {
        $brand="未发现匹配的。";
    }
    // cpuname
    $resulta=strstr($result2,'data-add-compare-name="'); //</li>
    $resultb=strstr($resulta,'" >添加比較',1);
    $cpuname=substr($resultb,23);
    #$resultc=strrpos($resultc," ");
    echo $brand . " " . $resultc;
    


//

*/


$pagecount=15;
$pages=$total/$pagecount;
$lastpagecount=$total%$pagecount;
if ($lastpagecount==0){ $lastpagecount=$pagecount; }

echo "總數 ".$pages." 頁,尾頁 " .$lastpagecount." 個";
$resultx=$response;
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

        if (preg_match("/intel/i", $result2)) {
            echo "Intel";
        } elseif (preg_match("/amd/i", $result2)) {
                
            echo "Amd";

        } elseif (preg_match("/via/i", $result2)) {
                
            echo "VIA";

        } else {

            echo "未发现匹配的。";

        }
        
    }
    

}


echo "99999";
$myfile = fopen("newfile.html", "w") or die("Unable to open file!");
$txt = $writeresult;
#$txt = $result2;
fwrite($myfile, $txt);

fclose($myfile);



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
