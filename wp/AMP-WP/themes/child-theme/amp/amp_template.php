<?php 
 /**
 *
 * Example AMP-WP Template
 *
 * Alexander Graef
 * portalzine.de
 * 
 */
	 // Load phpQuery
	  require('my-libs/phpQuery/phpQuery.php');
	 
	 // Load GesSHi
	  require('my-libs/geshi/geshi.php');  
	 
	 // Get AMP content and add it to phpQuery 
	  $doc = phpQuery::newDocumentHTML($this->get( 'post_amp_content' )); 	 
		  
	  $highlight = array();
	  
	  // Loop through the code snippets (pre tags)
	  foreach(pq('pre') as $snippet){
  		
		// Get classes of tag
		$class= pq($snippet)->attr("class");
		
		// Check for Crayon Syntax Highlighter Class
		if(strpos($class, "lang:")  !== false && strpos($class, "lang:default")  === false){
			preg_match("/lang:(?P<lang>\w+)/",$class, $catch);
		
		}else{
			// Fallback, if no language is detected
			$catch['lang'] = "php";	
		}		
 		
		// New Syntax Highlighter for each code snippet, with the correct language set
		// Storing for later stylesheet output. This makes also sure that stylesheets are only loaded once.
		$highlight[$catch['lang']] = new GeSHi(pq($snippet)->text(),$catch['lang']);
		
		// Uses Classes and no inline styles
		$highlight[$catch['lang']]->enable_classes();
		
		// Update code snippet
		$html = pq($snippet)->html($highlight[$catch['lang']]->parse_code());
	  }
	 
	
?><!doctype html>
<html amp>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<link href="https://fonts.googleapis.com/css?family=Oswald:400,400italic,700,700italic|Open+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
<?php do_action( 'amp_post_template_head', $this ); ?>
<style amp-custom>
<?php $this->load_parts( array( 'style' ) );
?> <?php do_action( 'amp_post_template_css', $this );
    
	// Load Stylesheets for highlighted languages
	
	foreach($highlight as $store){
		echo $store->get_stylesheet();
	}

?>
</style>
</head>
<body>
<nav class="amp-wp-title-bar">
  <div> <a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
    <?php $site_icon_url = $this->get( 'site_icon_url' ); ?>
    <?php if ( $site_icon_url ) : ?>
    <amp-img src="<?php echo esc_url( $site_icon_url ); ?>" width="32" height="32" class="amp-wp-site-icon"></amp-img>
    <?php endif; ?>
    <?php echo esc_html( $this->get( 'blog_name' ) ); ?> </a> </div>
</nav>
<div class="amp-wp-content">
  <div class="nav">
    <?php
	
	global $query_string; 
 	$posts = query_posts($query_string); if (have_posts()) : while (have_posts()) : the_post(); 

	$next_post = get_next_post();
	if (!empty( $next_post )): 
    	echo '<a class="next" href="'.amp_get_permalink( $next_post->ID ).'"> &#8604; '.$next_post->post_title.'</a>';
    endif; 
   ?>
  </div>
  <?php endwhile; endif; ?>
  <h1 class="amp-wp-title"><?php echo esc_html( $this->get( 'post_title' ) ); ?></h1>
  <ul class="amp-wp-meta">
    <?php $this->load_parts( apply_filters( 'amp_post_template_meta_parts', array( 'meta-author', 'meta-time', 'meta-taxonomy' ) ) ); ?>
  </ul>
  <?php 
  
	 // Output updated amp-content
	  echo  $doc->html(); 
	 
	  // amphtml content; no kses ?>
  <div class="nav bottom">
    <?php
	$prev_post = get_previous_post();
if (!empty( $prev_post )): ?>
    <a class="prev" href="<?php echo amp_get_permalink( $prev_post->ID ); ?>"> &#8605; <?php echo $prev_post->post_title; ?> </a><br>
    <?php endif; ?>
  </div>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>
</html>