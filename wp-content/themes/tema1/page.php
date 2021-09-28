<?php get_header();?>

<?php while(have_posts()){
    the_post();?>
    <h1 class="my-4 page-title 5555555555555"><?php echo wp_get_document_title(); ?></h1>
    <img class="img-fluid rounded" src="<?php echo get_the_post_thumbnail_url(null, 'full');?>" alt="<?php echo wp_get_document_title(); ?>">
    <p><small class="text-muted 11111111"><?php echo the_time('j F Y');?> <?php echo the_tags('');?></small></p>

    <?php the_content();?>
    <?php comments_template(); ?>
<?php }?>

<?php get_footer();?>