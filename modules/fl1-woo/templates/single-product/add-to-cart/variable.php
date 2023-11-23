<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Variations
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $post, $woocommerce, $product;

$variations = $product->get_available_variations();
$product_type = $product->get_type();

$attributes = get_post_meta($product->get_id() , '_product_attributes');
foreach($attributes[0] as $key => $value) {
    $the_attribute = $attributes[0][$key]['name'];
}

?>

<div class="wc-variable">
    <h4><?php echo $the_attribute; ?></h4>
    <p>Pick at least one option and how many.</p>

    <?php
        $variation_count = 1;
        foreach($variations as $key => $value):

            // Handle price
            if(!empty($value['price_html'])) {
                $price_html = $value['price_html'];
            } else { 
                $price_html = '<span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>'.number_format((float)$value['display_price'], 2, '.', '').'</span></span>';
            }

            // Max quantity
            $max_qty = $value['max_qty'];

            // custom or pre-defined attributes?
            if(!empty($value['attributes'])) {
                foreach ($value['attributes'] as $attr_key => $attr_value) {
                    $attribute_slug = $attr_key;
                    
                    $attribute_name = $attr_value;
                    if (strpos($attr_value, ' -') !== false) {
                        $attribute_name = substr(ucfirst($attr_value), 0, strpos(ucfirst($attr_value), " -"));
                    }
                }

                $variation_price = $value['display_regular_price'];

            } else {
                $attribute_name = implode('/', $value['attributes']);
                $variation_price_html = $price_html;
            }

            // Stock
            $out_of_stock = '';
            $out_of_stock_tooltip = '';
            if($value['is_in_stock'] != 1) { 
                $out_of_stock = ' wc-out-of-stock tooltip';
                $out_of_stock_tooltip = ' data-tooltipster=\'{"side":"top"}\' title="Sold out"';
            }

            // Description
            $variation_description = '';
            if(!empty($value['variation_description'])) {
                $variation_description = '<span class="variation-description">'.strip_tags($value['variation_description']).'</span>';
            }
    ?>
        <div class="wc-variation<?php echo $out_of_stock; ?>"<?php echo $out_of_stock_tooltip; ?>>
            <input id="<?php echo $value['variation_id']; ?>" type="checkbox" name="wc_variation" value=""
            data-product-id="<?php echo $post->ID; ?>"
            data-variation-id="<?php echo $value['variation_id']; ?>"
            data-variation-qty="1"
            data-variation-name="<?php echo $attribute_name; ?>"
            data-variation-slug="<?php echo $attribute_slug; ?>"
            data-variation-price="<?php echo $variation_price; ?>">

            <label for="<?php echo $value['variation_id']; ?>">
                <div class="wc-variation-meta">
                    <h5><?php echo $attribute_name;?><?php echo $variation_description;?></h5>
                </div><!-- wc-variation-meta -->

                <div class="wc-variation-quantity">
                    <input type="number" name="wc_variation_quantity" value="1" min="1" max="<?php echo $max_qty; ?>">
                </div><!-- wc-variation-quantity -->

                <div class="variation-price">
                    £<?php echo $variation_price; ?>
                </div>
            </label>
        </div><!-- wc-variation -->
    <?php $variation_count++; endforeach; ?>

</div><!-- wc-variable -->
