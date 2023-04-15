<?php


if (!class_exists( 'RWMB_Loader' )) {
	return;
}

class LittleDino_Metaboxes
{
	public function __construct()
	{
		// Team
		add_filter( 'rwmb_meta_boxes', array( $this, 'team_meta_boxes' ) );

		// Portfolio
		add_filter( 'rwmb_meta_boxes', array( $this, 'portfolio_meta_boxes' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'portfolio_post_settings_meta_boxes' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'portfolio_related_meta_boxes' ) );

		// Blog
		add_filter( 'rwmb_meta_boxes', array( $this, 'blog_settings_meta_boxes' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'blog_meta_boxes' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'blog_related_meta_boxes' ));

		// Page
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_layout_meta_boxes' ) );
		// Colors
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_color_meta_boxes' ) );
		// Logo
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_logo_meta_boxes' ) );
		// Header Builder
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_header_meta_boxes' ) );
		// Title
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_title_meta_boxes' ) );
		// Side Panel
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_side_panel_meta_boxes' ) );

		// Social Shares
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_soc_icons_meta_boxes' ) );
		// Footer
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_footer_meta_boxes' ) );
		// Copyright
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_copyright_meta_boxes' ) );

		// Shop Single
		add_filter( 'rwmb_meta_boxes', array( $this, 'shop_catalog_meta_boxes' ) );
	}

