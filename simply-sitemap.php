<?php
/*
Plugin Name: Simply Sitemap
Plugin URI:  https://github.com/jollygoodtime/simplysitemap
Description: Display Html Sitemaps
Version: 1.0.0
Author: jollygoodtime
Author URI: https://github.com/jollygoodtime/simplysitemap
Text Domain: simply-sitemap
License: GPLv3 or later
*/

function simply_sitemap_cat($categoryid = 0, $returnhtml = null) {
	
	$categories = get_categories ( 'parent=' . $categoryid );

	if ( (is_array($categories)) &&  (count ( $categories ) > 0)) 
	{
		foreach ( $categories as $categorie_single ) {
			
			if ($categorie_single->category_parent > 0) {
				$returnhtml .= "<div class='child-category'>" . $categorie_single->name . " " . "</div>";
			} else {
				$returnhtml .= "<div class='top-category'>" . $categorie_single->name . " " . "</div>";
			}
			
			$returnhtml .= "<ul class='ul-posts-cat'>";
			$categorie_single_id = $categorie_single->term_id;
			$posts = get_posts ( "posts_per_page=-1&category__in=$categorie_single_id");
			
			if ( (is_array($posts)) &&  (count ( $posts ) > 0)) 
			{
				foreach ( $posts as $post_single ) {
					$postlink = get_permalink ( $post_single->ID );
					$posttitle = $post_single->post_title;
					$returnhtml .= '<li  class="single-page-post">';
					$returnhtml .= "<a href='$postlink'>$posttitle</a>";
					$returnhtml .= "</li>";
				}
			}
			$returnhtml = simply_sitemap_cat ( $categorie_single->term_id, $returnhtml );
			$returnhtml .= "</ul>";
		}
	}
	
	return $returnhtml;
}

function simply_sitemap() {
	$returnhtml = '';
	$returnhtml .= simply_sitemap_cat ( 0, $returnhtml );
	$returnhtml .= "<ul class='ul-pages'>";
	$pageargs = [];
	$pageargs['$sort_order'] = 'ASC';
	$pageargs['post_status'] = 'publish';
	$alpages = get_pages($pageargs);
	foreach ( $alpages as $page_single ) 
	{
		$pagelink = get_permalink ( $page_single->ID );
		$returnhtml .= "<li class='single-page-post'><a href='$pagelink'>$page_single->post_title</a></li>";
	}
	$returnhtml .= "</ul>";
	
	return $returnhtml;
}

add_shortcode ( 'sitemappage', 'simply_sitemap' );
