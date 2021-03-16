<?php
/**
 * Functions which construct the theme by hooking into WordPress
 *
 * @package uni_education
 */


/*------------------------------------------------
            HEADER HOOK
------------------------------------------------*/

if ( ! function_exists( 'uni_education_doctype' ) ) :
	/**
	 * Doctype Declaration.
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_doctype() { ?>
		<!DOCTYPE html>
			<html <?php language_attributes(); ?>>
	<?php }
endif;
add_action( 'uni_education_doctype_action', 'uni_education_doctype', 10 );

if ( ! function_exists( 'uni_education_head' ) ) :
	/**
	 * head Codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_head() { ?>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
			<link rel="profile" href="http://gmpg.org/xfn/11">
			<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
				<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
			<?php endif; ?>
			<?php wp_head(); ?>
		</head>
	<?php }
endif;
add_action( 'uni_education_head_action', 'uni_education_head', 10 );

if ( ! function_exists( 'uni_education_body_start' ) ) :
	/**
	 * Body start codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_body_start() { ?>
		<body <?php body_class(); ?>>
		<?php do_action( 'wp_body_open' ); ?>
	<?php }
endif;
add_action( 'uni_education_body_start_action', 'uni_education_body_start', 10 );


if ( ! function_exists( 'uni_education_page_start' ) ) :
	/**
	 * Page starts html codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_page_start() { ?>
		<div id="page" class="site">
			<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'uni-education' ); ?></a>
	<?php }
endif;
add_action( 'uni_education_page_start_action', 'uni_education_page_start', 10 );


if ( ! function_exists( 'uni_education_loader' ) ) :
	/**
	 * loader html codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_loader() { 
		if ( ! uni_education_theme_option( 'enable_loader' ) )
			return;
		
		$loader = uni_education_theme_option( 'loader_type' )
		?>
		<div id="loader">
            <div class="loader-container">
               	<?php echo uni_education_get_svg( array( 'icon' => esc_attr( $loader ) ) ); ?>
            </div>
        </div><!-- #loader -->
	<?php }
endif;
add_action( 'uni_education_page_start_action', 'uni_education_loader', 20 );


if ( ! function_exists( 'uni_education_top_bar' ) ) :
	/**
	 * Page starts html codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_top_bar() { 
		if ( ! uni_education_theme_option( 'enable_topbar' ) )
			return;

		$topbar_open_hrs 	= uni_education_theme_option( 'topbar_open_hrs' );
		$phone 		= uni_education_theme_option( 'topbar_phone' );
		$email 		= uni_education_theme_option( 'topbar_email' );
		?>
		<div id="top-menu">
            <?php 
            echo uni_education_get_svg( array( 'icon' => 'up', 'class' => 'dropdown-icon' ) );
            echo uni_education_get_svg( array( 'icon' => 'down', 'class' => 'dropdown-icon' ) ); 
            ?>
            
            <div class="wrapper">
                <div class="secondary-menu">
                	<ul class="menu">
                		<?php if ( ! empty( $topbar_open_hrs ) ) : ?>
	                		<li>
	                			<?php 
                        		echo uni_education_get_svg( array( 'icon' => 'clock' ) ); 
		                        echo esc_html( $topbar_open_hrs ); 
		                        ?>
	                		</li>
	                	<?php endif;
	                	
	                	if ( ! empty( $phone ) ) : ?>
	                		<li><a href="<?php echo esc_url( 'tel:' . $phone ); ?>">
	                			<?php 
                        		echo uni_education_get_svg( array( 'icon' => 'phone-o' ) ); 
		                        echo esc_html( $phone ); 
		                        ?>
	                		</a></li>
                		<?php endif;
	                	
	                	if ( ! empty( $email ) ) : ?>
	                		<li><a href="<?php echo esc_url( 'mailto:' . $email ); ?>">
	                			<?php 
                        		echo uni_education_get_svg( array( 'icon' => 'plane' ) ); 
		                        echo esc_html( $email ); 
		                        ?>
	                		</a></li>
	                	<?php endif; ?>
                	</ul>
                </div><!-- .secondary-menu -->

	            <?php if ( uni_education_theme_option( 'show_top_search' ) ) : ?>
		            <div id="top-search" class="social-menu">
	                	<ul>
	                		<li>
	                			<div id="search"><?php get_search_form(); ?></div>
	                			<a href="#" class="search">
	                				<?php echo uni_education_get_svg( array( 'icon' => 'search' ) ); ?>
	            				</a>
	                		</li>
	                	</ul>
	                </div>
	            <?php endif;

	            if ( uni_education_theme_option( 'show_social_menu' ) && has_nav_menu( 'social' ) ) : ?>
	                <div class="social-menu">
	                    <?php  
	                	wp_nav_menu( array(
	                		'theme_location'  	=> 'social',
	                		'container' 		=> false,
	                		'menu_class'      	=> 'menu',
	                		'depth'           	=> 1,
	            			'link_before' 		=> '<span class="screen-reader-text">',
							'link_after' 		=> '</span>',
	                	) );
	                	?>
	                </div><!-- .social-menu -->
                <?php endif; ?>
            </div><!-- .wrapper -->
        </div><!-- #top-menu -->
	<?php }
endif;
add_action( 'uni_education_page_start_action', 'uni_education_top_bar', 20 );


if ( ! function_exists( 'uni_education_header_start' ) ) :
	/**
	 * Header starts html codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_header_start() { 
		$sticky_header = uni_education_theme_option( 'enable_sticky_header' ) ? 'sticky-header' : ''; 
		?>
		<header id="masthead" class="site-header <?php echo esc_attr( $sticky_header ); ?>">
		<div class="wrapper">
	<?php }
endif;
add_action( 'uni_education_header_start_action', 'uni_education_header_start', 10 );


if ( ! function_exists( 'uni_education_site_branding' ) ) :
	/**
	 * Site branding codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_site_branding() { ?>
		<div class="site-menu">
            <div class="container">
				<div class="site-branding pull-left">
					<?php
					// site logo
					the_custom_logo();
					?>
					<div class="site-details">
						<?php if ( is_front_page() && is_home() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php endif;

						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
						<?php endif; ?>
					</div><!-- .site-details -->
				</div><!-- .site-branding -->
	<?php }
endif;
add_action( 'uni_education_site_branding_action', 'uni_education_site_branding', 10 );


if ( ! function_exists( 'uni_education_primary_nav' ) ) :
	/**
	 * Primary nav codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_primary_nav() { ?>
		<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'uni-education' ); ?></span>
            <svg viewBox="0 0 40 40" class="icon-menu">
                <g>
                    <rect y="7" width="40" height="2"/>
                    <rect y="19" width="40" height="2"/>
                    <rect y="31" width="40" height="2"/>
                </g>
            </svg>
            <?php echo uni_education_get_svg( array( 'icon' => 'close' ) ); ?>
        </button>
		<nav id="site-navigation" class="main-navigation">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
        			'container' => 'div',
        			'menu_class' => 'menu nav-menu',
        			'menu_id' => 'primary-menu',
        			'fallback_cb' => 'uni_education_menu_fallback_cb',
				) );
			?>
		</nav><!-- #site-navigation -->
		</div><!-- .container -->
        </div><!-- .site-menu -->
	<?php }
endif;
add_action( 'uni_education_primary_nav_action', 'uni_education_primary_nav', 10 );


if ( ! function_exists( 'uni_education_header_ends' ) ) :
	/**
	 * Header ends codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_header_ends() { ?>
		</div><!-- .wrapper -->
		</header><!-- #masthead -->
	<?php }
endif;
add_action( 'uni_education_header_ends_action', 'uni_education_header_ends', 10 );


if ( ! function_exists( 'uni_education_site_content_start' ) ) :
	/**
	 * Site content start codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_site_content_start() { ?>
		<div id="content" class="site-content">
	<?php }
endif;
add_action( 'uni_education_site_content_start_action', 'uni_education_site_content_start', 10 );


/**
 * Display custom header title in frontpage and blog
 */
