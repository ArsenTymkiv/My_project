<?php get_header();?>
    <h1 class="my-4"></h1>
<?php if (have_posts()): while(have_posts()): the_post();?>
    <h2 class="card-title"><a href="<?the_permalink();?>"><?php echo wp_get_document_title(); ?></a></h2>
    <div class="card mb-4">
        <a href="<?the_permalink();?>"><img class="card-img-top" src="<?php echo get_the_post_thumbnail_url(null, 'full'); ?>" alt="<?php echo wp_get_document_title(); ?>"></a>
        <div class="card-body">
            <p class="card-text"><small class="text-muted"><?the_time('j F Y');?></small> <?php the_tags('');?></p>
            <?the_content('');?>
            <a href="<?the_permalink();?>" class="btn btn-primary shadow-none">Читать далее →</a>
        </div>
    </div>
<?php endwhile; else:?>
    <p>Записей не найдено.</p>
<?php endif;?>
<?php the_posts_pagination(array(
    'mid_size' => 4,
    'end_size' => 2,
));?>

<?php get_footer();?>