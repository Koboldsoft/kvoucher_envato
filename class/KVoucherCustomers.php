<?php
class KVoucherCustomers {
    
    // class instance
    static $instance;
    
    // customer WP_List_Table object
    public $customers_obj;
    
    // class constructor
    public function __construct() {
        add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
        add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
    }
    
    
    public static function set_screen( $status, $option, $value ) {
        return $value;
    }
    
    public function plugin_menu() {
        
        
        
        $hook = add_submenu_page(
            'kvoucher_options',
            'Customers',
            __('Customers','kvoucherpro'),
            'manage_options',
            'customers',
            [ $this, 'scPlugin_settings_page' ]
            );
        
        add_action( "load-$hook", [ $this, 'screen_option' ] );
        
        
        
    }
    
    
    /**
     * Plugin settings page
     */
    public function scPlugin_settings_page() {
        ?>
		<div class="wrap">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<a href="https://koboldsoft.com" target="_blank"><img alt="Koboldsoft.com" src="<?php echo plugin_dir_url( __DIR__ ).'img/koboldsoft_black_solutions.png' ?>" height="25"></a>
							<!-- This IF block will be auto removed from the Free Version and will only get executed if the user on a trial or have a valid license. -->
						 	<?php if ( kvo_fs()->can_use_premium_code__premium_only() ) { ?>
						 	<form method="post">
							<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    							<?php
    							//infoKoboldCouponPro();
    							//checkisUrlValide();
    							$this->customers_obj->prepare_items();
								$this->customers_obj->search_box(__('search','kvoucherpro'), 'search_id');
								$this->customers_obj->display(); 
								?>
							</form>
							<?php }else if ( kvo_fs()->is_not_paying() ) {
							    
							     echo '<section><h1>' . __('Awesome Professional Features', 'kvoucherpro') . '</h1>';
							     
							     echo '<a href="' . kvo_fs()->get_upgrade_url() . '">' . __('Upgrade Now!', 'kvoucherpro') . '</a>';
							     
							     echo '</section>';
							     
							 }?>
							
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Customers',
			'default' => 10,
			'option'  => 'customers_per_page'
		];

		add_screen_option( $option, $args );

		$this->customers_obj = new Customers_List();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

add_action( 'plugins_loaded', function () {
	KVoucherCustomers::get_instance();
} );