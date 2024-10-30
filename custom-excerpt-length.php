<?php
/**
 * Plugin Name: Custom Excerpt Length
 * Plugin URI:  https://thekrotek.com/wordpress-extensions/miscellaneous
 * Description: Adds Excerpt Length option on Reading page, which can be used to set the number of words in post excerpt.
 * Version:     1.0.0
 * Author:      The Krotek
 * Author URI:  https://thekrotek.com
 * Text Domain: customexcerpt
 * License:     GPL2
*/

defined("ABSPATH") or die("Restricted access");

$customexcerpt = new CustomExcerptLength();

class CustomExcerptLength
{
	var $textdomain;
	
	public function __construct()
	{
		add_action('init', array($this, 'init'));
		add_action('admin_init', array($this, 'admin_init'));
		
		$this->textdomain = 'customexcerpt';
	}
		
	public function init()
	{
		add_filter('excerpt_length', array($this, 'updateLength'), 1000, 1);
	}
		
	public function admin_init()
	{
		add_filter('plugin_row_meta', array($this, 'updatePluginMeta'), 10, 2);

		$page = 'reading';
		$section = 'default';

		// Excerpt Length
	
		$id = 'excerpt-length';
		$name = str_replace('-', '_', $id);
		
		$params = array(
			'id' => $id,
			'name' => $name,
			'default' => '55');
		
		register_setting($page, $name);
		add_settings_field($name, '<label for="'.$id.'">'.__('Excerpt Length', $this->textdomain).'</label>', array($this, 'addFieldNumber'), $page, $section, $params);
	}

	public function addFieldNumber($params)
	{
		echo '<input type="number" id="'.$params['id'].'" name="'.$params['name'].'" class="small-text" value="'.get_option($params['name'], $params['default']).'" min="1" step="1">';
	}

	public function updateLength($length)
	{
		$custom = get_option('excerpt_length', '55');
		
		if (!empty($custom)) $length = $custom;
		
		return $length;
	}

	public function updatePluginMeta($links, $file)
	{
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links, array('<a href="options-reading.php">'.__('Settings', $this->textdomain).'</a>'));
			$links = array_merge($links, array('<a href="https://thekrotek.com/support">'.__('Donate & Support', $this->textdomain).'</a>'));
		}
	
		return $links;
	}
}

?>
