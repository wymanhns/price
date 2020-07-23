<?php

$url="https://www.price.com.hk/category.php?c=100027&sort=2";
$url="http://127.0.0.1/1.html";
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

    $rData=array();
    $response = getHTTPS($url);
    $result1=strstr($response,"共");
    $result2=strstr($result1,"種項目" ,1);
    $total=substr($result2,3);

    $pagecount=15;
    $pages=$total/$pagecount;
    $lastpagecount=$total%$pagecount;
    if ($lastpagecount==0){ $lastpagecount=$pagecount; }

    echo "總數 ".$total." 個 , 共有 ".$pages." 頁,尾頁 " .$lastpagecount." 個<br>";
    $resultx=$response;
    $writeresult;
    for ($i=1; $i<=$pages; $i++)
    {
        $url1=$url."&page=".$i;
        // for test
        #$url1="http://127.0.0.1/1.html";
        //
        echo $url1. "<br>";
        $resultx = getHTTPS($url1);

        echo "******************************* Page ".$i." *************************************<br>";
        $pagecount=15;

            
        $result2=strstr($resultx,"li id="); //</li>
        #$result2=strstr($result1,'</li>',1);
        #$resultx=strstr($result1,'</li>');
        #$result4=strstr($result3,'li id="footer-wechat"',1);
        #$result2=$resultx;
            
        for ($y=1; $y<=15; $y++)
        {

            $xy = $y + (($i - 1) * 15);
            #echo $result2;

            echo "******************************** " . $xy . " / ". $total . "**********************************<br>";

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
            // hot         
            $resulty=strstr($resulta,'<!-- product name -->',1);
            $resultz=strstr($resulty,'hot_20'); 
            $resulty=strstr($resultz,'人氣項目',1);
            #print_r (strlen($resulty)); 
            $hot=0;
            if (strlen($resulty) > 0){
                $hot=1;
                echo "*** Hot! ***";
            }
            // 
            // cpu family cpucore cputhreads mtfrequency
            if ($brand == "Intel"){
                $resulta=strstr($result2,'<!-- product name -->'); 
                $resultb=strstr($resulta,'<!-- product rating -->',1);
                if (strlen($resultb) > 500 ) {
                    $resulty=strstr($resultb,'"product-caption">('); 
                    $resultz=strstr($resulty,')</span></div>',1);
                    $resultb=substr($resultz,19);
                    $cpufamily=strstr($resultb,',',1);
                    $resulty=strstr($resultz,'('); 
                    $resultz=stristr($resulty,'core',1);
                    $cpucore=substr($resultz,1);
                    preg_match_all('/\d+/',$resultz,$core);;  //只取數字
                    $cpucore=$core[0][0];
                    $resulty=stristr($resultb,'core'); 
                    $resultz=stristr($resulty,'threads',1);
                    $cputhreads=substr($resultz,4);
                    preg_match_all('/\d+/',$cputhreads,$threads);;  //只取數字
                    $cputhreads=$threads[0][0];
                    $resulta=strstr($result2,'外頻:'); 
                    $resultb=strstr($resulta,'Hz',1);
                    $cpumtfrequency=substr($resultb,64);
                    #echo strlen($cpumtfrequency);  
                } else {
                    $cpufamily="";
                    $cpucore="";
                    $cputhreads="";
                }
            } else {
                $resulta=strstr($result2,'<!-- product name -->'); 
                $resultb=strstr($resulta,'<!-- product rating -->',1);
                if (strlen($resultb) > 500 ) {
                    $resulty=strstr($resultb,'"product-caption">('); 
                    $resultz=strstr($resulty,')</span></div>',1);
                    $resultb=substr($resultz,19);
                    $cpufamily=strstr($resultb,',',1);
                    $resulty=strstr($resultz,'('); 
                    $resultz=stristr($resulty,'core',1);
                    $cpucore=substr($resultz,1);
                    preg_match_all('/\d+/',$resultz,$core);;  //只取數字
                    $cpucore=$core[0][0];
                    $resulty=stristr($resultb,'core'); 
                    $resultz=stristr($resulty,'thread',1);
                    $cputhreads=substr($resultz,4);
                    preg_match_all('/\d+/',$cputhreads,$threads);;  //只取數字
                    $cputhreads=$threads[0][0];
                    $cpumtfrequency="";
                } else {
                    $cpufamily="";
                    $cpucore="";
                    $cputhreads="";
                }
            }
            //

            // cpufrequency 
                $resulta=strstr($result2,'時脈:'); 
                $resultb=strstr($resulta,'Hz',1);
                $cpufrequency=substr($resultb,64);
                #echo strlen($cpufrequency);      
            //
            // cpusocket
                $resulta=strstr($result2,'Socket:'); 
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
            // cpunew
                $cpunew=$xy;
            //
            #echo "Strlen : ". strlen($resultb) . "<br>*" .$resultb . "*<br>" ;
            echo "CPUID:" . $cpuid . " CPU BRAND: " . $brand . " CPU NAME: " . $cpuname . " FAMILY: " . $cpufamily . " CORE: " .$cpucore. " THREADS: " .$cputhreads. " FREQUENCY: " . $cpufrequency."Hz MAX FREQUENCY: " .$cpumtfrequency . "Hz SOCKET: " .$cpusocket. " CACHE: " .$cpucache ."<br>";
            echo "IMG: <img src='https://www.price.com.hk/" . $cpupic . "'/>" . "<br>";
            echo " MINPRICE: " . $cpuminprice . " MAXPRICE: " . $cpumaxprice . "<br>";
            
            
            
            array_push($rData,array($cpuid,$brand,$cpuname,$cpufamily,"https://www.price.com.hk/" . $cpupic ,$cpusocket,$cpucore,$cputhreads,$cpufrequency,$cpumtfrequency,$cpucache,$cpuminprice,$cpumaxprice,$cpunew,$hot));
            $resultx=strstr($result2,"比較報價");
            $result2=substr($resultx,17);
            #$result2=$resultx;
            #echo $resultx;
        }

        #echo $result2;
        $jsonx = json_encode($rData);
        #print_r ($jsonx);
        file_put_contents('cpu.json', $jsonx);

        $writeresult=$writeresult . "<br>" . $result2;

        

    }

    /*
    $myfile = fopen( $name.".html", "w") or die("Unable to open file!");
    $txt = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "總數 ".$total." 個 , 共有 ".$pages." 頁,尾頁 " .$lastpagecount." 個<bar>". $writeresult;
    #$txt = $result2;
    fwrite($myfile, $txt);
    fclose($myfile);
    */

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
