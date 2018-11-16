<?php

abstract class Tribe__Events__Community__Tickets__Route__Abstract_Route {
	/**
	 * Hook prefix
	 * @var string
	 */
	protected $hook_prefix = 'tribe_ct_';

	/**
	 * Router
	 * @var string
	 */
	public $router;

	/**
	 * Route slug
	 * @var string
	 */
	public $route_slug;

	/**
	 * Route suffix
	 * @var string
	 */
	public $route_suffix;

	/**
	 * Route query vars
	 * @var array
	 */
	public $route_query_vars = array();

	/**
	 * Page arguments
	 * @var array
	 */
	public $page_args = array();

	/**
	 * Page title
	 * @var string
	 */
	public $title;

	/**
	 * Page template name
	 * @var string
	 */
	public $template;

	/**
	 * Handles the rendering of the route
	 * @abstract
	 */
	abstract public function callback( $arg = null );

	/**
	 * constructor
	 */
	public function __construct( $router ) {
		$this->router = $router;
		$this->template = $this->get_template();

		$this->add();
	}

	/**
	 * Adds the route to the router
	 */
	public function add() {
		$this->router->add_route(
			$this->route_slug,
			array(
				'path' => '^' . $this->path( $this->route_suffix ),
				'query_vars' => $this->route_query_vars,
				'page_callback' => array( $this, 'callback' ),
				'page_arguments' => $this->page_args,
				'access_callback' => true,
				'title' => $this->title,
				'template' => $this->template,
			)
		);
	}

	/**
	 * this code snippet taken from Tribe__Events__Community__Main::addRoutes
	 */
	protected function get_template() {
		$tec_template = tribe_get_option( 'tribeEventsTemplate' );

		switch ( $tec_template ) {
			case '' :
				$template_name = Tribe__Events__Templates::getTemplateHierarchy( 'default-template' );
				break;
			case 'default' :
				$template_name = 'page.php';
				break;
			default :
				$template_name = $tec_template;
		}

		return apply_filters( $this->hook_prefix . 'template', $template_name );
	}//end get_template
}
