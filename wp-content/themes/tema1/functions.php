<?php

function it_blog_style_frontend() {
wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
wp_enqueue_style('styles', get_stylesheet_directory_uri() . '/css/style.css');
}

add_action('wp_enqueue_scripts', 'it_blog_style_frontend');
function it_blog_include_myscript(){
wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.3.1.slim.min.js', '', '1.0', false);
wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', '', '1.0', false);
}

add_action('wp_enqueue_scripts', 'it_blog_include_myscript');
//Регистрация виджетов
if (function_exists('register_sidebar')){
    $i=1;
    register_sidebar(array(
        'name' => sprintf(__('Sidebar %d'), $i ),
        'id' => "sidebar-$i",
        'before_widget' => '<div class="card my-4 widget">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<h5 class="card-header">',
        'after_title'   => '</h5><div class="card-body">',
    ));
}
//поддержка миниатюр в записях.
add_theme_support('post-thumbnails');
//регистрируем меню
register_nav_menus(array(
    'top'  => 'Верхнее меню', // идентификатор меню и название
    'menu'            => '', // (string) название выводимого меню (указывается в админке при создании меню, приоритетнее, чем указанное местоположение theme_location - если указано, то параметр theme_location игнорируется)
    'container'       => 'div', // (string) контейнер меню. Обёртка ul. Указывается тег контейнера (по умолчанию тег div)
    'container_class' => '', // (string) class контейнера (div тега)
    'container_id'    => '', // (string) id контейнера (div тега)
    'menu_class'      => 'menu', // (string) class самого меню (ul тега)
    'menu_id'         => '', // (string) id самого меню (ul тега)
    'echo'            => true, // (boolean) выводить на экран или возвращать для обработки
    'fallback_cb'     => 'wp_page_menu', // (string) используемая (резервная) функция, если меню не существует (не удалось получить)
    'before'          => '', // (string) текст перед <a> каждой ссылки
    'after'           => '', // (string) текст после </a> каждой ссылки
    'link_before'     => '', // (string) текст перед анкором (текстом) ссылки
    'link_after'      => '', // (string) текст после анкора (текста) ссылки
    'depth'           => 0, // (integer) глубина вложенности (0 - неограничена, 2 - двухуровневое меню)
    'walker'          => '', // (object) класс собирающий меню. Default: new Walker_Nav_Menu
    'theme_location'  => '' // (string) расположение меню в шаблоне. (указывается ключ которым было зарегистрировано меню в функции register_nav_menus)
) );

/////////////////////////////////////////////////////////*WooCommerce*////////////////////////////////////////////////////////////////




add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

add_action('woocommerce_before_add_to_cart_button', 'text_single_product_summary');
function text_single_product_summary(){
    echo 'Contact number: ';

}


///////////////////////////////////*Shortcode*/////////////////////////////////////////////////////////////////////////////////////////

add_shortcode('number', 'show_number');
function show_number(){
    return ' +(380)123-45-67';
}



add_action('woocommerce_before_add_to_cart_button', 'number');
function number(){
    echo do_shortcode( '[number]');
}




