<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php
	elegant_description();
	elegant_keywords();
	elegant_canonical();

	/**
	 * Fires in the head, before {@see wp_head()} is called. This action can be used to
	 * insert elements into the beginning of the head before any styles or scripts.
	 *
	 * @since 1.0
	 */
	do_action( 'et_head_meta' );

	$template_directory_uri = get_template_directory_uri();
?>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	
	<link href="https://fonts.googleapis.com/css?family=Oswald|Rock+Salt|Ink+Free" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
	
	
<?php if (is_page(2670)): ?>
		<style media="screen">
/* ---------------------------------
SCSS Variables & Mixins
--------------------------------- */
.settings, .et_pb_pricing li span:before {
display: none;
}

.feature-carousel__list {
background-color: rgba(0, 0, 0, 0.4) !important;
border-radius: 10px;
box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);
color: #fff;
text-shadow: 2px 2px 4px black;
font-family: oswald;
}


/* ---------------------------------
Default Styles
--------------------------------- */
input[type="number"],
input[type="text"] {
margin: 0;
outline: 0;
border-radius: 0;
background: none;
vertical-align: middle;
-webkit-appearance: none;
}

button {
background: none;
border: none;
outline: none;
cursor: pointer;
}

/* ---------------------------------
Settings
--------------------------------- */
/* ---------------------------------
Preview Loading Animation
--------------------------------- */
.feature-wrap {
position: relative;
display: flex;
}
.feature-wrap.is-loading::before {
content: '';
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
background: #181818;
opacity: 0.3;
z-index: 3;
}
@media (max-width: 1024px) {
.feature-wrap {
  display: block;
}
}

.bounce-loading {
display: none;
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 80px;
z-index: 4;
}
.is-loading .bounce-loading {
display: block;
}

.bounce-loading__dot {
display: inline-block;
width: 18px;
height: 18px;
border-radius: 50%;
background: #ffffff;
animation: loadingContent 1.4s ease-in-out 0s infinite both;
}
.bounce-loading__dot.bounce-loading__dot--1 {
animation-delay: -0.32s;
}
.bounce-loading__dot.bounce-loading__dot--2 {
animation-delay: -0.16s;
}

@keyframes loadingContent {
0%, 80%, 100% {
  transform: scale(0);
}
40% {
  transform: scale(1);
}
}
#jem_heading{
  text-align:center;
  font-family:ink free;
  color:#fff;
  text-shadow:2px 2px 4px black;
  font-size:56px;
  margin-bottom:40px;
}
/* ---------------------------------
Feature Zoom
--------------------------------- */
.feature-zoom {
width: 100%;
height: 100vh;
z-index: 1;
}
@media (max-width: 1024px) {
.feature-zoom {
  height: 80vh;
}
}
@media (max-width: 550px) {
.feature-zoom {
  height: 50vh;
}
}

.feature-zoom__img {
position: relative;
width: 100%;
height: 100%;
background-repeat: no-repeat;
background-size: 100%;
background-position: 50% 50%;
transition: all 0.4s ease-in-out;
}
.feature-zoom__img::before {
content: "";
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
background: #e6e6e6;
z-index: -1;
}

/* ---------------------------------
Feature Carousel
--------------------------------- */
.feature-carousel {
position: absolute;
top: 30%;
right: 5rem;
max-width: 550px;
width: 100%;
transform: translatey(-50%);
z-index: 2;
}
@media (max-width: 1024px) {
.feature-carousel {
  position: relative;
  top: 0;
  right: auto;
  width: calc(100% - 5rem);
  margin: -3.75rem auto 0;
  transform: none;
}
}

.feature-carousel__list {
padding: 3.75rem 0;
background: #ffffff;
}

.feature-carousel__item {
padding: 0 3.75rem;
}
@media (max-width: 1024px) {
.feature-carousel__item {
  padding: 0 1.25rem;
}
}

.feature-carousel__btn {
position: relative;
width: 3.25rem;
height: 3.25rem;
margin: 0;
padding: 0;
}
.feature-carousel__btn::before {
content: "";
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
background: #ffffff;
border-radius: 50%;
transform: scale(0);
transition: transform 0.4s;
}
.feature-carousel__btn:hover::before {
transform: scale(1);
}
.feature-carousel__btn::after {
content: "";
display: block;
width: 12px;
height: 12px;
margin: auto;
border-top: 2px solid #181818;
border-left: 2px solid #181818;
transform: rotate(-45deg);
}
.feature-carousel__btn.feature-carousel__btn--prev, .feature-carousel__btn.feature-carousel__btn--next {
position: absolute;
top: -3.75rem;
}
@media (max-width: 1024px) {
.feature-carousel__btn.feature-carousel__btn--prev, .feature-carousel__btn.feature-carousel__btn--next {
  top: 50%;
}
}
.feature-carousel__btn.feature-carousel__btn--prev {
right: 5rem;
}
@media (max-width: 1024px) {
.feature-carousel__btn.feature-carousel__btn--prev {
  left: -2.5rem;
  right: 0;
}
}
.feature-carousel__btn.feature-carousel__btn--next {
right: 0;
}
.feature-carousel__btn.feature-carousel__btn--next::after {
transform: rotate(135deg);
}
@media (max-width: 1024px) {
.feature-carousel__btn.feature-carousel__btn--next {
  right: -2.5rem;
}
}
.feature-carousel__btn.slick-disabled::before, .feature-carousel__btn.slick-disabled::after {
opacity: 0.25;
}
@media (max-width: 550px) {
.feature-carousel__btn {
  width: 2.5rem;
  height: 2.5rem;
}
}

