<?php get_header();?>

    <h1 class="my-4 777777777777"></h1>

<?php if (have_posts() ){
   // Підключення постів
    while (have_posts() ){
get_template_part('archive');
    }
}else{?>
    <p>Записей не найдено.</p>
<?php }?>

<?php the_posts_pagination(array(
    'mid_size' => 4,
    'end_size' => 2,
));?>



<?php get_footer();?>




