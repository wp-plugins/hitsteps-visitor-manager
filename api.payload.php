<?php

$hitsteps_public_web_api_receiver="http://www.hitsteps.com/api/query.php";

if (!function_exists("hitsteps_public_query")){
	function hitsteps_public_query($post){
		global $hitsteps_public_web_api_receiver;
		
		$hs_option=get_hst_conf();
		
		if ($hs_option['code']!=''){
		
		$arg=array(
		'method'=>'POST',
		'timeout'=>45,
		'redirection'=>5,
		'body'=>$post		
		);

		 //Set the URL to work with
		$result=wp_remote_post($hitsteps_public_web_api_receiver."?code=".$hs_option['code'],$arg);
		
		$arr=array();
		
		if ( is_wp_error( $result ) )
		{
		$arr['error']=98;
		$arr['msg']="HTTP_API Call connection error: ".$result->get_error_message();

		return $arr;	
		}

		if ($result['body']=='db_down_for_maintaince'){
		$arr['error']=99;
		$arr['msg']="Hitstep's internal database error";

		return $arr;
		}
		if (strpos(strtolower($result['body']),"cloudflare")){
		$arr['error']=999;
		$arr['msg']="Hitstep's webserver down at moment. connection couldn't established.";

		return $arr;
		}
		
 

		
		$arr=(array) json_decode($result['body'], true);
		$arr['completed']=1;
		
		return $arr;
		
		}else{
		$arr['error']=120;
		$arr['msg']="You need to install hitsteps API code in setting menu.";
		return $arr;
		}
	}
}


function _hs_contact_form_query($input){
$render='';
$input['action']='contact_form_data_1';
$data=hitsteps_public_query($input);
$hs_option=get_hst_conf();
if ($hs_option['code']!=''){
if ($data['error']>0){
$render="<small><i>Hitsteps error #".$data['error'].": ".$data['msg']."</i></small>";
}else{

$render.="<!--Hitsteps: You can hide this column --><div style='margin:15px;padding:5px;padding-bottom:0px;background: #23282D; border:1px solid #eee; font-family: Helvetica, arial, tahoma; font-size: 9pt;'><img src='" . WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)). "logo.png' width='90' height='20' alt='hitsteps analytics' />";


if ($input['output_visitor_ip']){
if (is_array($data['visitor_ip'])){
$devicetype="PC";
if ($data['visitor_ip']['mobile']) $devicetype="Phone";
if ($data['visitor_ip']['tablet']) $devicetype="Tablet";

$render.="<div style='padding:10px;margin-bottom:5px;background: #efefef; '><img style='float: left; margin-right: 5px; margin-bottom:5px;border: #eee 1px solid;' src='http://www.hitsteps.com/responsive/flags/".strtoupper($data['visitor_ip']['country']).".png' width='74' height='50'>Name: ".$data['visitor_ip']['name']."<br>From: ".$data['visitor_ip']['city'].", ".$data['visitor_ip']['country_name']."<br>Org/ISP: ".$data['visitor_ip']['org']."<br>Device: ".$data['visitor_ip']['sw']."x".$data['visitor_ip']['sh']." ".$data['visitor_ip']['browser']." ".$data['visitor_ip']['browserv']." - ".$data['visitor_ip']['os']." - ".$devicetype." IP: ".$data['visitor_ip']['ip']."</div>";

}
}



if ($input['output_visitor_base']){
if (is_array($data['visitor_base'])){

$render.="
<div style='padding:10px;margin-bottom:5px;background: #efefef; '>First visited ".$data['visitor_base']['base']." from ". $data['visitor_base']['baseref'] ." landed on ".$data['visitor_base']['baseland']."</div>";
}
}



if ($input['output_visitor_path']){
if (is_array($data['visitor_path'])){

$totalview=$data['visitor_path']['hits'];
$more='';
if ($totalview>50){$totalview="more than 50";
$more='more than ';
}

$render.="<div style='padding:10px;margin-bottom:5px;background: #efefef; '>Recent pageviews are ".$totalview." pages, result in spending ".$more.round($data['visitor_path']['avg']/60,0)." minutes:<br><br>";


if (is_array($data['visitor_path']['pages'])){

$count=20;

foreach ($data['visitor_path']['pages'] as $pg){
$count--;
if ($count>0){
$pg['refname']=$pg['ref'];
$pg['reflink']="href='".$pg['ref']."'";
if ($pg['ref']==''){$pg['refname']='Direct';$pg['reflink']='';}


$render.="<div style='padding:5px; margin: 3px;margin-bottom:5px;background: #fff; line-height: 15pt;'>To: <a style='text-decoration: none;color: #666;' href='".$pg['url']."'>".$pg['name']."</a> - visited ".$pg['short_date']."<br><i style='text-decoration: none;color: #999;'>From: <a style='text-decoration: none;color: #999;' ".$pg['reflink'].">".$pg['refname']."</a></i></div>";

}}

}

$render.="</div>";
}
}


if ($input['output_visitor_link']){
if (isset($data['visitor_link'])){

$render.="<a style='display:block; padding:10px; background: #23282D;color: #fff; text-decoration: none;' href='".$data['visitor_link']."'>Know more about this visitor</a>";
}
}


$render.="</div>";


}
}


return $render;
}

?>