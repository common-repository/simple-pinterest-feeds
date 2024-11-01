<?php
/*
Plugin Name: Simple Pinterest Feeds
Plugin URI: https://wordpress.org/support/profile/amybeagh
Description: Simple Pinterest Feeds - Quick, small and easy Pinterest widget for wordpress.
Version: 1.0
Author :Amy Beagh
Author URI: https://wordpress.org/support/profile/amybeagh
*/
class spts_pinterest_feeds{
    public $options;
    public function __construct() {
        $this->options = get_option('spts_feeds_options');
        $this->spts_pinterest_feeds_register_settings_and_fields();
    }
    
    public static function spts_pinterest_feeds_tools_options_page(){
    add_options_page('Simple Pinterest Feeds', 'Simple Pinterest Feeds ', 'administrator', __FILE__, array('spts_pinterest_feeds','spts_pinterest_feeds_tools_options'));
    }
    
    public static function spts_pinterest_feeds_tools_options(){
	?>
	<div class="wrap">
	<div class="sptw_box_modal">
	  
	  <h2 class="sptf-style">Simple Pinterest Feeds Settings</h2>
	  <form method="post" action="options.php" enctype="multipart/form-data" class="spts_frm">
		<?php settings_fields('spts_feeds_options'); ?>
		<?php do_settings_sections(__FILE__); ?>
		<p class="sptf_submit">
		  <input name="submit" type="submit" class="sptf_but-info" value="Save Changes"/>
		</p>
	  </form>
	</div>
	</div>
	<?php
    }
	
    public function spts_pinterest_feeds_register_settings_and_fields(){
        register_setting('spts_feeds_options', 'spts_feeds_options',array($this,'spts_pinterest_feeds_validate_settings'));
        add_settings_section('spts_pinterest_feeds_main_section', '', array($this,'spts_pinterest_feeds_main_section_cb'), __FILE__);
        //Start Creating Fields and Options
        //pageURL
        add_settings_field('ptf_url', 'Pinterest Username', array($this,'sptf_pageURL_settings'), __FILE__,'spts_pinterest_feeds_main_section');
        //marginTop
        add_settings_field('sptf_Top', 'Margin Top', array($this,'sptf_marginTop_settings'), __FILE__,'spts_pinterest_feeds_main_section');
       
        //width
        add_settings_field('sptf_w', 'Width', array($this,'sptf_width_settings'), __FILE__,'spts_pinterest_feeds_main_section');
        //height
        add_settings_field('sptf_h', 'Height', array($this,'sptf_height_settings'), __FILE__,'spts_pinterest_feeds_main_section');
        //streams options
        add_settings_field('sptf_s', 'Image Width', array($this,'sptf_scale_settings'),__FILE__,'spts_pinterest_feeds_main_section');
		// show hide options
		add_settings_field('spts_status', 'Display on Frontend', array($this,'sptf_status_settings'),__FILE__,'spts_pinterest_feeds_main_section');
		//alignment option
        add_settings_field('sptf_alg', 'Alignment Position', array($this,'sptf_position_settings'),__FILE__,'spts_pinterest_feeds_main_section');
        
        //jQuery options
    
    }
    public function spts_pinterest_feeds_validate_settings($plugin_options){
        return($plugin_options);
    }
    public function spts_pinterest_feeds_main_section_cb(){
        //optional
    }
  
    
    
    //pageURL_settings
    public function sptf_pageURL_settings() {
        if(empty($this->options['pinterest_username'])) $this->options['pinterest_username'] = "pinterest";
        echo "<input name='spts_feeds_options[pinterest_username]' type='text' value='{$this->options['pinterest_username']}' />";
    }
    //marginTop_settings
    public function sptf_marginTop_settings() {
        if(empty($this->options['sptf_Top'])) $this->options['sptf_Top'] = "100";
        echo "<input name='spts_feeds_options[sptf_Top]' type='text' value='{$this->options['sptf_Top']}' />";
    }
    //alignment_settings
    public function sptf_position_settings(){
        if(empty($this->options['sptf_alg'])) $this->options['sptf_alg'] = "left";
        $items = array('left','right');
        foreach($items as $item){
            $selected = ($this->options['sptf_alg'] === $item) ? 'checked = "checked"' : '';
            echo "<input type='radio' name='spts_feeds_options[sptf_alg]' value='$item' $selected> ".ucfirst($item)."&nbsp;";
        }
    }
    //connection_settings
    public function sptf_connection_settings() {
        if(empty($this->options['connection'])) $this->options['connection'] = "10";
        echo "<input name='spts_feeds_options[connection]' type='text' value='{$this->options['connection']}' />";
    }
    //width_settings
    public function sptf_width_settings() {
        if(empty($this->options['sptf_w'])) $this->options['sptf_w'] = "350";
        echo "<input name='spts_feeds_options[sptf_w]' type='text' value='{$this->options['sptf_w']}' />";
    }
    //height_settings
    public function sptf_height_settings() {
        if(empty($this->options['sptf_h'])) $this->options['sptf_h'] = "400";
        echo "<input name='spts_feeds_options[sptf_h]' type='text' value='{$this->options['sptf_h']}' />";
    }
    //image_scale_settings
     public function sptf_scale_settings() {
        if(empty($this->options['sptf_s'])) $this->options['sptf_s'] = "80";
        echo "<input name='spts_feeds_options[sptf_s]' type='text' value='{$this->options['sptf_s']}' />";
    }
	
