<?php

/**
 * A Route describes a route and its parameters.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class Route {
	/**
	 * The hook called when this route is matched.
	 *
	 * @var string
	 */
	private $hook;

	/**
	 * The URL path that the route needs to match.
	 *
	 * @var string
	 */
	private $path;

	/**
	 * The URL path that the route needs to match.
	 *
	 * @var string
	 */
	private $methods;

	/**
	 * Constructor.
	 *
	 * @param string $path Path route.
	 * @param string $data Method and callback.
	 */
	public function __construct( $path, $data ) {

		$this->path = $path;

		foreach ( $data as $method => $callback ) {

			if ( in_array( $method, array( 'get', 'post', 'put', 'delete' ), true ) ) {
				if ( $callback ) { // callback.
					$this->hook[ $method ] = $callback;
				}
				$this->methods[] = $method;
			}
		}
	}

	/**
	 * Get the hook called when this route is matched.
	 *
	 * @param  string $method Request method.
	 * @return string
	 */
	public function get_hook( $method = 'get' ) {
		return isset( $this->hook[ $method ] ) ? $this->hook[ $method ] : false;
	}

	/**
	 * Get the URL path that the route needs to match.
	 *
	 * @return string
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Get the URL path that the route needs to match.
	 *
	 * @return string
	 */
	public function get_methods() {
		return $this->methods;
	}


	/**
	 * Checks if this route want to call a hook when matched.
	 *
	 * @param  string $method Request method.
	 * @return bool
	 */
	public function has_hook( $method = 'get' ) {
		return ! empty( $this->hook[ $method ] );
	}

}
