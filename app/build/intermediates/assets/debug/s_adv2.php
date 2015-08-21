<?php
//require('config.php');
header('Access-Control-Allow-Origin: *');
$x=(float)$_REQUEST['x'];
$y=(float)$_REQUEST['y'];
$type=(int)$_REQUEST['type'];//1.蝴蝶 2.青蛙 3.蛾類
$r=(int)$_REQUEST['m'];
if($r==0){$r2=50;}
else if($r==1){$r2=100;}
else if($r==2){$r2=500;}
else if($r==3){$r2=1000;}
else if($r==4){$r2=1500;}
else if($r==5){$r2=2000;}

if($type==1 || $type==0){
	/******/
	$msg='';
	$page=1;
	do
	{
		$tmp=join('',file("http://www.i35.club.tw/xml/near.php?lon=${x}&lat=${y}&lm=${r2}&cnt=500&page=$page"));
	        $page=$page+1;
		$msg=$msg.$tmp;
	}
	while (substr_count($tmp,'<rows/>')>0);
	$cnt=substr_count($msg,'<row>');
	for ($i=0;$i<$cnt;$i++)
	{
		$pt=strpos($msg,'<row>');
		$pt1=strpos($msg,'</row>');
		$data=substr($msg,$pt+5,$pt1-$pt-5);
		$mpt=strpos($data,'<rid>');
		$id=(int)substr($data,$mpt+5);
		$mpt=strpos($data,'<lon>');
		$mx=(double)substr($data,$mpt+5);
		$mpt=strpos($data,'<lat>');
		$my=(double)substr($data,$mpt+5);
		$mpt=strpos($data,'<ez_id>');
		$m_ez=(int)substr($data,$mpt+7);
		$mpt=strpos($data,'<near>');
		$near=(int)substr($data,$mpt+6);
		$mpt=strpos($data,'<collcet_time>');
		$mpt1=strpos($data,'</collcet_time>');
		$mtime=substr($data,$mpt+14,$mpt1-$mpt-14);
		$mpt=strpos($data,'<Collector>');
		$mpt1=strpos($data,'</Collector>');
		$collector=substr($data,$mpt+11,$mpt1-$mpt-11);
		$mpt=strpos($data,'<ez_name>');
		$mpt1=strpos($data,'</ez_name>');
		$name=substr($data,$mpt+9,$mpt1-$mpt-9);
		$mpt=strpos($data,'<fam>');
		$mpt1=strpos($data,'</fam>');
		$fam=substr($data,$mpt+5,$mpt1-$mpt-5);
		$mpt=strpos($data,'<fam>');
		$mpt1=strpos($data,'</fam>');
		$fam=substr($data,$mpt+5,$mpt1-$mpt-5);
		$mpt=strpos($data,'<genus_infra>');
		$mpt1=strpos($data,'</genus_infra>');
		$genus=substr($data,$mpt+13,$mpt1-$mpt-13);
		echo "$name,$id,$genus,$fam,$collector,$mtime,$near,${m_ez},${mx},$my";
		$msg=substr($msg,$pt1+6);
	#if($i!=$cnt-1)
		echo ';';
	}
}


if($type==2 || $type==0){
	//frog
	$msg=join('',file("http://www.i35.club.tw/frog/json_near.php?lon=${x}&lat=${y}&pcnt=100"));
	$pt=strpos($msg,'"rows":');
	$msg=substr($msg,$pt+7);
	$pt=strpos($msg,']');
	$msg=substr($msg,0,$pt);
	$cnt=substr_count($msg,'{');
	$id=-1;
	$m_ez=-1;
	$name='青蛙';
	$fam='';
	for ($i=0;$i<$cnt;$i++)
	{
		//pt=strpos(....) 找出字串出現的位置
		//msg=substr(...) 切割字串
		//$mx經度 $my緯度
		$pt=strpos($msg,'"lat":"');
		$msg=substr($msg,$pt+7);
		$my=(double) $msg;
		$pt=strpos($msg,'"lon":"');
	        $msg=substr($msg,$pt+7);
	        $mx=(double) $msg;
		$pt=strpos($msg,'"datime":"');
	        $msg=substr($msg,$pt+10);
		$pt1=strpos($msg,'"');
	        $mtime=substr($msg,0,$pt1);
		$pt=strpos($msg,'"collecter":"');
	        $msg=substr($msg,$pt+13);
		$pt1=strpos($msg,'"');
		$collector=substr($msg,0,$pt1);
		$pt=strpos($msg,'"pic_url":"');
	        $msg=substr($msg,$pt+11);
		$pt1=strpos($msg,'"');
		$mpic_url=substr($msg,0,$pt1);
		$mpic_url=str_replace('\\','',$mpic_url);
		$pt=strpos($msg,'"tag":"');
	        $msg=substr($msg,$pt+7);
		$pt1=strpos($msg,'"');
		$genus=substr($msg,0,$pt1);
		$pt=strpos($msg,'"near":');
	        $msg=substr($msg,$pt+7);
		$near=(int)$msg;
		if ($near>$r2) break;
		//echo "$name,$id,${genus},$fam,$collector ,$mtime,${near},${m_ez},${mx},$my,$mpic_url\n";
		
		// 緯度、經度、採集日期、採集者、圖片、正確辨識結果(名稱)、距離多少公尺

		echo "$name,$my,$mx,$mtime,$collector,$mpic_url,$tag,$near";
		echo ';';
	}
	/***************/
}

