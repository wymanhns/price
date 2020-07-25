<?php

//  url  
$cpu=array("cpu","https://www.price.com.hk/category.php?c=100014&sort=2");
$ramurl=array("cpu","https://www.price.com.hk/category.php?c=100027&gp=20&sort=2");

/*   test url */
#$cpu=array("cpu","http://127.0.0.1/w1/cpu.html?"); 
//$ramurl="http://127.0.0.1/w1/ram.html?"; 


ReadPrices($cpu);



function ReadPrices($dataname) {

    $NameData=$dataname[0];
    $url=$dataname[1];

    $rData=array();
    $response = getHTTPS($url);
    $result1=strstr($response,"共");
    $result2=strstr($result1,"種項目" ,1);
    $total=substr($result2,3);
    $pagecount=15;

    //for test
        if (stripos($url,'price.com.hk')){
            $pagecount=15;
        } else {
            $pagecount=$total;
        }
    //
    
    $pages=$total/$pagecount;
    $lastpagecount=$total%$pagecount;
    if ($lastpagecount==0){ $lastpagecount=$pagecount; }

    echo "總數 ".$total." 個 , 共有 ".$pages." 頁,尾頁 " .$lastpagecount." 個<br>";
    $resultx=$response;
    $writeresult;
    #$pages=1;////////////////////////
    for ($i=1; $i<=$pages; $i++)
    {
        $url1=$url."&page=".$i;

        echo $url1. "<br>";
        $resultx = getHTTPS($url1);
        $writeresult=$writeresult . "<br>" . $resultx;

        print_r ("<<<******* Page ".$i." *******>>>");

        
        $result2=strstr($resultx,'<li id="track_'); //</li>
        #$pagecount=2;

        for ($y=1; $y<=$pagecount; $y++)
        {

            $xy = $y + (($i - 1) * 15);
            #echo $result2;

            print_r ("<br>********** " . $xy . " / ". $total . " **********<br>");

            $itemopen=stristr($result2,'<!--item-->');
            $result2=substr($itemopen,11);
            $itemclose=stristr($result2,'<!-- product price -->',1);
            
            #print_r ($itemclose);  // 每一件物件 HTML

            // cpuid
                $resulta=strstr($itemclose,'data-add-compare="'); 
                $resultb=strstr($resulta,'data-add-compare-cid',1);
                preg_match_all(CheckDot($resultb),$resultb,$core);
                $cpuid=$core[0][0];
            //
            // cpuname
                $resulta=strstr($itemclose,'data-add-compare-name="'); 
                $resultb=strstr($resulta,'/>',1);
                $cpuname=substr($resultb,23);
                $cpuname=strstr($cpuname,'"',1);
            //
            // cpufamily
                $resulty=strstr($itemclose,'"product-caption">('); 
                $resultz=strstr($resulty,')</span></div>',1);
                $resultb=substr($resultz,19);
                $cpufamily=strstr($resultb,',',1);
                
                if (stripos($resultb,',')){$cpufamily=strstr($resultb,',',1); } else { $cpufamily=0;}

                $resulty=strstr($cpuname,' '); 
                $resulty=substr($resulty,1);
                if (stripos($resulty,'-')){$cpufamily=strstr($resulty,'-',1); } else { $cpufamily=strstr($resulty,' ',5);}
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
                $resulta=strstr($itemclose,'/space/product/'); 
                $resultb=strstr($resulta,'"/></a>',1);
                $cpupic=substr($resultb,1);
            //
            // hot         
                $resulty=strstr($itemclose,'<!-- product name -->',1);
                $resultz=strstr($resulty,'hot_20'); 
                $resulty=strstr($resultz,'人氣項目',1);
                $hot=0;
                if (strlen($resulty) > 0){
                    $hot=1;
                    echo "*** Hot! ***";
                }
            // 
            // cpunew
                $cpunew=$xy;
            //
            // cpucore
                $resulty=strstr($itemclose,'product-caption">('); 
                $resultz=stristr($resulty,'core',1);
                $cpucore=substr($resultz,18);
                if (strlen($cpucore) > 3){$cpucore=substr($cpucore,strlen($cpucore) -3);}
                #print_r ($cpucore."**<br>");
                preg_match_all(CheckDot($cpucore),$cpucore,$core);
                $cpucore=$core[0][0];
            //
            // cputhreads 
                $resulty=stristr($resulty,'core'); 
                $resultz=stristr($resulty,'threads',1);
                $cputhreads=substr($resultz,4);
                preg_match_all(CheckDot($cputhreads),$cputhreads,$threads); 
                $cputhreads=$threads[0][0];
            //
            // cpufrequency cpumtfrequency 
                $resulta=strstr($result2,'時脈'); 
                $resulta=strstr($resulta,'<span>'); 
                $resulta=strstr($resulta,'</span>',1);
                $resulta=substr($resulta,6);
                $Ghz=stripos($resulta,'g');
                if (stripos($resulta,'/')){
                    $resultb=strstr($resulta,'/',1);
                    preg_match_all(CheckDot($resultb),$resultb,$frequency);  
                    $resultc=strstr($resulta,'/');
                    $cpufrequency=$frequency[0][0];
                    preg_match_all(CheckDot($resultc),$resultc,$mtfrequency);  
                    $cpumtfrequency=$mtfrequency[0][0];
                } else {
                    preg_match_all(CheckDot($resulta),$resulta,$frequency);  
                    $cpufrequency=$frequency[0][0];
                    $cpumtfrequency=0;
                }
            // cpumtfrequency by "外頻:"
                if (stripos($itemclose,'外頻:')){
                    $resulta=strstr($itemclose,'外頻:');
                    $resulta=strstr($resulta,'<span>'); 
                    $resultb=strstr($resulta,'Hz',1);
                    preg_match_all(CheckDot($resultb),$resultb,$mtfrequency);  
                    $cpumtfrequency=$mtfrequency[0][0];
                    #$cpumtfrequency=substr($resultb,6);
                    #echo strlen($cpumtfrequency); 
                }
                if ($Ghz>0){$cpufrequency=$cpufrequency*1000; $cpumtfrequency=$cpumtfrequency*1000; } //Ghz
            //
            // cpusocket
                $resulta=strstr($itemclose,'Socket:'); 
                $resulta=strstr($resulta,'<span>');
                $resultb=strstr($resulta,'</span></td>',1);
                $cpusocket=substr($resultb,6);
            //
            // cpucache
                $resulta=stristr($itemclose,'Cache:'); 
                $resultb=stristr($resulta,'<span>');
                $resultb=strstr($resultb,'</span>',1);
                $resultb=substr($resultb,6);
                $kmcheck=stripos($resultb,'k');
                #print_r ($kmcheck."*Cache** ".$resultb);
                
                if ($kmcheck>0) {$resultb=stristr($resultb,'k',1); $cpucache=$resultb."KB"; } else {$resultb=stristr($resultb,'m',1); $cpucache=$resultb."MB"; }
                #print_r ("*Cache** ".$resultb);
                #$cpucache=$resultb;
                #echo strlen($cpucache);
            //
            // cpuminprice cpumaxprice    text-price-number    行貨
                $resulta=strstr($itemclose,'text-price-number" data-price="'); 
                $resultb=strstr($resulta,'">',1);
                //$stoppos=strpos(substr($resultb,31),'.');
                $resultb=substr($resultb,31);
                preg_match_all(CheckDot($resultb),$resultb,$cpuminprice);  
                $cpuminprice=$cpuminprice[0][0];
                #echo "///".strlen($cpuminprice)."///";
                $resulta=substr($resulta,31);
                $resulta=strstr($resulta,'text-price-number" data-price="'); 
                $resultb=strstr($resulta,'</span>',1);
                $resultb=substr($resultb,31);
                preg_match_all(CheckDot($resultb),$resultb,$cpumaxprice);  
                $cpumaxprice=$cpumaxprice[0][0];
                if (!$cpumaxprice){ $cpumaxprice=$cpuminprice;}
                #echo "//".substr($resulta,31) ."//";
                #echo strlen($cpumaxprice);  
            //

            echo "CPUID:" . $cpuid . " CPU BRAND:" . $brand . " CPU NAME:" . $cpuname . " FAMILY:" . $cpufamily . " CORE:" .$cpucore. " THREADS:" .$cputhreads. " FREQUENCY:" . $cpufrequency."MHz MAX FREQUENCY:" .$cpumtfrequency . "MHz SOCKET:" .$cpusocket. " CACHE:" .$cpucache ."  <br>";
            echo "IMG: <img src='https://www.price.com.hk/" . $cpupic . "'/>" . "   <br>";
            echo " MINPRICE: " . $cpuminprice . " MAXPRICE: " . $cpumaxprice . "   <br>";
            #print_r ($cpucache);
            array_push($rData,array($cpuid,$brand,$cpuname,$cpufamily,"https://www.price.com.hk/" . $cpupic ,$cpusocket,$cpucore,$cputhreads,$cpufrequency,$cpumtfrequency,$cpucache,$cpuminprice,$cpumaxprice,$cpunew,$hot));

        }
        #print_r ($itemopen);
        #echo $result2;
        $jsonx = json_encode($rData);
        #print_r ($jsonx);
        file_put_contents('cpu.json', $jsonx);

        

    }

    
    $myfile = fopen( "cpu.html", "w") or die("Unable to open file!");
    //$txt = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "總數 ".$total." 個 , 共有 ".$pages." 頁,尾頁 " .$lastpagecount." 個<bar>". $writeresult;
    $txt = $writeresult;
    fwrite($myfile, $txt);
    fclose($myfile);
    

}

function CheckDot($vol) { //只數字 '/\d+/' 數字带小数点 C'/(\d+)\.(\d+)/is'
    if (stripos($vol,'.')){
        return '/(\d+)\.(\d+)/is';
    } else {
        return '/\d+/';
    }

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
