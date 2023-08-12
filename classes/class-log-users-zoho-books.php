<?php

class LogUsersBooks {


    public static function activation() {

    }

    public static function deactivation() {

    }

    public static function uninstall() {

    }

    public static function init() {
        add_shortcode('log_users', [__CLASS__,'form_users_log']);
    }

    public static function form_users_log(){
        if( isset($_POST["date_log"]) ) {
            self::save_log();
        }
        include WP_PLUGIN_DIR.'/log-users-zoho-books/templates/form-users.php';
        $html = ob_get_contents();
	    ob_end_clean();
        return $html;
    }

    public static function save_log(){
        if(
            isset($_POST['fist_name']) &&
            isset($_POST['services']) &&
            isset($_POST['mount'])
        ) {
            $fist_name = $_POST['fist_name'];
            $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : "";
            $dni = isset($_POST['dni']) ? $_POST['dni'] : "";
            $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
            $services = $_POST['services'];
            $mount = $_POST['mount'];
            $description = isset($_POST['description']) ? $_POST['description'] : "";
        } else {
            echo "<b style='color:red'>No se guardo el registro, se requiere nombre, servicio y monto</b>";
        }
    }

}