if($type==3 || $type==0){
	/******************/
	//moth
	$msg=join('',file("http://www.i35.club.tw/tw_moths/json_near.php?lon=${x}&lat=${y}&pcnt="));
	$pt=strpos($msg,'"rows":');
	$msg=substr($msg,$pt+7);
	$pt=strpos($msg,']');
	$msg=substr($msg,0,$pt);
	$cnt=substr_count($msg,'{');
	$m_ez=-2;
	$name='蛾';
	for ($i=0;$i<$cnt;$i++)
	{
	    $pt=strpos($msg,'"uuid":"');
	    $msg=substr($msg,$pt+8);
	    $id=(int) $msg;
		$fam='';
	    $pt=strpos($msg,'"fam":"');
		if ($pt>0)
		{
	        $msg=substr($msg,$pt+7);
			$pt1=strpos($msg,'"');
			$fam=substr($msg,0,$pt1);
		}
		$sci='';
	    $pt=strpos($msg,'"sci":"');
		if ($pt>0)
		{
	        $msg=substr($msg,$pt+7);
			$pt1=strpos($msg,'"');
			$sci=substr($msg,0,$pt1);
		}
		$cname='';
	    $pt=strpos($msg,'"cname":"');
		if ($pt>0)
		{
	        $msg=substr($msg,$pt+9);
			$pt1=strpos($msg,'"');
			$cname=substr($msg,0,$pt1);
		}
		$life='';
	    $pt=strpos($msg,'"life":"');
		if ($pt>0)
		{
	        $msg=substr($msg,$pt+8);
			$pt1=strpos($msg,'"');
			$life=substr($msg,0,$pt1);
		}
		$mtime='';
	    $pt=strpos($msg,'"dat":"');
		if ($pt>0)
		{
	        $msg=substr($msg,$pt+7);
			$pt1=strpos($msg,'"');
			$mtime=substr($msg,0,$pt1);
		}
		$addr='';
	    $pt=strpos($msg,'"addr":"');
		if ($pt>0)
		{
	        $msg=substr($msg,$pt+8);
			$pt1=strpos($msg,'"');
			$addr=substr($msg,0,$pt1);
			//取得經緯度
			$google=join('',file("http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($addr)."&sensor=false&language=zh-TW"));
			$gpt=strpos($google,'"location" : {');
			$google=substr($google,$gpt+13);
			$gpt=strpos($google,'"lat" :');
			$google=substr($google,$gpt+7);
			$my=(double) $google;
			$gpt=strpos($google,'"lng" :');
			$google=substr($google,$gpt+7);
			$mx=(double) $google;
		}
		$collector='';
	    $pt=strpos($msg,'"user":"');
		if ($pt>0)
		{
	        $msg=substr($msg,$pt+8);
			$pt1=strpos($msg,'"');
			$collector=substr($msg,0,$pt1);
		}
		$mpic_url='';
	    $pt=strpos($msg,'"link":"');
		if ($pt>0)
		{
	        $msg=substr($msg,$pt+8);
			$pt1=strpos($msg,'"');
			$mpic_url=substr($msg,0,$pt1);
		    $mpic_url=str_replace('\\','',$mpic_url);
		}
		$near=10000;
	    $pt=strpos($msg,'"near":"');
		if ($pt>0)
		{
	        $msg=substr($msg,$pt+8);
			$pt1=strpos($msg,'"');
			$near=(double)substr($msg,0,$pt1);
		}
		
	    if ($near>$r2) break;
		$genus=$cname.' '.$sci;
	    //echo "$name,$id,${genus},$fam,$collector,$mtime,${near},${m_ez},${mx},$my,$mpic_url\n";
	    echo "$name,$id,${genus},$fam,$collector,$mtime,${near},${mx},$my,$mpic_url\n";
		echo ';';
	}
	/******************/
}

?>
