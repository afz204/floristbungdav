<?php

    $arrstatusorder = array(
        0 => 'New Order',
        1 => 'On Production',
        2 => 'On Delivery',
        3 => 'Success',
        4 => 'Return',
        5 => 'Complain',
    );
    $arrtime = [
		0 => '9am - 1pm',
		1 => '2pm - 5pm',
		2 => '6pm - 8pm',
		3 => '9pm - 0am',
		4 => '1am - 5am',
		5 => '6am - 8am'
    ];
    

    $listjobs = $config->runQuery("select t.*, td.*, td.id as DetailTransactionID, provinces.name as ProvinsiName, regencies.name as KotaName, districts.name as Kecamatan, villages.name as Kelurahan from transaction as t
    left join transaction_details as td on td.id_trx = t.transactionID
    LEFT JOIN provinces ON provinces.id = t.provinsi_id 
    LEFT JOIN regencies on regencies.id = t.kota_id 
    LEFT JOIN districts ON districts.id = t.kecamata_id 
    LEFT JOIN villages on villages.id = t.kelurahan_id
    where t.statusOrder = 1 and t.id_kurir = '' and t.delivery_date = '". $config->getDate('Y/m/d') ."' and t.id_florist = '". $userdata['ID'] ."' ORDER BY t.delivery_date DESC");
    $listjobs->execute();

    $oldjob = $config->runQuery("select t.*, td.*, td.id as DetailTransactionID, kurirs.nama_kurir, provinces.name as ProvinsiName, regencies.name as KotaName, districts.name as Kecamatan, villages.name as Kelurahan from transaction as t
    left join transaction_details as td on td.id_trx = t.transactionID
    left join kurirs on kurirs.id = t.id_kurir
    LEFT JOIN provinces ON provinces.id = t.provinsi_id 
    LEFT JOIN regencies on regencies.id = t.kota_id 
    LEFT JOIN districts ON districts.id = t.kecamata_id 
    LEFT JOIN villages on villages.id = t.kelurahan_id
    where t.statusOrder = 1 and t.id_kurir = '' and t.delivery_date != '". $config->getDate('Y/m/d') ."' and t.id_florist = '". $userdata['ID'] ."' ORDER BY t.delivery_date DESC");
    $oldjob->execute();

    $history = $config->runQuery("select t.*, td.*, kurirs.nama_kurir, provinces.name as ProvinsiName, regencies.name as KotaName, districts.name as Kecamatan, villages.name as Kelurahan from transaction as t
    left join transaction_details as td on td.id_trx = t.transactionID
    left join kurirs on kurirs.id = t.id_kurir
    LEFT JOIN provinces ON provinces.id = t.provinsi_id 
    LEFT JOIN regencies on regencies.id = t.kota_id 
    LEFT JOIN districts ON districts.id = t.kecamata_id 
    LEFT JOIN villages on villages.id = t.kelurahan_id
    where t.statusOrder = 2 and t.id_kurir != '' and t.id_florist = '". $userdata['ID'] ."' ORDER BY t.delivery_date DESC");
    $history->execute();
    // $config->_debugvar($history);
    $florist = explode('@', $userdata['Email']);
    $listkurir = $config->Products('id, nama_kurir', 'kurirs WHERE status = 1');
?>
<style>
    .col-md-4 img {
        display: block;
        width: 100%;
        padding: 1%;
    }
    .badge {
        font-size: 85%;
        border-radius: .15rem;
    }
    .col-md-8 {
        padding-right: unset;
    }
</style>

<div class="row">
    <div class="col-lg-6" style="float:left;">
    <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-danger" aria-current="page"><b>Today Order</b></li>
            </ol>
            </nav>
    <div style=" overflow-y: scroll; height:550px;">
        

            <div class="row mb-2" style="display: inline-flex;">
                <?php while($data = $listjobs->fetch(PDO::FETCH_LAZY)) { 
                        $product = $config->getData('*', 'products', "product_id = '". $data['id_product'] ."'");
                        ?>
                    <div class="col-md-12">
                        <div class="card flex-md-row mb-4 box-shadow h-md-250">
                            <div class="row" style="display: inline-flex; height: 340px;">
                                <div class="col-md-8">
                                    <div class="d-flex flex-column align-items-start">
                                        <a href="javascript:;" class="badge badge-primary"><?=$arrstatusorder[$data['statusOrder']]?> #<?=$data['transactionID']?></a>
                                        <h3 class="mb-0">
                                        <a class="text-dark" href="javascript:;" style="text-transform: capitalize;"><?=$data['product_name']?></a>
                                        </h3>
                                        <div class="mb-1 text-muted" style="font-weight: 500;font-size: 15px;">Delivery Date: <b><?=Date('d F Y', strtotime($data['delivery_date']))?></b> <br><span style="font-weight: 500;font-size: 15px;">Time:</span> <b><?=$arrtime[$data['delivery_time']]?></b></div>
                                        <p style="margin: unset;font-weight: 500;font-size: 15px;">Alamat Kirim :</p>
                                        <p style="margin: unset;font-weight: 400;font-size: 15px; text-transform: capitalize;"><?=strtoupper($data['alamat_penerima'])?>, <?=$data['Kelurahan']?>, <?=$data['Kecamatan']?>, <?=$data['KotaName']?>, <?=$data['ProvinsiName']?></p>
                                        <p style="margin: unset;font-weight: 500;font-size: 15px;padding-top: 1%;">Notes :</p>
                                        <p class="card-text mb-auto" style="border: 1px dashed #dc3545; border-radius: 10px; padding: 1%; display: block; width: 100%; height: 100px; background-color: #ffe0e3;font-size: 14px;font-weight: 600; text-align: justify;"><?=$data['florist_remarks']?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div style="padding-top: 15%;">
                                        <picture >
                                            <a href="<?=PRODUCTION.'assets/images/product/'.$product['images']?>" data-toggle="lightbox" data-gallery="example-gallery">
                                                    <img src="<?=PRODUCTION.'assets/images/product/'.$product['images']?>" class="img-fluid img-thumbnail">
                                                </a>
                                        </picture>
                                        <br>
                                    <?php
                                        if(empty($data['id_kurir'])){
                                            $btnkurir = '<button class="btn btn-sm btn-warning" onclick="pilihKurir(\''. $data['transactionID'] .'\')" style="font-size: 12px;">Kurir</button>';
                                        }else{
                                            $kurir = $config->getData('id, nama_kurir', 'kurirs', "id = '". $data['id_kurir'] ."'");
                                            $btnkurir = '<button class="btn btn-sm btn-warning" onclick="pilihKurir(\''. $data['transactionID'] .'\')">'. $kurir['nama_kurir'] .'</button>';
                                        }
                                    ?>
                                    <div class="btn-group" style="padding-left: 15%;" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-sm btn-<?=$data['florist_action'] > 0 ? 'success' : 'info'?>" onclick="changejobsflorist(<?=$data['DetailTransactionID']?>)"><?=$data['florist_action'] > 0 ? 'Done!' : 'Done'?></button>
                                        <?=$btnkurir?>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
    </div>

    </div>
    <div class="col-lg-6" style="float:left;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-success" aria-current="page"><b>List Order</b></li>
        </ol>
        </nav>
    <div style=" overflow-y: scroll; height:550px;">
        

        <div class="row mb-2" style="display: inline-flex;">
            <?php while($data = $oldjob->fetch(PDO::FETCH_LAZY)) { 
                    $product = $config->getData('*', 'products', "product_id = '". $data['id_product'] ."'");
                ?>
            <div class="col-md-12">
                <div class="card flex-md-row mb-4 box-shadow h-md-250">
                    <div class="row" style="display: inline-flex; height: 340px;">
                        <div class="col-md-8">
                            <div class="d-flex flex-column align-items-start">
                                <a href="javascript:;" class="badge badge-primary"><?=$arrstatusorder[$data['statusOrder']]?> #<?=$data['transactionID']?></a>
                                <h3 class="mb-0">
                                <a class="text-dark" href="javascript:;" style="text-transform: capitalize;"><?=$data['product_name']?></a>
                                </h3>
                                <div class="mb-1 text-muted" style="font-weight: 500;font-size: 15px;">Delivery Date: <b><?=Date('d F Y', strtotime($data['delivery_date']))?></b> <br><span style="font-weight: 500;font-size: 15px;">Time:</span> <b><?=$arrtime[$data['delivery_time']]?></b></div>
                                <p style="margin: unset;font-weight: 500;font-size: 15px;">Alamat Kirim :</p>
                                <p style="margin: unset;font-weight: 400;font-size: 15px;"><?=$data['alamat_penerima']?>, <?=$data['Kelurahan']?>, <?=$data['Kecamatan']?>, <?=$data['KotaName']?>, <?=$data['ProvinsiName']?></p>
                                <p style="margin: unset;font-weight: 500;font-size: 15px;padding-top: 1%;">Notes :</p>
                                <p class="card-text mb-auto" style="border: 1px dashed #dc3545; border-radius: 10px; padding: 1%; display: block; width: 100%; height: 100px; background-color: #ffe0e3;font-size: 14px;font-weight: 600; text-align: justify;"><?=$data['florist_remarks']?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                                <div style="padding-top: 15%;">
                                    <picture >
                                        <a href="<?=PRODUCTION.'assets/images/product/'.$product['images']?>" data-toggle="lightbox" data-gallery="example-gallery">
                                                <img src="<?=PRODUCTION.'assets/images/product/'.$product['images']?>" class="img-fluid img-thumbnail">
                                            </a>
                                    </picture>
                                    <br>
                                    <?php
                                        if(empty($data['id_kurir'])){
                                            $btnkurir = '<button class="btn btn-sm btn-warning" onclick="pilihKurir(\''. $data['transactionID'] .'\')" style="font-size: 12px;">Kurir</button>';
                                        }else{
                                            $kurir = $config->getData('id, nama_kurir', 'kurirs', "id = '". $data['id_kurir'] ."'");
                                            $btnkurir = '<button class="btn btn-sm btn-warning" onclick="pilihKurir(\''. $data['transactionID'] .'\')">'. $kurir['nama_kurir'] .'</button>';
                                        }
                                    ?>
                                    <div class="btn-group" style="padding-left: 15%;" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-sm btn-<?=$data['florist_action'] > 0 ? 'success' : 'info'?>" onclick="changejobsflorist(<?=$data['DetailTransactionID']?>)"><?=$data['florist_action'] > 0 ? 'Done!' : 'Done'?></button>
                                        <?=$btnkurir?>
                                    </div>
                                </div>
                            <!-- <img class="card-img-right flex-auto d-none d-md-block img-responsive img-thumbnail" alt="Thumbnail [200x250]" src="<?=PRODUCTION?>assets/images/product/<?=$product['images']?>" data-holder-rendered="true" width="100%;"> -->
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        </div>
    </div>
</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item text-secondary" aria-current="page"><b>History</b></li>
  </ol>
</nav>

<div class="row mb-2" style="display: inline-flex;">
    <?php while($data = $history->fetch(PDO::FETCH_LAZY)) { 
            $product = $config->getData('*', 'products', "product_id = '". $data['id_product'] ."'");
        ?>
    <div class="col-md-6">
        <div class="card flex-md-row mb-4 box-shadow h-md-250">
            <div class="row" style="display: inline-flex; height: 285px;">
                <div class="col-md-8">
                    <div class="d-flex flex-column align-items-start">
                        <a href="javascript:;" class="badge badge-primary"><?=$arrstatusorder[$data['statusOrder']]?></a>
                        <h3 class="mb-0">
                        <a class="text-dark" href="javascript:;" style="text-transform: capitalize;"><?=$data['product_name']?></a>
                        </h3>
                        <div class="mb-1 text-muted" style="font-weight: 500;font-size: 15px;">Delivery Date: <b><?=Date('d F Y', strtotime($data['delivery_date']))?></b> <br><span style="font-weight: 500;font-size: 15px;">Time:</span> <b><?=$arrtime[$data['delivery_time']]?></b></div>
                        <p style="margin: unset;font-weight: 500;font-size: 15px;">Alamat Kirim :</p>
                        <p style="margin: unset;font-weight: 400;font-size: 15px; text-transform: capitalize;"><?=strtoupper($data['alamat_penerima'])?>, <?=$data['Kelurahan']?>, <?=$data['Kecamatan']?>, <?=$data['KotaName']?>, <?=$data['ProvinsiName']?></p>
                        <p style="margin: unset;font-weight: 500;font-size: 15px;padding-top: 1%;">Notes :</p>
                        <p class="card-text mb-auto" style="border: 1px dashed #dc3545; border-radius: 10px; padding: 1%; display: block; width: 100%; height: 75px; background-color: #ffe0e3;font-size: 14px;font-weight: 600; text-align: justify;"><?=$data['florist_remarks']?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="padding-top: 15%;">
                        <picture >
                            <a href="<?=PRODUCTION.'assets/images/product/'.$product['images']?>" data-toggle="lightbox" data-gallery="example-gallery">
                                    <img src="<?=PRODUCTION.'assets/images/product/'.$product['images']?>" class="img-fluid img-thumbnail">
                                </a>
                        </picture>
                        <br>
                        <button class="btn btn-sm btn-block btn-success"><?=$data['nama_kurir']?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<div class="modal fade" id="modalselectkurir" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
            <div class="modal-body">
                <form id="formSelectKurir" method="post" data-parsley-validate="" class="needs-validation" novalidate="" autocomplete="off">
                    <div class="form-group">
                        <select class="form-control" name="listKurir" id="listKurir" required>
                            <option value="">:: pilih kurir ::</option>
                            <?php while ($kr = $listkurir->fetch(PDO::FETCH_LAZY)){ ?>
                            <option value="<?=$kr['id']?>"><?=$kr['nama_kurir']?></option>
                            <?php } ?>
                        </select>
                    </div>
					<input type="hidden" name="TransactionNumberKurir">
                    <button class="btn btn-success btn-sm btn-block" type="submit">Pilih Kurir</button>
                </form>
            </div>
        </div>
    </div>
</div>


