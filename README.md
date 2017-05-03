# wp-materialize-walker-nav-menu
A custom WordPress nav walker class to implement the materialize navigation style in a custom theme using the WordPress built in menu manager.
# NOTES
This is a utility class that is intended to format your WordPress theme menu with the correct syntax and classes to utilize the Materialize dropdown navigation, and does not include the required Materialize JS files. You will have to include them manually.

# Installation
Place **class-materialize-navwalker.php** in your WordPress theme folder ``/wp-content/your-theme/``
Open your WordPress themes functions.php file ``/wp-content/your-theme/functions.php`` and add the following code:

```php
// Require Materialize Custom Nav Walker Class
require get_template_directory() . '/class-materialize-walker-nav-menu.php';

add_action( 'wp_footer' , 'materialize_nav_walker_dropdown_init' );

if( ! function_exists('materialize_nav_walker_dropdown_init') ) {

  function materialize_nav_walker_dropdown_init() { ?>
      <script>
          jQuery(document).ready(function($) {
              jQuery(".nav-item-dropdown-button").dropdown({constrainWidth: true});
              jQuery(".side-menu-nav-item-dropdown-button").dropdown({constrainWidth: false});
              jQuery(".button-collapse").sideNav();
          });
      </script>
  <?php }

}
```

# Usage
Update your wp_nav_menu() function in header.php to use the new walker by adding a "walker" item to the wp_nav_menu array.
```php
<?php
	wp_nav_menu( array(
		'theme_location'    => 'primary',
		'menu_id'           => 'primary-menu',
		'menu_class' 	    => 'right hide-on-med-and-down',
		'walker'		    =>	new Materialize_Walker_Nav_Menu(),
	) );
?>
```
# Examples

## Example - 1 (Right Side Navigation Items)
```html
<nav class="main-navigation" role="navigation">
	<div class="nav-wrapper">
		<a href="#" data-activates="primary-mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>
		<?php
		    wp_nav_menu( array(
		        'menu'              => 'primary',
		        'menu_id' 			=> 'primary-menu',
		        'theme_location'    => 'primary',
		        'depth'             =>  1,
		        'container'         => 'div',
		        'menu_class' 		=> 'right hide-on-med-and-down',
				'walker'			=>	new Materialize_Walker_Nav_Menu(),
		    );
		    
		    wp_nav_menu( array(
		        'menu'              => 'primary',
		        'menu_id' 			=> 'primary-mobile-menu',
		        'theme_location'    => 'primary',
		        'depth'             =>  1,
		        'container'         => 'div',
		        'menu_class' 		=> 'side-nav',
				'walker'			=>	new Materialize_Walker_Nav_Menu(),
		    );
		?>
	</div>
</nav>
```
## Example - 2 (Left Side Navigation Items)
```html
<nav class="main-navigation" role="navigation">
	<div class="nav-wrapper">
		<a href="#" data-activates="primary-mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>
		<?php
		    wp_nav_menu( array(
		        'menu'              => 'primary',
		        'menu_id' 			=> 'primary-menu',
		        'theme_location'    => 'primary',
		        'depth'             =>  1,
		        'container'         => 'div',
		        'menu_class' 		=> 'left hide-on-med-and-down',
				'walker'			=>	new Materialize_Walker_Nav_Menu(),
		    );
		    
		    wp_nav_menu( array(
		        'menu'              => 'primary',
		        'menu_id' 			=> 'primary-mobile-menu',
		        'theme_location'    => 'primary',
		        'depth'             =>  1,
		        'container'         => 'div',
		        'menu_class' 		=> 'side-nav',
				'walker'			=>	new Materialize_Walker_Nav_Menu(),
		    );
		?>
	</div>
</nav>
```

## Example - 3 (Fixed Navbar)
```html
<div class="navbar-fixed">
    <nav>
    	<div class="nav-wrapper">
        	<a href="#" data-activates="primary-mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>
            <?php
                wp_nav_menu( array(
                    'menu'              => 'primary',
                    'menu_id' 			=> 'primary-menu',
                    'theme_location'    => 'primary',
                    'depth'             =>  1,
                    'container'         => 'div',
                    'menu_class' 		=> 'left hide-on-med-and-down',
                    'walker'			=>	new Materialize_Walker_Nav_Menu(),
                );

                wp_nav_menu( array(
                    'menu'              => 'primary',
                    'menu_id' 			=> 'primary-mobile-menu',
                    'theme_location'    => 'primary',
                    'depth'             =>  1,
                    'container'         => 'div',
                    'menu_class' 		=> 'side-nav',
                    'walker'			=>	new Materialize_Walker_Nav_Menu(),
                );
            ?>        
      	</div>
    </nav>
  </div>
```

# Extras
This script included the ability to add Materialize dividers, dropdown headers, material-icons to your menus through the WordPress menu UI.

## Dividers
Simply add a Link menu item with a URL of # and a Link Text or Title Attribute of divider (case-insensitive so ‘divider’ or ‘Divider’ will both work ).

## Material-icons
To add an Icon to your link simple place the material icon name with prefix material_icon class name in the CSS Classes (optional) field.
IE : material_icon-view_module

## Icons only
To show icon only add a class "icon-only" class name in the CSS Classes (optional) field.

# References
* [Navbar - Materialize](http://materializecss.com/navbar.html)
* [Dropdown - Materialize](http://materializecss.com/dropdown.html)
* [wp_nav_menu() | Function | WordPress Developer Resources](https://developer.wordpress.org/reference/functions/wp_nav_menu/)
