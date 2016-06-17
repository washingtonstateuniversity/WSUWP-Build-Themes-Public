<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Util_LoadInterface
 *
 * Classes that implement this interface will have their load() method called automatically when they are retrieved as
 * a module from a class that is an instance of MAKE_Util_Modules via the get_module() method.
 *
 * In addition to the two methods in this interface, a MAKE_Util_LoadInterface class should have a $loaded
 * boolean property that starts as false. The load() method should check this property when it is called and only run
 * if the value is still false. Then it should change the value to true at its completion.
 *
 * The is_loaded() method should return the current boolean value of the $loaded property.
 *
 * @since 1.7.0.
 */
interface MAKE_Util_LoadInterface {
	public function load();

	public function is_loaded();
}