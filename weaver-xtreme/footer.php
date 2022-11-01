<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
/**
 * The template for displaying the footer.
 *
 * Contains all content after the closing of the id=main div
 *
 */

weaverx_inject_area( 'prefooter' );        // put the prefooter optional area

if ( weaverx_getopt( 'footer_hide' ) != 'hide' && ! weaverx_is_checked_page_opt( '_pp_hide_footer' ) ) {
	$f_class = weaverx_area_class( 'footer', 'pad', '-trbl', 'margin-none' );
	?>
<footer id="colophon" <?php echo 'class="colophon ' . $f_class . '"';
echo weaverx_schema( 'footer' ); ?>>
	<div id="colophon-inside" class="block-inside">
	<?php // +since: 3.1.10: removed role='contentinfo' on footer

	if ( apply_filters( 'weaverx_replace_pb_area', 'footer' ) == 'footer' ) {
		if ( weaverx_has_widgetarea( 'footer-widget-area' ) ) {
			$p_class = weaverx_area_class( 'footer_sb', 'pad', '', 'margin-bottom' );
			weaverx_put_widgetarea( 'footer-widget-area', $p_class );
			weaverx_clear_both( 'footer-widget-area' );
		}

		/* ======== EXTRA HTML ======== */

		$extra = weaverx_get_per_page_value( '_pp_footer_html' );
		if ( '' == $extra ) {
			$extra = weaverx_getopt( 'footer_html_text' );
		}            // $extra is privileged user defined, not translated, no escaping necessary

		$hide = weaverx_getopt_default( 'footer_html_hide', 'hide-none' );

		if ( '' == $extra && is_customize_preview() ) {
			echo '<div id="footer-html" style="display:inline;"></div>';        // need the area there for customizer live preview
		} elseif ( '' != $extra && $hide != 'hide' && ! weaverx_is_checked_page_opt( '_pp_hide_footer_html' ) ) {

			$c_class = weaverx_area_class( 'footer_html', 'not-pad', '-none', 'margin-none' );    // no translations - no need for escaping

			// 'expand_footer_html' removed V5

			// see if the content is just an int, assume it to be a post id if so.
			// it seems that if a string has an int in it, the ( int ) cast will just cast that part, and throw away the rest.
			// we want an int and only an int, so we double cast to test, and that seems to work

			$post_idw = ( int ) trim( $extra );

			if ( ( string ) $post_idw == $extra && $post_idw != 0 ) {        // assume a number only is a post id to provide as replacement
				$builder_content = apply_filters( 'weaverx_page_builder_content', $post_idw, 'footer-html', $c_class );
				// $builder_content is generated by a page builder, and cannot be sanitized here or it is likely to break that content
				// We have to assume that the page builder generates properly sanitized content.
				weaverx_echo_sanitized_html( $builder_content );
			} else {
				?>
				<div id="footer-html" class="<?php echo esc_attr( $c_class ); ?>">
					<?php echo do_shortcode( $extra ); ?>
				</div> <!-- #footer-html -->
			<?php }
		}

		/* ======== COPYRIGHT AREA ======== */

		$date = getdate();
		$yearw = $date['year'];
		// 'expand_site-ig-wrap' removed V5
			echo '<div id="site-ig-wrap">';
		echo '<span id="site-info">' . "\n";

		$cp = weaverx_getopt( 'copyright' );
		$copy = '';
		if ( strlen( $cp ) > 0 ) {
			if ( '&nbsp;' != $cp )    // really leave nothing if specify blank
			{
				$copy = do_shortcode( $cp );
			}
		} else {
			$copy = '&copy;' . $yearw . ' - <a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) .
			        '" rel="home">' . get_bloginfo( 'name' ) . '</a>';
		}
		// $copy has already been escaped. It might contain user defined HTML tags, and does not need more sanitizing here.
		echo wp_kses_post( apply_filters( 'weaverx_copyright', $copy ) );
		?>
		</span> <!-- #site-info -->
		<?php
		$privacy_link = '';
		if ( function_exists( 'get_the_privacy_policy_link' ) ) {
			$privacy_link = get_the_privacy_policy_link( '', '' );
		}

		if ( ! weaverx_getopt( '_hide_poweredby' ) ) {
			$powered_by = '<span id="site-generator">'
			              . '<a href="' . esc_url( esc_attr__( '//wordpress.org/', 'weaver-xtreme' ) ) . '" title="'
			              . esc_attr__( 'Proudly powered by WordPress', 'weaver-xtreme' )
			              . '" target="_blank" rel="nofollow"><span style="font-size:120%;padding-top:2px;" class="genericon genericon-wordpress"></span> - '
			              . weaverx_site( '/weaver-xtreme', '', 'Weaver Xtreme Theme', false )
			              . $privacy_link . '</span> <!-- #site-generator -->';
			// Powered by may contain theme or user defined HTML code, so needs wp_kses_post instead of esc_html
			echo wp_kses_post( apply_filters( 'weaverx_poweredby', $powered_by ) );
		} else {
			echo '<span id="site-generator">';
			echo wp_kses_post( $privacy_link );
			echo '</span>';
		}
		weaverx_clear_both( 'site-generator' ); ?>
		</div><!-- #site-ig-wrap -->
		<?php weaverx_clear_both( 'site-ig-wrap' ); ?>
		</div></footer><!-- #colophon-inside, #colophon -->
		<?php
		weaverx_clear_both( 'colophon' );
	} else {    // end if not footer page builder replacement
		echo "</div></footer><!-- #colophon-inside, #colophon -->\n";
		weaverx_clear_both( 'colophon' );
	}
} // end if !hide_footer


