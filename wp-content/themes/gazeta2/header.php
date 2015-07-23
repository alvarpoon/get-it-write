<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<!-- Meta -->
	<meta charset="<?php bloginfo( 'charset' );?>">
	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="<?php print get_template_directory_uri()?>/assets/js/html5shiv.js"></script>
		<script src="<?php print get_template_directory_uri()?>/assets/js//respond.min.js"></script>
	<![endif]-->
	<?php wp_head();?>
</head>
<body <?php body_class();?>>
<?php global $gazeta_global_data;?>
<?php if( gazeta_shows_top_bar() ):?>
<!-- Topbar -->
<div class="top-bar container">
	<div class="row">
		<div class="col-md-6">
			<ul class="tb-left">
				<li class="tbl-date"><span><?php print date_i18n( apply_filters( 'gazeta_current_datetime/format' , 'l, F j, Y') , current_time('timestamp'));?></span></li>
				<?php if( gazeta_get_the_weather(apply_filters("the_weather", "c")) ):?>
					<li class="tbl-temp"><i class="fa fa-sun-o"></i><?php print gazeta_get_the_weather(apply_filters("the_weather", "c"));?></li>
				<?php endif;?>
			</ul>
		</div>
		<div class="col-md-6">
			<ul class="tb-right">
				<?php 
				$socials_field = function_exists( 'gazeta_user_contactmethods' ) ? gazeta_user_contactmethods( array() ): '';
				if( is_array( $socials_field ) && !empty( $socials_field ) ):
				?>			
					<li class="tbr-social">
						<span>
							<?php foreach ( $socials_field  as $key=>$value):?>
								<?php if( isset( $gazeta_global_data['header_social_'.$key] ) && !empty( $gazeta_global_data['header_social_'.$key] ) ):?>
									<a href="<?php print esc_url( $gazeta_global_data['header_social_'.$key] )?>" class="fa fa-<?php print esc_attr( $key );?>"></a>
								<?php endif;?>
							<?php endforeach;?>
						</span>
					</li>
				<?php endif;?>
				<li class="tbr-login">
					<?php 
						if( get_current_user_id() ){
							printf( __('Hi %s, %s','gazeta'), '<a href="'.get_author_posts_url( get_current_user_id() ) .'">'.get_the_author_meta( 'display_name', get_current_user_id() ).'</a>', '<a href="'.wp_logout_url( apply_filters( 'gazeta_logout_url' , home_url()) ).'">'.__('Logout?','gazeta').'</a>' );
						}
						else{
							?>
								<a href="<?php print wp_login_url( apply_filters( 'login_url_redirect' , home_url()) );?>"><?php _e('Login','gazeta');?></a>		
							<?php 
						}
					?>
					
				</li>
			</ul>
		</div>
	</div>
</div>
<?php endif;?>
<div class="container wrapper">
	<div class="header">
		<div class="col-md-12">
			<!-- Logo -->
			<div class="col-md-<?php if( is_active_sidebar( 'header-sidebar' ) ):?>4<?php else:?>12<?php endif;?> logo">
				<?php if( get_header_image() ):?>
					<h1>
						<a title="<?php print esc_attr( get_bloginfo( 'description' ) );?>" href="<?php print home_url( '/' );?>">
							<img alt="<?php print esc_attr( get_bloginfo( 'description' ) );?>" src="<?php header_image();?>">
						</a>
					</h1>
				<?php else:?>
					<h1>
						<a title="<?php print esc_attr( get_bloginfo( 'description' ) );?>" href="<?php print home_url( '/' );?>">
							<?php print esc_attr( get_bloginfo( 'name' ) );?>
						</a>
					</h1>				
				<?php endif;?>
			</div>
			<?php if( is_active_sidebar( 'header-sidebar' ) ):?>
				<div class="col-md-8">
					<?php dynamic_sidebar( 'header-sidebar' );?>
				</div>
			<?php endif;?>
		</div>
	</div>
	
	<!-- Header -->
	<header>
		<div class="col-md-12">
			<div class="row">
				<!-- Navigation -->
				<div class="col-md-12">
					<?php if( has_nav_menu('main_navigation') ):?>
						<?php 
						$mega_menu_settings = get_site_option( 'megamenu_settings' );
						$megamenu = true;
						if( !isset( $mega_menu_settings['main_navigation']['enabled'] ) ):
						$megamenu = false;
						?>
						<div class="menu-trigger"><i class="fa fa-align-justify"></i> <?php _e('Menu','gazeta');?></div>
						<?php endif;?>
						<nav class="main-nagivation <?php if( $megamenu ):?>main-nagivation-mega<?php endif;?>">
					    	<?php 
					    		wp_nav_menu( apply_filters( 'gazeta_nav_menu_args' , array(
					    			'menu_class'		=>	'mega-menu-horizontal',
					    			'menu_id'			=>	'nav',
					    			'theme_location'	=>	'main_navigation',
					    			'container'			=>	null,
					    			'walker'			=>	class_exists( 'BootStrap_Walker_Nav_Menu' ) ? new BootStrap_Walker_Nav_Menu() : ''
					    		)) );
					    	?>									
				    	</nav>
					<?php endif;?>
					<!-- Search -->
					<div class="search-form">
						<form method="get" class="search-form" action="<?php print esc_url( home_url('/') );?>">
							<input type="search" name="s" value="<?php print esc_attr( get_query_var('s') );?>" placeholder="<?php _e('Type to search and hit enter','gazeta');?>">
							<?php do_action( 'gazeta_searchform' );?>
						</form>
					</div>
					<span class="search-trigger"><i class="fa fa-search"></i></span>
				</div>
			</div>
		</div>
	</header>