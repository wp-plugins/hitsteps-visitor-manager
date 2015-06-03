<?php

if(function_exists('wpcf7_add_shortcode')) {
add_action('plugins_loaded', 'contact_form_7_hitsteps_fields', 11);

function contact_form_7_hitsteps_fields() {
	global $pagenow;
	if(function_exists('wpcf7_add_shortcode')) {

		wpcf7_add_shortcode( 'hitsteps_cf7', 'wpcf7_hitsteps_cf7_shortcode_handler', true );
		wpcf7_add_shortcode( 'hitsteps_cf7*', 'wpcf7_hitsteps_cf7_shortcode_handler', true );

	} 
}


/* Shortcode handler */
function wpcf7_hitsteps_cf7_shortcode_handler( $tag ) {

	$html='';

	if ( ! is_array( $tag ) ) {
		return '';
    }
                
    $tag = new WPCF7_Shortcode( $tag );        
            
	$atts = array();
	
    $class = wpcf7_form_controls_class( $tag->type );
            

    $vi = $tag->has_option( 'disable_visitor_ip' ) ? '0' : '1';    
    $vb = $tag->has_option( 'disable_visitor_base' ) ? '0' : '1';    
    $vp = $tag->has_option( 'disable_visitor_path' ) ? '0' : '1';    
    $vl = $tag->has_option( 'disable_visitor_link' ) ? '0' : '1';    
    
    $def = 0 ;
    if(isset($tag->values) && is_array($tag->values) && count($tag->values)>0) {
        $def = $tag->values[0];
    }
           
    $atts['class'] = ' hitsteps_cf7' ;
    
    
    $option=get_hst_conf();
	if ($option['code'] !='') {
    $html .= "<input type='hidden' value='' name='_hs_uid_data' id='_hs_data_uid_auto_fill' />
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
    
    

                $html.= "<input type='hidden' name='_hs_data_output_visitor_ip' value='".$vi."' />";
                $html.= "<input type='hidden' name='_hs_data_output_visitor_path' value='".$vp."' />";
                $html.= "<input type='hidden' name='_hs_data_output_visitor_link' value='".$vl."' />";
                $html.= "<input type='hidden' name='_hs_data_output_visitor_base' value='".$vb."' />";
        
     }
     
    return $html ;
}



/* Tag generator */
add_action( 'admin_init', 'wpcf7_add_tag_generator_hitsteps_cf7', 30 );
function wpcf7_add_tag_generator_hitsteps_cf7() {
	if(function_exists('wpcf7_add_tag_generator')) {
		wpcf7_add_tag_generator( 'hitsteps_cf7', __( 'Hitsteps Analytics', 'wpcf7' ), 'wpcf7-tg-pane-hitsteps_cf7', 'wpcf7_tg_pane_hitsteps_cf7' );
	}
}


function wpcf7_tg_pane_hitsteps_cf7( $type = 'hitsteps_cf7' ) {

	if ( ! in_array( $type, array() ) )
		$type = 'hitsteps_cf7';

?>
<div id="wpcf7-tg-pane-<?php echo $type; ?>" class="hidden">
<form action="">
<table>

<tr><td><input type="hidden" name="name" class="tg-name oneline" value='' /></td><td></td></tr>
</table>

<table>


<tr><td><input type="checkbox" name="disable_visitor_ip" class="option" />&nbsp;<?php echo esc_html( __( 'Disable Visitor IP, Name, ISP and Country information', 'contact-form-7' ) ); ?></td></tr>
<tr><td><input type="checkbox" name="disable_visitor_path" class="option" />&nbsp;<?php echo esc_html( __( 'Disable Visitor Viewed Pages Path', 'contact-form-7' ) ); ?></td></tr>
<tr><td><input type="checkbox" name="disable_visitor_link" class="option" />&nbsp;<?php echo esc_html( __( 'Disable Visitor More Information Link', 'contact-form-7' ) ); ?></td></tr>
<tr><td><input type="checkbox" name="disable_visitor_base" class="option" />&nbsp;<?php echo esc_html( __( 'Disable Visitor Base Visits', 'contact-form-7' ) ); ?></td></tr>

</table>

<div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'contact-form-7' ) ); ?><br /><input type="text" name="<?php echo $type; ?>" class="tag wp-ui-text-highlight code" readonly="readonly" onfocus="this.select()" /></div>

<div class="tg-mail-tag"><?php echo esc_html( __( "And, put this code into the Mail fields below.", 'contact-form-7' ) ); ?><br /><input type="text" class="mail-tagi wp-ui-text-highlight codei" readonly="readonly" onfocus="this.select()" value="[hitsteps_analytics]" /></div>
</form>
</div>
<?php
}






// Add the info to the email
	function hitsteps_wpcf7_before_send_mail($array) {
	global $_POST,$wpdb;
	$_hs_tracking_info='';
	
	 $option=get_hst_conf();
	 
	 if(wpautop($array['body']) == $array['body']){
	 
	if ($option['code']!=''){
	if (round($_POST['_hs_uid_data'])>0){
	$input=array("input_UID"=>round($_POST['_hs_uid_data']),
	"output_visitor_ip"=>round($_POST['_hs_data_output_visitor_ip']),
	"output_visitor_path"=>round($_POST['_hs_data_output_visitor_path']),
	"output_visitor_base"=>round($_POST['_hs_data_output_visitor_base']),
	"output_visitor_link"=>round($_POST['_hs_data_output_visitor_link'])
	);

		if(wpautop($array['body']) == $array['body']) // The email is of HTML type
			$lineBreak = "<br/>";
		else
			$lineBreak = "\n";
			
		$_hs_tracking_info = _hs_contact_form_query($input);
	
	}
	}else{
	$_hs_tracking_info="Please enable Hitsteps API code in WordPress setting page to enable Contact form Analytics.";
	}
	
	}else{
	$_hs_tracking_info="Hitsteps Analytics only support HTML content type emails, Please change this contact form to HTML.";
	}
	
		$array['body'] = str_replace('[hitsteps_analytics]', $_hs_tracking_info, $array['body']);

		return $array;
	
	}


add_filter('wpcf7_mail_components', 'hitsteps_wpcf7_before_send_mail');

}

?>