<?php
// http://phugiakhang.info/modal/
// WP_List_Table is not loaded automatically so we need to load it in our application
if(!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
 
/**
 * Create a new table class that will extend the WP_List_Table
 */
class TemplateSeoChecker_List_Table extends WP_List_Table {
	
	private $perPage;
	
	private $post_type;
	
	public function __construct($perPage = 20, $post_type = 'post') {
		$this->perPage = $perPage;
		$this->post_type = $post_type;
		parent::__construct($perPage);
	}
    
    /**
     * Prepare the items for the table to process
     * @return Void
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
 
        $data = $this->table_data();
        usort( $data, array(&$this, 'sort_data'));
 
        $perPage = $this->perPage;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
 
        $this->set_pagination_args(array('total_items' => $totalItems, 'per_page' => $perPage));
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
 
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
 
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     * @return Array
     */
    public function get_columns() {
        $columns = array(
        	'url' => __('URL', 'tsc'),
        	'date' => __('Test date', 'tsc'),
        	'score' => __('SEO Score'),
        	'improvement' => __('Improvement', 'tsc'),
        	'actions' => '',
        );
        return $columns;
    }
 
    /**
     * Define which columns are hidden
     * @return Array
     */
    public function get_hidden_columns() {
        return array();
    }
 
    /**
     * Define the sortable columns
     * @return Array
     */
    public function get_sortable_columns() {
        return array('score' => array('score', false));
    }
 
    /**
     * Get the table data
     * @return Array
     */
    private function table_data() {
        global $wpdb;
        $locale = get_locale();

        $data = array();
        $resultados = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."posts LEFT JOIN tsc_urls_parsed ON (tsc_urls_parsed.post_name = ".$wpdb->prefix."posts.post_name) WHERE post_type = %s AND post_status = 'publish'", $this->post_type));
        
        foreach($resultados as $res) {
        	$post_url = get_permalink($res->ID);
        	
        	$score = (empty($res->post_name)) ? '???/100' : $res->score.'/100';
        	$color_class = jja_tsc_color_code((float) $res->score, 4);
        	
        	$improvement = (!empty($res->post_name)) ? ((float) $res->improvement) : '';
			$color = jja_tsc_color_code($improvement, 1);
			
			$last_time_checked = ($locale == 'en_US' || 'en_EN') ? date('Y/m/d - h:m:s', strtotime($res->time_checked)) : date('d/m/Y - h:m:s', strtotime($res->time_checked));
			$last_time_checked = (empty($res->post_name)) ? '' : $last_time_checked;
			$view_seo_summary = (empty($res->post_name)) ? '' : '<a href="#" title="'.__('View SEO summary', 'tsc'). '" data-url="'.$post_url.'" class="view-summary" data-score="'.$res->score.'"><img src="'.TSC_MEDIA_URI.'/img/view-details.png" height="24" width="24" alt="'.__('View SEO summary', 'tsc').'" /></a>';
			
        	$data[] = array(
        		'url' => '<a href="'.$post_url.'" target="_blank">'.$post_url.'</a>', 
        		'score' => '<span class="'.$color_class.'"><strong>'.$score.'</strong></span>',
        		'date' => $last_time_checked,
        		'improvement' => '<span class="'.$color.'"><strong>'.$improvement.'</strong></span>',
        		'actions' => '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=template-seo-checker&recalc='.$res->ID.'&check_posttype='.$this->post_type.'" title="'.__('Recalc SEO score for current URL', 'tsc').'" class="recalc advice" data-url="'.$post_url.'"><img src="'.TSC_MEDIA_URI.'/img/recalc.png" height="24" width="24" alt="'.__('Recalc SEO score for current URL', 'tsc').'" /></a>'.$view_seo_summary,
        	);
        }

        return $data;
    }
 
    /**
     * Define what data to show on each column of the table
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     * @return Mixed
     */
    public function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'url':
            case 'date' :
            case 'score' :
            case 'improvement':
            case 'actions':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
 
    /**
     * Allows you to sort the data by the variables set in the $_GET
     * @return Mixed
     */
    private function sort_data($a, $b) {
        // Set defaults
        $orderby = 'score';
        $order = 'asc';
 
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }
 
        // If order is set use this as the order
        if(!empty($_GET['order'])) {
            $order = $_GET['order'];
        }
 
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc') {
            return $result;
        }
 
        return -$result;
    }
    
    function display() {
    	extract( $this->_args );
    	$this->display_tablenav('top'); ?>
		<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>" cellspacing="0">
	    	<thead>
	    		<tr><?php $this->print_column_headers(); ?></tr>
	    	</thead>
	    	<tfoot>
	    		<tr><?php $this->print_column_headers(false); ?></tr>
	    	</tfoot>
	    	<tbody id="the-list"<?php if($singular) echo " data-wp-lists='list:$singular'"; ?>>
	    		<?php $this->display_rows_or_placeholder(); ?>
	    	</tbody>
	    </table><?php
    	$this->display_tablenav('bottom');
    }
}