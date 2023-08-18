<?php

if( !defined('ABSPATH') ) exit;

class BooksActions extends ZohoBooks {

    public static function create_customer( $input_data ) {
        if( !isset($input_data['first_name']) || !isset($input_data['last_name']) ) {
            return [
                "success" => false,
                "data" => []
            ];
        }
        $first_name = $input_data['first_name'];
        $last_name = $input_data['last_name'];

        $data = [
            "contact_name" => "$first_name $last_name",
            "contact_type" => "customer",
            "customer_sub_type" => "individual",
            // "contact_persons" => [
            //     "first_name" => $first_name,
            //     "last_name" => $last_name,
            //     "phone" => $input_data["phone"],
            //     "mobile" => $input_data["phone"],
            // ],
            "billing_address" => [
                "attention" => "",
                "address" => "",
                "street2" => "",
                "state_code" => "",
                "city" => "Upata",
                "state" => "Bolivar",
                "zip" => "",
                "country" => "Venezuela",
                "fax" => "",
                "phone" => $input_data["phone"],
            ],
            "shipping_address" => [
                "attention" => "",
                "address" => "",
                "street2" => "",
                "state_code" => "",
                "city" => "Upata",
                "state" => "Bolivar",
                "zip" => "",
                "country" => "Venezuela",
                "fax" => "",
                "phone" => $input_data["phone"],
            ],
            "custom_fields" => [
                [
                    "label" => "DNI",// intval( CF_DNI ),
                    "value" => $input_data["dni"]
                ],
                // [
                //     "label" => "cf_tipo_de_servicio", //intval( CF_TIPO_SERVICIO ),
                //     "value" => $input_data["services"],
                // ],
            ]
        ];
        LogControl::insert(__FILE__, __LINE__, "create customer, input data: ".$data["contact_name"], "loguser,internal");
        $customer = ZohoBooks::create_customer($data);
        if( isset($customer['contact_id']) ) {
            update_user_meta(
                $input_data['customer_id'],
                '_book_contact_id',
                $customer['contact_id']
            );
            LogControl::insert(__FILE__, __LINE__, "creado customer con id: ".$customer['contact_id'], "loguser,internal" );
            return [
                "success" => true,
                "data" => $customer
            ];
        }
        return [
            "success" => false,
            "data" => []
        ];
    }
}