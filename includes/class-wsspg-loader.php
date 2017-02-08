<?php
/**
 * Wsspg Loader
 *
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @since       1.0.0
 * @package     Wsspg
 * @subpackage  Wsspg/includes
 * @author      wsspg <wsspg@mail.com>
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright   (c) 2016 https://github.com/wsspg
 */

if( ! defined( 'ABSPATH' ) ) exit; // exit if accessed directly.

/**
 * Wsspg Loader Class
 *
 * @since  1.0.0
 * @class  Wsspg_Loader
 */
class Wsspg_Loader {
	
	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since  1.0.0
	 * @var    array
	 */
	protected $actions;
	
	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since  1.0.0
	 * @var    array
	 */
	protected $filters;
	
	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
		
		$this->actions = array();
		$this->filters = array();
	}
	
	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since  1.0.0
	 * @param  string
	 * @param  object
	 * @param  string
	 * @param  int
	 * @param  int
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}
	
	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since  1.0.0
	 * @param  string
	 * @param  object
	 * @param  string
	 * @param  int
	 * @param  int
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}
	
	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since   1.0.0
	 * @param   array
	 * @param   string
	 * @param   object
	 * @param   string
	 * @param   int
	 * @param   int
	 * @return  array
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
		
		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);
		return $hooks;
	}
	
	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since  1.0.0
	 */
	public function run() {
		
		foreach( $this->filters as $hook ) {
			add_filter(
				$hook['hook'],
				array(
					$hook['component'],
					$hook['callback']
				),
				$hook['priority'],
				$hook['accepted_args']
			);
		}
		foreach( $this->actions as $hook ) {
			add_action(
				$hook['hook'],
				array(
					$hook['component'],
					$hook['callback']
				),
				$hook['priority'],
				$hook['accepted_args']
			);
		}
	}
}
