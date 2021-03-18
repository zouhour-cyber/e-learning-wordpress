<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: Shortcode
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'SPFTESTIMONIAL_Field_shortcode' ) ) {
	class SPFTESTIMONIAL_Field_shortcode extends SPFTESTIMONIAL_Fields {


		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}
		public function render() {

			$post_id = get_the_ID();
			echo ( ! empty( $post_id ) ) ? '<div class="spftestimonial-scode-wrap-side"><h3 class="spftestimonial-sc-title">Shortcode</h3> <p>Copy and paste this shortcode into your posts, pages or block editor:</p> <span class="spftestimonial-shortcode-selectable">[sp_testimonial_form id="' . $post_id . '"]</span> <button class="spftestimonial-copy"><div class="spftestimonial-tooltip">Copy</div><i class="fa fa-clone"></i></button></div><div class="spftestimonial-scode-wrap-side"><h3 class="spftestimonial-sc-title">Template Include</h3><p>Paste the PHP code into your template file:</p><span class="spftestimonial-shortcode-selectable">&lt;?php echo do_shortcode(\'[sp_testimonial_form id="' . $post_id . '"]\'); ?&gt;</span> <button class="spftestimonial-copy"><i class="fa fa-clone"></i><div class="spftestimonial-tooltip">Copy</div></button></div>' : '';
		}

	}
}
