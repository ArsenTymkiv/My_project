<?php get_header();?>
<?while(have_posts()):
    the_post();?>
    <h1 class="my-4 page-title"><?php echo wp_get_document_title(); ?></h1>
    <img class="img-fluid rounded" src="<?php echo get_the_post_thumbnail_url(null, 'full');?>" alt="<?php echo wp_get_document_title(); ?>">
    <p><small class="text-muted"><?the_time('j F Y');?> <?php the_tags('');?></small></p>
    <?php the_content();?>
    <?php comments_template(); ?>
<?endwhile;?>

<?php get_footer();?>
