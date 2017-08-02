<?php
/**
 * Template Name: Home
 */

namespace Yoast\YoastCom\Theme;
?>
<?php get_header(); ?>

<?php get_template_part( 'html_includes/siteheader', array( 'home' => true ) ); ?>

<div class="site">

	<main role="main">

		<?php
		$conference_banner = get_theme_option( 'yoastcon_2017_banner', 'child-settings' );
		if ( $conference_banner ) {
			get_template_part( 'html_includes/partials/conference-banner' );
		}
		?>

		<section class="row iceberg">
			<?php get_template_part( 'html_includes/partials/promoblocks-homepage' ); ?>
		</section>

		<hr class="hr--no-pointer" data-push-sticky>

		<div class="rowholder">
			<section class="row iceberg theme-courses">

				<h2><a href="<?php echo url_plugin_overview(); ?>"><?php _e( 'WordPress plugins by Yoast', 'yoastcom' ); ?></a></h2>
				<?php get_template_part( 'html_includes/partials/more-plugin' ); ?>
			</section>
		</div>

		<hr>

		<div class="rowholder">
			<section class="row">

				<h2 class="color-courses--secondary"><a class="color-courses--secondary" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"><?php _e( 'Recent posts on our SEO blog', 'yoastcom' ); ?></a></h2>

				<?php get_template_part( 'html_includes/partials/recent-articles', array( 'class1' => 'theme-courses--secondary color-courses--secondary', 'class2' => 'color-courses--secondary' ) ); ?>

			</section>
		</div>

		<?php get_template_part( 'html_includes/partials/newsletter-subscribe', array( 'class' => 'theme-seo-blog announcement--pointer-top island' ) ); ?>

		<div class="rowholder">
			<section class="row island iceberg theme-courses">
				<h2><?php _e( 'Bundle products and save money!', 'yoastcom' ); ?></h2>
				<?php get_template_part( 'html_includes/partials/more-bundleplugin', array( 'class1' => 'color-courses', 'class2' => 'color-cta' ) ); ?>
			</section>
		</div>

		<?php get_template_part( 'html_includes/partials/announcement', array(
			'class' => 'announcement--pointer theme-plugins',
			'text'  => __( 'Yoast is all about improving the web. We make websites work!  You can learn how to optimize your website yourself in our Academy &raquo;', 'yoastcom' ),
			'url'   => url_academy_overview(),
			'icon'  => 'graduation-cap',
		) ); ?>

		<div class="rowholder">
			<section class="row island iceberg">
				<h2 class="color-courses"><?php _e( 'Browse our SEO blog categories', 'yoastcom' ); ?></h2>
				<?php get_template_part( 'html_includes/partials/more-categories', array( 'class' => 'color-courses' ) ); ?>
			</section>
		</div>

		<hr class="hr--no-pointer">

	</main>

	<div class="rowholder">
		<?php get_template_part( 'html_includes/fullfooter' ); ?>
		</div>

</div>

<?php get_footer(); ?>
