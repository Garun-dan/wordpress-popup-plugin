Pengembangan Plugin Pop-Up WordPress dengan WP Scaffold

1. Persiapan Awal

Sebelum mulai mengembangkan plugin, pastikan Anda memiliki:

Instalasi WordPress lokal (bisa menggunakan MAMP, XAMPP, atau LocalWP)

Composer dan Node.js terinstal di sistem Anda

WP CLI (WordPress Command Line Interface) untuk scaffolding

GitHub untuk menyimpan repositori kode

Editor kode seperti VS Code atau PHPStorm

2. Membuat Struktur Plugin dengan WP Scaffold

Jalankan perintah berikut untuk membuat plugin menggunakan WP Scaffold:

wp scaffold plugin wp-popup-plugin --plugin_name="WP PopUp Plugin" --author="Nama Anda" --author_uri="https://github.com/username" --plugin_uri="https://github.com/username/wp-popup-plugin"

Struktur direktori yang dihasilkan akan terlihat seperti ini:

wp-popup-plugin/
|-- includes/
|-- assets/
| |-- js/
| |-- css/
| |-- scss/
|-- src/
|-- templates/
|-- wp-popup-plugin.php
|-- README.md

3. Implementasi OOP dan Pola Singleton

Buka wp-popup-plugin.php, buat kelas utama plugin dengan pola Singleton:

if (!defined('ABSPATH')) {
exit; // Exit if accessed directly.
}

final class WP_Popup_Plugin {
private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->define_hooks();
    }

    private function define_hooks() {
        add_action('init', array($this, 'register_cpt_popup'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function register_cpt_popup() {
        register_post_type('wp_popup', array(
            'labels'      => array(
                'name'          => __('Pop Ups'),
                'singular_name' => __('Pop Up')
            ),
            'public'      => true,
            'has_archive' => false,
            'supports'    => array('title', 'editor', 'custom-fields'),
            'show_in_rest' => true
        ));
    }

    public function enqueue_scripts() {
        wp_enqueue_script('wp-popup-script', plugin_dir_url(__FILE__) . 'assets/js/popup.js', array('vue'), null, true);
        wp_enqueue_style('wp-popup-style', plugin_dir_url(__FILE__) . 'assets/css/popup.css');
    }

}

WP_Popup_Plugin::get_instance();

4. Menggunakan WordPress REST API

Tambahkan endpoint REST API agar hanya pengguna masuk yang dapat mengakses popup:

class WP_Popup_API {
public function \_\_construct() {
add_action('rest_api_init', array($this, 'register_api_endpoints'));
}

    public function register_api_endpoints() {
        register_rest_route('artistudio/v1', '/popup/', array(
            'methods'  => 'GET',
            'callback' => array($this, 'get_popup_data'),
            'permission_callback' => function () {
                return is_user_logged_in();
            }
        ));
    }

    public function get_popup_data() {
        $args = array('post_type' => 'wp_popup', 'posts_per_page' => -1);
        $query = new WP_Query($args);
        $popups = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $popups[] = array(
                    'title' => get_the_title(),
                    'content' => get_the_content(),
                    'page' => get_post_meta(get_the_ID(), 'page', true)
                );
            }
        }
        return $popups;
    }

}

new WP_Popup_API();

5. Menggunakan Vue JS untuk Pop-Up

Tambahkan Vue JS sebagai frontend pop-up.

Di dalam assets/js/popup.js:

new Vue({
el: '#popup-container',
data: {
showPopup: false,
popupContent: ''
},
created() {
fetch('/wp-json/artistudio/v1/popup')
.then(response => response.json())
.then(data => {
if (data.length > 0) {
this.popupContent = data[0].content;
this.showPopup = true;
}
});
}
});

Di dalam templates/popup-template.php:

<div id="popup-container" v-if="showPopup">
    <div class="popup-content">{{ popupContent }}</div>
    <button @click="showPopup = false">Close</button>
</div>

6. Styling dengan SASS

Tambahkan file assets/scss/popup.scss:

#popup-container {
position: fixed;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
background: white;
padding: 20px;
box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
display: none;
}
.popup-content {
font-size: 16px;
}

Jalankan kompilasi SASS:

sass assets/scss/popup.scss assets/css/popup.css

7. Mengunggah ke GitHub

git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/username/wp-popup-plugin.git
git push -u origin main

8. Instalasi Plugin di WordPress

Salin folder wp-popup-plugin ke dalam wp-content/plugins/.

Aktifkan plugin melalui Dashboard WordPress > Plugins.

Tambahkan konten pop-up melalui Custom Post Type yang telah dibuat.

Coba akses /wp-json/artistudio/v1/popup untuk melihat API.

Dengan langkah-langkah yang lebih terperinci ini, plugin pop-up WordPress Anda akan lebih terstruktur dan mudah digunakan. 🚀
