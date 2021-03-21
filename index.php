<?php
/*
 Plugin name: edit_user_basic
 plugin uri: www.localhost.com
 Description: Short Code para modificar los datos basicos del usuario
 Author: Luis Castillo
 Version: 0.1
 Author URI:
 */

add_shortcode("edit_user_basic","edit_user_basic");

function edit_user_basic(){
    $errors = array();
    $mensaje_exito="";
    if(isset($_POST["submit_update"])){

        $data["first_name"] = sanitize_text_field($_POST["account_first_name"]);
        $data["last_name"] = sanitize_text_field($_POST["account_last_name"]);
        $data["dni"] = sanitize_text_field($_POST["account_dni"]);
        $data["display_name"] = sanitize_text_field($_POST["account_display_name"]);
        $data["user_email"] = sanitize_email($_POST["account_email"]);
        $data["billing_address_1"] = sanitize_text_field($_POST["billing_address_1"]);
        $data["billing_address_2"] = sanitize_text_field($_POST["billing_address_2"]);
        $data["billing_city"] = sanitize_text_field($_POST["billing_city"]);

        //validaciones
        if( trim($data["first_name"]) == ""){
            $errors[] = "Por favor, rellena el campo de nombre.";
        }
        if( trim($data["last_name"]) == ""){
            $errors[] = "Por favor, rellena el campo de apellido.";
        }
        if( trim($data["dni"]) == ""){
            $errors[] = "Por favor, rellena el campo de dni.";
        }
        if( trim($data["display_name"]) == ""){
            $errors[] = "Por favor, rellena el campo de nombre visible.";
        }
        if(!is_email($data["user_email"])){
            $errors[] = "Por favor, rellena el campo de email y que sea un correo valido.";
        }
        if( trim($data["billing_address_1"]) == ""){
            $errors[] = "Por favor, rellena el campo de dirección de la calle.";
        }
        if( trim($data["billing_city"]) == ""){
            $errors[] = "Por favor, rellena el campo de localidad / ciudad.";
        }


        //Procedo a actualizar
        if(count($errors) == 0 ){
            $user = (object)$data;
            $user->ID = get_current_user_id();
            $user_id = wp_update_user( $user );
            update_user_meta($user->ID,"first_name",$user->first_name);
            update_user_meta($user->ID,"last_name",$user->last_name);
            update_user_meta($user->ID,"billing_first_name",$user->first_name);
            update_user_meta($user->ID,"billing_last_name",$user->last_name);
            update_user_meta($user->ID,"dni",$user->dni);
            update_user_meta($user->ID,"billing_address_1",$user->billing_address_1);
            update_user_meta($user->ID,"billing_address_2",$user->billing_address_2);
            update_user_meta($user->ID,"billing_city",$user->billing_city);
            if($user_id > 0){
                $mensaje_exito = "Se han realizado los cambios exitosamente";
            }
        }

    }

    $id = get_current_user_id();
    $data_user = get_user_to_edit($id);
    $first_name = get_user_meta($id,"first_name",true);
    $last_name = get_user_meta($id,"last_name",true);
    $dni = get_user_meta($id,"dni",true);
    $billing_address_1 = get_user_meta($id,"billing_address_1",true);
    $billing_address_2 = get_user_meta($id,"billing_address_2",true);
    $billing_city = get_user_meta($id,"billing_city",true);
    $display_name = $data_user->data->display_name;
    $user_email = $data_user->data->user_email;

    ob_start();
    $path = 'assets/css/woocommerce-layout.css';
    wp_enqueue_style( "nombre", apply_filters( 'woocommerce_get_asset_url', plugins_url( $path, WC_PLUGIN_FILE ), $path ) );

    ?>
<!--    <link rel="stylesheet" type="text/css" href="<?php echo content_url()."/plugins/woocommerce/assets/css/woocommerce-layout.css?ver=4.9.0"; ?>" media="screen" />
-->
    <div class="woocommerce-MyAccount-content">
        <?php if(count($errors) > 0 ){ ?>
            <div class="woocommerce-notices-wrapper"><ul class="woocommerce-error" role="alert">
                    <?php foreach ($errors as $error){?>
                        <li><?php echo $error ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
        <?php if( $mensaje_exito != "" ){ ?>
            <div class="woocommerce-notices-wrapper"><ul class="woocommerce-message" role="alert">
                 <li><?php echo $mensaje_exito ?></li>
            </ul>
        </div>
        <?php } ?>
        <form class="woocommerce-EditAccountForm edit-account" action="<?php get_the_permalink();?>" method="post">
            <input type="hidden" name="submit_update" id="submit_update" value="SI" />

            <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                <label for="account_first_name">Nombre&nbsp;<span style="color:red;">*</span></label>
                <br/>
                <input type="text" style="width: 100%" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo $first_name ?>">
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                <label for="account_last_name">Apellidos&nbsp;<span style="color:red;">*</span></label>
                <br/>
                <input type="text" style="width: 100%"  class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo $last_name ?>">
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                <label for="account_dni">DNI&nbsp;<span style="color:red;">*</span></label>
                <br/>
                <input type="text" style="width: 100%"  class="woocommerce-Input woocommerce-Input--text input-text" name="account_dni" id="account_dni" autocomplete="family-name" value="<?php echo $dni ?>">
            </p>
            <div class="clear"></div>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="account_display_name">Nombre visible&nbsp;<span style="color:red;">*</span></label>
                <br/>
                <input type="text" style="width: 100%"  class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo $display_name ?>">
                <br/><span><em>Así será como se mostrará tu nombre en la sección de tu cuenta y en las valoraciones</em></span>
            </p>
            <div class="clear"></div>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="account_email">Dirección de correo electrónico&nbsp;<span style="color:red;">*</span></label>
                <br/>
                <input type="email" style="width: 100%"  class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo $user_email ?>">
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                <label for="account_dni">DNI&nbsp;<span style="color:red;">*</span></label>
                <br/>
                <input type="text" style="width: 100%"  class="woocommerce-Input woocommerce-Input--text input-text" name="account_dni" id="account_dni" autocomplete="family-name" value="<?php echo $dni ?>">
            </p>

            <div class="clear"></div>

            <h1>Dirección</h1>

            <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                <label for="account_first_name">Dirección de la calle &nbsp;<span style="color:red;">*</span></label>
                <br/>
                <input type="text" style="width: 100%" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_address_1" id="billing_address_1" autocomplete="given-name" value="<?php echo $billing_address_1 ?>">
            </p>
            <p>
                <input type="text" style="width: 100%" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_address_2" id="billing_address_2" autocomplete="given-name" value="<?php echo $billing_address_2 ?>">
            </p>

            <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                <label for="account_last_name">Localidad / Ciudad  &nbsp;<span style="color:red;">*</span></label>
                <br/>
                <input type="text" style="width: 100%"  class="woocommerce-Input woocommerce-Input--text input-text" name="billing_city" id="billing_city" autocomplete="family-name" value="<?php echo $billing_city ?>">
            </p>

            <div class="clear"></div>


            <p>
                <button type="submit" class="woocommerce-Button button" name="save_account_details" value="Guardar los cambios">Guardar los cambios</button>
            </p>

        </form>

    </div>
    <?php
    return ob_get_clean();

    //return "<pre>".print_r(apply_filters( 'woocommerce_get_asset_url', plugins_url( $path, WC_PLUGIN_FILE ), $path ),true)."</pre>";

}

