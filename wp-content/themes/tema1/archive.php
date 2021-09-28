<h1 class="my-4 page-title 11111111111111111113">
 <?php the_post();?>
    <h2 class="card-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
    <div class="card mb-4">
        <a href="<?php the_permalink();?>"><img class="card-img-top  232" src="<?php echo get_the_post_thumbnail_url(null, 'full'); ?>" alt="<?php the_title();?>"></a>
        <div class="card-body">
            <p class="card-text"><small class="text-muted"><?php the_time('j F Y');?></small> <?php the_tags('');?></p>
            <?php the_content('');?>
            <a href="<?php the_permalink();?>" class="btn btn-primary shadow-none">Читать далее →</a>
        </div>
    </div>

