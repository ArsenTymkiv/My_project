
<?php
/**
 * Template Name: Custom
 */


get_header(); ?>

        <h3><strong>Категорії постів:</strong></h3>


<?php
/////////Категорії постів
$taxonomy = 'category'; // Here, you can pass custom taxonomy also.
$args = array(
    'taxonomy' => $taxonomy,
);

// Get All categories.
$categories = get_categories($args);


foreach($categories as $category) {

    echo '<div class="description">';
        echo '<a href="' . get_term_link($category) . '" title="' . sprintf(__("View all posts in %s"), $category->name) . '" ' . '>' . $category->name . '</a><p>'. $category->description .'</p>';
    echo '</div>';

    //echo '<p>'. $category->description .'</p>';


}

?>

<br><br/>
<div class="center">
    <button id="btn1" class="buttonProps blue" onclick="toggleColor()">classList.toggle()</button>
</div>

<br><br/>




            <h3><strong>Категорії товарів:</strong></h3>


<?php
/////////Категорії товарів


$cat_args = get_terms( 'product_cat', array(
    'orderby'    => 'name',
    'order'      => 'asc',
    'hide_empty' => true
));

$product_categories = get_terms( 'product_cat', $cat_args );
//var_dump($product_categories);
if( !empty($product_categories) ){

    foreach ($product_categories as $category) {
        $cat_thumb_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
        $shop_catalog_img = wp_get_attachment_image_src( $cat_thumb_id, 'shop_catalog' );
        $term_link = get_term_link( $category, 'product_cat' );?>
     <div class="wr">
        <a href="<?php echo get_term_link($category); ?>"><img class="img_cat" src="<?php echo $shop_catalog_img[0]; ?>" alt="<?php echo $category->name; ?>" /></a>

         <?php

       echo '<div class="text_cat">';
       echo '<a href=.get_term_link($category). >';
       echo $category->name;
        echo '</a>';
        echo '</div>';
              ?> </div> <?php
    }







}
/*
 $args = array(
    'post_type' => 'page'
);
$query = new WP_Query;
$my_posts = $query->query($args);

foreach( $my_posts as $my_post ){
    echo '<p>'. $my_post->post_title .'</p>';
}
















?>
<?php
echo '<br><br/>';
echo '<br><br/>';
$my_posts = new WP_Query;
$myposts = $my_posts->query( array(
    'posts_per_page' => 2,
    'category_name' => 'TESTCAT'
) );
while ( $my_posts->have_posts() ) {
    $my_posts->the_post();

    the_title("....."); // выведем заголовок поста
}
// Возвращаем оригинальные данные поста. Сбрасываем $post.
wp_reset_postdata();







echo '<br><br/>';
echo '<br><br/>';
// создаем экземпляр
$my_posts = new WP_Query;

// делаем запрос
$myposts = $my_posts->query( array(
    'post_type' => 'page'
) );

// обрабатываем результат
foreach( $myposts as $pst ){
    echo " ".esc_html( $pst->post_title );
}













echo '<br><br/>';
echo '<br><br/>';




// задаем нужные нам критерии выборки данных из БД
$args = array(
    'posts_per_page' => 6,
    'order by' => 'comment_count'
);

$query = new WP_Query( $args, [ 'category_name' => 'TEST' ] );

// Цикл
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        echo '<li>' . get_the_title() . '</li>';
    }
} else {
    // Постов не найдено
}
// Возвращаем оригинальные данные поста. Сбрасываем $post.
wp_reset_postdata();











echo '<br><br/>';
echo '<br><br/>';

// запрос
$query = new WP_Query( $args ); ?>

<?php if ( $query->have_posts() ) : ?>

    <!-- пагинация -->

    <!-- цикл -->
    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <h2><?php the_title(); ?></h2>
    <?php endwhile; ?>
    <!-- конец цикла -->

    <!-- пагинация -->

    <?php wp_reset_postdata(); ?>

<?php else : ?>
    <p><?php esc_html_e( 'Нет постов по вашим критериям.' ); ?></p>
<?php endif; ?>

 <?php










*/
?>







<?php get_footer(); ?>