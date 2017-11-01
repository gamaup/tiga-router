<?php
/**
 * Pagination class
 *
 * @package Tiga Router
 */

namespace Tiga;

/**
 * Pagination Class
 */
class Pagination {

	/**
	 * Config
	 *
	 * @var array
	 */
	private $config = array();

	/**
	 * Initialize pagination config
	 *
	 * @param array $config Config array.
	 */
	public function setup( $config ) {

		$this->config['per_page']           = 10;
		$this->config['item_to_show']       = 2;
		$this->config['skip_item']          = true;
		$this->config['first_tag_open']     = '<li>';
		$this->config['first_tag_close']    = '</li>';
		$this->config['last_tag_open']      = '<li>';
		$this->config['last_tag_close']     = '</li>';
		$this->config['prev_tag_open']      = '<li>';
		$this->config['prev_tag_close']     = '</li>';
		$this->config['prev_tag_text']      = 'Prev';
		$this->config['next_tag_open']      = '<li>';
		$this->config['next_tag_close']     = '</li>';
		$this->config['next_tag_text']      = 'Next';
		$this->config['cur_tag_open']       = "<li class='active'>";
		$this->config['cur_tag_close']      = '</li>';
		$this->config['link_attribute']     = "class=''";
		$this->config['link_attribute_active'] = "class='active'";
		$this->config['num_tag_open']       = '<li>';
		$this->config['num_tag_close']      = '</li>';
		$this->config['skip_tag_open']      = '<li>';
		$this->config['skip_tag_close']     = '</li>';
		$this->config['skip_tag_text']      = "<a href='#'>....</a>";
		$this->config['start_page']         = 0;
		$this->config['appends']            = array(); // appends get parameter.

		/* merge options */
		foreach ( $config as $key => $value ) {
			$this->config[ $key ] = $value;
		}
		if ( $this->config['item_to_show'] < 2 ) {
			$this->config['item_to_show'] = 2;
		}
		$this->total        = intval( $config['rows'] );
		$this->per_page     = intval( $config['per_page'] );
		$this->current_page = intval( $config['current_page'] );
		$this->base_url     = urldecode( $config['base_url'] );
	}

	/**
	 * Return the number of offset for given convig
	 *
	 * @return int
	 */
	public function offsett() {
		/* calculate offset */
		return $this->per_page * $this->current_page;
	}
	/**
	 * Render the pagination link
	 *
	 * @return string
	 */
	public function render() {
		/* appends any get parameter if specified */
		$appends = array();
		if ( is_array( $appends ) && count( $this->config['appends'] ) > 0 ) {

			foreach ( $this->config['appends'] as $parameter ) {
				if ( array_key_exists( $parameter, $_GET ) ) { // Input var okay.
					$appends[ $parameter ] = sanitize_text_field( $_GET[ $parameter ] ); // Input var okay.
				}
			}

			/* build new url */
			$this->base_url = $this->base_url . '?' . http_build_query( $appends );
		}

		/* calculate iteration */
		$iteration = (int) ceil( $this->total / $this->per_page ) + $this->config['start_page'] - 1 ;

		if ( 0 === $iteration ) {
			return;
		}

		$item_to_show = $this->config['item_to_show'];
		$first_item_max = $item_to_show - 1 ;
		$last_item_min = $iteration + 1 - $item_to_show ;
		$print_array = array();
		if ( $this->config['skip_item'] ) {
			/* calculate pagination print */
			for ( $i = 0;$i <= $first_item_max;$i++ ) {
				$print_array[ $i ] = true;
			}
			for ( $i = $last_item_min;$i <= $iteration;$i++ ) {
				$print_array[ $i ] = true;
			}
			if ( ! isset( $print_array[ $this->current_page - $item_to_show - 1 ] ) ) {
				$print_array[ $this->current_page - $item_to_show - 1 ] = 'skip';
			}
			if ( ! isset( $print_array[ $this->current_page + $item_to_show + 1 ] ) ) {
				$print_array[ $this->current_page + $item_to_show + 1 ] = 'skip';
			}
			for ( $i = $this->current_page;$i <= $this->current_page + $item_to_show;$i++ ) {
				$print_array[ $i ] = true;
			}
			for ( $i = $this->current_page - $item_to_show;$i <= $this->current_page;$i++ ) {
				$print_array[ $i ] = true;
			}
		}
		for ( $i = $this->config['start_page'] ; $i <= $iteration   ; $i++ ) {
			if ( $this->config['skip_item'] ) {
				if ( ! isset( $print_array[ $i ] ) ) {
					continue;
				}
				if ( 'skip' === $print_array[ $i ] ) {
					echo $this->config['skip_tag_open'];
					echo "{$this->config['skip_tag_text']}";
					echo $this->config['skip_tag_close'];
					continue;
				}
			}

			$page_number = $i + 1 - $this->config['start_page'];

			/* prev */
			if ( $i === $this->config['start_page'] && $this->current_page !== $this->config['start_page'] ) {
				$prev_url = str_replace( '[paginate]',$this->current_page - 1, $this->base_url );
				echo $this->config['prev_tag_open'];
				echo "<a href='{$prev_url}' {$this->config['link_attribute']} >{$this->config['prev_tag_text']}</a>";
				echo $this->config['prev_tag_close'];
			}

			$url = str_replace( '[paginate]',$i, $this->base_url );
			/* current */
			if ( $i == $this->current_page ) {
				echo $this->config['cur_tag_open'];
				echo "<a href='{$url}' {$this->config['link_attribute_active']} >$page_number </a>";
				echo $this->config['cur_tag_close'];
			} elseif ( $i === $this->config['start_page'] ) { // first.
				// $url = str_replace('[paginate]','', $this->base_url);
				echo $this->config['first_tag_open'];
				echo "<a href='{$url}' {$this->config['link_attribute']} >$page_number </a>";
				echo $this->config['first_tag_close'];
			} elseif ( $i === $iteration ) { // last.
				echo $this->config['last_tag_open'];
				echo "<a href='{$url}' {$this->config['link_attribute']} >$page_number </a>";
				echo $this->config['last_tag_close'];
			} else {
				echo $this->config['num_tag_open'];
				echo "<a href='{$url}' {$this->config['link_attribute']} >$page_number </a>";
				echo $this->config['num_tag_close'];
			}

			/* next */
			if ( $i === $iteration ) {
				if ( $i !== $this->current_page ) {
					$next_url = str_replace( '[paginate]',$this->current_page + 1, $this->base_url );
				} else {
					$next_url = '#';
				}
				echo $this->config['next_tag_open'];
				echo "<a href='{$next_url}' {$this->config['link_attribute']} >{$this->config['next_tag_text']}</a>";
				echo $this->config['next_tag_close'];
			}
			$print_skip = true;
		}
	}
}
