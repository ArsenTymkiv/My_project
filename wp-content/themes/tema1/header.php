<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo wp_get_document_title(); ?></title>
    <?php wp_head();?>
</head>
<body <?php body_class(); ?>>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/"></> ARS <small>- my project </small></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <?php wp_nav_menu('menu_class=main&theme_location=top');?>
                <?php
                ?>
                <!--  <li class="nav-item active">

                Регистрация виджетов    <a class="nav-link" href="/">Главная</a>
                 </li>
                 <li class="nav-item active">
                     <a class="nav-link" href="/about/">О нас</a>
                 </li>
                 <a class="nav-link" href="/service/">Сервис</a>
                 </li>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="/contacts/">Контакты</a>
                 </li>-->
            </ul>
        </div>
    </div>
</nav>
<main role="main" class="container">
    <div class="row">
        <div class="col-lg-8">