	public function team_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Team Options', 'littledino'),
	        'post_types' => array( 'team' ),
	        'context' => 'advanced',
	        'fields' => array(
	        	array(
		            'name' => esc_html__('Member Department', 'littledino'),
		            'id' => 'department',
		            'type' => 'text',
		            'class' => 'field-inputs'
				),
				array(
		            'name' => esc_html__('Member Since', 'littledino'),
		            'id' => 'department_since',
		            'type' => 'text',
		            'class' => 'field-inputs'
				),
				array(
					'name' => esc_html__('Member Info', 'littledino'),
		            'id' => 'info_items',
		            'type' => 'social',
		            'clone' => true,
		            'sort_clone' => true,
		            'options' => array(
						'name' => array(
							'name' => esc_html__('Name', 'littledino'),
							'type_input' => 'text'
						),
						'description' => array(
							'name' => esc_html__('Description', 'littledino'),
							'type_input' => 'text'
						),
						'link' => array(
							'name' => esc_html__('Link', 'littledino'),
							'type_input' => 'text'
						),
					),
		        ),
		        array(
					'name' => esc_html__('Social Icons', 'littledino'),
					'id' => 'soc_icon',
					'type' => 'select_icon',
					'options' => WglAdminIcon()->get_icons_name(),
					'clone' => true,
					'sort_clone' => true,
					'placeholder' => esc_attr__( 'Select an icon', 'littledino'),
					'multiple' => false,
					'std' => 'default',
				),
		        array(
					'name' => esc_html__('Info Background Image', 'littledino'),
					'id' => "mb_info_bg",
					'type' => 'file_advanced',
					'max_file_uploads' => 1,
					'mime_type' => 'image',
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function portfolio_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Portfolio Options', 'littledino'),
	        'post_types' => array( 'portfolio' ),
	        'context' => 'advanced',
	        'fields' => array(
	        	array(
					'id' => 'mb_portfolio_featured_img',
					'name' => esc_html__('Show Featured image on single', 'littledino'),
					'type' => 'switch',
					'std' => 'true',
				),
				array(
					'id' => 'mb_portfolio_title',
					'name' => esc_html__('Show Title on single', 'littledino'),
					'type' => 'switch',
					'std' => 'true',
				),
				array(
					'id' => 'mb_portfolio_link',
					'name' => esc_html__('Add Custom Link for Portfolio Grid', 'littledino'),
					'type' => 'switch',
				),
				array(
                    'name' => esc_html__('Custom Url for Portfolio Grid', 'littledino'),
                    'id' => 'portfolio_custom_url',
                    'type' => 'text',
					'class' => 'field-inputs',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_portfolio_link', '=', '1')
						), ),
					),
                ),
                array(
                    'id' => 'portfolio_custom_url_target',
                    'name' => esc_html__('Open Custom Url in New Window', 'littledino'),
                    'type' => 'switch',
                    'std' => 'true',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_portfolio_link', '=', '1')
						), ),
					),
                ),
				array(
					'name' => esc_html__('Info', 'littledino'),
					'id' => 'mb_portfolio_info_items',
					'type' => 'social',
					'clone' => true,
					'sort_clone' => true,
					'desc' => esc_html__('Description', 'littledino'),
					'options' => array(
						'name' => array(
							'name' => esc_html__('Name', 'littledino'),
							'type_input' => 'text'
							),
						'description' => array(
							'name' => esc_html__('Description', 'littledino'),
							'type_input' => 'text'
							),
						'link' => array(
							'name' => esc_html__('Url', 'littledino'),
							'type_input' => 'text'
							),
					),
		        ),
		        array(
					'name'     => esc_html__( 'Info Description', 'littledino' ),
					'id'       => "mb_portfolio_editor",
					'type'     => 'textarea',
					'multiple' => false,
					'desc' => esc_html__('Info description is shown in one row with a main info', 'littledino'),
				),
		        array(
					'name' => esc_html__('Categories On/Off', 'littledino'),
					'id' => "mb_portfolio_single_meta_categories",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'yes' => esc_html__('On', 'littledino'),
						'no' => esc_html__('Off', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
		        array(
					'name' => esc_html__('Date On/Off', 'littledino'),
					'id' => "mb_portfolio_single_meta_date",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'yes' => esc_html__('On', 'littledino'),
						'no' => esc_html__('Off', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
		        array(
					'name' => esc_html__('Tags On/Off', 'littledino'),
					'id' => "mb_portfolio_above_content_cats",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'yes' => esc_html__('On', 'littledino'),
						'no' => esc_html__('Off', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
		        array(
					'name' => esc_html__('Share Links On/Off', 'littledino'),
					'id' => "mb_portfolio_above_content_share",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'yes' => esc_html__('On', 'littledino'),
						'no' => esc_html__('Off', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function portfolio_post_settings_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Portfolio Post Settings', 'littledino'),
	        'post_types' => array( 'portfolio' ),
	        'context' => 'advanced',
	        'fields' => array(
				array(
					'name' => esc_html__('Post Layout', 'littledino'),
					'id' => "mb_portfolio_post_conditional",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'custom' => esc_html__('Custom', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Post Layout Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_portfolio_post_conditional', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Post Content Layout', 'littledino'),
					'id' => "mb_portfolio_single_type_layout",
					'type' => 'button_group',
					'options' => array(
						'1' => esc_html__('Title First', 'littledino'),
						'2' => esc_html__('Image First', 'littledino'),
						'3' => esc_html__('Overlay Image', 'littledino'),
						'4' => esc_html__('Overlay Image with Info', 'littledino'),
					),
					'multiple' => false,
					'std' => '1',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_portfolio_post_conditional', '=', 'custom')
						), ),
					),
				),
				array(
					'name' => esc_html__('Alignment', 'littledino'),
					'id' => "mb_portfolio_single_align",
					'type' => 'button_group',
					'options' => array(
						'left' => esc_html__('Left', 'littledino'),
						'center' => esc_html__('Center', 'littledino'),
						'right' => esc_html__('Right', 'littledino'),
					),
					'multiple' => false,
					'std' => 'left',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_portfolio_post_conditional', '=', 'custom')
						), ),
					),
				),
				array(
					'name' => esc_html__('Spacing', 'littledino'),
					'id' => 'mb_portfolio_single_padding',
					'type' => 'wgl_offset',
					'options' => array(
						'mode' => 'padding',
						'top' => true,
						'right' => false,
						'bottom' => true,
						'left' => false,
					),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_portfolio_post_conditional', '=', 'custom'),
							array('mb_portfolio_single_type_layout', '!=', '1'),
							array('mb_portfolio_single_type_layout', '!=', '2'),
						)),
					),
					'std' => array(
						'padding-top' => '165',
						'padding-bottom' => '165'
					)
				),
				array(
					'id' => 'mb_portfolio_parallax',
					'name' => esc_html__('Add Portfolio Parallax', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_portfolio_post_conditional', '=', 'custom'),
							array('mb_portfolio_single_type_layout', '!=', '1'),
							array('mb_portfolio_single_type_layout', '!=', '2'),
						)),
					),
				),
				array(
					'name' => esc_html__('Prallax Speed', 'littledino'),
					'id' => "mb_portfolio_parallax_speed",
					'type' => 'number',
					'std' => 0.3,
					'step' => 0.1,
					'attributes' => array(
						'data-conditional-logic' =>  array( array(
							array('mb_portfolio_post_conditional', '=', 'custom'),
							array('mb_portfolio_single_type_layout', '!=', '1'),
							array('mb_portfolio_single_type_layout', '!=', '2'),
							array('mb_portfolio_parallax', '=',true),
						)),
					),
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function portfolio_related_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Related Portfolio', 'littledino'),
	        'post_types' => array( 'portfolio' ),
	        'context' => 'advanced',
	        'fields' => array(
				array(
					'id' => 'mb_portfolio_related_switch',
					'name' => esc_html__('Portfolio Related', 'littledino'),
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'on' => esc_html__('On', 'littledino'),
						'off' => esc_html__('Off', 'littledino'),
					),
					'inline' => true,
					'multiple' => false,
					'std' => 'default'
				),
				array(
					'name' => esc_html__('Portfolio Related Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_portfolio_related_switch', '=', 'on')
						)),
					),
				),
	        	array(
					'id' => 'mb_pf_carousel_r',
					'name' => esc_html__('Display items carousel for this portfolio post', 'littledino'),
					'type' => 'switch',
					'std' => 1,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_portfolio_related_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Title', 'littledino'),
					'id' => "mb_pf_title_r",
					'type' => 'text',
					'std' => esc_html__('Related Portfolio', 'littledino'),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_portfolio_related_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Categories', 'littledino'),
					'id' => "mb_pf_cat_r",
					'multiple' => true,
					'type' => 'taxonomy_advanced',
					'taxonomy' => 'portfolio-category',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_portfolio_related_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Columns', 'littledino'),
					'id' => "mb_pf_column_r",
					'type' => 'button_group',
					'options' => array(
						'2' => esc_html__('2', 'littledino'),
						'3' => esc_html__('3', 'littledino'),
						'4' => esc_html__('4', 'littledino'),
					),
					'multiple' => false,
					'std' => '3',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_portfolio_related_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Number of Related Items', 'littledino'),
					'id' => "mb_pf_number_r",
					'type' => 'number',
					'min' => 0,
					'step' => 1,
					'std' => 3,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_portfolio_related_switch', '=', 'on')
						)),
					),
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function blog_settings_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Post Settings', 'littledino'),
	        'post_types' => array( 'post' ),
	        'context' => 'advanced',
	        'fields' => array(
				array(
					'name' => esc_html__('Post Layout Settings', 'littledino'),
					'type' => 'wgl_heading',
				),
				array(
					'name' => esc_html__('Post Layout', 'littledino'),
					'id' => "mb_post_layout_conditional",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'custom' => esc_html__('Custom', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Post Layout Type', 'littledino'),
					'id' => "mb_single_type_layout",
					'type' => 'button_group',
					'options' => array(
						'1' => esc_html__('Title First', 'littledino'),
						'2' => esc_html__('Image First', 'littledino'),
						'3' => esc_html__('Overlay Image', 'littledino'),
					),
					'multiple' => false,
					'std' => '1',
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_post_layout_conditional', '=', 'custom')
							),
						),
					),
				),
				array(
					'name' => esc_html__('Spacing', 'littledino'),
					'id' => 'mb_single_padding_layout_3',
					'type' => 'wgl_offset',
					'options' => array(
						'mode' => 'padding',
						'top' => true,
						'right' => false,
						'bottom' => true,
						'left' => false,
					),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_post_layout_conditional', '=', 'custom'),
							array('mb_single_type_layout', '=', '3'),
						)),
					),
					'std' => array(
						'padding-top' => '66',
						'padding-bottom' => '45'
					)
				),
				array(
					'id' => 'mb_single_apply_animation',
					'name' => esc_html__('Apply Animation', 'littledino'),
					'type' => 'switch',
					'std' => 1,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_post_layout_conditional', '=', 'custom'),
							array('mb_single_type_layout', '=', '3'),
						)),
					),
				),
				array(
					'name' => esc_html__('Featured Image Settings', 'littledino'),
					'type' => 'wgl_heading',
				),
				array(
					'name' => esc_html__('Featured Image', 'littledino'),
					'id' => "mb_featured_image_conditional",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'custom' => esc_html__('Custom', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Featured Image Settings', 'littledino'),
					'id' => "mb_featured_image_type",
					'type' => 'button_group',
					'options' => array(
						'off' => esc_html__('Off', 'littledino'),
						'replace' => esc_html__('Replace', 'littledino'),
					),
					'multiple' => false,
					'std' => 'off',
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_featured_image_conditional', '=', 'custom')
							),
						),
					),
				),
				array(
					'name' => esc_html__('Featured Image Replace', 'littledino'),
					'id' => "mb_featured_image_replace",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_featured_image_conditional', '=', 'custom'),
							array( 'mb_featured_image_type', '=', 'replace' ),
						)),
					),
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function blog_meta_boxes( $meta_boxes ) {
		$meta_boxes[] = array(
			'title' => esc_html__('Post Format Layout', 'littledino'),
			'post_types' => array( 'post' ),
			'context' => 'advanced',
			'fields' => array(
				// Standard Post Format
				array(
					'name' => esc_html__('Standard Post( Enabled only Featured Image for this post format)', 'littledino'),
					'id' => "post_format_standard",
					'type' => 'static-text',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('formatdiv', '=', '0')
						), ),
					),
				),
				// Gallery Post Format
				array(
					'name' => esc_html__('Gallery Settings', 'littledino'),
					'type' => 'wgl_heading',
				),
				array(
					'name' => esc_html__('Add Images', 'littledino'),
					'id' => "post_format_gallery",
					'type' => 'image_advanced',
					'max_file_uploads' => '',
				),
				// Video Post Format
				array(
					'name' => esc_html__('Video Settings', 'littledino'),
					'type' => 'wgl_heading',
				),
				array(
					'name' => esc_html__('Video Style', 'littledino'),
					'id' => "post_format_video_style",
					'type' => 'select',
					'options' => array(
						'bg_video' => esc_html__('Background Video', 'littledino'),
						'popup' => esc_html__('Popup', 'littledino'),
					),
					'multiple' => false,
					'std' => 'bg_video',
				),
				array(
					'name' => esc_html__('Start Video', 'littledino'),
					'id' => "start_video",
					'type' => 'number',
					'std' => '0',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('post_format_video_style', '=', 'bg_video'),
						), ),
					),
				),
				array(
					'name' => esc_html__('End Video', 'littledino'),
					'id' => "end_video",
					'type' => 'number',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('post_format_video_style', '=', 'bg_video'),
						), ),
					),
				),
				array(
					'name' => esc_html__('oEmbed URL', 'littledino'),
					'id' => "post_format_video_url",
					'type' => 'oembed',
				),
				// Quote Post Format
				array(
					'name' => esc_html__('Quote Settings', 'littledino'),
					'type' => 'wgl_heading',
				),
				array(
					'name' => esc_html__('Quote Text', 'littledino'),
					'id' => "post_format_qoute_text",
					'type' => 'textarea',
				),
				array(
					'name' => esc_html__('Author Name', 'littledino'),
					'id' => "post_format_qoute_name",
					'type' => 'text',
				),
				array(
					'name' => esc_html__('Author Position', 'littledino'),
					'id' => "post_format_qoute_position",
					'type' => 'text',
				),
				array(
					'name' => esc_html__('Author Avatar', 'littledino'),
					'id' => "post_format_qoute_avatar",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
				),
				// Audio Post Format
				array(
					'name' => esc_html__('Audio Settings', 'littledino'),
					'type' => 'wgl_heading',
				),
				array(
					'name' => esc_html__('oEmbed URL', 'littledino'),
					'id' => "post_format_audio_url",
					'type' => 'oembed',
				),
				// Link Post Format
				array(
					'name' => esc_html__('Link Settings', 'littledino'),
					'type' => 'wgl_heading',
				),
				array(
					'name' => esc_html__('URL', 'littledino'),
					'id' => "post_format_link_url",
					'type' => 'url',
				),
				array(
					'name' => esc_html__('Text', 'littledino'),
					'id' => "post_format_link_text",
					'type' => 'text',
				),
			)
		);
		return $meta_boxes;
	}

	public function blog_related_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Related Blog Post', 'littledino'),
	        'post_types' => array( 'post' ),
	        'context' => 'advanced',
	        'fields' => array(

	        	array(
					'name' => esc_html__('Related Options', 'littledino'),
					'id' => "mb_blog_show_r",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'custom' => esc_html__('Custom', 'littledino'),
						'off'  	  => esc_html__('Off', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Related Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_blog_show_r', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Title', 'littledino'),
					'id' => "mb_blog_title_r",
					'type' => 'text',
					'std' => esc_html__('Related Posts', 'littledino'),
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_blog_show_r', '=', 'custom')
						), ),
					),
				),
				array(
					'name' => esc_html__('Categories', 'littledino'),
					'id' => "mb_blog_cat_r",
					'multiple' => true,
					'type' => 'taxonomy_advanced',
					'taxonomy' => 'category',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_blog_show_r', '=', 'custom')
						), ),
					),
				),
				array(
					'name' => esc_html__('Columns', 'littledino'),
					'id' => "mb_blog_column_r",
					'type' => 'button_group',
					'options' => array(
						'12' => esc_html__('1', 'littledino'),
						'6' => esc_html__('2', 'littledino'),
						'4' => esc_html__('3', 'littledino'),
						'3' => esc_html__('4', 'littledino'),
					),
					'multiple' => false,
					'std' => '6',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_blog_show_r', '=', 'custom')
						), ),
					),
				),
				array(
					'name' => esc_html__('Number of Related Items', 'littledino'),
					'id' => "mb_blog_number_r",
					'type' => 'number',
					'min' => 0,
					'step' => 1,
					'std' => 2,
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_blog_show_r', '=', 'custom')
							),
						),
					),
				),
	        	array(
					'id' => 'mb_blog_carousel_r',
					'name' => esc_html__('Display items carousel for this blog post', 'littledino'),
					'type' => 'switch',
					'std' => 1,
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_blog_show_r', '=', 'custom')
							),
						),
					),
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function page_layout_meta_boxes( $meta_boxes ) {

	    $meta_boxes[] = array(
	        'title' => esc_html__('Page Layout', 'littledino'),
	        'post_types' => array( 'page' , 'post', 'team', 'practice', 'portfolio', 'product' ),
	        'context' => 'advanced',
	        'fields' => array(
				array(
					'name' => esc_html__('Page Sidebar Layout', 'littledino'),
					'id' => "mb_page_sidebar_layout",
					'type' => 'wgl_image_select',
					'options' => array(
						'default' => get_template_directory_uri() . '/core/admin/img/options/1c.png',
						'none' => get_template_directory_uri() . '/core/admin/img/options/none.png',
						'left' => get_template_directory_uri() . '/core/admin/img/options/2cl.png',
						'right' => get_template_directory_uri() . '/core/admin/img/options/2cr.png',
					),
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Sidebar Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_sidebar_layout', '!=', 'default'),
							array('mb_page_sidebar_layout', '!=', 'none'),
						)),
					),
				),
				array(
					'name' => esc_html__('Page Sidebar', 'littledino'),
					'id' => "mb_page_sidebar_def",
					'type' => 'select',
					'placeholder' => esc_attr__( 'Select a Sidebar', 'littledino'),
					'options' => littledino_get_all_sidebar(),
					'multiple' => false,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_sidebar_layout', '!=', 'default'),
							array('mb_page_sidebar_layout', '!=', 'none'),
						)),
					),
				),
				array(
					'name' => esc_html__('Page Sidebar Width', 'littledino'),
					'id' => "mb_page_sidebar_def_width",
					'type' => 'button_group',
					'options' => array(
						'9' => esc_html( '25%' ),
						'8' => esc_html( '33%' ),
					),
					'std' => '9',
					'multiple' => false,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_sidebar_layout', '!=', 'default'),
							array('mb_page_sidebar_layout', '!=', 'none'),
						)),
					),
				),
				array(
					'id' => 'mb_sticky_sidebar',
					'name' => esc_html__('Sticky Sidebar On?', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_sidebar_layout', '!=', 'default'),
							array('mb_page_sidebar_layout', '!=', 'none'),
						)),
					),
				),
				array(
					'name' => esc_html__('Sidebar Side Gap', 'littledino'),
					'id' => "mb_sidebar_gap",
					'type' => 'select',
					'options' => array(
						'def' => 'Default',
	                    '0' => '0',
	                    '15' => '15',
	                    '20' => '20',
	                    '25' => '25',
	                    '30' => '30',
	                    '35' => '35',
	                    '40' => '40',
	                    '45' => '45',
	                    '50' => '50',
					),
					'std' => 'def',
					'multiple' => false,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_sidebar_layout', '!=', 'default'),
							array('mb_page_sidebar_layout', '!=', 'none'),
						)),
					),
				),
	        )
	    );
	    return $meta_boxes;
	}

	public function page_color_meta_boxes( $meta_boxes ) {

	    $meta_boxes[] = array(
	        'title' => esc_html__('Page Colors', 'littledino'),
	        'post_types' => array( 'page' , 'post', 'team', 'practice', 'portfolio' ),
	        'context' => 'advanced',
	        'fields' => array(
	        	array(
					'name' => esc_html__('Page Colors', 'littledino'),
					'id' => "mb_page_colors_switch",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'custom' => esc_html__('Custom', 'littledino'),
					),
					'inline' => true,
					'multiple' => false,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Colors Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_colors_switch', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('General Theme Color', 'littledino'),
	                'id' => 'mb_page_theme_color',
	                'type' => 'color',
	                'std' => '#fa9db7',
					'js_options' => array( 'defaultColor' => '#fa9db7' ),
	                'validate' => 'color',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
				),
				array(
					'name' => esc_html__('Secondary Color', 'littledino'),
	                'id' => 'mb_page_theme_secondary_color',
	                'type' => 'color',
	                'std' => '#ffc85b',
					'js_options' => array( 'defaultColor' => '#ffc85b' ),
	                'validate' => 'color',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
				),
				array(
					'name' => esc_html__('Third Color', 'littledino'),
	                'id' => 'mb_page_theme_third_color',
	                'type' => 'color',
	                'std' => '#45b3df',
					'js_options' => array( 'defaultColor' => '#45b3df' ),
	                'validate' => 'color',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
				),
				array(
					'name' => esc_html__('Body Background Color', 'littledino'),
	                'id' => 'mb_body_background_color',
	                'type' => 'color',
	                'std' => '#ffffff',
					'js_options' => array( 'defaultColor' => '#ffffff' ),
	                'validate' => 'color',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
	            ),
				array(
					'name' => esc_html__('Scroll Up Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_colors_switch', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Button Background Color', 'littledino'),
	                'id' => 'mb_scroll_up_bg_color',
	                'type' => 'color',
	                'std' => '#ff9e21',
					'js_options' => array( 'defaultColor' => '#ff9e21' ),
	                'validate' => 'color',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
	            ),
	            array(
					'name' => esc_html__('Button Arrow Color', 'littledino'),
	                'id' => 'mb_scroll_up_arrow_color',
	                'type' => 'color',
	                'std' => '#ffffff',
					'js_options' => array( 'defaultColor' => '#ffffff' ),
	                'validate' => 'color',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
	            ),
	        )
	    );
	    return $meta_boxes;
	}

	public function page_logo_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Logo', 'littledino'),
	        'post_types' => array( 'page', 'post' ),
	        'context' => 'advanced',
	        'fields' => array(
	        	array(
					'name' => esc_html__('Logo', 'littledino'),
					'id' => "mb_customize_logo",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'custom' => esc_html__('Custom', 'littledino'),
					),
					'multiple' => false,
					'inline' => true,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Logo Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_customize_logo', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Header Logo', 'littledino'),
					'id' => "mb_header_logo",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_customize_logo', '=', 'custom')
						)),
					),
				),
				array(
					'id' => 'mb_logo_height_custom',
					'name' => esc_html__('Enable Logo Height', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' => array( array(
					    	array('mb_customize_logo', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Set Logo Height', 'littledino'),
					'id' => 'mb_logo_height',
					'type' => 'number',
					'min' => 0,
					'step' => 1,
					'std' => 50,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_customize_logo', '=', 'custom'),
							array('mb_logo_height_custom', '=',true)
						)),
					),
				),
				array(
					'name' => esc_html__('Sticky Logo', 'littledino'),
					'id' => 'mb_logo_sticky',
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_logo', '=', 'custom')
						)),
					),
				),
				array(
					'id' => 'mb_sticky_logo_height_custom',
					'name' => esc_html__('Enable Sticky Logo Height', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
					    	array('mb_customize_logo', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Set Sticky Logo Height', 'littledino'),
					'id' => 'mb_sticky_logo_height',
					'type' => 'number',
					'min' => 0,
					'step' => 1,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_logo', '=', 'custom'),
							array('mb_sticky_logo_height_custom', '=',true),
						)),
					),
				),
				array(
					'name' => esc_html__('Mobile Logo', 'littledino'),
					'id' => 'mb_logo_mobile',
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_logo', '=', 'custom')
						)),
					),
				),
				array(
					'id' => 'mb_mobile_logo_height_custom',
					'name' => esc_html__('Enable Mobile Logo Height', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
					    	array('mb_customize_logo', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Set Mobile Logo Height', 'littledino'),
					'id' => 'mb_mobile_logo_height',
					'type' => 'number',
					'min' => 0,
					'step' => 1,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_logo', '=', 'custom'),
							array('mb_mobile_logo_height_custom', '=',true),
						)),
					),
				),
				array(
					'name' => esc_html__('Mobile Menu Logo', 'littledino'),
					'id' => 'mb_logo_mobile_menu',
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_logo', '=', 'custom')
						)),
					),
				),
				array(
					'id' => 'mb_mobile_logo_menu_height_custom',
					'name' => esc_html__('Enable Mobile Logo Height', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
					    	array('mb_customize_logo', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Set Mobile Logo Height', 'littledino'),
					'id' => "mb_mobile_logo_menu_height",
					'type' => 'number',
					'min' => 0,
					'step' => 1,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_logo', '=', 'custom'),
							array('mb_mobile_logo_menu_height_custom', '=',true),
						)),
					),
				),
	        )
	    );
	    return $meta_boxes;
	}

	public function page_header_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Header', 'littledino'),
	        'post_types' => array( 'page', 'post', 'portfolio', 'product' ),
	        'context' => 'advanced',
	        'fields' => array(
	        	array(
					'name' => esc_html__('Header Settings', 'littledino'),
					'id' => 'mb_customize_header_layout',
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('default', 'littledino'),
						'custom' => esc_html__('custom', 'littledino'),
						'hide' => esc_html__('hide', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
	        	array(
					'name' => esc_html__('Header Builder', 'littledino'),
					'id' => 'mb_customize_header',
					'type' => 'select',
					'options' => littledino_get_custom_preset(),
					'multiple' => false,
					'std' => 'default',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_header_layout', '!=', 'hide')
						)),
					),
				),
				array(
					'id' => 'mb_menu_header',
					'name' => esc_html__('Menu', 'littledino'),
					'type' => 'select',
					'options' => littledino_get_custom_menu(),
					'multiple' => false,
					'std' => 'default',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_header_layout', '=', 'custom')
						)),
					),
				),
                array(
                    'id' => 'mb_header_sticky',
                    'name' => esc_html__('Sticky Header', 'littledino'),
                    'type' => 'switch',
                    'std' => 1,
                    'attributes' => array(
                        'data-conditional-logic' => array(array(
                            ['mb_customize_header_layout', '=', 'custom']
                        )),
                    ),
                ),
	        )
        );

		return $meta_boxes;
	}

    public function page_title_meta_boxes($meta_boxes)
    {
		$meta_boxes[] = [
			'title' => esc_html__('Page Title', 'littledino'),
			'post_types' => ['page', 'post', 'team', 'practice', 'portfolio', 'product'],
			'context' => 'advanced',
			'fields' => [
				array(
					'id' => 'mb_page_title_switch',
					'name' => esc_html__('Page Title', 'littledino'),
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'on' => esc_html__('On', 'littledino'),
						'off' => esc_html__('Off', 'littledino'),
					),
					'std' => 'default',
					'inline' => true,
					'multiple' => false
				),
				[
					'name' => esc_html__('Page Title Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => [
					    'data-conditional-logic' => [[
							['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_page_title_bg_switch',
                    'name' => esc_html__('Use Background?', 'littledino'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'std' => true,
                ],
                [
                    'id' => 'mb_page_title_bg',
                    'name' => esc_html__('Background', 'littledino'),
                    'type' => 'wgl_background',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_bg_switch', '=', true],
                        ]],
                    ],
                    'image' => esc_url(get_template_directory_uri() . '/img/page_title_bg.png'),
                    'repeat' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_bg_image')['background-repeat'] ?? ''),
                    'size' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_bg_image')['background-size'] ?? ''),
                    'attachment' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_bg_image')['background-attachment'] ?? ''),
                    'position' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_bg_image')['background-position'] ?? ''),
                    'color' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_bg_image')['background-color'] ?? ''),
                ],
                [
                    'id' => 'mb_page_title_height',
                    'name' => esc_html__('Height', 'littledino'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_bg_switch', '=', true],
                        ]],
                    ],
                    'min' => 0,
                    'std' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_height')['height']),
                ],
				array(
					'name' => esc_html__('Title Alignment', 'littledino'),
					'id' => 'mb_page_title_align',
					'type' => 'button_group',
					'options' => array(
						'left' => esc_html__('left', 'littledino'),
						'center' => esc_html__('center', 'littledino'),
						'right' => esc_html__('right', 'littledino'),
					),
					'std' => 'center',
					'multiple' => false,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=' , 'on' )
						)),
					),
				),
				[
					'id' => 'mb_page_title_padding',
					'name' => esc_html__('Paddings Top/Bottom', 'littledino'),
					'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
					'options' => [
						'mode' => 'padding',
						'top' => true,
						'right' => false,
						'bottom' => true,
						'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_padding')['padding-top'] ?? ''),
                        'padding-bottom' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_padding')['padding-bottom'] ?? ''),
                    ],
				],
                [
                    'id' => 'mb_page_title_margin',
                    'name' => esc_html__('Margin Bottom', 'littledino'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'mode' => 'margin',
                        'top' => false,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => ['margin-bottom' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_margin')['margin-bottom'] ?? '')],
                ],
				array(
					'id' => 'mb_page_title_border_switch',
					'name' => esc_html__('Border Top Switch', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
						'data-conditional-logic' =>  array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name' => esc_html__('Border Top Color', 'littledino'),
					'id' => 'mb_page_title_border_color',
					'type' => 'color',
					'attributes' => array(
						'data-conditional-logic' =>  array( array(
							array('mb_page_title_border_switch', '=',true)
						)),
					),
				),
				array(
					'id' => 'mb_page_title_parallax',
					'name' => esc_html__('Parallax Switch', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
						'data-conditional-logic' =>  array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name' => esc_html__('Prallax Speed', 'littledino'),
					'id' => 'mb_page_title_parallax_speed',
					'type' => 'number',
					'std' => 0.3,
					'step' => 0.1,
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array( 'mb_page_title_parallax', '=',true ),
							array( 'mb_page_title_switch', '=', 'on' ),
						)),
					),
				),
				array(
					'id' => 'mb_page_title_parallax_mouse',
					'name' => esc_html__('Parallax Mouse', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
						'data-conditional-logic' =>  array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),

				array(
					'id' => 'mb_page_title_mouse_bg',
					'name' => esc_html__('Background', 'littledino'),
					'type' => 'wgl_background',
				    'image' => esc_url(get_template_directory_uri() . "/img/page_title_bg.png"),
				    'position' => 'center bottom',
				    'attachment' => 'scroll',
				    'size' => 'cover',
				    'repeat' => 'no-repeat',
					'color' => 'rgba(255,255,255,0)',
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_parallax_mouse', '=',true ),
							array( 'mb_page_title_switch', '=', 'on' ),
						)),
					),
				),
				array(
					'name' => esc_html__('Prallax Speed', 'littledino'),
					'id' => 'mb_page_title_parallax_speed_mouse',
					'type' => 'number',
					'std' => 0.03,
					'step' => 0.01,
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array( 'mb_page_title_parallax_mouse', '=',true ),
							array( 'mb_page_title_switch', '=', 'on' ),
						)),
					),
				),
				array(
					'id' => 'mb_page_change_tile_switch',
					'name' => esc_html__('Custom Page Title', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name' => esc_html__('Page Title', 'littledino'),
					'id' => 'mb_page_change_tile',
					'type' => 'text',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array( 'mb_page_change_tile_switch', '=', '1' ),
							array( 'mb_page_title_switch', '=', 'on' ),
						)),
					),
				),
				array(
					'id' => 'mb_page_title_breadcrumbs_switch',
					'name' => esc_html__('Show Breadcrumbs', 'littledino'),
					'type' => 'switch',
					'std' => 1,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name' => esc_html__('Breadcrumbs Alignment', 'littledino'),
					'id' => 'mb_page_title_breadcrumbs_align',
					'type' => 'button_group',
					'options' => array(
						'left' => esc_html__('left', 'littledino'),
						'center' => esc_html__('center', 'littledino'),
						'right' => esc_html__('right', 'littledino'),
					),
					'std' => 'center',
					'multiple' => false,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' ),
							array( 'mb_page_title_breadcrumbs_switch', '=', '1' )
						)),
					),
				),
				array(
					'name' => esc_html__('Page Title Typography', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
                [
                    'id' => 'mb_page_title_font',
                    'name' => esc_html__('Page Title Font', 'littledino'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_font')['font-size'] ?? ''),
                        'line-height' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_font')['line-height'] ?? ''),
                        'color' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_font')['color'] ?? ''),
                    ],
                ],
                [
                    'id' => 'mb_page_title_breadcrumbs_font',
                    'name' => esc_html__('Page Title Breadcrumbs Font', 'littledino'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_breadcrumbs_font')['font-size']),
                        'line-height' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_breadcrumbs_font')['line-height']),
                        'color' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_breadcrumbs_font')['color']),
                    ],
                ],
				array(
					'name' => esc_html__('Responsive Layout', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_title_switch', '=', 'on')
						)),
					),
				),
				array(
					'id' => 'mb_page_title_resp_switch',
					'name' => esc_html__('Responsive Layout On/Off', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_title_switch', '=', 'on')
						)),
					),
				),
				[
                    'id' => 'mb_page_title_resp_resolution',
					'name' => esc_html__('Screen breakpoint', 'littledino'),
					'type' => 'number',
				    'attributes' => [
					    'data-conditional-logic' => [[
							['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
					'min' => 1,
                    'std' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_resp_resolution')),
                ],
				[
                    'id' => 'mb_page_title_resp_height',
					'name' => esc_html__('Height', 'littledino'),
					'type' => 'number',
				    'attributes' => [
					    'data-conditional-logic' => [[
							['mb_page_title_switch', '=', 'on'],
							['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
					'min' => 0,
					'std' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_resp_height')['height'] ?? ''),
                ],
                [
                    'id' => 'mb_page_title_resp_padding',
                    'name' => esc_html__('Padding Top/Bottom', 'littledino'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_resp_padding')['padding-top'] ?? ''),
                        'padding-bottom' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_resp_padding')['padding-bottom'] ?? ''),
                    ],
                ],
				array(
					'name' => esc_html__('Page Title Font', 'littledino'),
					'id' => 'mb_page_title_resp_font',
					'type' => 'wgl_font',
					'options' => array(
						'font-size' => true,
						'line-height' => true,
						'font-weight' => false,
						'color' => true,
					),
					'std' => array(
						'font-size' => '42',
						'line-height' => '60',
						'color' => '#0a3380',
					),
				    'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_title_switch', '=', 'on'),
							array('mb_page_title_resp_switch', '=', '1'),
						)),
					),
				),
				array(
					'id' => 'mb_page_title_resp_breadcrumbs_switch',
					'name' => esc_html__('Show Breadcrumbs', 'littledino'),
					'type' => 'switch',
					'std' => 1,
				    'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_page_title_switch', '=', 'on'),
							array('mb_page_title_resp_switch', '=', '1'),
						)),
					),
				),
                [
                    'id' => 'mb_page_title_resp_breadcrumbs_font',
                    'name' => esc_html__('Page Title Breadcrumbs Font', 'littledino'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                            ['mb_page_title_resp_breadcrumbs_switch', '=', '1'],
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_breadcrumbs_font')['font-size']),
                        'line-height' => esc_attr((int) LittleDino_Theme_Helper::get_option('page_title_breadcrumbs_font')['line-height']),
                        'color' => esc_attr(LittleDino_Theme_Helper::get_option('page_title_breadcrumbs_font')['color']),
                    ],
                ],
            ],
        ];

	    return $meta_boxes;
	}

	public function page_side_panel_meta_boxes($meta_boxes)
    {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Side Panel', 'littledino'),
	        'post_types' => array( 'page' ),
	        'context' => 'advanced',
	        'fields' => array(
	        	array(
					'name' => esc_html__('Side Panel', 'littledino'),
					'id' => "mb_customize_side_panel",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'custom' => esc_html__('Custom', 'littledino'),
					),
					'multiple' => false,
					'inline' => true,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Side Panel Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_side_panel', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Content Type', 'littledino'),
					'id' => 'mb_side_panel_content_type',
					'type' => 'button_group',
					'options' => array(
						'widgets' => esc_html__('Widgets', 'littledino'),
						'pages' => esc_html__('Page', 'littledino')
					),
					'multiple' => false,
					'std' => 'widgets',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_side_panel', '=', 'custom')
						)),
					),
				),
				array(
	        		'name' => esc_html__('Select a page', 'littledino'),
					'id' => 'mb_side_panel_page_select',
					'type' => 'post',
					'post_type' => 'side_panel',
					'field_type' => 'select_advanced',
					'placeholder' => esc_attr__( 'Select a page', 'littledino'),
					'query_args' => array(
					    'post_status' => 'publish',
					    'posts_per_page' => - 1,
					),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_side_panel', '=', 'custom'),
							array('mb_side_panel_content_type', '=', 'pages')
						)),
					),
	        	),
				array(
					'name' => esc_html__('Paddings', 'littledino'),
					'id' => 'mb_side_panel_spacing',
					'type' => 'wgl_offset',
					'options' => array(
						'mode' => 'padding',
						'top' => true,
						'right' => true,
						'bottom' => true,
						'left' => true,
					),
					'std' => array(
						'padding-top' => '105',
						'padding-right' => '90',
						'padding-bottom' => '105',
						'padding-left' => '90'
					),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_side_panel', '=', 'custom')
						)),
					),
				),

				array(
					'name' => esc_html__('Title Color', 'littledino'),
					'id' => "mb_side_panel_title_color",
					'type' => 'color',
					'std' => '#ffffff',
					'js_options' => array(
						'defaultColor' => '#ffffff',
					),
					'attributes' => array(
						'data-conditional-logic' =>  array( array(
							array('mb_customize_side_panel', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Text Color', 'littledino'),
					'id' => "mb_side_panel_text_color",
					'type' => 'color',
					'std' => '#313538',
					'js_options' => array(
						'defaultColor' => '#313538',
					),
					'attributes' => array(
						'data-conditional-logic' =>  array( array(
							array('mb_customize_side_panel', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Background Color', 'littledino'),
					'id' => "mb_side_panel_bg",
					'type' => 'color',
					'std' => '#ffffff',
					'alpha_channel' => true,
					'js_options' => array(
						'defaultColor' => '#ffffff',
					),
					'attributes' => array(
						'data-conditional-logic' =>  array( array(
							array('mb_customize_side_panel', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Text Align', 'littledino'),
					'id' => "mb_side_panel_text_alignment",
					'type' => 'button_group',
					'options' => array(
						'left' => esc_html__('Left', 'littledino'),
						'center' => esc_html__('Center', 'littledino'),
						'right' => esc_html__('Right', 'littledino'),
					),
					'multiple' => false,
					'std' => 'center',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_customize_side_panel', '=', 'custom')
						), ),
					),
				),
				array(
					'name' => esc_html__('Width', 'littledino'),
					'id' => "mb_side_panel_width",
					'type' => 'number',
					'min' => 0,
					'step' => 1,
					'std' => 480,
					'attributes' => array(
						'data-conditional-logic' =>  array( array(
							array('mb_customize_side_panel', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__('Position', 'littledino'),
					'id' => "mb_side_panel_position",
					'type' => 'button_group',
					'options' => array(
						'left' => esc_html__('Left', 'littledino'),
						'right' => esc_html__('Right', 'littledino'),
					),
					'multiple' => false,
					'std' => 'right',
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_customize_side_panel', '=', 'custom')
							),
						),
					),
				),
	        )
	    );
	    return $meta_boxes;
	}

	public function page_soc_icons_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Social Shares', 'littledino'),
	        'post_types' => array( 'page' ),
	        'context' => 'advanced',
	        'fields' => array(
	        	array(
					'name' => esc_html__('Social Shares', 'littledino'),
					'id' => "mb_customize_soc_shares",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'on' => esc_html__('On', 'littledino'),
						'off' => esc_html__('Off', 'littledino'),
					),
					'multiple' => false,
					'inline' => true,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Choose your share style.', 'littledino'),
					'id' => "mb_soc_icon_style",
					'type' => 'button_group',
					'options' => array(
						'standard' => esc_html__('Standard', 'littledino'),
						'hovered' => esc_html__('Hovered', 'littledino'),
					),
					'multiple' => false,
					'std' => 'standard',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_soc_shares', '=', 'on')
						)),
					),
				),
				array(
					'id' => 'mb_soc_icon_position',
					'name' => esc_html__('Fixed Position On/Off', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_soc_shares', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Offset Top(in percentage)', 'littledino'),
					'id' => 'mb_soc_icon_offset',
					'type' => 'number',
					'std' => 50,
					'min' => 0,
					'step' => 1,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_soc_shares', '=', 'on')
						)),
					),
					'desc' => esc_html__('Measurement units defined as "percents" while position fixed is enabled, and as "pixels" while position is off.', 'littledino'),
				),
				array(
					'id' => 'mb_soc_icon_facebook',
					'name' => esc_html__('Facebook Share On/Off', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_soc_shares', '=', 'on')
						)),
					),
				),
				array(
					'id' => 'mb_soc_icon_twitter',
					'name' => esc_html__('Twitter Share On/Off', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_soc_shares', '=', 'on')
						)),
					),
				),
				array(
					'id' => 'mb_soc_icon_linkedin',
					'name' => esc_html__('Linkedin Share On/Off', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_soc_shares', '=', 'on')
						)),
					),
				),
				array(
					'id' => 'mb_soc_icon_pinterest',
					'name' => esc_html__('Pinterest Share On/Off', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_soc_shares', '=', 'on')
						)),
					),
				),
				array(
					'id' => 'mb_soc_icon_tumblr',
					'name' => esc_html__('Tumblr Share On/Off', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_customize_soc_shares', '=', 'on')
						)),
					),
				),

	        )
	    );
	    return $meta_boxes;
	}

	public function page_footer_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Footer', 'littledino'),
	        'post_types' => array( 'page' ),
	        'context' => 'advanced',
	        'fields' => array(
	        	array(
					'name' => esc_html__('Footer', 'littledino'),
					'id' => "mb_footer_switch",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'on' => esc_html__('On', 'littledino'),
						'off' => esc_html__('Off', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Footer Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_footer_switch', '=', 'on')
						)),
					),
				),
				array(
					'id' => 'mb_footer_add_wave',
					'name' => esc_html__('Add Wave', 'littledino'),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_footer_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Set Wave Height', 'littledino'),
					'id' => "mb_footer_wave_height",
					'type' => 'number',
					'min' => 0,
					'step' => 1,
					'std' => 158,
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
					    	array('mb_footer_switch', '=', 'on'),
							array('mb_footer_add_wave', '=', '1')
						)),
					),
				),
				array(
					'name' => esc_html__('Content Type', 'littledino'),
					'id' => 'mb_footer_content_type',
					'type' => 'button_group',
					'options' => array(
						'widgets' => esc_html__('Default', 'littledino'),
						'pages' => esc_html__('Page', 'littledino')
					),
					'multiple' => false,
					'std' => 'widgets',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_footer_switch', '=', 'on')
						)),
					),
				),
				array(
	        		'name' => esc_html__('Select a page', 'littledino'),
					'id' => 'mb_footer_page_select',
					'type' => 'post',
					'post_type' => 'footer',
					'field_type' => 'select_advanced',
					'placeholder' => esc_attr__( 'Select a page', 'littledino'),
					'query_args' => array(
					    'post_status' => 'publish',
					    'posts_per_page' => - 1,
					),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_footer_switch', '=', 'on'),
							array('mb_footer_content_type', '=', 'pages')
						)),
					),
	        	),
				array(
					'name' => esc_html__('Paddings', 'littledino'),
					'id' => 'mb_footer_spacing',
					'type' => 'wgl_offset',
					'options' => array(
						'mode' => 'padding',
						'top' => true,
						'right' => true,
						'bottom' => true,
						'left' => true,
					),
					'std' => array(
						'padding-top' => '0',
						'padding-right' => '0',
						'padding-bottom' => '0',
						'padding-left' => '0'
					),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_footer_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Background', 'littledino'),
					'id' => "mb_footer_bg",
					'type' => 'wgl_background',
				    'image' => '',
				    'position' => 'center center',
				    'attachment' => 'scroll',
				    'size' => 'cover',
				    'repeat' => 'no-repeat',
					'color' => '#2d4073',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_footer_switch', '=', 'on')
						)),
					),
				),
	        ),
	     );
	    return $meta_boxes;
	}

	public function page_copyright_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Copyright', 'littledino'),
	        'post_types' => array( 'page' ),
	        'context' => 'advanced',
	        'fields' => array(
				array(
					'name' => esc_html__('Copyright', 'littledino'),
					'id' => "mb_copyright_switch",
					'type' => 'button_group',
					'options' => array(
						'default' => esc_html__('Default', 'littledino'),
						'on' => esc_html__('On', 'littledino'),
						'off' => esc_html__('Off', 'littledino'),
					),
					'multiple' => false,
					'std' => 'default',
				),
				array(
					'name' => esc_html__('Copyright Settings', 'littledino'),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_copyright_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Editor', 'littledino'),
					'id' => "mb_copyright_editor",
					'type' => 'textarea',
					'cols' => 20,
					'rows' => 3,
					'std' => 'Copyright © 2019 LittleDino by WebGeniusLab. All Rights Reserved',
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_copyright_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Text Color', 'littledino'),
					'id' => "mb_copyright_text_color",
					'type' => 'color',
					'std' => '#b1bee0',
					'js_options' => array(
						'defaultColor' => '#b1bee0',
					),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_copyright_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Background Color', 'littledino'),
					'id' => "mb_copyright_bg_color",
					'type' => 'color',
					'std' => '#233668',
					'js_options' => array(
						'defaultColor' => '#233668',
					),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_copyright_switch', '=', 'on')
						)),
					),
				),
				array(
					'name' => esc_html__('Paddings', 'littledino'),
					'id' => 'mb_copyright_spacing',
					'type' => 'wgl_offset',
					'options' => array(
						'mode' => 'padding',
						'top' => true,
						'right' => false,
						'bottom' => true,
						'left' => false,
					),
					'std' => array(
						'padding-top' => '28',
						'padding-bottom' => '28',
					),
					'attributes' => array(
					    'data-conditional-logic' =>  array( array(
							array('mb_copyright_switch', '=', 'on')
						)),
					),
				),
	        ),
	     );
	    return $meta_boxes;

	}

	public function shop_catalog_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title' => esc_html__('Catalog Options', 'littledino'),
	        'post_types' => array( 'product' ),
	        'context' => 'advanced',
	        'fields' => array(
	        	array(
					'id' => 'mb_product_carousel',
					'name' => esc_html__('Product Carousel', 'littledino'),
					'type' => 'switch',
					'std' => '',
				),
	        ),
	    );
	    return $meta_boxes;
	}

}
new LittleDino_Metaboxes();

?>
