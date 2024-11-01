<?php
/*
Plugin Name: Optimise Opengraph and Microdata Generator for SEO
Plugin URI: http://www.tinbaohiem.com
Description: WprdPress Plugin to add tags to head of your site good for SEO and Facebook Opengraph and Google Schema
Version: 1.0
Author: Lamvt
Author URI: http://www.tinbaohiem.com
License: GPL3
*/

//funtions get image from content post and show in itemprop="image" this image will support for Google + and Facebook ....

function getimage($contents)
{
    $regx = '/<img .*src=["\']([^ ^"^\']*)["\']/';
	preg_match_all( $regx  , $contents , $matches );
	$image = $matches[1][0];
    if(!$image){ 
		$image= plugins_url( 'images/thumbnail.png', __FILE__ );
	}else{
		$url = strpos($image, site_url());
		if($url===false){
			$image =  $_SERVER['HTTP_HOST'].$image;
		}
	}
	return $image;
}
	
//$contents = get_the_content(); function get descriptions from contents post
function getdescriptions($descritions){
	$regex = "/\<img[^\>]*>/";
				$maxchar = 250;
				//if(bloginfo('charset')=='UTF-8'){
				//$maxchar = 400;
				//}
					//$amount = 48;
					


	$descritions = preg_replace($regex,"",$descritions);
	$descritions = preg_replace('/<strong>/i','',$descritions);
	$descritions = preg_replace('/<b>/i','',$descritions);
	$descritions = preg_replace('/<em>/i','',$descritions);
	$descritions = preg_replace('/<\/strong>/i','',$descritions);
	$descritions = preg_replace('/<\/b>/i','',$descritions);
	$descritions = preg_replace('/<\/em>/i','',$descritions);
	$descritions = preg_replace('/(\[caption(.*?)\])(.*?)(\[\/caption\])/i', '', $descritions); 
	$descritions = preg_replace('#<a(.*?)>(.*?)<\/a>#is', '', $descritions); 
	$descritions = htmlspecialchars($descritions);
	$descritions = strip_tags($descritions);
	
	if (( strlen($descritions) >= $maxchar )&&($espacio = strpos($descritions, " ", $maxchar ))){ 
						$title = mb_substr( $descritions, 0, $maxchar, 'UTF-8' );
						$descritions = substr($descritions,0,$espacio);		
					} else {		
						echo $descritions;
					}
	return $descritions;

}

// fields for Facebook OPengraph and Schema Microdata add to head of site.
function metatags() {
	if(is_single() ){
		if (have_posts()) : while (have_posts()) : the_post(); 
				$content = get_the_content();
				
			$title=get_the_title($post->post_title);
			$link=get_permalink();
			$image=getimage($content);
			$blogname=get_option('blogname');
			$descriptions= getdescriptions($content);
		endwhile; endif; 
	}else{
			$title=get_option('blogname');
			$link=get_option('siteurl');
			$image=getimage($content);
			$blogname=get_option('blogname');
			$descriptions=get_option('blogdescription');
	}
	$metatags.="\n";
	$metatags.='<meta property="og:title" content="'.$title.'" />';
	$metatags.="\n";
	$metatags.='<meta property="og:type" content="blog" />';
	$metatags.="\n";
	$metatags.='<meta property="og:url" content="'.$link.'" />';
	$metatags.="\n";
	$metatags.='<meta property="og:image" content="'.$image.'" />';
	$metatags.="\n";
	$metatags.='<meta property="og:site_name" content="'.$blogname.'" />';
	$metatags.="\n";
	$metatags.='<meta property="og:description" content="'.$descriptions.'" />';
	$metatags.="\n";
	$metatags.='<meta itemprop="name" content="'.$title.'">';
	$metatags.="\n";
	$metatags.='<meta itemprop="description" content="'.$descriptions.'">';
	$metatags.="\n";
	$metatags.='<meta itemprop="image" content="'.$image.'">';
	$metatags.="\n";
	$metatags.='<meta itemprop="url" content="'.$link.'">';
	$metatags.="\n";
	$metatags.="\n";
	
	echo $metatags;
}

add_action('wp_head', 'metatags');
?>