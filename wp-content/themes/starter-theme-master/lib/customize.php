<?php

/**
*
* $acid_location is where the Acid folder is located in your theme
* In this scenaario, the Acid folder is located in wp-contents/themes/YOUR_THEME/inc
* 
* It is important to specify the location properly so that Acid knows the location of the assets it needs to look and function correctly
* 
*/
require_once( get_stylesheet_directory() . '/inc/Acid/acid.php');
$acid_location = get_stylesheet_directory_uri() . '/inc/'; 
$acid = acid_instance( $acid_location );

/**
*
* Create your theme options as PHP arrays
* WordPress Customizer's structure allows you to create Options that are nested in Sections, which are in turn nested in Panels
* 
* Acid uses the same structure, allowing you to nest options easily, without needing to reference the section or panel ID
*
*/
$data = array (

	'panels'    => array(
        
		NULL      => array(
            
			'sections'       => array(
				
				'home_page_image_section'     => array(
		    
					'title'         => __( 'Custom Images', CHILD_THEME_NAME ),
					'description'   => __( 'Custom Images for the Homepage', CHILD_THEME_NAME ),
					'priority'		=> 1,
		    
					'options'       => array(

						'home-widget-1-image' => array(
							'label'         => __( 'Home Widget 1 Section Image:', CHILD_THEME_NAME ),
							'type'          => 'image',
						),

						'home-widget-4-image' => array(
							'label'         => __( 'Home Widget 4 Section Image:', CHILD_THEME_NAME ),
							'type'          => 'image',
						),

						'blog-hero-image' => array(
							'label'         => __( 'Blog Hero Image:', CHILD_THEME_NAME ),
							'type'          => 'image',
						),
					),
				),
			),
		),
	),
);


$acid->config( $data );


