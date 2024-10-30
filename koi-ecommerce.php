<?php
/**
 * Plugin Name: Koi eCommerce
 * Plugin URI: https://koi.app
 * Description: Plugin to help you sell your products online using the Koi eCommerce platform.
 * Version: 1.0
 * Author: Koi.app
 */


/**
 * CSS and JS
 *
 * @return void
 */
function koi_ecommerce_scripts(): void {
    wp_enqueue_script( 'koi_ecommerce_init_js', plugins_url( '/koi-ecommerce.js', __FILE__ ));
    wp_enqueue_style('koi_ecommerce_init_js', plugins_url( '/koi-ecommerce.css', __FILE__ ), array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts','koi_ecommerce_scripts');
add_action( 'admin_print_styles', 'koi_ecommerce_scripts' );

/**
 * @return void
 */
function koi_js_params_admin() : void{
    echo '<script type="text/javascript">
        window.koi_url = "' . esc_attr(get_option( 'koi_url' )) . '";
        window.koi_front_token = "' . esc_attr(get_option( 'koi_front_token' )) . '";
        window.koi_back_token = "' . esc_attr(get_option( 'koi_back_token' )) . '";
    </script>';
}
add_action( 'admin_head', 'koi_js_params_admin' );

function koi_js_params_front() : void{
    echo '<script type="text/javascript">
        window.koi_url = "' . esc_attr(get_option( 'koi_url' )) . '";
        window.koi_front_token = "' . esc_attr(get_option( 'koi_front_token' )) . '";
    </script>';
}
add_action( 'wp_head', 'koi_js_params_front' );


/**
 * Front end
 *
 * @param $items
 * @param $args
 * @return mixed|string
 */
function koi_menu_items( $items, $args ){
    $menu_obj = $args->menu;

    if( ( is_object( $menu_obj ) && $menu_obj->name === 'Main' ) || ( is_string( $menu_obj ) && $menu_obj === 'Main Menu' ) ){
        $items = $items . '<li><a href="#" onclick="window.dispatchEvent(new CustomEvent(\'koi-show-cart\'));">Cart</a></li>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'koi_menu_items', 10, 2 );

/**
 * Admin editor blocks
 * @return void
 */
function koi_register_blocks() : void {
    register_block_type( __DIR__ );
}
add_action( 'init', 'koi_register_blocks' );


function koi_admin_menu () : void {
    add_menu_page(
        'Koi Ecommerce',
        'Koi Ecommerce',
        'manage_options',
        'koi-ecommerce',
        'koi_admin_page',
        'dashicons-cart',
        6
    );
}

add_action( 'admin_menu', 'koi_admin_menu' );

function koi_settings () {
    register_setting( 'koi-ecommerce-settings', 'koi_url' );
    register_setting( 'koi-ecommerce-settings', 'koi_front_token' );
    register_setting( 'koi-ecommerce-settings', 'koi_back_token' );
}

add_action( 'admin_init', 'koi_settings' );


if( ! function_exists( 'koi_admin_page' ) ) {
    function koi_admin_page() { ?>
        <div class="wrap">
            <img
                style="width: 150px; height: auto; margin: 0 auto;"
                src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iODA4cHgiIGhlaWdodD0iNDA5cHgiIHZpZXdCb3g9IjAgMCA4MDggNDA5IiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPgogICAgPGcgaWQ9IlBhZ2UtMSIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPHBhdGggZD0iTTE2NC43NjU5MywwIEw4MS42NzE3MTgzLDE0Mi40MzA4MDEgQzc3LjM3MDUxNzIsMTQ5LjgwMzQzOCA2OS40NzcyMTExLDE1NC4zMzY4NTYgNjAuOTQxNjMxNSwxNTQuMzM2ODU2IEwyMy4zNTM5NTQsMTU0LjMzNjg1NiBDMTIuMDExMTQwNSwxNTQuMzM2ODU2IDIuNTA2MTczNzMsMTQ2LjQ2ODA5OSAxLjUyMTAwNTU0ZS0xNCwxMzUuODkxNTMyIEw5LjEwMzIzMzc0ZS0xMywzMC43NjY2MjI0IEM5LjA5MDEzMjE5ZS0xMywyMC4wNjgzOTc1IDEuMTEzOTA3MzMsMTYuMTg4OTYyNyAzLjIwNTU5MjQ1LDEyLjI3Nzg1MTYgQzUuMjk3Mjc3NTYsOC4zNjY3NDA0NSA4LjM2Njc0MDQ1LDUuMjk3Mjc3NTYgMTIuMjc3ODUxNiwzLjIwNTU5MjQ1IEMxNi4xODg5NjI3LDEuMTEzOTA3MzMgMjAuMDY4Mzk3NSw3LjIyMjIzNjU5ZS0xNiAzMC43NjY2MjI0LC0xLjI0MzAwODM3ZS0xNSBMMTY0Ljc2NTkzLC04LjY0MjI4MjVlLTE2IFogTTI4MSwtNS4zNTY2NTY5NmUtMTYgTDQ3MC41LDAgQzU4My40NDIyMzEsLTIuMDc0NzE1MTNlLTE0IDY3NSw5MS41NTc3Njg3IDY3NSwyMDQuNSBDNjc1LDMxNy40NDIyMzEgNTgzLjQ0MjIzMSw0MDkgNDcwLjUsNDA5IEwyODEsNDA5IEwxNjguNzE0NjU5LDIxNi41ODA2MDcgQzE2NC4zNTI3MzUsMjA5LjEwNTczMyAxNjQuMzUzMDYsMTk5Ljg2MTM2NCAxNjguNzE1NTExLDE5Mi4zODY3OTYgTDI4MSwwIFogTTE2NC43NjU5Myw0MDkgTDMwLjc2NjYyMjQsNDA5IEMyMC4wNjgzOTc1LDQwOSAxNi4xODg5NjI3LDQwNy44ODYwOTMgMTIuMjc3ODUxNiw0MDUuNzk0NDA4IEM4LjM2Njc0MDQ1LDQwMy43MDI3MjIgNS4yOTcyNzc1Niw0MDAuNjMzMjYgMy4yMDU1OTI0NSwzOTYuNzIyMTQ4IEMxLjExMzkwNzMzLDM5Mi44MTEwMzcgOS4wOTk3NjE4NGUtMTMsMzg4LjkzMTYwMiA5LjA4NjY2MDNlLTEzLDM3OC4yMzMzNzggTDkuMDkxNjc0NTRlLTEzLDI3My4xMDg0NjggQzIuNTA2MTczNzMsMjYyLjUzMTkwMSAxMi4wMTExNDA1LDI1NC42NjMxNDQgMjMuMzUzOTU0LDI1NC42NjMxNDQgTDYwLjk0MTYzMTUsMjU0LjY2MzE0NCBDNjkuNDc3MjExMSwyNTQuNjYzMTQ0IDc3LjM3MDUxNzIsMjU5LjE5NjU2MiA4MS42NzE3MTgzLDI2Ni41NjkxOTkgTDE2NC43NjU5Myw0MDkgWiBNNDcxLjIwNDE4OCwzMjUuMzE0MTIxIEM1MzguMjE1NzAyLDMyNS4zMTQxMjEgNTkyLjUzOTI2NywyNzAuOTU5OTQxIDU5Mi41MzkyNjcsMjAzLjkxMDY2MyBDNTkyLjUzOTI2NywxMzYuODYxMzg0IDUzOC4yMTU3MDIsODIuNTA3MjA0NiA0NzEuMjA0MTg4LDgyLjUwNzIwNDYgQzQwNC4xOTI2NzUsODIuNTA3MjA0NiAzNDkuODY5MTEsMTM2Ljg2MTM4NCAzNDkuODY5MTEsMjAzLjkxMDY2MyBDMzQ5Ljg2OTExLDI3MC45NTk5NDEgNDA0LjE5MjY3NSwzMjUuMzE0MTIxIDQ3MS4yMDQxODgsMzI1LjMxNDEyMSBaIE03NDkuNTI3MTksNDA4Ljg3NTYxNiBDNzQ5LjQ5NDI4Myw0MDguODc1Nzg2IDc0OS40NjEzNzQsNDA4Ljg3NTg4OCA3NDkuNDI4NDY2LDQwOC44NzU5MjIgQzczNi4xNzM2MzksNDA4Ljg4OTgxMiA3MjUuNDE3MjIsMzk4LjE1NTkxMSA3MjUuNDAzMzMsMzg0LjkwMTA4NSBMNzI1LjAyNTE3NSwyNC4wMjUxNDkxIEM3MjUuMDI1MTY3LDI0LjAxNjc2NjEgNzI1LjAyNTE2MiwyNC4wMDgzODMgNzI1LjAyNTE2MiwyNCBDNzI1LjAyNTE2MiwxMC43NDUxNjYgNzM1Ljc3MDMyOCw1Ljk4NzU4NzE4ZS0xNSA3NDkuMDI1MTYyLDAgTDc4NCwwIEM3OTcuMjU0ODM0LC0yLjQzNDg3MzVlLTE1IDgwOCwxMC43NDUxNjYgODA4LDI0IEw4MDgsMzg0LjY5NzM2NiBDODA4LDM5Ny45MDM4NSA3OTcuMzMwMTgyLDQwOC42Mjg4ODIgNzg0LjEyMzg3Myw0MDguNjk3MDQ2IEw3NDkuNTI3MTksNDA4Ljg3NTYxNiBaIiBpZD0iQ29tYmluZWQtU2hhcGUiIGZpbGw9IiM0RjQ2RTUiPjwvcGF0aD4KICAgIDwvZz4KPC9zdmc+" />
            <h1>Koi Ecommerce</h1>
            <form method="post" action="options.php">

        <?php
            settings_fields( 'koi-ecommerce-settings' );
            do_settings_sections( 'koi-ecommerce-settings' );
        ?>

        <table class="form-table">
            <tr>
                <th scope="row">Koi URL</th>
                <td><input type="text" name="koi_url" value="<?php echo esc_attr(get_option( 'koi_url' )); ?>"/></td>
            </tr>
            <tr>
                <th scope="row">Front End Token</th>
                <td><input type="text" name="koi_front_token" value="<?php echo esc_attr(get_option( 'koi_front_token' )); ?>"/></td>
            </tr>
            <tr>
                <th scope="row">Back End Token</th>
                <td><input type="text" name="koi_back_token" value="<?php echo esc_attr(get_option( 'koi_back_token' )); ?>"/></td>
            </tr>
        </table>

       <?php submit_button(); ?>
        </form></div>
    <?php }
}