.feature-carousel__label {
display: flex;
align-items: center;
}

.feature-carousel__label-line {
display: inline-block;
width: 3.625rem;
height: 1px;
margin: 0 0.625rem;
background-color: #181818;
transform: scaleX(0);
transform-origin: 0 0;
transition: transform 0.4s ease-out 0.3s;
}
.slick-active .feature-carousel__label-line {
transform: scaleX(1);
}
@media (max-width: 550px) {
.feature-carousel__label-line {
  width: 3rem;
}
}

.feature-carousel__label-text {
font-size: 32px;
font-weight: bold;
color: #fff;
font-family: ink free;
text-shadow:2px 2px 4px black;
}
@media (max-width: 550px) {
.feature-carousel__label-text {
  font-size: 0.625rem;
}
}

.feature-carousel__description {
margin-top: 3.75rem;
font-size: 1.5rem;
line-height: 1.6;
}
@media (max-width: 1024px) {
.feature-carousel__description {
  font-size: 1.125rem;
}
}
@media (max-width: 550px) {
.feature-carousel__description {
  font-size: inherit;
}

}
.js-item-number {
  display:none;
}
.product_container {
  margin-bottom:40px;
}
</style>
		
<?php endif?>
	

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
	$product_tour_enabled = et_builder_is_product_tour_enabled();
	$page_container_style = $product_tour_enabled ? ' style="padding-top: 0px;"' : ''; ?>
	<div id="page-container"<?php echo et_core_intentionally_unescaped( $page_container_style, 'fixed_string' ); ?>>
<?php
	if ( $product_tour_enabled || is_page_template( 'page-template-blank.php' ) ) {
		return;
	}

	$et_secondary_nav_items = et_divi_get_top_nav_items();

	$et_phone_number = $et_secondary_nav_items->phone_number;

	$et_email = $et_secondary_nav_items->email;

	$et_contact_info_defined = $et_secondary_nav_items->contact_info_defined;

	$show_header_social_icons = $et_secondary_nav_items->show_header_social_icons;

	$et_secondary_nav = $et_secondary_nav_items->secondary_nav;

	$et_top_info_defined = $et_secondary_nav_items->top_info_defined;

	$et_slide_header = 'slide' === et_get_option( 'header_style', 'left' ) || 'fullscreen' === et_get_option( 'header_style', 'left' ) ? true : false;
