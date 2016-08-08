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
    <?php while ( have_posts() ) : the_post();
      //set up variables
      $services = get_field('services');
      $summary = get_field('summary');
      $service_1 = get_field('service_1');
      $service_2 = get_field('service_2');
      $service_3 = get_field('service_3');
      $service_4 = get_field('service_4');
      $service_image_1 = get_field('service_image_1');
      $service_image_2 = get_field('service_image_2');
      $service_image_3 = get_field('service_image_3');
      $service_image_4 = get_field('service_image_4');
      $service_summary_1 = get_field('service_summary_1');
      $service_summary_2 = get_field('service_summary_2');
      $service_summary_3 = get_field('service_summary_3');
      $service_summary_4 = get_field('service_summary_4');
      $size = "full";
    ?>

    <div class="about-hero about">
      <?php the_content(); ?>
    </div>
    <?php endwhile; // end of the loop. ?>
  </div><!-- .container -->
</section><!-- .home-page -->
<section class="about-content">
  <h4><?php echo $services ?></h4>
  <p class="center"><?php echo $summary ?></p>
  <div class="service-1">
    <?php echo wp_get_attachment_image( $service_image_1, $size); ?>
    <span class="service-feed-1">
      <h2><?php echo $service_1 ?></h2>
      <p><?php echo $service_summary_1 ?></p>
    </span>
  </div>

  <div class="service-2">
    <span class="service-feed-2">
      <h2><?php echo $service_2 ?></h2>
      <p><?php echo $service_summary_2 ?></p>
    </span>
    <?php echo wp_get_attachment_image( $service_image_2, $size); ?>

  </div>
  <div class="service-3">
    <?php echo wp_get_attachment_image( $service_image_3, $size); ?>
    <span class="service-feed-3">
      <h2><?php echo $service_3 ?></h2>
      <p><?php echo $service_summary_3 ?></p>
    </span>
  </div>
  <div class="service-4">
    <span class="service-feed-4">
      <h2><?php echo $service_4 ?></h2>
      <p><?php echo $service_summary_4 ?></p>
    </span>
    <?php echo wp_get_attachment_image( $service_image_4, $size); ?>
  </div>
</section>
<section>
  <div class="about-cta">
    <span>Intersted in working with us?</span>
    <button class="about-btn">Contact Us</button>
  </div>
</section>

<?php get_footer(); ?>
