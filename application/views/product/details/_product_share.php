<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row-custom product-share">
    <label><?php echo trans("share"); ?>:</label>
    <ul>
        <li>
            <a href="javascript:void(0)" onclick='window.open("https://vk.com/share.php?url=<?php echo generate_product_url($product); ?>", "Share This Post", "width=640,height=450");return false'>
                <i class="icon-vk"></i>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick='window.open("https://t.me/share/url?url=<?php echo generate_product_url($product); ?>&amp;text=<?php echo get_product_title($product); ?>", "Share This Post", "width=640,height=450");return false'>
                <i class="icon-telegram"></i>
            </a>
        </li>
        <li>
            <a href="https://api.whatsapp.com/send?text=<?php echo str_replace("&", "", get_product_title($product)); ?> - <?php echo generate_product_url($product); ?>" target="_blank">
                <i class="icon-whatsapp"></i>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick='window.open("http://pinterest.com/pin/create/button/?url=<?php echo generate_product_url($product); ?>&amp;media=<?php echo get_product_image($product->id, 'image_default'); ?>", "Share This Post", "width=640,height=450");return false'>
                <i class="icon-pinterest"></i>
            </a>
        </li>
        
    </ul>
</div>


