<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: form_upper_section
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'SPFTESTIMONIAL_Field_form_upper_section' ) ) {
	class SPFTESTIMONIAL_Field_form_upper_section extends SPFTESTIMONIAL_Fields {


		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}
		public function render() { ?>
		<div class="sp-tfree-help sp-tfree-upgrade tfree-upper-box-area">
	<div class="sp-tfree-pro-features">
		<h1 class="sp-tfree-text-center">Easily Collect and Display Testimonials on Your Website, Boost Sales!</h1>
		<p class="sp-tfree-text-center sp-tfree-pro-subtitle"> With this Testimonial Pro, you can quickly create multiple forms to collect Testimonials or Feedbacks from your website visitors and customers.</p>

		<div class="feature-section three-col">
			<div class="col">
				<div class="sp-tfree-feature">
					<h3><span class="dashicons dashicons-yes"></span>Collect New Testimonials Automatically</h3>
					<h3><span class="dashicons dashicons-yes"></span>Create Unlimited Testimonial Forms</h3>
					<h3><span class="dashicons dashicons-yes"></span>Email Notifications for New Testimonials</h3>
					<h3><span class="dashicons dashicons-yes"></span>Manage New Testimonials Before Publish</h3>
				</div>
			</div>
			<div class="col">
				<div class="sp-tfree-feature">
					<h3><span class="dashicons dashicons-yes"></span>Protect your Form against Spam</h3>
					<h3><span class="dashicons dashicons-yes"></span>Drag-and-Drop Testimonial Form Builder</h3>
					<h3><span class="dashicons dashicons-yes"></span>5+ Beautiful Layouts to Display Testimonials</h3>
					<h3><span class="dashicons dashicons-yes"></span>10+ Professionally Designed Themes</h3>
				</div>
			</div>
			<div class="col">
				<div class="sp-tfree-feature">
					<h3><span class="dashicons dashicons-yes"></span>Collect and Display Video Testimonials </h3>
					<h3><span class="dashicons dashicons-yes"></span>Add Testimonial Forms To Any Page or Post</h3>
					<h3><span class="dashicons dashicons-yes"></span>Rich Snippets or Structured Data compatible</h3>
					<h3><span class="dashicons dashicons-yes"></span>Regular Updates & Great Customer Support</h3>
				</div>
			</div>
		</div>
		<p  class="sp-tfree-text-center sp-tfree-pro-subtitle">Get access to all robust features and start collecting fresh testimonials right now.</p>
		<p class="sp-tfree-text-center"><a class="tfree-upgrade-btn" target="_blank" href="https://shapedplugin.com/plugin/testimonial-pro/?ref=1">Upgrade To Testimonial Pro Now!</a></p>

	</div>
	</div>
			<?php

		}
	}
}
