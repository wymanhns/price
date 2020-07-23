<?php

$url="https://www.price.com.hk/category.php?c=100014&sort=2";
$cpu="10014";
$response = getHTTPS($url);
$result1=strstr($response,"共");
$result2=strstr($result1,"種項目" ,1);
$total=substr($result2,3);
$rData=array();

$pagecount=15;
$pages=$total/$pagecount;
$lastpagecount=$total%$pagecount;
if ($lastpagecount==0){ $lastpagecount=$pagecount; }

echo "總數 ".$total." 個 , 共有 ".$pages." 頁,尾頁 " .$lastpagecount." 個<bar>";
$resultx=$response;
$writeresult="<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";

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
        for ($y=1; $y<=15; $y++)
        {

        #cho $result2;
        $xyz= $y + (($x-1)*15);

        echo "******************************** " . $xyz . " / ". $total . "**********************************<br>";

            // cpuid
            $resulta=strstr($result2,'data-add-compare="'); 
            $resultb=strstr($resulta,'" data-add-compare-cid',1);
            $cpuid=substr($resultb,18);
            //
            // cpuname
                $resulta=strstr($result2,'data-add-compare-name="'); 
                $resultb=strstr($resulta,'" >添加比較',1);
                $cpuname=substr($resultb,23);
            //
            // brand 
            if (preg_match("/intel/i", $cpuname)) {
                $brand="Intel";
            } elseif (preg_match("/amd/i", $cpuname)) {
                $brand="Amd";
            } elseif (preg_match("/via/i", $cpuname)) {
                $brand="VIA";
            } else {
                $brand="未知品牌。";
            }
            //    
            // cpupic
                $resulta=strstr($result2,'/space'); 
                $resultb=strstr($resulta,'"/></a>',1);
                $cpupic=substr($resultb,1);
            //
                
            // cpu family cpucore cputhreads
                $resulta=strstr($result2,'<!-- product name -->'); 
                $resultb=strstr($resulta,'<!-- product rating -->',1);
                if (strlen($resultb) > 500 ) {
                    $resulty=strstr($resultb,'"product-caption">('); 
                    $resultz=strstr($resulty,')</span></div>',1);
                    $resultb=substr($resultz,19);
                    $cpufamily=strstr($resultb,',',1);
                    $resulty=strstr($resultb,','); 
                    $resultz=strstr($resulty,'Core',1);
                    $cpucore=substr($resultz,1);
                    $resulty=strstr($resultb,'Core '); 
                    $resultz=strstr($resulty,'Threads',1);
                    $cputhreads=substr($resultz,5);
                } else {
                    $cpufamily="";
                    $cpucore="";
                    $cputhreads="";
                }
            //

            // cpufrequency mtfrequency
                $resulta=strstr($result2,'時脈'); 
                $resultb=strstr($resulta,'MHz',1);
                $cpufrequency=substr($resultb,64);
                #echo strlen($cpufrequency);
                $resulta=strstr($result2,'外頻'); 
                $resultb=strstr($resulta,'MHz',1);
                $cpumtfrequency=substr($resultb,64);
                #echo strlen($cpumtfrequency);        
            //
            // cpusocket
                $resulta=strstr($result2,'Socket'); 
                $resultb=strstr($resulta,'</span></td>',1);
                $cpusocket=substr($resultb,64);
            //
            // cpucache
                $resulta=strstr($result2,'Cache:'); 
                $resultb=strstr($resulta,'</span></td>',1);
                $cpucache=substr($resultb,63);
                #echo strlen($cpucache);
            //
            // cpuminprice cpumaxprice    text-price-number    行貨
                $resulta=strstr($result2,'text-price-number" data-price="'); 
                $resultb=strstr($resulta,'</span>',1);
                $stoppos=strpos(substr($resultb,31),'.');
                #echo "//".$resulta ."//";
                $cpuminprice=substr($resultb,31,$stoppos);
                #echo "///".strlen($cpuminprice)."///";
                $resulta=substr($resulta,31);
                $resulta=strstr($resulta,'text-price-number" data-price="'); 
                $resultb=strstr($resulta,'</span>',1);
                $stoppos=strpos(substr($resultb,31),'.');
                $cpumaxprice=substr($resultb,31,$stoppos);
                #echo "//".substr($resulta,31) ."//";
                #echo strlen($cpumaxprice);  
            //
            #echo "Strlen : ". strlen($resultb) . "<br>*" .$resultb . "*<br>" ;
            echo "CPUID:" . $cpuid . " CPU BRAND: " . $brand . " CPU NAME: " . $cpuname . " FAMILY: " . $cpufamily . " CORE: " .$cpucore. " THREADS: " .$cputhreads. " FREQUENCY: " . $cpufrequency."MHz MAX FREQUENCY: " .$cpumtfrequency . "MHz SOCKET: " .$cpusocket. " CACHE: " .$cpucache ."<br>";
            echo "IMG: <img src='https://www.price.com.hk/" . $cpupic . "'/>" . "<br>";
            echo " MINPRICE: " . $cpuminprice . " MAXPRICE: " . $cpumaxprice . "<br>";
            
            $yout="CPUID:" . $cpuid . " CPU BRAND: " . $brand . " CPU NAME: " . $cpuname . " FAMILY: " . $cpufamily . " CORE: " .$cpucore. " THREADS: " .$cputhreads. " FREQUENCY: " . $cpufrequency."MHz MAX FREQUENCY: " .$cpumtfrequency . "MHz SOCKET: " .$cpusocket. " CACHE: " .$cpucache ."<br> IMG: <img src='https://www.price.com.hk/" . $cpupic . "'/>" . "<br> MINPRICE: " . $cpuminprice . " MAXPRICE: " . $cpumaxprice . "<br>";
            
            array_push($rData,array($cpuid,$brand,$cpuname,$cpufamily,"https://www.price.com.hk/" . $cpupic ,$cpusocket,$cpufrequency,$cpumtfrequency,$cpucache,$cpuminprice,$cpumaxprice));
            $resultx=strstr($result2,"比較報價");
            $result2=substr($resultx,17);

            $writeresult=$writeresult . "<br>" . $yout;
        }
        echo "*************************************************************************<br>";

        $writeresult="<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
        
    }
    

}


echo "99999";
$myfile = fopen("newfile.html", "w") or die("Unable to open file!");
$txt = $writeresult . "總數 ".$total." 個頂目 , 共有 ".$pages." 頁,尾頁 " .$lastpagecount." 個<br>" ;
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