function uni_education_custom_header_title() {
	if ( is_home() && is_front_page() ) : 
		$title = uni_education_theme_option( 'latest_blog_title', 'Blogs' ); ?>
		<h2><?php echo esc_html( $title ); ?></h2>
	<?php elseif ( is_singular() || ( is_home() && ! is_front_page() ) ): ?>
		<h2><?php single_post_title(); ?></h2>
	<?php elseif ( is_archive() ) : 
		the_archive_title( '<h2>', '</h2>' );
	elseif ( is_search() ) : ?>
		<h2><?php printf( esc_html__( 'Search Results for: %s', 'uni-education' ), get_search_query() ); ?></h2>
	<?php elseif ( is_404() ) :
		echo '<h2>' . esc_html__( 'Oops! That page can&#39;t be found.', 'uni-education' ) . '</h2>';
	endif;
}


if ( ! function_exists( 'uni_education_add_breadcrumb' ) ) :
	/**
	 * Add breadcrumb.
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_add_breadcrumb() {
		// Bail if Breadcrumb disabled.
		if ( ! uni_education_theme_option( 'enable_breadcrumb' ) ) {
			return;
		}
		
		// Bail if Home Page.
		if ( ! is_home() && is_front_page() ) {
			return;
		}

		echo '<div id="breadcrumb-list" >';
				/**
				 * uni_education_breadcrumb hook
				 *
				 * @hooked uni_education_breadcrumb -  10
				 *
				 */
				do_action( 'uni_education_breadcrumb' );
		echo '</div><!-- #breadcrumb-list -->';
		return;
	}
