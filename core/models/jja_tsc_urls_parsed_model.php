<?php 

class jja_tsc_urls_parsed_model {
	
	private $tableName = 'tsc_urls_parsed';
	
	private static $instance = null;
 
    public static function get_instance() {
		if(null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
    }
	
	public function __construct() {}
	
	public function get_all_urls_parsed() {
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM {$this->tableName}");
	}
	
	public function get_average_score() {
		global $wpdb;
		return $wpdb->get_row("select count(*) as num_urls, avg(score) as score, current_timestamp as timestamp from {$this->tableName}");
	}
	
	public function get_by_url($url) {
		global $wpdb;
		return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tableName} WHERE url = %s", $url));
	}
	
	public function update_url($data, $url) {
		global $wpdb;
		$wpdb->update($this->tableName, $data, array('url' => $url), array('%d', '%d'),	array('%s'));
	}
	
	public function delete_score_by_url($url) {
		global $wpdb;
		$wpdb->delete($this->tableName, array('url' => $url));
	}
	
	public function add_score_by_url($data) {
		global $wpdb;
		$wpdb->insert($this->tableName, $data, array('%s', '%f', '%f'));
	}
}
?>