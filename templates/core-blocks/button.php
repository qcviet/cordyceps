<?php
/**
 * Block: Button
 *
 * @package cordyceps
 * @author biogreen
 * @since 2.0.0.
 */

$data = wp_parse_args($args, [
    'class' => '',
    'button_text' => '',
    'button_url'  => '',
    'button_link_target' => '_self',
    'button_link_rel' => '',
	'svg_icon_before' => '',
	'svg_icon_after' => '',
    'type' => 'primary',
]);

$_class = 'btn';
$_class .= !empty( $data['class'] ) ? esc_attr(' ' . $data['class'] ) : '';
$_class .= !empty( $data['type'] ) ? esc_attr(' btn-' . $data['type'] ) : ' btn-primary';
$_class .= !empty( $data['svg_icon_before'] ) ? ' has-icon has-before-icon' : '';
$_class .= !empty( $data['svg_icon_after'] ) ? ' has-icon has-after-icon' : '';

if ( !empty( $data['button_text'] ) ) : ?>
    <a class="<?php echo esc_attr($_class); ?>"
        href="<?php echo esc_url( $data['button_url'] ); ?>"
        target="<?php echo esc_attr( $data['button_link_target'] ); ?>"
        <?php if ( !empty( $data['button_link_rel'] ) ) : ?> rel="<?php echo esc_attr( $data['button_link_rel'] ); ?>"<?php endif; ?>
    >
        <?php if ( !empty( $data['svg_icon_before'] ) ) : ?>
            <span class="icon" aria-hidden="true"><?php echo $data['svg_icon_before']; ?></span>
        <?php endif; ?>
        <span class="text"><?php echo esc_html( $data['button_text'] ); ?></span>
        <?php if ( !empty( $data['svg_icon_after'] ) ) : ?>
            <span class="icon" aria-hidden="true"><?php echo $data['svg_icon_after']; ?></span>
        <?php endif; ?>
    </a>
<?php endif;