endif;


if ( ! function_exists( 'uni_education_custom_header' ) ) :
	/**
	 * Site content codes
	 *
	 * @since Uni Education 1.0.0
	 *
	 */
	function uni_education_custom_header() {
		if ( ! is_home() && is_front_page() ) {
			return;
		}
		
		$image = get_header_image() ? get_header_image() : get_template_directory_uri() . '/assets/uploads/banner.jpg';
		if ( is_singular() ) {
			$image = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'full' ) : $image;
		}
		?>

        <div class="inner-header-image" style="background-image: url( '<?php echo esc_url( $image ); ?>' )">
        	<div class="overlay"></div>
        	<div class="wrapper">
                <div class="custom-header-content">
                    <?php 
                    echo uni_education_custom_header_title();
                    uni_education_add_breadcrumb(); 
                    ?>
                </div><!-- .custom-header-content -->
        	</div><!-- .wrapper -->
        </div><!-- .custom-header-content-wrapper -->
		<?php
	}
endif;
add_action( 'uni_education_site_content_start_action', 'uni_education_custom_header', 20 );


/*------------------------------------------------
            FOOTER HOOK
------------------------------------------------*/

if ( ! function_exists( 'uni_education_site_content_ends' ) ) :
	/**
	 * Site content ends codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_site_content_ends() { ?>
		</div><!-- #content -->
	<?php }
endif;
add_action( 'uni_education_site_content_ends_action', 'uni_education_site_content_ends', 10 );


if ( ! function_exists( 'uni_education_footer_start' ) ) :
	/**
	 * Footer start codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_footer_start() { ?>
		<footer id="colophon" class="site-footer">
	<?php }
endif;
add_action( 'uni_education_footer_start_action', 'uni_education_footer_start', 10 );


if ( ! function_exists( 'uni_education_site_info' ) ) :
	/**
	 * Site info codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_site_info() { 
		$copyright = uni_education_theme_option('copyright_text');
		$poweredby = sprintf( esc_html__( ' Uni Education by %1$s Shark Themes %2$s', 'uni-education' ), '<a href="' . esc_url( 'http://sharkthemes.com/' ) . '" target="_blank">','</a>' );
		?>
		<div class="site-info">
            <div class="wrapper">
            	<?php if ( ! empty( $copyright ) ) : ?>
	                <div class="copyright">
	                    <p>
	                    	<?php 
	                    	echo uni_education_santize_allow_tags( $copyright ); 
	                    	if ( function_exists( 'the_privacy_policy_link' ) ) {
								the_privacy_policy_link( ' | ' );
							}
	                    	?>
	                    </p>
	                </div><!-- .copyright -->
	            <?php endif; 

	            if ( ! empty( $copyright ) ) : ?>
	                <div class="powered-by">
	                    <p><?php echo uni_education_santize_allow_tags( $poweredby ); ?></p>
	                </div><!-- .powered-by -->
	            <?php endif; ?>
            </div><!-- .wrapper -->    
        </div><!-- .site-info -->
	<?php }
endif;
add_action( 'uni_education_site_info_action', 'uni_education_site_info', 10 );


if ( ! function_exists( 'uni_education_footer_ends' ) ) :
	/**
	 * Footer ends codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_footer_ends() { ?>
		</footer><!-- #colophon -->
	<?php }
endif;
add_action( 'uni_education_footer_ends_action', 'uni_education_footer_ends', 10 );


if ( ! function_exists( 'uni_education_slide_to_top' ) ) :
	/**
	 * Footer ends codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_slide_to_top() { ?>
		<div class="backtotop">
            <?php echo uni_education_get_svg( array( 'icon' => 'up' ) ); ?>
        </div><!-- .backtotop -->
	<?php }
endif;
add_action( 'uni_education_footer_ends_action', 'uni_education_slide_to_top', 20 );


if ( ! function_exists( 'uni_education_page_ends' ) ) :
	/**
	 * Page ends codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_page_ends() { ?>
		</div><!-- #page -->
	<?php }
endif;
add_action( 'uni_education_page_ends_action', 'uni_education_page_ends', 10 );


if ( ! function_exists( 'uni_education_body_html_ends' ) ) :
	/**
	 * Body & Html ends codes
	 *
	 * @since Uni Education 1.0.0
	 */
	function uni_education_body_html_ends() { ?>
		</body>
		</html>
	<?php }
endif;
add_action( 'uni_education_body_html_ends_action', 'uni_education_body_html_ends', 10 );