/*

*** HERE IS AN EXAMPLE OF THE FIELDS AVAILABLE AND HOW TO USE IT ***


$data = array (
    
    'panels'    => array(
        
        NULL      => array(
            
            'sections'       => array(
                
                'custom_section'     => array(
                    
                    'title'         => __( 'first sample section', 'theme-slug' ),
                    'description'   => __( 'desc in first sample', 'theme-slug' ),
                    
                    'options'       => array(
                        
                        'toggle-sample'     => array(
                            
                            'label'     => __( 'Toggle on or off', 'theme-slug' ),
                            'type'      => 'toggle',
                            'default'   => false
                            
                        ),

                        'image-sample'      => array(
                            
                            'label'     => __( 'select an image', 'theme-slug' ),
                            'type'      => 'radio-image',
                            'choices'   => array(
                                array(
                                    'label'    => __( 'guy running', 'theme-slug' ),
                                    'url'       => AcidConfig::assets_url() . 'images/smartcat-icon.jpg',
                                ),
                                array(
                                    'label'    => __( 'couple', 'theme-slug' ),
                                    'url'       => AcidConfig::assets_url() . 'images/smartcat-icon.jpg',
                                ),
                                array(
                                    'label'    => __( 'guy running', 'theme-slug' ),
                                    'url'       => AcidConfig::assets_url() . 'images/smartcat-icon.jpg',
                                ),
                            ),
                            
                        ),
                        
                        'range-sample'      => array(
                            
                            'label'     => __( 'Opacity %' , 'theme-slug' ),
                            'type'      => 'range',
                            'default'   => 20,
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1
                            
                        ),
                        
                        'sortable-sample'   => array(
                            'label'     => __( 'Sortable links', 'theme-slug' ),
                            'type'      => 'sortable',
                        ),        
                        
                    ),
                ),   
            ),  
        ),
        
        'another-panel'     => array(
            
            'title'         => __( 'Another Panel', 'theme-slug' ),
            'description'   => __( 'This is another panel', 'theme-slug' ),

            'sections'          => array(

                'demo-section'      => array(
                    
                    'title'         => __( 'Section title', 'theme-slug' ),
                    'description'   => __( 'This is another section demo', 'theme-slug' ),

                    'options'       => array(
                        
                        
                        'demo-decimal1'       => array(

                            'label'         => __( 'Enter a Decimal', 'theme-slug' ),
                            'description'   => __( 'Decimal', 'theme-slug' ),
                            'type'          => 'range',
                            'default'       => 10,
                            'min'           => 0,
                            'max'           => 100,
                            'step'          => 5

                        ),
                        

                        'demo-color'        => array(
                            'label'         => __( 'Pick a color', 'theme-slug' ),
                            'description'   => __( 'Colorpicker option', 'theme-slug' ),
                            'type'          => 'color',
                            'default'       => __( '#cc0000', 'theme-slug' )

                        ),

                        'demo-text'         => array(
                            'label'         => __( 'Enter your title', 'theme-slug' ),
                            'description'   => __( 'Create any text, HTML is not allowed', 'theme-slug' ),
                            'type'          => 'text',
                            'default'       => __( 'Created with Acid Framework', 'theme-slug' )

                        ),

                        'demo-image'        => array(
                            'label'         => __( 'Upload an image', 'theme-slug' ),
                            'description'   => __( 'Allow users to select an image or upload a new one', 'theme-slug' ),
                            'type'          => 'image',

                        ),
                        
                        'image-sample'      => array(
                            
                            'label'     => __( 'select an image', 'theme-slug' ),
                            'type'      => 'radio-image',
                            'choices'   => array(
                                array(
                                    'label'    => __( 'guy running', 'theme-slug' ),
                                    'url'       => AcidConfig::assets_url() . 'images/smartcat-icon.jpg',
                                ),
                                array(
                                    'label'    => __( 'couple', 'theme-slug' ),
                                    'url'       => AcidConfig::assets_url() . 'images/smartcat-icon.jpg',
                                ),
                                array(
                                    'label'    => __( 'guy running', 'theme-slug' ),
                                    'url'       => AcidConfig::assets_url() . 'images/smartcat-icon.jpg',
                                ),
                            ),
                            
                        ),

                        'demo-url'          => array(

                            'label'         => __( 'Enter a URL', 'theme-slug' ),
                            'description'   => __( 'Descriptions are optional', 'theme-slug' ),
                            'type'          => 'url',
                            'default'       => 'https://acidframework.com'

                        ),

                        'demo-number'       => array(

                            'label'         => __( 'Enter a Number', 'theme-slug' ),
                            'description'   => __( 'Numeric value only', 'theme-slug' ),
                            'type'          => 'number',
                            'default'       => 10,
                            'min'           => 0,
                            'max'           => 100,
                            'step'          => 5

                        ),

                        'demo-range'        => array(

                            'label'         => __( 'Enter a Number', 'theme-slug' ),
                            'description'   => __( 'Numeric value only', 'theme-slug' ),
                            'type'          => 'range',
                            'default'       => 10,
                            'min'           => 0,
                            'max'           => 100,
                            'step'          => 5

                        ),
                        
                        'demo-textarea'         => array(
                            'label'         => __( 'Enter text', 'theme-slug' ),
                            'description'   => __( 'Create any text, HTML is not allowed', 'theme-slug' ),
                            'type'          => 'textarea',
                            'default'       => __( 'Created with Acid Framework', 'theme-slug' )

                        ),
                        
                        'demo-date'         => array(
                            'label'         => __( 'Enter a date', 'theme-slug' ),
                            'type'          => 'date',
                            'default'       => '2018-03-16'

                        ),
                        
                        'demo-checkbox'         => array(
                            'label'         => __( 'Yes or no ?', 'theme-slug' ),
                            'description'   => __( 'Use this control for options that are togglelable', 'theme-slug' ),
                            'type'          => 'checkbox',
                            'default'       => false

                        ),
                        
                        'demo-toggle'       => array(
                            'label'         => __( 'On or Off ?', 'theme-slug' ),
                            'description'   => __( 'Same as a checkbox, but looks more cool', 'theme-slug' ),
                            'type'          => 'toggle',
                            'default'       => true

                        ),
                        
                        'demo-radio-toggle'       => array(
                            'label'         => __( 'Which option do you want?', 'theme-slug' ),
                            'description'   => __( 'Radio toggle between various things', 'theme-slug' ),
                            'type'          => 'radio-toggle',
                            'default'       => true,
                            'choices'       => array(
                                'one'       => __( 'One', 'theme-slug' ),
                                'two'       => __( 'Two', 'theme-slug' ),
                                'three'       => __( 'Three', 'theme-slug' ),
                            ),

                        ),
                        
                        'demo-color-picker' => array(
                            
                            'label'         => __( 'Select a color', 'theme-slug' ),
                            'description'   => __( 'Which color do you want?', 'theme-slug' ),
                            'type'          => 'color-select',
                            'default'       => true,
                            'choices'       => array(
                                '#06AED5'    => __( 'Blue 1', 'theme-slug' ),
                                '#086788'    => __( 'Blue 2', 'theme-slug' ),
                                '#38618C'    => __( 'Blue 3', 'theme-slug' ),
                                '#FFBF29'     => __( 'Yellow 1', 'theme-slug' ),
                                '#FFF1D0'     => __( 'Baige 1', 'theme-slug' ),
                                '#DD1C1A'     => __( 'Red 1', 'theme-slug' ),
                                '#FF5964'     => __( 'Red 2', 'theme-slug' ),
                            ),                            
                            
                        ),
                        
                        'demo-html'         => array(
                            'label'         => __( 'Enter HTML Content', 'theme-slug' ),
                            'type'          => 'html',
                            'default'       => __( 'Enter any content here', 'theme-slug' ),
                            
                        ),
                        
                        'demo-radio'        => array(
                            'label'         => __( 'Select one of many', 'theme-slug' ),
                            'description'   => __( 'Limits user to one selection', 'theme-slug' ),
                            'type'          => 'radio',
                            'default'       => 'red',
                            'choices'       => array(
                                'red'       => __( 'Red', 'theme-slug' ),
                                'blue'      => __( 'Blue', 'theme-slug' ),
                                'green'     => __( 'Green', 'theme-slug' ),
                                'purple'    => __( 'purple', 'theme-slug' ),
                            ),

                        ),
                        
                        'demo-select'       => array(
                            'label'         => __( 'Select from dropdown', 'theme-slug' ),
                            'type'          => 'select',
                            'default'       => 'purple',
                            'choices'       => array(
                                'red'       => __( 'Red', 'theme-slug' ),
                                'blue'      => __( 'Blue', 'theme-slug' ),
                                'green'     => __( 'Green', 'theme-slug' ),
                                'purple'    => __( 'purple', 'theme-slug' ),
                            ),

                        ),
                        
                        'demo-pages'        => array(
                            'label'         => __( 'Select a page', 'theme-slug' ),
                            'type'          => 'dropdown-pages',

                        ),
                        
                        'demo-email'        => array(
                            'label'         => __( 'Enter email address', 'theme-slug' ),
                            'type'          => 'email',
                            'default'       => get_option( 'admin_email' ),

                        ),

                    ),                      
                ),
            ),
        ),
    ),
);
*/