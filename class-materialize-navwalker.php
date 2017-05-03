<?php
class Materialize_Walker_Nav_Menu extends Walker_Nav_Menu {

	/**
	 * Unique id for dropdowns
	 */
	public $submenu_unique_id = '';

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		$output .= "{$n}{$indent}<ul id=\"$this->submenu_unique_id\" class=\"sub-menu dropdown-content\">{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 * 
	 * @see Walker::end_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	/**
	 * Starts the element output.
	 * 
	 * @see Walker::start_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// set active class for current nav menu item
		if( $item->current == 1 ) {
			$classes[] = 'active';
		}

		// set active class for current nav menu item parent
		if( in_array( 'current-menu-parent' ,  $classes ) ) {
			$classes[] = 'active';
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		// add a divider in dropdown menus
		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li class="divider">';
		} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
			$output .= $indent . '<li class="divider">';
		} else {
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $class_names .'>';

			$atts = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';

			if( in_array('menu-item-has-children', $classes ) ) {
				$atts['href']   = '';
				$this->submenu_unique_id = 'dropdown-'.uniqid();
				$atts['data-activates'] = $this->submenu_unique_id;
				$atts['data-belowOrigin'] = 'true';
				if( strpos( $args->menu_class , 'side-nav' ) !== FALSE ) {
					$atts['class'] = ' side-menu-nav-item-dropdown-button';
				} else {
					$atts['class'] = ' nav-item-dropdown-button';
				}
			} else {
				$atts['href']   = ! empty( $item->url ) ? $item->url  : '';
				$atts['class'] = '';
			}

			$atts['class'] .= ' waves-effect waves-light';

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			if( ! in_array( 'icon-only' , $classes ) ) {
				
				$title = apply_filters( 'the_title', $item->title, $item->ID );

				$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
			}

			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';

			// set icon on left side
			if( !empty( $classes ) ) {
				foreach ($classes as $class_name) {
					if( strpos( $class_name , 'material_icon' ) !== FALSE ) {
						$icon_name = explode( '-' , $class_name );
						if( isset( $icon_name[1] ) && !empty( $icon_name[1] ) ) {
							$item_output .= '<i class="material-icons left">'.$icon_name[1].'</i>';
						}
					}
				}
			}

			$item_output .= $args->link_before . $title . $args->link_after;

			if( in_array('menu-item-has-children', $classes) ){
				if( $depth == 0 ) {
		        	$item_output .= '<i class="material-icons right">arrow_drop_down</i>';
		    	}
		    }

			$item_output .= '</a>';
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Materialize_Walker_Nav_Menu
