<?php

if (!class_exists('HITSTEPS_GF_hitsteps')) {
    class HITSTEPS_GF_hitsteps
    {
	private static $name = 'Gravity Forms - Hitsteps tracker';
    private static $slug = 'hitsteps_gf_hitsteps_field';
	private static $version = '1.2.4';
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // register actions
            if (self::is_gravityforms_installed()) {
                //start plug in
                add_filter('gform_add_field_buttons', array(&$this,'hitsteps_add_field') );
				add_filter('gform_field_type_title' , array(&$this,'hitsteps_title'), 10, 2);
				add_action('gform_editor_js', array(&$this,'hitsteps_editor_js'));
				add_action('gform_field_standard_settings', array(&$this,'hitsteps_settings_visitor_title'), 10, 2 );
				add_action('gform_field_standard_settings', array(&$this,'hitsteps_settings_visitor_ip'), 10, 2 );
				add_action('gform_field_standard_settings', array(&$this,'hitsteps_settings_visitor_path'), 10, 2 );
				add_action('gform_field_standard_settings', array(&$this,'hitsteps_settings_visitor_base'), 10, 2 );
				add_action('gform_field_standard_settings', array(&$this,'hitsteps_settings_visitor_link'), 10, 2 );
				add_filter('gform_tooltips', array(&$this,'hitsteps_settings_visitor_ip_tooltip'));
				add_filter('gform_tooltips', array(&$this,'hitsteps_settings_visitor_path_tooltip'));
				add_filter('gform_tooltips', array(&$this,'hitsteps_settings_visitor_base_tooltip'));
				add_filter('gform_tooltips', array(&$this,'hitsteps_settings_visitor_link_tooltip'));

				add_action('gform_enqueue_scripts', array(&$this,'hitsteps_custom_js'), 90, 3);
				add_filter('gform_field_content', array(&$this,'hitsteps_display_field'), 10, 5);
            }
        } // END __construct
        
        /**
         * Add hitsteps field to 'standard fields' group in Gravity Forms forms editor
         */        
        public static function hitsteps_add_field($field_groups)
        {
            foreach ($field_groups as &$group) {
                if ($group["name"] == "standard_fields") {
                    $group["fields"][] = array(
                        "class" => "button",
                        "value" => __("Hitsteps Tracker", "gravityforms"),
                        "onclick" => "StartAddField('hitsteps');"
                    );
                    break;
                }
            }
            return $field_groups;
        } // END hitsteps_add_field
        
        /**
         * Add title to hitsteps field, displayed in Gravity Forms forms editor
         */        
        public static function hitsteps_title($title, $field_type)
        {
            if ($field_type == "hitsteps")
                return "hitsteps";
        } // END hitsteps_title
        
        /**
         * JavaSript to add field options to infoxbox field in the Gravity forms editor
         */
        public static function hitsteps_editor_js()
        {
?>
		<script type='text/javascript'>
			jQuery(document).ready(function($) {
				// standard field options
				fieldSettings["hitsteps"] = ".hitsteps_field_visitor_title_setting, .label_setting, .hitsteps_field_visitor_ip_setting, .hitsteps_field_visitor_path_setting, .hitsteps_field_visitor_base_setting, .hitsteps_field_visitor_link_setting, .conditional_logic_field_setting"; 
		 
				//custom field options				
				jQuery(document).bind("gform_load_field_settings", function(event, field, form){
					if ('hitsteps' == field['type']) {

					jQuery("#hitsteps_field_visitor_ip").prop('checked', field["hitsteps_field_visitor_ip"] == false ? false : true);
					jQuery("#hitsteps_field_visitor_path").prop('checked', field["hitsteps_field_visitor_path"] == false ? false : true);
					jQuery("#hitsteps_field_visitor_base").prop('checked', field["hitsteps_field_visitor_base"] == false ? false : true);
					jQuery("#hitsteps_field_visitor_link").prop('checked', field["hitsteps_field_visitor_link"] == false ? false : true);

					}
				});
			});
		 
		</script>
		<?php
        } // END hitsteps_editor_js
        
 
		/**
         * Add hitsteps 'Visitor Title' field, displayed in Gravity Forms emails 
         */
        public static function hitsteps_settings_visitor_title($position, $form_id)
        {
            // Create settings on position
            if ($position == 50) {
?>
		 
			<li class="hitsteps_field_visitor_title_setting field_setting">
												<label for="hitsteps_field_visitor_title">
												
												
												
Label won't be visible to your visitors, it is just for your reference in this page.



</label>
												
												
												
												
											</li>
											
											
			<?php
                
            }
        } // END
        
        
        
        
		/**
         * Add hitsteps 'Visitor IP' field, displayed in Gravity Forms emails 
         */
        public static function hitsteps_settings_visitor_ip($position, $form_id)
        {
            // Create settings on position
            if ($position == 50) {
?>
		 
			<li class="hitsteps_field_visitor_ip_setting field_setting">
												<label for="hitsteps_field_visitor_ip">
												
												
												

												<input type="checkbox" name="hitsteps_field_visitor_ip" id="hitsteps_field_visitor_ip" value="1"  onchange="SetFieldProperty('hitsteps_field_visitor_ip', this.checked);"  />  <?php _e("Show Visitor IP and Details", "gravityforms"); ?>
												<?php gform_tooltip("hitsteps_field_visitor_ip_tooltip");?></label>
												
												
												
												
											</li>
											
											
			<?php
                
            }
        } // END hitsteps_settings_visitor_ip
        
		public static function hitsteps_settings_visitor_ip_tooltip($tooltips){
			$tooltips["hitsteps_field_visitor_ip_tooltip"] = "Visitor IP, ISP, Name and details will be shown in email";
			return $tooltips;
		}





 
        
		/**
         * Add hitsteps 'Visitor Path' field, displayed in Gravity Forms emails 
         */
        public static function hitsteps_settings_visitor_path($position, $form_id)
        {
            // Create settings on position
            if ($position == 50) {
?>
		 
			<li class="hitsteps_field_visitor_path_setting field_setting">
												<label for="hitsteps_field_visitor_path">
												
												
												

												<input type="checkbox" name="hitsteps_field_visitor_path" id="hitsteps_field_visitor_path" value="1"  onchange="SetFieldProperty('hitsteps_field_visitor_path', this.checked);"  />  <?php _e("Show Visitor Path", "gravityforms"); ?>
												<?php gform_tooltip("hitsteps_field_visitor_path_tooltip");?></label>
												
												
												
												
											</li>
											
											
			<?php
                
            }
        } // END hitsteps_settings_visitor_path
        
		public static function hitsteps_settings_visitor_path_tooltip($tooltips){
			$tooltips["hitsteps_field_visitor_path_tooltip"] = "Recent visitor pageviews path and their referrers will be shown in email";
			return $tooltips;
		}



        
		/**
         * Add hitsteps 'Visitor Base' field, displayed in Gravity Forms emails 
         */
        public static function hitsteps_settings_visitor_base($position, $form_id)
        {
            // Create settings on position
            if ($position == 50) {
?>
		 
			<li class="hitsteps_field_visitor_base_setting field_setting">
												<label for="hitsteps_field_visitor_base">
												
												
												

												<input type="checkbox" name="hitsteps_field_visitor_base" id="hitsteps_field_visitor_base" value="1"  onchange="SetFieldProperty('hitsteps_field_visitor_base', this.checked);"  />  <?php _e("Show Visitor Base", "gravityforms"); ?>
												<?php gform_tooltip("hitsteps_field_visitor_base_tooltip");?></label>
												
												
												
												
											</li>
											
											
			<?php
                
            }
        } // END hitsteps_settings_visitor_base
        
		public static function hitsteps_settings_visitor_base_tooltip($tooltips){
			$tooltips["hitsteps_field_visitor_base_tooltip"] = "Visitor first landing time, landing page and landing referrer will be shown";
			return $tooltips;
		}




        
		/**
         * Add hitsteps 'Visitor Link' field, displayed in Gravity Forms emails 
         */
        public static function hitsteps_settings_visitor_link($position, $form_id)
        {
            // Create settings on position
            if ($position == 50) {
?>
		 
			<li class="hitsteps_field_visitor_link_setting field_setting">
												<label for="hitsteps_field_visitor_link">
												
												
												

												<input type="checkbox" name="hitsteps_field_visitor_link" id="hitsteps_field_visitor_link" value="1"  onchange="SetFieldProperty('hitsteps_field_visitor_link', this.checked);"  />  <?php _e("Show Visitor Link", "gravityforms"); ?>
												<?php gform_tooltip("hitsteps_field_visitor_link_tooltip");?></label>
												
												
												
												
												<?php
												 $option=get_hst_conf();
												 if ($option['code']=='')
												 {
												 ?>
												<div class="highlight" style="background:lightyellow; padding:10px; ">
												You need to install Hitsteps API code for this function to work. Please use hitsteps menu under setting.
												</div>
												<?php
												}
												?>
												
												
											</li>
											
											
			<?php
                
            }
        } // END hitsteps_settings_visitor_link
        
        
        
        

        
        
		public static function hitsteps_settings_visitor_link_tooltip($tooltips){
			$tooltips["hitsteps_field_visitor_link_tooltip"] = "Contain a link to click and view visitor profile in web dashboard";
			return $tooltips;
		}





        /**
         * Queue JavaScript and stylesheet for front end
         */
        public function hitsteps_custom_js()
        {
            //add_action('wp_footer', array(&$this,'hitsteps_custom_js_script'));
        } // END hitsteps_custom_js
        

		/**
         * Displays hitsteps field
         */
        public static function hitsteps_display_field($content, $field, $value, $lead_id, $form_id)
        {     
        
        

        $option=get_hst_conf();
        
         if ((!is_admin()) && ($field['type'] == 'hitsteps') && $option['code'] !='') {
            
          $vb=1;
          $vp=1;
          $vi=1;
          $vl=1;
           
           if (isset($field['hitsteps_field_visitor_base'])&&!$field['hitsteps_field_visitor_base']) $vb=0;
           if (isset($field['hitsteps_field_visitor_ip'])&&!$field['hitsteps_field_visitor_ip']) $vi=0;
           if (isset($field['hitsteps_field_visitor_path'])&&!$field['hitsteps_field_visitor_path']) $vp=0;
           if (isset($field['hitsteps_field_visitor_link'])&&!$field['hitsteps_field_visitor_link']) $vl=0;
            
            
                $content = "<input type='hidden' value='' name='_hs_uid_data' id='_hs_data_uid_auto_fill' />

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
                </script>";
                $content.= "<input type='hidden' name='_hs_data_output_visitor_ip' value='".$vi."' />";
                $content.= "<input type='hidden' name='_hs_data_output_visitor_path' value='".$vp."' />";
                $content.= "<input type='hidden' name='_hs_data_output_visitor_link' value='".$vl."' />";
                $content.= "<input type='hidden' name='_hs_data_output_visitor_base' value='".$vb."' />";
                
                
            } 
            return $content;
        } // END hitsteps_display_field
        

        /*
         * Check if GF is installed
         */
        private static function is_gravityforms_installed()
        {
            return class_exists('GFAPI');
        } // END is_gravityforms_installed
    }
    $HITSTEPS_GF_hitsteps = new HITSTEPS_GF_hitsteps();
    
    
    
    
    add_filter("gform_pre_send_email", "_hits_gf_before_email");
    add_action( 'gform_after_submission', '_hits_gf_after_submission', 10, 2 );
    
}

