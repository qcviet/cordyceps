<?php
/**
 * Core Block: Image
 *
 * @package cordyceps
 * @author biogreen
 * @since 2.0.0
 */

$data = wp_parse_args($args, [
    'class' => '',
    'image_id' => '',
    'image_size' => 'large',
    'image_class' => '',
    'lazyload' => true,
]);

$_class = 'image';
$_class .= !empty( $data['class'] ) ? esc_attr(' ' . $data['class']) : '';
$_class .= !empty( $data['image_size'] ) ? esc_attr(' has-image-size-' . $data['image_size']) : '';

if ( !empty( $data['image_id'] ) ) :
    $_image_class = 'image__img';
    $_image_class .= !empty( $data['image_class'] ) ? esc_attr(' ' . $data['image_class']) : '';

    if ( ! $data['lazyload'] ) {
        $_image_class .= ' no-lazy';
    }
?>
    <figure class="<?php echo esc_attr( $_class ); ?>">
        <?php echo wp_get_attachment_image( $data['image_id'], $data['image_size'], null, [
        'class' => $_image_class
    ] ); ?>
    </figure>
<?php endif;

