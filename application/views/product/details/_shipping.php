<?php if($shipping): ?>
<div class="row-custom product-links">
  <div style="float: left; margin-right: 15px;"><img src="/assets/img/truck.svg" width=50></div>
  <div style="margin: 10px;"><?= trans('shipping_cost');?>: <b> <?= $shipping['price'] ?></b></div>
  <div><?= trans('delivery_date');?>: <b><?= $shipping['date'] ?></b></div>
  <!--div>Возвраты: Пподавец не принимает возвраты</div-->
</div>
<?php endif;?>
