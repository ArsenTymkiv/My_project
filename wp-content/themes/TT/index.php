<?php get_header(); ?>
<main>
    <div class="content">
        <h1>Main Content</h1>
    <?php if (have_posts()) : while (have_posts()) : the_post();?>
    <h1> <?php the_title(); ?> </h1>
    <h4>Posted on <?php the_time('F js, Y')?></h4>
    <p> <?php the_content(__('(more...)')); ?></p>
    <hr> <?php endwhile;else: ?><hr/>
     <p><?php _e('Sorry, no posts') ?></p> <?php endif;?>

  </div>
    <?php get_sidebar(); ?>
</main>
<div class="delimetr"></div>
<?php get_footer(); ?>
</main>