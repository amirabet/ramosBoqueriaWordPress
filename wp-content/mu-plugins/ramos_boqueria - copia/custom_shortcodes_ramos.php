<?php
//[url_lang] Devuelve la URL global + carpeta idioma
function url_lang_func($atts, $content=null){
	$blogurl = get_bloginfo('url');
	if (function_exists('qtrans_getLanguage')){
		$lang = qtrans_getLanguage();
		$urlbylang = ( $blogurl . ('/') . $lang );
	}else{ //Not activeQtrans
		$urlbylang = $blogurl;
	}
	return $urlbylang;
}
add_shortcode( 'url_lang', 'url_lang_func' );
?>
<?php
//[socialmedia] Devuelve icono Socialmedia + URL
function socialmedia_iconlist($type){
	extract(shortcode_atts(array(
        'type' => 'type'
    ), $type));
     
    // Tipo de red social
    switch ($type) {
        case 'rss':
			//URL de los Feeds
			$blogurl = get_bloginfo('url');
			if (function_exists('qtrans_getLanguage')){
				$lang = qtrans_getLanguage();
				$urlbylang = $blogurl . '/' . $lang;
			}else{ //Not activeQtrans
				$urlbylang = $blogurl;
			}
			$url_rss_lang = '<a href="' . $urlbylang . '/feed/" class="socialmedia_rss"><i class="fa fa-rss-sign"></i>&nbsp;<span>RSS</span></a>';
            return $url_rss_lang;
            break;
		case 'facebook':
			$facebook_array = get_option('social_facebook');
			if (isset($facebook_array['url']) && $facebook_array['url'] != ''){
				$fb_url_cliente = $facebook_array['url'];
				$fb_url = '<a href="http://www.facebook.com/' . $fb_url_cliente . '" target="_blank" class="socialmedia_fb"><i class="fa fa-facebook-square"></i>&nbsp;<span>Facebook</span></a>';
				return $fb_url;
			}elseif (isset($facebook_array['id']) && $facebook_array['id'] != ''){
				$fb_url_cliente = $facebook_array['id'];
				$fb_url = '<a href="http://www.facebook.com/' . $fb_url_cliente . '" target="_blank" class="socialmedia_fb"><i class="fa fa-facebook-square"></i>&nbsp;<span>Facebook</span></a>';
				return $fb_url;
			}
            break;
		case 'twitter':
			$twitter_array = get_option('social_twitter');
			if (isset($twitter_array['url']) && $twitter_array['url'] != ''){
				$tw_url_cliente = $twitter_array['url'];
				$tw_url = '<a href="http://www.twitter.com/' . $tw_url_cliente . '" target="_blank" class="socialmedia_tw"><i class="fa fa-twitter-square"></i>&nbsp;<span>Twitter</span></a>';
				return $tw_url;
			}elseif (isset($twitter_array['user_id']) && $twitter_array['user_id'] != ''){
				$tw_url_cliente = $twitter_array['user_id'];
				$tw_url = '<a href="http://www.twitter.com/' . $tw_url_cliente . '" target="_blank" class="socialmedia_tw"><i class="fa fa-twitter-square"></i>&nbsp;<span>Twitter</span></a>';
				return $tw_url;
			}
            break;
		case 'gplus':
			$gplus_array = get_option('social_gplus');
			if (isset($gplus_array['url']) && $gplus_array['url'] != ''){
				$gplus_url_cliente = $gplus_array['url'];
				$gplus_url = '<a href="https://plus.google.com/' . $gplus_url_cliente . '" target="_blank" class="socialmedia_gplus"><i class="fa fa-google-plus-square"></i>&nbsp;<span>Google Plus</a>';
				return $gplus_url;
			}
            break;
		case 'linkedin':
            $lin_array = get_option('social_lin');
			if (isset($lin_array['url']) && $lin_array['url'] != ''){
				$lin_url_cliente = $lin_array['url'];
				$lin_url = '<a href="http://www.linkedin.com/' . $lin_url_cliente . '" target="_blank" class="socialmedia_lin"><i class="fa fa-linkedin-square"></i>&nbsp;<span>Linked In</span></a>';
				return $lin_url;
			}
			break;
		case 'pinterest':
            $pin_array = get_option('social_pinterest');
			if (isset($pin_array['url']) && $pin_array['url'] != ''){
				$pin_url_cliente = $pin_array['url'];
				$pin_url = '<a href="http://www.pinterest.com/' . $pin_url_cliente . '" target="_blank" class="socialmedia_pin"><i class="fa fa-pinterest-square"></i>&nbsp;<span>Pinterest</span></a>';
				return $pin_url;
			}
			break;
		case 'instagram':
            $ig_array = get_option('social_igram');
			if (isset($ig_array['url']) && $ig_array['url'] != ''){
				$ig_url_cliente = $ig_array['url'];
				$ig_url = '<a href="http://instagram.com/' . $ig_url_cliente . '" target="_blank" class="socialmedia_ig"><i class="fa fa-instagram"></i>&nbsp;<span>Instagram</span></a>';
				return $ig_url;
			}
			break;
		case 'youtube':
            $yt_array = get_option('social_ytube');
			if (isset($yt_array['url']) && $yt_array['url'] != ''){
				$yt_url_cliente = $yt_array['url'];
				$yt_url = '<a href="https://www.youtube.com/user/' . $yt_url_cliente . '" target="_blank" class="socialmedia_yt"><i class="fa fa-youtube-square"></i>&nbsp;<span>YouTube</span></a>';
				return $yt_url;
			}
			break;
    }
}
add_shortcode( 'socialmedia', 'socialmedia_iconlist' );
?>
<?php
//Activamos los shortcodes
//[url_lang]
add_action( 'init', 'url_lang_func');
//[socialmedia]
add_action( 'init', 'socialmedia_iconlist');
//Activamos los shortcodes para Widgets de HTML
add_filter('widget_text', 'do_shortcode'); 
?>
