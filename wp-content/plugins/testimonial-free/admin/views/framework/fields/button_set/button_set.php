<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: button_set
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'SPFTESTIMONIAL_Field_button_set' ) ) {
	class SPFTESTIMONIAL_Field_button_set extends SPFTESTIMONIAL_Fields {


		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple' => false,
					'options'  => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo $this->field_before();

			if ( ! empty( $args['options'] ) ) {

				echo '<div class="spftestimonial-siblings spftestimonial--button-group" data-multiple="' . $args['multiple'] . '">';

				foreach ( $args['options'] as $key => $option ) {

					$type           = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra          = ( $args['multiple'] ) ? '[]' : '';
					$active         = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' spftestimonial--active' : '';
					$checked        = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' checked' : '';
					$pro_only_class = ( isset( $option['pro_only'] ) && $option['pro_only'] == true ) ? ' spftestimonial-pro-only' : '';
					echo '<div class="spftestimonial--sibling spftestimonial--button' . $active . $pro_only_class . '">';
					echo '<input type="' . $type . '" name="' . $this->field_name( $extra ) . '" value="' . $key . '"' . $this->field_attributes() . $checked . '/>';
					if ( isset( $option['option_name'] ) && ! empty( $option['option_name'] ) ) {
						echo $option['option_name'];
					} else {
						echo $option;
					}
					echo '</div>';

				}

				echo '</div>';

			}

			echo '<div class="clear"></div>';

			echo $this->field_after();

		}

	}
}