function _hits_gf_before_email($email){
global $_POST, $_hs_uid_data_cache;

if (round($_POST['_hs_uid_data'])>0){

if (!isset($_hs_uid_data_cache[round($_POST['_hs_uid_data'])])) $_hs_uid_data_cache[round($_POST['_hs_uid_data'])]=='';
if ($_hs_uid_data_cache[round($_POST['_hs_uid_data'])]==''){


$input=array("input_UID"=>round($_POST['_hs_uid_data']),
	"output_visitor_ip"=>round($_POST['_hs_data_output_visitor_ip']),
	"output_visitor_path"=>round($_POST['_hs_data_output_visitor_path']),
	"output_visitor_base"=>round($_POST['_hs_data_output_visitor_base']),
	"output_visitor_link"=>round($_POST['_hs_data_output_visitor_link'])
	);
	$_hs_uid_data_cache[round($_POST['_hs_uid_data'])]=_hs_contact_form_query($input);

}

$email['message'].=$_hs_uid_data_cache[round($_POST['_hs_uid_data'])];
}


return $email;
}


function _hits_gf_after_submission( $entry, $form ) {
global $_POST, $_hs_uid_data_cache;
if (round($_POST['_hs_uid_data'])>0){

//get hitstep's field input ID in gravity form
$inputID=0;
	foreach ($form['fields'] as $field){
		if ($field->type=='hitsteps'){
		$inputID=$field->id;
		}
	}



//entry ID: $entry['id']
//input ID: $inputID?

if (!isset($_hs_uid_data_cache[round($_POST['_hs_uid_data'])])) $_hs_uid_data_cache[round($_POST['_hs_uid_data'])]=='';
if ($_hs_uid_data_cache[round($_POST['_hs_uid_data'])]==''){

$input=array("input_UID"=>round($_POST['_hs_uid_data']),
	"output_visitor_ip"=>round($_POST['_hs_data_output_visitor_ip']),
	"output_visitor_path"=>round($_POST['_hs_data_output_visitor_path']),
	"output_visitor_base"=>round($_POST['_hs_data_output_visitor_base']),
	"output_visitor_link"=>round($_POST['_hs_data_output_visitor_link'])
	);
	$_hs_uid_data_cache[round($_POST['_hs_uid_data'])]=_hs_contact_form_query($input);

}

$success = GFAPI::update_entry_field( $entry['id'],  $inputID, $_hs_uid_data_cache[round($_POST['_hs_uid_data'])] );

}
    
}

?>