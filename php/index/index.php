<?php

    $arrstatusorder = array(
        0 => 'New Order',
        1 => 'On Production',
        2 => 'On Delivery',
        3 => 'Success',
        4 => 'Return',
        5 => 'Complain',
    );

    $listjobs = $config->runQuery("select t.*, td.* from transaction as t
    left join transaction_details as td on td.id_trx = t.transactionID
    where t.statusOrder = '1' and t.id_florist = '". $userdata['ID'] ."'");
    $listjobs->execute();
?>
<style>
    .col-md-4 img {
        display: block;
        width: 100%;
        padding: 1%;
    }
</style>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item text-success" aria-current="page"><b>New Order</b></li>
  </ol>
</nav>

<div class="row mb-2" style="display: inline-flex;">
    <?php while($data = $listjobs->fetch(PDO::FETCH_LAZY)) { 
            $product = $config->getData('*', 'products', "product_id = '". $data['id_product'] ."'");
        ?>
    <div class="col-md-6">
        <div class="card flex-md-row mb-4 box-shadow h-md-250">
            <div class="row" style="display: inline-flex;">
                <div class="col-md-8">
                    <div class="d-flex flex-column align-items-start">
                        <a href="javascript:;" class="badge badge-primary"><?=$arrstatusorder[$data['statusOrder']]?></a>
                        <h3 class="mb-0">
                        <a class="text-dark" href="javascript:;"><?=$data['product_name']?></a>
                        </h3>
                        <div class="mb-1 text-muted">Delivery Date: <b><?=Date('d F Y', strtotime($data['delivery_date']))?></b> <br> Time: <b><?=$data['delivery_time']?></b></div>
                        <br>Notes Florist:
                        <p class="card-text mb-auto" style="border: 1px dashed #dc3545; border-radius: 4px; padding: 1%; display: block; width: 100%;"><?=$data['florist_remarks']?></p>
                    </div>
                </div>
                <div class="col-md-4">
                        <picture>
                            <a href="<?=PRODUCTION.'assets/images/product/'.$product['images']?>" data-toggle="lightbox" data-gallery="example-gallery">
                                    <img src="<?=PRODUCTION.'assets/images/product/'.$product['images']?>" class="img-fluid img-thumbnail">
                                </a>
                        </picture>
                    <!-- <img class="card-img-right flex-auto d-none d-md-block img-responsive img-thumbnail" alt="Thumbnail [200x250]" src="<?=PRODUCTION?>assets/images/product/<?=$product['images']?>" data-holder-rendered="true" width="100%;"> -->
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item text-secondary" aria-current="page"><b>History</b></li>
  </ol>
</nav>