	// show hide settings
	public function sptf_status_settings()
	{
		if(empty($this->options['spts_status'])) $this->options['spts_status'] = "on";
		$status_itms = array('on','off');
		foreach($status_itms as $status_val){
			$checked_st = ($this->options['spts_status'] === $status_val) ? 'checked = "checked"' : '';
			echo "<input type='radio' name='spts_feeds_options[spts_status]' value='$status_val' $checked_st> ".ucfirst($status_val)."&nbsp;";
		}
	}
    
   
    

    // put jQuery settings before here
}
add_action('admin_menu', 'spts_pinterest_feeds_trigger_options_function');
	function spts_pinterest_feeds_trigger_options_function(){
		spts_pinterest_feeds::spts_pinterest_feeds_tools_options_page();
	}


add_action('admin_init','spts_pinterest_feeds_trigger_create_object');
	function spts_pinterest_feeds_trigger_create_object(){
		new spts_pinterest_feeds();
	}


add_action('wp_footer','spts_pinterest_feeds_add_content_in_footer');
function spts_pinterest_feeds_add_content_in_footer(){
    $options_pt = get_option('spts_feeds_options');
    extract($options_pt);
	$total_height=$sptf_h-110;
	$total_width=$sptf_w+40;
	$sptf_out = '';
	$sptf_out .= '<a data-pin-do="embedUser" href="http://pinterest.com/'.$pinterest_username.'/"
	data-pin-scale-width="'.$sptf_s.'" data-pin-scale-height="'.$total_height.'" 
	data-pin-board-width="'.$total_width.'"></a>';
	$imgURL = plugins_url( 'assets/spts_icon.png', __FILE__ );
?>
	<script type="text/javascript">
    (function(d){
      var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
      p.type = 'text/javascript';
      p.async = true;
      p.src = '//assets.pinterest.com/js/pinit.js';
      f.parentNode.insertBefore(p, f);
    }(document));
    </script> 
    <script type="text/javascript">
    jQuery(document).ready(function()
    {
    jQuery('#sptf_feeds_outer').click(function(){
         jQuery(this).parent().toggleClass('sptf_show');
    });
    });
    </script>
<?php 
	if($spts_status == 'on'){
	if($sptf_alg=='left'){?>
	<style>
	#sptf_feeds_outer{
	transition: all 0.5s ease-in-out 0s;
	left: -<?php echo trim($sptf_w+10);?>px;
	top: <?php echo $sptf_Top;?>px;
	z-index: 10000;
	height:<?php echo trim($sptf_h+30);?>px;	
	}
	#sptf_feeds_outer2{
	text-align: left;
	width:<?php echo trim($sptf_w);?>px;
	height:<?php echo trim($sptf_h);?>px;	
	}
	.sptf_show #sptf_feeds_outer{
		left:0px;
	}
	#sptf_feeds_outer2 img{
	top: 0px;
	right:-46px;	
	}
	</style>
	<div id="pt_pinterest_feeds_display">
	  <div id="sptf_feeds_outer">
	  <div id="sptf_feeds_outer2">
	  <a class="sptf_open" id="sptf_link" href="#"></a>
	  <img src="<?php echo $imgURL;?>" alt=""> <?php echo $sptf_out; ?>
	   </div>
	   
		
	  </div>
	</div>
	
	<?php } else { ?>
	<style>
	#sptf_feeds_outer{
	transition: all 0.5s ease-in-out 0s;
	right: -<?php echo trim($sptf_w+10);?>px;
	top: <?php echo $sptf_Top;?>px;
	z-index: 10000;
	height:<?php echo trim($sptf_h+30);?>px;	
	}
	#sptf_feeds_outer2 {
	text-align: left;
	width:<?php echo trim($sptf_w);?>px;
	height:<?php echo trim($sptf_h);?>px;	
	}
	.sptf_show #sptf_feeds_outer{
		right:0px;
	}
	#sptf_feeds_outer2 img{
		top: 0px;
		left:-46px;
	}
	</style>
	<div id="pt_pinterest_feeds_display">
	  <div id="sptf_feeds_outer">
		<div id="sptf_feeds_outer2">
		 <a class="sptf_open" id="sptf_link" href="#"></a>
		 <img src="<?php echo $imgURL;?>" alt=""> <?php echo $sptf_out; ?> 
		
		</div>
		
	   </div>
	</div>
	
	<?php } } ?>
	<?php
}
	add_action( 'wp_enqueue_scripts', 'register_spts_feeds_styles' );
	add_action( 'admin_enqueue_scripts', 'register_spts_feeds_styles' );
	function register_spts_feeds_styles() {
		wp_register_style( 'register_sptf_styles', plugins_url( 'assets/spts_style.css' , __FILE__ ) );
		wp_enqueue_style( 'register_sptf_styles' );
	}
	
	/* shortcode code */
	function sptf_feeds_sh_fun(){
		$options_pt2 = get_option('spts_feeds_options');
		extract($options_pt2);
		$total_height=$sptf_h-110;
		$total_width=$sptf_w+40;
		$sptf_out_sh = '';
		$sptf_out_sh .= '<div class="sptf_feeds_sh_output"><a data-pin-do="embedUser" href="http://pinterest.com/'.$pinterest_username.'/"
		data-pin-scale-width="'.$sptf_s.'" data-pin-scale-height="'.$total_height.'" 
		data-pin-board-width="'.$total_width.'"></a></div>';
		
		return $sptf_out_sh;
		
	}
	add_shortcode('sptf_feeds_output', 'sptf_feeds_sh_fun');




