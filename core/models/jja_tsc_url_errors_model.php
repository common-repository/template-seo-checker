<?php

class jja_tsc_urls_errors_model {
	
	private $tableName = 'tsc_url_errors';
	
	private $errorsTableName = 'tsc_errors';
	
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
		return $wpdb->get_results("SELECT * FROM {$this->tableName}");
	}
	
	public function get_errors_by_url($url) {
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->tableName} WHERE url = %s", $url));
	}
	
	public function get_front_errors_by_url($url) {
		global $wpdb;
		$resultados = $wpdb->get_results($wpdb->prepare("select id_error, error, comments, num_elements, score from {$this->errorsTableName}, {$this->tableName} WHERE url = %s and id_error = tsc_errors.id", $url));
		
		$res = array();
		foreach($resultados as $result) {
			$comments = ($result->id_error != 4) ? __($result->comments, 'tsc') : '';
			
			if($result->id_error == 4) {
				$comments = '';
				if(empty($result->comments)) {
					$comments .= '&nbsp;';
				} else {
					$errores = explode('||', $result->comments);
					$headings = explode('--', $errores[0]);
				}
				
				$comments .= '<ul>';
				if(isset($errores[1]) && !empty($errores[1])) {
					$comments .= '<li>'.$errores[1].'</li>';
				}
				foreach($headings as $heading) {
					$comments .= '<li>'.$heading.'</li>';
				}
				$comments .= '</ul>';
			} else {
				$comments = $result->comments;
			}
			
			$res[] = array('id_error' => $result->id_error, 'error' => __($result->error, 'tsc'), 'comments' => $comments, 'num_elements' => $result->num_elements, 'score' => '<span class="'.jja_tsc_color_code($result->score, 4).'">'.$result->score.'/100</span>');
		}
		return $res;
	}
	
	public function delete_url_error($url) {
		global $wpdb;
		$wpdb->delete($this->tableName, array('url' => $url));
	}
	
	public function insert_url_error($data) {
		global $wpdb;
		$wpdb->insert($this->tableName, $data, array('%s', '%d', '%s', '%s', '%d'));
	}
}
?>