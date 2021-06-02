<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Customers_List extends WP_List_Table {
    
    /*
    private function encrytURLVarables($array){
        
        $data = encryptData( http_build_query( $array ),'~B-$mAf5~jm<Fz!p' );
        
        return http_build_query( array( 'data' => $data['data'],'iv' => $data['iv'] ) );
        
    }
    */
    
    /** Class constructor */
    public function __construct() {
        
        parent::__construct( [
            'singular' => __( 'Customer', 'sp' ), //singular name of the listed records
            'plural'   => __( 'Customers', 'sp' ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ] );
        
    }
    
    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_customers($view, $per_page = 10, $page_number = 1 ) {
        
        global $wpdb;
        if($view == ''){
            
            $sql = "SELECT * FROM {$wpdb->prefix}usr_kvoucher";
            
        }else{
        
            $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}usr_kvoucher WHERE del LIKE 0 AND action LIKE %s" , $view);
            
        }
        
        
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }else{
            
            $sql .= ' ORDER BY ID DESC';
            
        }
        
        
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        
        
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        
        return $result;
    }
    
    public function get_customers_search( $per_page = 10, $page_number = 1 , $search_id){
        
        global $wpdb;
        
        $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}usr_kvoucher WHERE id LIKE %s
                                                                             OR fname LIKE %s 
                                                                             OR nname LIKE %s
                                                                             OR streetname LIKE %s
                                                                             OR city LIKE %s",
                                                                             $search_id , 
                                                                        "%" . $search_id . "%" ,
                                                                        "%" . $search_id . "%" ,
                                                                        "%" . $search_id . "%" ,
                                                                        "%" . $search_id . "%" 
                                                                            );
        
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }
        
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        
        
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        
        return $result;
        
        
    }
    
    /**
     * return a single customer
     *
     * @param int $id customer ID
     */
    public static function get_customer( $id ) {
        global $wpdb;
        
        $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}usr_kvoucher WHERE id = %d", $id )  );
        
        return $result;
        
    }
    
    
    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count($search_id,$view) {
        
        global $wpdb;
        
        if(!empty($view) || $view == '0' && empty($search_id)){
        
            $sql = $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}usr_kvoucher WHERE del LIKE 0 AND action LIKE %s" , $view);
            
        }
        
        if(empty($search_id) && empty($view) && $view != '0'){
        
            $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}usr_kvoucher";
            
        }
        
        if(!empty($search_id) && empty($view)){
        
            $sql = $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}usr_kvoucher WHERE id LIKE %s
                                                                                OR fname LIKE %s
                                                                                OR nname LIKE %s
                                                                                OR streetname LIKE %s
                                                                                OR city LIKE %s
                                                                                AND action LIKE %s",
                                                                                $search_id ,
                                                                                "%" . $search_id . "%" ,
                                                                                "%" . $search_id . "%" ,
                                                                                "%" . $search_id . "%" ,
                                                                                "%" . $search_id . "%",
                                                                                $view
                                                                                    );
        }
        
        if(!empty($search_id) && !empty($view)){
            
            $sql = $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}usr_kvoucher WHERE id LIKE %s
                                                                                OR fname LIKE %s
                                                                                OR nname LIKE %s
                                                                                OR streetname LIKE %s
                                                                                OR city LIKE %s",
                                                                                $search_id ,
                                                                                "%" . $search_id . "%" ,
                                                                                "%" . $search_id . "%" ,
                                                                                "%" . $search_id . "%" ,
                                                                                "%" . $search_id . "%"
                                                                                    );
        }
        
        
        
        return $wpdb->get_var( $sql );
    }
    
    /** Text displayed when no customer data is available */
    public function no_items() {
        _e( 'No customers avaliable.', 'sp' );
    }
    
    
    
    
    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case ('price');
            case 'nname';
            case 'title';
            case 'streetname':
            case 'city':
            case 'shipping';
            case 'date':
            case 'action':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }
    
    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-cancel[]" value="%s" />', $item['id']
            );
    }
    
    
    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_id( $item ) {
        
        $delete_nonce = wp_create_nonce( 'sp_delete_customer' );
        
        $cancel_nonce = wp_create_nonce( 'sp_cancel_customer' );
        
        $title = '<strong>' . $item['id'] . '</strong>';
        
        $actions = [
            'show' => sprintf( '<a href="?page=%s&action=%s&customer=%s&key=%s">'.__('show','kvoucherpro').'</a>', esc_attr( $_REQUEST['page'] ), 'show', absint( $item['id'] ), $item['key_kvoucher'] ),
            'cancel' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">'.__('cancel','kvoucherpro').'</a>', esc_attr( $_REQUEST['page'] ), 'cancel', absint( $item['id'] ), $cancel_nonce )
        ];
        
        return $title . $this->row_actions( $actions );
    }
    
    function column_action( $item ) {
        
        switch ($item['action']) {
            case 0:
                $output =  "<b style='color:green;'>".__('open','kvoucherpro')."</b>";
                break;
            case 1:
                $output = "<b style='color:Maroon;'>".__('redeemed','kvoucherpro')."</b>";
                break;
                
            case 2:
                $output = "<b style='color:Orange;'>".__('canceled','kvoucherpro')."</b>";
                break;
            
        }
        
        return $output;
    }
    
    function column_documents( $item ) {
        
        $validity = $item['validity'];
        
        $coupon_data['coupon_data']['id'] = $item['id'];
        
        $coupon_data['coupon_data']['url'] = home_url();
        
        $coupon_data['coupon_data']['lang'] = get_locale();
        
        $coupon_data['buyer_data'] = $item;
        
        $coupon_data['buyer_data']['date'] = date_format(date_create($item['date']),'d-m-Y');
        
        $coupon_data['buyer_data']['futuredate'] =  date('d-m-Y', strtotime('+'.$validity.' year', strtotime($item['date'])) );
        
        $coupon_data['buyer_data']['value_added_tax'] =  $item['vat'];
        
        $coupon_data['company_data'] = get_option('kvoucher_plugin_company_textfiels');
        
        $coupon_data['company_data']['email_admin'] = get_option('admin_email');
        
        $coupon_data['style_data'] = get_option('kvoucher_plugin_style_textfiels');
        
        $dataencrypt = encrytURLVarables($coupon_data);
        
        if (strcmp($item['shipping'], 'E-mail') === 0) {
        
            $output = '<a alt='.__('Coupon','kvoucherpro').'" target="_blank" title="'.__('Coupon','kvoucherpro').'" href="https://couponsystem.koboldsoft.com/documents.php?document=coupon&'.http_build_query( $dataencrypt ).'"><img style="margin-right:5px" src="' . esc_url( plugins_url( 'img/file-pdf-solid.svg', dirname(__FILE__) ) ) . '" width="15" ></a>';
        }
  
        $output .= '<a alt="'.__('Bill','kvoucherpro').'" target="_blank" title="'.__('Bill','kvoucherpro').'" href="https://couponsystem.koboldsoft.com/documents.php?document=bill&'.http_build_query( $dataencrypt ).'"><img src="' . esc_url( plugins_url( 'img/file-pdf-regular.svg', dirname(__FILE__) ) ) . '" width="15" ></a>';
  
  //$output .= '<a alt="'.__('Bill','kvoucherpro').'" target="_blank" title="'.__('Bill','kvoucherpro').'" href="http://localhost/createpdf/documents.php?document=bill&'.$dataencrypt.'"><img src="' . esc_url( plugins_url( 'img/file-pdf-regular.svg', dirname(__FILE__) ) ) . '" width="15" ></a>';

        return $output;
    }
    
    
    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'id'    =>  __('ID','kvoucherpro'),
            'price'    =>  __('Value','kvoucherpro'),
            'title'    => __('Title','kvoucherpro'),
            'nname'    => __('Last Name','kvoucherpro'),
            'streetname' =>  __('Street','kvoucherpro'),
            'city'    => __( 'City', 'kvoucherpro' ),
            'shipping'    => __( 'Shipping', 'kvoucherpro' ),
            'date'    => __( 'Date', 'kvoucherpro' ),
            'action'    => __( 'Status', 'kvoucherpro' ),
            'documents'    => __( 'Documents', 'kvoucherpro' )
        ];
        
        return $columns;
    }
    
    
    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'nname' => array( 'nname', true ),
            'city' => array( 'city', true ),
            'shipping' => array( 'shipping', true ),
            'id' => array( 'id', false ),
            'price' => array( 'price', false ),
            'streetname' => array( 'streetname', false ),
            'date' => array('date', false),
            'action' => array('action', false)
        );
        
        return $sortable_columns;
    }
    
    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = [
            'bulk-cancel' => 'Stornieren'
        ];
        
        return $actions;
    }
    
    
    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {
        
        $this->_column_headers = $this->get_column_info();
        
        /** Process bulk action */
        $this->process_bulk_action();
        
        $this->process_show_status();
        
        $total_items  = self::record_count($_REQUEST['s'],$_REQUEST['view']);
        
        $per_page = $this->get_items_per_page( 'customers_per_page', 10 );
        
        if(!empty($_POST['s'])){
            
            $current_page = $this->get_pagenum();
            
            $this->set_pagination_args( [
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page'    => $per_page //WE have to determine how many items to show on a page
            ] );
            
            $this->views();
            
            $this->items = self::get_customers_search( $per_page, $current_page , $_REQUEST['s'] );
            
        }else{
            
            $current_page = $this->get_pagenum();
            
            $this->set_pagination_args( [
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page'    => $per_page //WE have to determine how many items to show on a page
            ] );
            
           $this->views();
        
           $this->items = self::get_customers($_REQUEST['view'], $per_page, $current_page );
            
        }
    }
    
    public function setSingleButtonAction($action,$text,$color,$confirmtext){
        
        $output = '<input style="margin-right:5px;background:'.$color.';border-color:'.$color.';box-shadow: 0 1px 0 '.$color.';text-shadow: 0 1px 0 '.$color.'" id="submit" name="coupon_action_'.$action.'" class="button button-primary" type="submit" onclick="return confirm('.$confirmtext.');" value="'.$text.'">';
        
        return $output;
        
        
    }
    
    public function setAction($id,$action){
        
        global $wpdb;
        
        $wpdb->update(
            
            $wpdb->prefix.'usr_kvoucher',
            array(
                'action' => $action,
            ),
            array( 'id' => $id ),
            array(
                '%d'
            ),
            array( '%d' )
            );
        
    }
    
    private function checkCustomer($id,$key){
        
        if (strcmp($id, $key) !== 0) {
            
            die( 'Go get a life script kiddies' );
        }
        
    }
    
    protected function get_views() {
        $status_links = array(
            "all"       => "<a href='/wp-admin/admin.php?page=customers'>".__("all","kvoucherpro")."</a>",
            "open" => "<a href='/wp-admin/admin.php?page=customers&view=0'>".__("open","kvoucherpro")."</a>",
            "redeemed"   =>"<a href='/wp-admin/admin.php?page=customers&view=1'>".__("redeemed","kvoucherpro")."</a>",
            "cancel"   =>"<a href='/wp-admin/admin.php?page=customers&view=2'>".__("canceled","kvoucherpro")."</a>"
        );
        return $status_links;
    }
    
    private function checkCurrency($currency){
        
        switch ($currency) {
            
            case 'euro':
                return "€";
                break;
            case 'dollar':
                return "$";
                break;
            case 'british_pound':
                return "£";
                break;
            case empty($currency) || $currency == null:
                return "€";
                break;
        }
        
    }
    
    
    public function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        
        if( 'cancel' === $this->current_action() ){
            
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );
            
            if ( ! wp_verify_nonce( $nonce, 'sp_cancel_customer' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::setAction( absint( $_GET['customer'] ),'2' );
                
                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url
                
                //wp_redirect( esc_url_raw(add_query_arg()) );
                
                $url_redirect = '/wp-admin/admin.php?page=customers';
                
                echo("<script>location.href = '".$url_redirect."'</script>");
                
                exit;
            }
        }
        
        if( 'show' === $this->current_action() ){
            
            $id = $_REQUEST['customer'];
            
            if(isset($_REQUEST['coupon_action_redeem'])){ self::setAction($id,'1');}
            
            if(isset($_REQUEST['coupon_action_sto'])){ self::setAction($id,'2');}
            
            if(isset($_REQUEST['coupon_action_ret'])){ self::setAction($id,'0');}
            
            $result = self::get_customer(absint(  $id) );
            
            self::checkCustomer($_REQUEST['key'], $result -> key_kvoucher);
            
            echo ($result->action == '0' ? "<p style='color:green;'>".__('The voucher has not yet been redeemed','kvoucherpro')."</p>." : "");
            
            echo ($result->action == '1' ? "<p style='color:red;'>".__('The voucher is redeemed','kvoucherpro')."</p>.":"");
            
            echo ($result->action == '2' ? "<p style='color:Orange;'>".__('The voucher has been canceled','kvoucherpro')."</p>." : "");
            
            if( $result->action == '0' ){ echo self::setSingleButtonAction('redeem',__('Redeem voucher','kvoucherpro'),'#0085ba',__("'Are you sure you want to redeem the voucher?'",'kvoucherpro')); };
            
            if( $result->action == '0' ){ echo self::setSingleButtonAction('sto',__('Cancel voucher','kvoucherpro'),'Red',__("'Are you sure you want to cancel the voucher?'",'kvoucherpro')); };
            
            if( $result->action != '0' ){ echo self::setSingleButtonAction('ret',__('Mark the voucher as OPEN again','kvoucherpro'),'#0085ba',__("'Are you sure you want to mark the voucher as OPEN again?'",'kvoucherpro')); };
            
            echo '</p>';
            
            echo '<h3>'.__('Voucher recipient','kvoucherpro').':</h3>';
            
            echo '<table class="form-table" role="presentation"';
            
            echo '<tbody>';
            
            echo '<tr><th scope="row">ID:</th><td>'. $result->id .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Issued on','kvoucherpro').':</th><td>'. $result->date .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Title','kvoucherpro').':</th><td>'. $result->for_title .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('First name','kvoucherpro').':</th><td>'. $result->for_fname .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Last Name','kvoucherpro').':</th><td>'. $result->for_nname .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Occasion','kvoucherpro').':</th><td>'. $result->occasion .'</td></tr>';
            
            echo '</tbody>';
            
            echo '</table>';
            
            echo '<h3>'.__('Billing address','kvoucherpro').':</h3>';
            
            echo '<table class="form-table" role="presentation"';
            
            echo '<tbody>';
            
            echo '<tr><th scope="row">'.__('Value','kvoucherpro').':</th><td>'. number_format( $result->price, 2, ',', ' ') .' '.self::checkCurrency($result->currency).'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Shipping','kvoucherpro').':</th><td>'. $result->shipping .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Title','kvoucherpro').':</th><td>'. $result->title .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('First Name','kvoucherpro').':</th><td>'. $result->fname .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Last Name','kvoucherpro').':</th><td>'. $result->nname .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Street','kvoucherpro').':</th><td>'. $result->streetname .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('City','kvoucherpro').':</th><td>' . $result->plz . ' ' . $result->city .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Country','kvoucherpro').':</th><td>'. $result->country .'</td></tr>';
                
            echo '<tr><th scope="row">E-mail:</th><td>'. $result->email .'</td></tr>';
            
            echo '<tr><th scope="row">'.__('Phone','kvoucherpro').':</th><td>'. $result->phone .'</td></tr>';
            
            echo '</tbody>';
            
            echo '</table>';
            
            if(!empty($result->dif_email)){
        
                echo '<h3>'.__('Differing Shipping Address','kvoucherpro').':</h3>';
            
                echo '<table class="form-table" role="presentation"';
            
                echo '<tbody>';
            
                echo '<tr><th scope="row">'.__('Title','kvoucherpro').':</th><td>'. $result->dif_title .'</td></tr>';
            
                echo '<tr><th scope="row">'.__('First Name','kvoucherpro').':</th><td>'. $result->dif_fname .'</td></tr>';
            
                echo '<tr><th scope="row">'.__('Last Name','kvoucherpro').':</th><td>'. $result->dif_nname .'</td></tr>';
            
                echo '<tr><th scope="row">'.__('Street','kvoucherpro').':</th><td>'. $result->dif_streetname .'</td></tr>';
            
                echo '<tr><th scope="row">'.__('City','kvoucherpro').':</th><td>' . $result->dif_plz . ' ' . $result->dif_city .'</td></tr>';
            
                echo '<tr><th scope="row">'.__('Country','kvoucherpro').':</th><td>'. $result->dif_country .'</td></tr>';
            
                echo '<tr><th scope="row">E-mail:</th><td>'. $result->dif_email .'</td></tr>';
            
                echo '</tbody>';
            
                echo '</table>';
                
            }
            
            exit;
            
        }
        
        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-cancel' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-cancel' )
            ) {
                
                $cancel_ids = esc_sql( $_POST['bulk-cancel'] );
                
                // loop over the array of record IDs and delete them
                foreach ( $cancel_ids as $id ) {
                    
                            self::setAction( $id,'2' );
                        
                   }
                
                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url
                
                //wp_redirect( esc_url_raw(add_query_arg()) );
                
                $url_redirect = '/wp-admin/admin.php?page=customers';
                
                echo("<script>location.href = '".$url_redirect."'</script>");
                
                exit;
            }
    }
    
}