<?php

/**
 * Settings HTML rendering static class.
 */
abstract class EddAcpSettingsHtml {

	/**
	 * Renders a generic HTML field.
	 * 
	 * @param  string $type The type of the field to render. This should translate to a static method for this class.
	 * @param  Aventura\Edd\AddToCartPopup\Core\Settings $settings The settings class instance.
	 * @param  string $id The ID of the option. Used to get the value to use when rendering the field.
	 * @return string The HTML output.
	 */
	public static function renderField($type, $settings, $id) {
		// Checks if method for this type exists, and the settings instance has the option with the given id.
		if (!method_exists(__CLASS__, $type) || !$settings->hasOption($id)) {
			return;
		}
		// Begin buffering
		ob_start();
		// Call the static method for the field's type, pasing the ID, option name and option value.
		echo self::$type($id, $settings->getSubValueOptionName($id), $settings->getSubValue($id));
		// Get the option description and output a label for the option field.
		$desc = $settings->getOption($id)->desc;
		printf('<label for="%1$s">%2$s</label>', esc_attr($id), esc_attr($desc));
		// Return the buffered output
		return ob_get_clean();
	}

	/**
	 * Renders a regular text field.
	 * 
	 * @param  string $id The field ID.
	 * @param  string $name The name attribute of the field.
	 * @param  string $value The value of the field.
	 * @return string The HTML output.
	 */
	public static function text($id, $name, $value) {
		ob_start(); ?>
		<input type="text" class="regular-text" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" />
		<?php return ob_get_clean();
	}

	/**
	 * Renders a colorpicker field.
	 * 
	 * @param  string $id The field ID.
	 * @param  string $name The name attribute of the field.
	 * @param  string $value The value of the field.
	 * @return string The HTML output.
	 */
	public static function colorpicker($id, $name, $value) {
		ob_start(); ?>
		<div class="edd-acp-colorpicker">
			<input type="hidden" class="edd-acp-colorpicker-value" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" />
			<div class="edd-acp-colorpicker-preview"></div>
		</div>
		<?php return ob_get_clean();
	}

	/**
	 * Renders a checkbox field.
	 * 
	 * @param  string $id The field ID.
	 * @param  string $name The name attribute of the field.
	 * @param  string $value The value of the field.
	 * @return string The HTML output.
	 */
	public static function checkbox($id, $name, $value) {
		ob_start(); ?>
		<input type="hidden" name="<?php echo esc_attr($name); ?>" value="0" />
		<input type="checkbox" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" <?php checked($value, '1'); ?> value="1" />
		<?php return ob_get_clean();
	}

}

// Get text domain
$textDomain = edd_acp()->getTextDomain()->getName();

// Get settings instance to add options to it
edd_acp()->getSettings()

	->addOption(
			'enabled',
			__('Enable Popup', $textDomain),
			__('Tick this box to enable the popup. Untick it to disable it.', $textDomain),
			'0',
			function($settings, $id, $args) {
				echo EddAcpSettingsHtml::renderField('checkbox', $settings, $id);
			}
		)

	->addOption(
			'textcolor',
			__('Text Color', $textDomain),
			__('Change the color of the text inside popup box.', $textDomain),
			'#000000',
			function($settings, $id, $args) {
				echo EddAcpSettingsHtml::renderField('colorpicker', $settings, $id);
			}
		)

	->addOption(
			'bgcolor',
			__('Background Color', $textDomain),
			__('Change the background color of the popup box.', $textDomain),
			'#ffffff',
			function($settings, $id, $args) {
				echo EddAcpSettingsHtml::renderField('colorpicker', $settings, $id);
			}
		)

	->addOption(
			'maintext',
			__('Popup Text', $textDomain),
			__('The text shown on the popup. The "%s" will be replaced by the name of the item added to the cart.', $textDomain),
			__('%s has been added to your cart!', $textDomain),
			function($settings, $id, $args) {
				echo EddAcpSettingsHtml::renderField('text', $settings, $id);
			}
		)

	->addOption(
			'pluraltext',
			__('Popup Plural Text', $textDomain),
			__('The text shown on the popup when multiple items have been added to the cart. The "%s" will be replaced with a comma separated list of the added item names.', $textDomain),
			__('%s have been added to your cart!', $textDomain),
			function($settings, $id, $args) {
				echo EddAcpSettingsHtml::renderField('text', $settings, $id);
			}
		)

	->addOption(
			'checkoutBtnText',
			__('Checkout Button Text', $textDomain),
			__('The text of the Checkout button.', $textDomain),
			__('Proceed to Checkout', $textDomain),
			function($settings, $id, $args) {
				echo EddAcpSettingsHtml::renderField('text', $settings, $id);
			}
		)

	->addOption(
			'continueBtnText',
			__('Continue Button Text', $textDomain),
			__('The text of the continue shopping button.', $textDomain),
			__('Continue shopping', $textDomain),
			function($settings, $id, $args) {
				echo EddAcpSettingsHtml::renderField('text', $settings, $id);
			}
		)
;