<?php
/**
 * Template Name: Custom
 */
get_header(); ?>



<?php
$taxonomy = 'category'; // Here, you can pass custom taxonomy also.
$args = array(
    'taxonomy' => $taxonomy
);

// Get All categories.
$categories = get_categories($args);


foreach($categories as $category) {
    echo '<li>
            <a href="' . get_term_link($category) . '" title="' . sprintf(__("View all posts in %s"), $category->name) . '" ' . '>' . $category->name . '</a>
         </li> ';

}

?>








<?php get_footer(); ?>