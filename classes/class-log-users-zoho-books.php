<?php

class LogUsersBooks {


    public static function activation() {

    }

    public static function deactivation() {

    }

    public static function uninstall() {

    }

    public static function init() {
        require_once 'class-books-actions.php';
        add_shortcode( 'log_users', [__CLASS__,'form_users_log']);
        add_action( 'wp_head', [__CLASS__, 'js_head']);
        add_action( 'admin_menu', [__CLASS__, 'tab_zoho']);
        add_action( 'wp_ajax_save_product', [__CLASS__,'save_product']);
    }

    public static function save_product(){
        if( isset( $_POST['zoho_api_item_id'] ) ) {
            update_option( 'zoho_api_item_id', $_POST['zoho_api_item_id']);
        }
        if( isset( $_POST['zoho_api_item_name'] ) ) {
            update_option( 'zoho_api_item_name', $_POST['zoho_api_item_name']);
        }
    }

    public static function tab_zoho() {
        add_submenu_page(
            'zoho-settings',
            'Log Users',
            'Log Users',
            'manage_options',
            'log-users',
            'render_log_users'
        );

        function render_log_users() {
            include_once WP_PLUGIN_DIR.'/log-users-zoho-books/templates/log.php';
        }
    }

    public static function js_head() {
        ?>
            <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
        <?php
    }

    public static function form_users_log(){
        ob_start();
        if( isset($_POST["date_log"]) ) {
            self::save_log();
            ?>
                <script>
                    setTimeout(() => {
                        document.location.href+=""
                    }, 2000);
                </script>
            <?php
        }else {
            include WP_PLUGIN_DIR.'/log-users-zoho-books/templates/form-users.php';
            echo "<script>const wp_customers = ". json_encode( self::get_customers() ).";";
            include WP_PLUGIN_DIR.'/log-users-zoho-books/templates/form.js';
            echo "</script>";
        }
        $html = ob_get_contents();
	    ob_end_clean();
        return $html;
    }

    public static function save_log(){
        try {
            if(
                isset($_POST['first_name']) &&
                isset($_POST['services']) &&
                isset($_POST['mount'])
            ) {
                $first_name = $_POST['first_name'];
                $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : "";
                $dni = isset($_POST['dni']) ? $_POST['dni'] : "";
                $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
                $services = $_POST['services'];
                $mount = $_POST['mount'];
                $description = isset($_POST['description']) ? $_POST['description'] : "";
                $date_log = isset($_POST['date_log']) ? $_POST['date_log'] : date("Y-m-d");
                $contact_id = $_POST["contact_id"];
                if ( $_POST['id'] == -1 ) {
                    $response_create_user = BooksActions::create_customer([
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "dni" => $dni,
                        "phone" => $phone,
                        "services" => $services
                    ]);
                    if( $response_create_user["success"]==false ) {
                        LogControl::insert(__FILE__, __LINE__, "El usuario no pudo ser creado, ponte en contacto con el administrador", "loguser,error");
                        throw new Exception("El usuario no pudo ser creado, ponte en contacto con el administrador");
                    }
                    $contact_id = $response_create_user["data"]["contact_id"];
                }
                $input_invoice = [
                    "customer_id" => $contact_id,
                    "date" => $date_log,
                    "due_date" => $date_log,
                    "discount" => 0,
                    "line_items" => [
                        [
                            "item_id" => get_option('zoho_api_item_id',''),
                            "name" => get_option('zoho_api_item_name',''),
                            "quantity" => floatval(1),
                            "description" => $description."<br/> Servicios: ".implode(",",$services),
                            "rate" => floatval($mount),
                            "unit" => "box",
                            "discount" => floatval(0)
                        ]
                    ]
                ];
                $invoice = BooksActions::create_invoice($input_invoice);
                if( !isset($invoice["invoice_id"]) ) {
                    LogControl::insert(__FILE__, __LINE__, "No se pudo crear factura para la orden  para cliente $first_name $last_name por monto de $mount", "loguser,error");
                    throw new Exception("No se pudo crear factura para la orden  para cliente $first_name $last_name por monto de $mount");
                }
                LogControl::insert(__FILE__, __LINE__, "Creada factura ".$invoice['invoice_id']." para cliente $first_name $last_name por monto de $mount", "loguser,important");
                BooksActions::create_payment([
                    "customer_id" => $contact_id,
                    "payment_mode" => "cash",
                    "amount" => floatval($mount),
                    "date" => $date_log,
                    "description" => "Payment has been added",
                    "invoices" => [
                        [
                            "invoice_id" => $invoice['invoice_id'],
                            "amount_applied" => floatval($mount)
                        ]
                    ]
                ]);
                echo "Creada factura ".$invoice['invoice_id']." para cliente $first_name $last_name por monto de $mount";
            } else {
                throw new Exception("<b style='color:red'>No se guardo el registro, se requiere nombre, servicio y monto</b>");
            }
        } catch( Exception $error ) {
            echo $error->getMessage();
        }
    }

    public static function user_exists(){
        $customers = self::get_customers();

    }

    public static function get_customers() {
        $users = [];
        $_users = get_users([
            "role" => "customer"
        ]);

        foreach( $_users as $_user ) {
            $customer = new WC_Customer( $_user->ID );
            $data = json_decode( json_encode( $_user->data ), true );
            $dni = get_user_meta($_user->ID, '_book_cf_dni');
            $contact_id = get_user_meta($_user->ID, '_book_contact_id');
            $data["dni"] = count($dni) ? $dni[0] : "";
            $data["contact_id"] = count($contact_id) ? $contact_id[0] : "";
            unset( $data["user_pass"] );
            $users[] = [
                "ID" => $_user->ID,
                "data" => $data,
                "roles" => $_user->roles,
                "billing" => [
                    "billing_first_name" => $customer->get_billing_first_name(),
                    "billing_last_name" => $customer->get_billing_last_name(),
                    "billing_company" => $customer->get_billing_company(),
                    "billing_country" => $customer->get_billing_country(),
                    "billing_address_1" => $customer->get_billing_address_1(),
                    "billing_address_2" => $customer->get_billing_address_2(),
                    "billing_city" => $customer->get_billing_city(),
                    "billing_state" => $customer->get_billing_state(),
                    "billing_postcode" => $customer->get_billing_postcode(),
                    "billing_phone" => $customer->get_billing_phone(),
                    "billing_email" => $customer->get_billing_email()
                ],
                "shipping" => [
                    "shipping_first_name" => $customer->get_billing_first_name(),
                    "shipping_last_name" => $customer->get_billing_last_name(),
                    "shipping_company" => $customer->get_billing_company(),
                    "shipping_country" => $customer->get_billing_country(),
                    "shipping_address_1" => $customer->get_billing_address_1(),
                    "shipping_address_2" => $customer->get_billing_address_2(),
                    "shipping_city" => $customer->get_billing_city(),
                    "shipping_state" => $customer->get_billing_state(),
                    "shipping_postcode" => $customer->get_billing_postcode(),
                    "shipping_phone" => $customer->get_billing_phone(),
                    "shipping_email" => $customer->get_billing_email()
                ]
            ];
        }
        return $users;
    }

}