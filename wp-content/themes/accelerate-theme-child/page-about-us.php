<?php
/**
 * The template for displaying the about us page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Accelerate Marketing
 * @since Accelerate Marketing 1.0
 */

get_header(); ?>
<section class="about-page">
  <div class="site-content flex">
    <?php while ( have_posts() ) : the_post(); ?>
    <div class="about-hero about">
      <?php the_content(); ?>
    </div>
    <?php endwhile; // end of the loop. ?>
  </div><!-- .container -->
</section><!-- .home-page -->


<?php get_footer(); ?>
