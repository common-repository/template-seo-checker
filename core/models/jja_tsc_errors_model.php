<?php 

class jja_tsc_errors_model {
	
	private $tableName = 'tsc_errors';
	
	private static $instance = null;
 
    public static function get_instance() {
		if(null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
    }
	
	public function __construct() {}
	
	public function get_all_errors() {
		global $wpdb;
		if($wpdb->get_var("SHOW TABLES LIKE '{$this->tableName}'") == $this->tableName) {
			return $wpdb->get_results("SELECT * FROM {$this->tableName}");
		} else {
			return array();
		}
	}
	
	public function get_error_by_key($key) {
		if(false === ($error = get_transient('tsc_error_key_'.$key))) {
			global $wpdb;
			$error = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tableName} WHERE error = %s", array($key)));
			set_transient('tsc_error_key_'.$key, $error);
		} 
		return $error;
	}
	
	public function get_error_by_id($key) {
		if(false === ($error = get_transient('tsc_error_id_'.$key))) {
			global $wpdb;
			$error = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tableName} WHERE id = %d", array($key)));
			set_transient('tsc_error_id_'.$key, $error);
		}
		return $error;
	}
}
?>