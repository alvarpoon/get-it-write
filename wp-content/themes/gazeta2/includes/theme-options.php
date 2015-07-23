<?php
if( !defined('ABSPATH') ) exit;
if (!class_exists("Gazeta_Theme_Options")) {
    class Gazeta_Theme_Options {

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {
			$this->initSettings();
        }

        public function initSettings() {

            if ( !class_exists("ReduxFramework" ) ) {
                return;
            }       
            
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();
            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function setSections() {
			//---- Theme Option Here ----//
			$schedules	=	array();
        	$wp_get_schedules	=	function_exists( 'wp_get_schedules' ) ? wp_get_schedules() : null;
        	if( is_array( $wp_get_schedules ) && !empty( $wp_get_schedules ) ){
        		foreach ($wp_get_schedules as $key=>$value) {
        			$schedules[ $key ]	=	$value['display'];
        		}
        	}
			// General settings
			$this->sections[] 	=	array(
				'title'	=>	__('General','gazeta'),
				'icon'	=>	'el-icon-website',
				'desc'	=>	null,
				'fields'	=>	array(						
					array(
						'id'	=>	'favicon',
						'type'	=>	'media',
						'url' => true,
						'preview'  => false,
						'subtitle' => __('Upload any media using the WordPress native uploader', 'gazeta'),
						'title'	=>	__('Favicon','gazeta')
					),
					array(
						'id'        => 'sidebar',
						'type'      => 'image_select',
						'title'     => __('Blog Layout', 'gazeta'),
						'options'   => array(
							'left-sidebar' => array('alt' => __('Left Sidebar','gazeta'),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
							'right-sidebar' => array('alt' => __('Right Sidebar','gazeta'),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png')
						),
						'default' => 'right-sidebar',
						'description'	=>	__('Set the layout of the blog/category/archive/author/tag/search page, not for static page.','gazeta')
					),	
					array(
						'id'			=>	'transient',
						'type'			=>	'checkbox',
						'title'			=>	__('Enable Transient','gazeta'),
						'description'	=>	__('Caching the Widget/Shortcode','gazeta'),
						'default'  		=> '0'
					),							
					array(
						'id'       => 'transient_expiration',
						'type'     => 'text',
						'title'    => __( 'Transient Expiration', 'gazeta' ),
						'subtitle' => __( 'Set/update the expiration value of the transients.', 'gazeta' ),
						'validate' => 'numeric',
						'description'     => __( 'Time until expiration in seconds from now, or 0 for never expires. Ex: For one day, the expiration value would be: (60 * 60 * 24), all widgets and shortcodes will be cached with expiration is 300 seconds (mean is 5 minutes), you can set this value in the widget or the shortcode with the various values, example: <strong>[shortcode expiration="600"]</strong> or enter a general value in this option, this will effect to all Widgets and Shortcodes.', 'gazeta' ),
						'required'  => array('transient', "=", '1')
					),
					
					array(
						'id'       => '404-page',
						'type'     => 'select',
						'data'     => 'pages',
						'title'    => __( '404 Page', 'gazeta' ),
						'subtitle' => __( 'Choose the 404 Error Page.', 'gazeta' ),
						'description'     => __( 'Or leave blank for default.', 'gazeta' ),
					),						
// 					array(
// 						'id'       => 'minifying',
// 						'type'     => 'switch',
// 						'title'    => __( 'Minifying JS/CSS', 'gazeta' ),
// 						'subtitle'	=>	__('Loading JS/CSS Min','gazeta'),
// 						'description'	=>	__('This does not effect to another plugins\'s JS/CSS.','gazeta'),
// 						'default'	=>	0
// 					),
					array(
						'id'       => 'viewing',
						'type'     => 'switch',
						'title'    => __( 'Viewing', 'gazeta' ),
						'subtitle' => __( 'Activating Post Viewing feature.', 'gazeta' ),
						'description'	=>	__('On/Off will display/hide the View count number, this feature require Jetpack\'s Stats feature activated.','gazeta'),
						'default'	=>	1
					),
                   array(
                        'id' => 'custom_css',
                        'type' => 'ace_editor',
                        'title' => __('Custom CSS', 'gazeta'),
                        'subtitle' => __('Paste your CSS code here, no style tag.', 'gazeta'),
                        'mode' => 'css',
                        'theme' => 'monokai'
                    ),
                    array(
                    	'id' => 'custom_css_mobile',
                    	'type' => 'ace_editor',
                    	'title' => __('Mobile Custom CSS', 'gazeta'),
                    	'subtitle' => __('Paste your CSS code here, no style tag, this CSS will effect to the site on Mobile.', 'gazeta'),
                    	'mode' => 'css',
                    	'theme' => 'monokai'
                    ),
                    array(
                        'id' => 'custom_js',
                        'type' => 'ace_editor',
                        'title' => __('Custom JS', 'gazeta'),
                        'subtitle' => __('Paste your JS code here, no script tag, eg: alert(\'hello world\');', 'gazeta'),
                        'mode' => 'javascript',
                        'theme' => 'chrome'
                    ),
                    array(
                    	'id' => 'custom_js_mobile',
                    	'type' => 'ace_editor',
                    	'title' => __('Mobile Custom JS', 'gazeta'),
                    	'subtitle' => __('Paste your JS code here, no script tag, this JS will effect to the site on Mobile eg: alert(\'hello world\');', 'gazeta'),
                    	'mode' => 'javascript',
                    	'theme' => 'chrome'
                    )                    
				)
			);
			
			// Styling.
			$this->sections[] 	=	array(
				'title'	=>	__('Styling','gazeta'),
				'icon'	=>	'el-icon-th-list',
				'desc'	=>	null,
				'fields'	=>	array(
					array(
						'id'          => 'body',
						'type'        => 'typography',
						'title'       => __( 'Body', 'gazeta' ),
						'font-size'	=>	false,
						'color'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'body' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'body' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Raleway',
							'google'      => true
						)
					),
					array(
						'id'          => 'menu',
						'type'        => 'typography',
						'title'       => __( 'Menu Item', 'gazeta' ),
						'font-size'	=>	false,
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'header nav ul li a' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'header nav ul li a' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),					
					array(
						'id'          => 'heading',
						'type'        => 'typography',
						'title'       => __( 'Heading', 'gazeta' ),
						'font-size'	=>	false,
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'h1,h2,h3,h4,h5,h6' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'h1,h2,h3,h4,h5,h6' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),					
					array(
						'id'          => 'heading1',
						'type'        => 'typography',
						'title'       => __( 'Heading 1', 'gazeta' ),
						'color'	=>	false,
						'font-family'	=>	false,
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'h1' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'h1' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),	
					array(
						'id'          => 'heading2',
						'type'        => 'typography',
						'title'       => __( 'Heading 2', 'gazeta' ),
						'color'	=>	false,
						'font-family'	=>	false,
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'h2' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'h2' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),
					array(
						'id'          => 'heading3',
						'type'        => 'typography',
						'title'       => __( 'Heading 3', 'gazeta' ),
						'color'	=>	false,
						'font-family'	=>	false,
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'h3' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'h3' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),
					array(
						'id'          => 'heading4',
						'type'        => 'typography',
						'title'       => __( 'Heading 4', 'gazeta' ),
						'color'	=>	false,
						'font-family'	=>	false,
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'h4' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'h4' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),
					array(
						'id'          => 'heading5',
						'type'        => 'typography',
						'title'       => __( 'Heading 5', 'gazeta' ),
						'color'	=>	false,
						'font-family'	=>	false,
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'h5' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'h5' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),
					array(
						'id'          => 'heading6',
						'type'        => 'typography',
						'title'       => __( 'Heading 6', 'gazeta' ),
						'color'	=>	false,
						'font-family'	=>	false,
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'h6' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'h6' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),
					array(
						'id'          => 'entry-title',
						'type'        => 'typography',
						'title'       => __( 'Entry Title', 'gazeta' ),
						'font-size'	=>	false,
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( '.entry-title, .entry-title a, .post-title a' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( '.entry-title, .entry-title a, .post-title a' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),
					array(
						'id'          => 'entry-content',
						'type'        => 'typography',
						'title'       => __( 'Entry Content', 'gazeta' ),
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( 'div.entry-content p, p.entry-content' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( 'div.entry-content p, p.entry-content' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Arial',
							'google'      => true
						)
					),	
					array(
						'id'          => 'widget-heading-font',
						'type'        => 'typography',
						'title'       => __( 'Widget Heading Font', 'gazeta' ),
						'font-weight'	=>	false,
						'font-style'	=>	false,
						'subsets'	=>	false,
						//'compiler'      => true,  // Use if you want to hook in your own CSS compiler
						'google'      => true,
						// Disable google fonts. Won't work if you haven't defined your google api key
						'subsets'	  => false,
						'font-backup' => false,
						'text-align'	=>	false,
						'line-height'	=>	false,
						'all_styles'  => true,
						// Enable all Google Font style/weight variations to be added to the page
						'output'      => array( '.side-widget h5 span' ),
						// An array of CSS selectors to apply this font style to dynamically
						'compiler'    => array( '.side-widget h5 span' ),
						// An array of CSS selectors to apply this font style to dynamically
						'units'       => 'px',
						// Defaults to px
						'default'     => array(
							'font-family' => 'Oswald',
							'google'      => true
						)
					),
					array(
						'id'       => 'widget_heading_wrapper_bg',
						'type'     => 'background',
						'output'   => array( '.side-widget h5' ),
						'title'    => __( 'Widget Wrapper Background', 'saturn' ),
						'subtitle' => __( 'Pick a background color for the Widget Heading.', 'saturn' ),
						'background-repeat'	=>	false,
						'background-attachment'	=>false,
						'background-position'	=>	false,
						'background-image'	=>	false,
						'background-size'	=> false
					),					
					array(
						'id'       => 'widget_heading_inner_bg',
						'type'     => 'background',
						'output'   => array( '.side-widget h5 span' ),
						'title'    => __( 'Widget Inner Background', 'saturn' ),
						'subtitle' => __( 'Pick a background color for the Widget Heading.', 'saturn' ),
						'background-repeat'	=>	false,
						'background-attachment'	=>false,
						'background-position'	=>	false,
						'background-image'	=>	false,
						'background-size'	=> false						
					),
					array(
						'id'       => 'widget_heading_color',
						'type'     => 'color',
						'output'   => array( '.side-widget h5 span' ),
						'title'    => __( 'Widget Heading Color', 'saturn' ),
						'subtitle' => __( 'Pick a color for the Widget Heading (default: #fff).', 'saturn' ),
						'validate' => 'color'
					),					
				)
			);		
			
			$header_field	=	array();
			$header_field[] = 	array(
				'id'			=>	'top_bar',
				'type'			=>	'checkbox',
				'title'			=>	__('Shows the Top Bar','gazeta'),
				'default'  		=> '1'
			);
			$header_field[] = 	array(
				'id'			=>	'header_current_time',
				'type'			=>	'checkbox',
				'title'			=>	__('Current Datetime','gazeta'),
				'subtitle'		=>	__('Display current datetime','gazeta'),
				'default'  		=> '1'
			);
			$header_field[] = 	array(
				'id'			=>	'header_current_time_format',
				'type'			=>	'text',
				'title'			=>	__('Datetime Format','gazeta'),
				'subtitle'		=>	__('Formatting Date and Time','gazeta'),
				'description'	=>	sprintf( __('Check <strong>%s</strong> for more info.','gazeta'), '<a href="http://codex.wordpress.org/Formatting_Date_and_Time">'.__('HERE','gazeta').'</a>' ),
				'default'  		=> 'l, F j, Y'
			);
			$header_field[] = 	array(
				'id'			=>	'header_weather',
				'type'			=>	'checkbox',
				'title'			=>	__('Weather','gazeta'),
				'subtitle'		=>	__('Display the Weather Informations','gazeta'),
				'default'  		=> '0'
			);
			$header_field[] = 	array(
				'id'			=>	'header_weather_apikey',
				'type'			=>	'text',
				'title'			=>	__('Wunderground API Key','gazeta'),
				'subtitle'		=>	__('A paid service of Wunderground.com','gazeta'),
				'description'	=>	sprintf( __('This Key can be found at %s, You would need to signup and register a key, <i>This featured may not work properly for some locations.</i>','gazeta'), '<a href="http://www.wunderground.com/weather/api/">'.__('HERE','gazeta').'</a>' ),
				'required'  => array('header_weather', "=", '1'),
			);
			$header_field[] = 	array(
				'id'			=>	'header_weather_location',
				'type'			=>	'text',
				'title'			=>	__('Location','gazeta'),
				'subtitle'		=>	__('Getting this location\'s weather information','gazeta'),
				'description'	=>	sprintf( __('Or leave blank for getting visitor\'s location, the location is based on the IP address, here is %s','gazeta') ,'<a target="_blank" href="http://ipinfo.io/">'.__('yours','gazeta').'</a>'),
				'required'  => array('header_weather', "=", '1'),
			);
			$header_field[] = 	array(
				'id'			=>	'header_weather_expiration',
				'type'			=>	'text',
				'title'			=>	__('Transient Expiration','gazeta'),
				'subtitle' => __( 'Set/update the expiration value of the transients.', 'gazeta' ),
				'default'	=>	1200,
				'required'  => array('header_weather', "=", '1'),
			);
			$socials_field = function_exists( 'gazeta_user_contactmethods' ) ? gazeta_user_contactmethods( array() ): '';
			if( is_array( $socials_field ) ){
				foreach ( $socials_field  as $key=>$value) {
					$header_field[]	=	array(
						'id'			=>	'header_social_' . $key,
						'type'			=>	'text',
						'title'			=>	$value,
						'subtitle'		=>	sprintf( __('%s Profile Url','gazeta'), $value )
					);
				}
			}			
			// Header.
			$this->sections[] 	=	array(
				'title'	=>	__('Header','gazeta'),
				'icon'	=>	'el-icon-wrench',
				'desc'	=>	null,
				'fields'	=>	apply_filters( 'gazeta_theme_options/header_field_args' , $header_field)
			);	
			// Footer.
			$this->sections[] 	=	array(
				'title'	=>	__('Footer','gazeta'),
				'icon'	=>	'el-icon-wrench',
				'desc'	=>	null,
				'fields'	=>	array(
					array(
						'id'       => 'footer-columns',
						'type'     => 'text',
						'title'    => __( 'Footer\'s Columns', 'gazeta' ),
						'subtitle' => __('Setting the Footer\'s widget columns.','gazeta'),	
						'validate' => 'numeric',
						'default'  => '4',
					),
					array(
						'id'       => 'credits',
						'type'     => 'textarea',
						'title'    => __( 'Footer Text', 'gazeta' ),
						'subtitle' => __( 'HTML Allowed', 'gazeta' ),
						'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
						'default'  => sprintf( __('Copyright &copy; 2014 %s','gazeta'), get_bloginfo('name') )
					),
				)
			);			
        }
        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'gazeta_global_data', // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get('Name'), // Name that appears at the top of your panel
                'display_version' => $theme->get('Version'), // Version that appears at the top of your panel
                'menu_type' => 'submenu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true, // Show the sections below the admin menu item or not
                'menu_title' => __('Theme Options', 'gazeta'),
                'page' => __('Theme Options', 'gazeta'),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                'async_typography'  => true,
                //'admin_bar' => false, // Show the panel pages on the admin bar
                'global_variable' => '', // Set a different name for your global variable other than the opt_name
                'dev_mode' => false, // Show the time the page took to load, etc
                'customizer' => true, // Enable basic customizer support
                // OPTIONAL -> Give you extra features
                'page_priority' => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
                'menu_icon' => '', // Specify a custom URL to an icon
                'last_tab' => '', // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '_options', // Page slug used to denote the panel
                'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
                'default_show' => false, // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '', // What to print by the field's title if the value shown is default. Suggested: *
                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                //'domain'             	=> 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
                //'footer_credit'      	=> '', // Disable the footer credit of Redux. Please leave if you can help it.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'show_import_export' => true, // REMOVE
                'system_info' => false, // REMOVE
                'help_tabs' => array(),
                'help_sidebar' => '', // __( '', $this->args['domain'] );            
            );
            $this->args['share_icons'][] = array(
                'url' => 'https://twitter.com/marstheme',
                'title' => __('Follow us on Twitter','gazeta'),
                'icon' => 'el-icon-twitter'
            );
            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace("-", "_", $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'gazeta'), $v);
            } else {
                $this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'gazeta');
            }

            // Add content after the form.
            //$this->args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'gazeta');
        }

    }

    new Gazeta_Theme_Options();
}