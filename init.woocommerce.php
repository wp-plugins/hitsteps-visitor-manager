<?php

add_action( 'woocommerce_email_after_order_table', 'add_hitsteps_analytics_to_woo_email', 10, 2 );
add_filter( 'woocommerce_after_order_notes', 'add_hitsteps_analytics_woo_checkout_field' );


 function add_hitsteps_analytics_woo_checkout_field( $fields ) {
$option=get_hst_conf();
if ($option['woo']!=2){


if ($option['code'] !='') {

echo  "<input type='hidden' value='' name='_hs_uid_data' id='_hs_data_uid_auto_fill' />
    <script>
                //Load hitsteps script once page fully loaded.
                function _hs_data_uid_auto_fill_func(){
                var hstc=document.createElement('script');
				hstc.src='//www.hitsteps.com/api/getUID.php?code=".$option['code']."';
				hstc.async=true;
				var htssc = document.getElementsByTagName('script')[0];
				htssc.parentNode.insertBefore(hstc, htssc);                
                }
				(function(){
				setTimeout(function(){ _hs_data_uid_auto_fill_func(); }, 1000);
				})();
    </script>
    ";

}
}
}





function add_hitsteps_analytics_to_woo_email( $order, $is_admin ) {

 global $_POST;

 $option=get_hst_conf();
 if ($option['woo']!=2){

$html='';

	if ( ! $is_admin ) return;
	

 if ($option['code']!=''){
	
	if (round($_POST['_hs_uid_data'])>0){
	$input=array("input_UID"=>round($_POST['_hs_uid_data']),
	"output_visitor_ip"=>1,
	"output_visitor_path"=>1,
	"output_visitor_base"=>1,
	"output_visitor_link"=>1
	);

		$html ="<br>". _hs_contact_form_query($input);
		echo $html;
	}

}else{
echo "Please enable Hitsteps API code in WordPress setting page to enable WooCommerce Email Analytics.";
}
}
}


?>