do_action( 'weaverxplus_action', 'footer' );

weaverx_inject_area( 'fixedbottom' );

echo "</div><!-- /#wrapper --><div class='clear-wrapper-end' style='clear:both;'></div>\n";
weaverx_inject_area( 'postfooter' );        // and this is the end options insertion
do_action( 'weaverxplus_action', 'postfooter' );
echo "\n<a href=\"#page-top\" id=\"page-bottom\">&uarr;</a>\n";

if ( ! ( $content_h_ff = weaverx_getopt( 'content_h_font_family' ) ) ) {
	$content_h_ff = '0';
}

$font_size = weaverx_getopt_default( 'content_h_font_size', 'default' );
switch ( $font_size ) {
	case 'xxs-font-size':
		$h_fontmult = 0.625;
		break;
	case 'xs-font-size':
		$h_fontmult = 0.75;
		break;
	case 's-font-size':
		$h_fontmult = 0.875;
		break;
	case 'l-font-size':
		$h_fontmult = 1.125;
		break;
	case 'xl-font-size':
		$h_fontmult = 1.25;
		break;
	case 'xxl-font-size':
		$h_fontmult = 1.5;
		break;
	default:
		$h_fontmult = 1;
		break;
}


if ( isset( $GLOBALS['weaverx_sb_layout'] ) ) {
	$sb_layout = $GLOBALS['weaverx_sb_layout'];
} else {
	$sb_layout = 'none';
}

$local = array(
	'hideTip'             => ( weaverx_getopt( 'hide_tooltip' ) ) ? '1' : '0',
	'hFontFamily'         => $content_h_ff,
	'hFontMult'           => $h_fontmult,
	'sbLayout'            => $sb_layout,
	'flowColor'           => ( weaverx_getopt( 'flow_color' ) ) ? '1' : '0',
	'full_browser_height' =>
		( weaverx_getopt( 'full_browser_height' ) || weaverx_is_checked_page_opt( '_pp_full_browser_height' ) ) ? '1' : '0',
	'primary'             => ( weaverx_getopt( 'primary_eq_widgets' ) ) ? '1' : '0',    // #primary-widget-area
	'secondary'           => ( weaverx_getopt( 'secondary_eq_widgets' ) ) ? '1' : '0',  // '#secondary-widget-area',
	'top'                 => ( weaverx_getopt( 'top_eq_widgets' ) ) ? '1' : '0',        // '.widget-area-top',
	'bottom'              => ( weaverx_getopt( 'bottom_eq_widgets' ) ) ? '1' : '0',     // '.widget-area-bottom',
	'header_sb'           => ( weaverx_getopt( 'header_sb_eq_widgets' ) ) ? '1' : '0',  // '#header-widget-area',
	'footer_sb'           => ( weaverx_getopt( 'footer_sb_eq_widgets' ) ) ? '1' : '0',   // '#footer-widget-area',
);

wp_localize_script( 'weaver-xtreme-JSLibEnd', 'wvrxEndOpts', $local );      // in footer.php because don't know the values yet in functions.php

wp_footer();

weaverx_masonry( 'invoke-code' );
?>
</body>
</html>
