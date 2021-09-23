<html>
<head>
    <?php wp_head(); ?>
    <meta charset="utf-8" />
    <title>Мой блог</title>
    <link href="style.css" rel="stylesheet" type="text/css" />

</head>
<body <?php body_class(); ?>>
<div id="top">
<div class="container">
    <a href="/" class="logo">LOGO</a>
    <nav>
        <?php
        wp_nav_menu(array(
           'theme_location' => 'header-menu',
        ));
        ?>
       <!-- <ul>
            <li><a href="">Home</a></li>
            <li><a href="">Product catalog</a></li>
            <li><a href="">About</a></li>
            <li><a href="">Contact</a></li>
        </ul>-->
    </nav>
</div>
</div>
