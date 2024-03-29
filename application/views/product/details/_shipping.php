<?php if($shipping): ?>
<div class="row-custom product-links">
  <div><?= trans('shipping_cost');?>:  <?= $shipping['price'] ?></div>
  <div><?= trans('delivery_date');?>: <?= $shipping['date'] ?></div>
  <!--div>Возвраты: Пподавец не принимает возвраты</div-->
</div>
<?php endif;?>