?>

	<?php if ( $et_top_info_defined && ! $et_slide_header || is_customize_preview() ) : ?>
		<?php ob_start(); ?>
		<div id="top-header"<?php echo $et_top_info_defined ? '' : 'style="display: none;"'; ?>>
			<div class="container clearfix">

			<?php if ( $et_contact_info_defined ) : ?>

				<div id="et-info">
				<?php if ( '' !== ( $et_phone_number = et_get_option( 'phone_number' ) ) ) : ?>
					<span id="et-info-phone"><?php echo et_core_esc_previously( et_sanitize_html_input_text( $et_phone_number ) ); ?></span>
				<?php endif; ?>

				<?php if ( '' !== ( $et_email = et_get_option( 'header_email' ) ) ) : ?>
					<a href="<?php echo esc_attr( 'mailto:' . $et_email ); ?>"><span id="et-info-email"><?php echo esc_html( $et_email ); ?></span></a>
				<?php endif; ?>

				<?php
				if ( true === $show_header_social_icons ) {
					get_template_part( 'includes/social_icons', 'header' );
				} ?>
				</div> <!-- #et-info -->

			<?php endif; // true === $et_contact_info_defined ?>

				<div id="et-secondary-menu">
				<?php
					if ( ! $et_contact_info_defined && true === $show_header_social_icons ) {
						get_template_part( 'includes/social_icons', 'header' );
					} else if ( $et_contact_info_defined && true === $show_header_social_icons ) {
						ob_start();

						get_template_part( 'includes/social_icons', 'header' );

						$duplicate_social_icons = ob_get_contents();

						ob_end_clean();

						printf(
							'<div class="et_duplicate_social_icons">
								%1$s
							</div>',
							et_core_esc_previously( $duplicate_social_icons )
						);
					}

					if ( '' !== $et_secondary_nav ) {
						echo et_core_esc_wp( $et_secondary_nav );
					}

					et_show_cart_total();
				?>
				</div> <!-- #et-secondary-menu -->

			</div> <!-- .container -->
		</div> <!-- #top-header -->
	<?php
		$top_header = ob_get_clean();

		/**
		 * Filters the HTML output for the top header.
		 *
		 * @since 3.10
		 *
		 * @param string $top_header
		 */
		echo et_core_intentionally_unescaped( apply_filters( 'et_html_top_header', $top_header ), 'html' );
	?>
	<?php endif; // true ==== $et_top_info_defined ?>

	<?php if ( $et_slide_header || is_customize_preview() ) : ?>
		<?php ob_start(); ?>
		<div class="et_slide_in_menu_container">
			<?php if ( 'fullscreen' === et_get_option( 'header_style', 'left' ) || is_customize_preview() ) { ?>
				<span class="mobile_menu_bar et_toggle_fullscreen_menu"></span>
			<?php } ?>

			<?php
				if ( $et_contact_info_defined || true === $show_header_social_icons || false !== et_get_option( 'show_search_icon', true ) || class_exists( 'woocommerce' ) || is_customize_preview() ) { ?>
					<div class="et_slide_menu_top">

					<?php if ( 'fullscreen' === et_get_option( 'header_style', 'left' ) ) { ?>
						<div class="et_pb_top_menu_inner">
					<?php } ?>
			<?php }

				if ( true === $show_header_social_icons ) {
					get_template_part( 'includes/social_icons', 'header' );
				}

				et_show_cart_total();
			?>
			<?php if ( false !== et_get_option( 'show_search_icon', true ) || is_customize_preview() ) : ?>
				<?php if ( 'fullscreen' !== et_get_option( 'header_style', 'left' ) ) { ?>
					<div class="clear"></div>
				<?php } ?>
				<form role="search" method="get" class="et-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						printf( '<input type="search" class="et-search-field" placeholder="%1$s" value="%2$s" name="s" title="%3$s" />',
							esc_attr__( 'Search &hellip;', 'Divi' ),
							get_search_query(),
							esc_attr__( 'Search for:', 'Divi' )
						);
					?>
					<button type="submit" id="searchsubmit_header"></button>
				</form>
			<?php endif; // true === et_get_option( 'show_search_icon', false ) ?>

			<?php if ( $et_contact_info_defined ) : ?>

				<div id="et-info">
				<?php if ( '' !== ( $et_phone_number = et_get_option( 'phone_number' ) ) ) : ?>
					<span id="et-info-phone"><?php echo et_core_esc_previously( et_sanitize_html_input_text( $et_phone_number ) ); ?></span>
				<?php endif; ?>

				<?php if ( '' !== ( $et_email = et_get_option( 'header_email' ) ) ) : ?>
					<a href="<?php echo esc_attr( 'mailto:' . $et_email ); ?>"><span id="et-info-email"><?php echo esc_html( $et_email ); ?></span></a>
				<?php endif; ?>
				</div> <!-- #et-info -->

			<?php endif; // true === $et_contact_info_defined ?>
			<?php if ( $et_contact_info_defined || true === $show_header_social_icons || false !== et_get_option( 'show_search_icon', true ) || class_exists( 'woocommerce' ) || is_customize_preview() ) { ?>
				<?php if ( 'fullscreen' === et_get_option( 'header_style', 'left' ) ) { ?>
					</div> <!-- .et_pb_top_menu_inner -->
				<?php } ?>

				</div> <!-- .et_slide_menu_top -->
			<?php } ?>

			<div class="et_pb_fullscreen_nav_container">
				<?php
					$slide_nav = '';
					$slide_menu_class = 'et_mobile_menu';

					$slide_nav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'echo' => false, 'items_wrap' => '%3$s' ) );
					$slide_nav .= wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => '', 'fallback_cb' => '', 'echo' => false, 'items_wrap' => '%3$s' ) );
				?>

				<ul id="mobile_menu_slide" class="<?php echo esc_attr( $slide_menu_class ); ?>">

				<?php
					if ( '' === $slide_nav ) :
				?>
						<?php if ( 'on' === et_get_option( 'divi_home_link' ) ) { ?>
							<li <?php if ( is_home() ) echo( 'class="current_page_item"' ); ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'Divi' ); ?></a></li>
						<?php }; ?>

						<?php show_page_menu( $slide_menu_class, false, false ); ?>
						<?php show_categories_menu( $slide_menu_class, false ); ?>
				<?php
					else :
						echo et_core_esc_wp( $slide_nav ) ;
					endif;
				?>

				</ul>
			</div>
		</div>
	<?php
		$slide_header = ob_get_clean();

		/**
		 * Filters the HTML output for the slide header.
		 *
		 * @since 3.10
		 *
		 * @param string $top_header
		 */
		echo et_core_intentionally_unescaped( apply_filters( 'et_html_slide_header', $slide_header ), 'html' );
	?>
	<?php endif; // true ==== $et_slide_header ?>

	<?php ob_start(); ?>
		<header id="main-header" data-height-onload="<?php echo esc_attr( et_get_option( 'menu_height', '66' ) ); ?>">
			<div class="container clearfix et_menu_container">
			<?php
				$logo = ( $user_logo = et_get_option( 'divi_logo' ) ) && ! empty( $user_logo )
					? $user_logo
					: $template_directory_uri . '/images/logo.png';

				ob_start();
			?>
				<div class="logo_container">
					<span class="logo_helper"></span>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php echo esc_attr( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" id="logo" data-height-percentage="<?php echo esc_attr( et_get_option( 'logo_height', '54' ) ); ?>" />
					</a>
				</div>
			<?php
				$logo_container = ob_get_clean();

				/**
				 * Filters the HTML output for the logo container.
				 *
				 * @since 3.10
				 *
				 * @param string $logo_container
				 */
				echo et_core_intentionally_unescaped( apply_filters( 'et_html_logo_container', $logo_container ), 'html' );
			?>
				<div id="et-top-navigation" data-height="<?php echo esc_attr( et_get_option( 'menu_height', '66' ) ); ?>" data-fixed-height="<?php echo esc_attr( et_get_option( 'minimized_menu_height', '40' ) ); ?>">
					<?php if ( ! $et_slide_header || is_customize_preview() ) : ?>
						<nav id="top-menu-nav">
						<?php
							$menuClass = 'nav';
							if ( 'on' === et_get_option( 'divi_disable_toptier' ) ) $menuClass .= ' et_disable_top_tier';
							$primaryNav = '';

							$primaryNav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => 'top-menu', 'echo' => false ) );
							if ( empty( $primaryNav ) ) :
						?>
							<ul id="top-menu" class="<?php echo esc_attr( $menuClass ); ?>">
								<?php if ( 'on' === et_get_option( 'divi_home_link' ) ) { ?>
									<li <?php if ( is_home() ) echo( 'class="current_page_item"' ); ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'Divi' ); ?></a></li>
								<?php }; ?>

								<?php show_page_menu( $menuClass, false, false ); ?>
								<?php show_categories_menu( $menuClass, false ); ?>
							</ul>
						<?php
							else :
								echo et_core_esc_wp( $primaryNav );
							endif;
						?>
						</nav>
					<?php endif; ?>

					<?php
					if ( ! $et_top_info_defined && ( ! $et_slide_header || is_customize_preview() ) ) {
						et_show_cart_total( array(
							'no_text' => true,
						) );
					}
					?>

					<?php if ( $et_slide_header || is_customize_preview() ) : ?>
						<span class="mobile_menu_bar et_pb_header_toggle et_toggle_<?php echo esc_attr( et_get_option( 'header_style', 'left' ) ); ?>_menu"></span>
					<?php endif; ?>

					<?php if ( ( false !== et_get_option( 'show_search_icon', true ) && ! $et_slide_header ) || is_customize_preview() ) : ?>
					<div id="et_top_search">
						<span id="et_search_icon"></span>
					</div>
					<?php endif; // true === et_get_option( 'show_search_icon', false ) ?>

					<?php

					/**
					 * Fires at the end of the 'et-top-navigation' element, just before its closing tag.
					 *
					 * @since 1.0
					 */
					do_action( 'et_header_top' );

					?>
				</div> <!-- #et-top-navigation -->
			</div> <!-- .container -->
			<div class="et_search_outer">
				<div class="container et_search_form_container">
					<form role="search" method="get" class="et-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						printf( '<input type="search" class="et-search-field" placeholder="%1$s" value="%2$s" name="s" title="%3$s" />',
							esc_attr__( 'Search &hellip;', 'Divi' ),
							get_search_query(),
							esc_attr__( 'Search for:', 'Divi' )
						);
					?>
					</form>
					<span class="et_close_search_field"></span>
				</div>
			</div>
		</header> <!-- #main-header -->
	<?php
		$main_header = ob_get_clean();

		/**
		 * Filters the HTML output for the main header.
		 *
		 * @since 3.10
		 *
		 * @param string $main_header
		 */
		echo et_core_intentionally_unescaped( apply_filters( 'et_html_main_header', $main_header ), 'html' );
	?>
		<div id="et-main-area">
	<?php
		/**
		 * Fires after the header, before the main content is output.
		 *
		 * @since 3.10
		 */
		do_action( 'et_before_main_content' );
