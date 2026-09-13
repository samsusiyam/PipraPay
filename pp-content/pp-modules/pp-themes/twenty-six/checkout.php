<?php
    if (!defined('PipraPay_INIT')) {
        http_response_code(403);
        exit('Direct access not allowed');
    }

    if(isset($_GET['lang'])){
        if($_GET['lang'] !== ""){
            pp_set_lang($_GET['lang']);
?>
            <script>
                location.href = '?lang=';
            </script>
<?php
            exit();
        }
    }

    if(isset($_GET['cancel'])){
        pp_set_transaction_status($data['transaction']['ref'], 'canceled');
?>
        <script>
            location.href = '<?php echo pp_checkout_address();?>';
        </script>
<?php
        exit();
    }

    $pp_gateways_mfs = pp_gateways('mfs', $data);
    $pp_gateways_bank = pp_gateways('bank', $data);
    $pp_gateways_global = pp_gateways('global', $data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $data['lang']['checkout']?> - <?php echo $data['brand']['name'];?></title>
    <link rel="shortcut icon" href="<?php echo $data['brand']['favicon'];?>">
    <?php
       echo pp_assets('head');
    ?>

    <style>
        .container{
            max-width: 650px; 
            width: 100%;
        }
        .company-logo{
            margin-top: 15px;
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 100% !important;
        }
        .company-name{
            margin-top: 15px;
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 600;
        }

        .btn-primary {
            --tblr-btn-border-color: transparent;
            --tblr-btn-hover-border-color: transparent;
            --tblr-btn-active-border-color: transparent;
            --tblr-btn-color: <?php echo $data['options']['text_color'];?>;
            --tblr-btn-bg: <?php echo $data['options']['primary_color'];?>;
            --tblr-btn-hover-color: <?php echo $data['options']['text_color'];?>;
            --tblr-btn-hover-bg: <?php echo pp_hexToRgba($data['options']['primary_color'], 0.80)?>;
            --tblr-btn-active-color: <?php echo $data['options']['text_color'];?>;
            --tblr-btn-active-bg: <?php echo pp_hexToRgba($data['options']['primary_color'], 0.80)?>;
            --tblr-btn-disabled-bg: <?php echo $data['options']['primary_color'];?>;
            --tblr-btn-disabled-color: <?php echo $data['options']['text_color'];?>;
            --tblr-btn-box-shadow: <?php echo $data['options']['text_color'];?>;
        }

        .btn-check:checked+.btn, .btn.active, .btn.show, .btn:first-child:active, :not(.btn-check)+.btn:active{
            color: <?php echo $data['options']['text_color'];?>;
            background-color: <?php echo $data['options']['primary_color'];?>;
            border-color: <?php echo $data['options']['primary_color'];?>;
        }
    </style>

    <?php
        $seoTitle = trim($data['options']['seo_title'] ?? '');
        $seoDesc  = trim($data['options']['seo_description'] ?? '');
        $seoKey   = trim($data['options']['seo_keywords'] ?? '');
        $analyticsCode = trim($data['options']['analytics_code'] ?? '');

        if ($seoTitle !== '' && $seoTitle !== '--') {
            echo '<title>' . htmlspecialchars($seoTitle) . '</title>' . PHP_EOL;
            echo '<meta name="title" content="' . htmlspecialchars($seoTitle) . '">' . PHP_EOL;
            echo '<meta property="og:title" content="' . htmlspecialchars($seoTitle) . '">' . PHP_EOL;
        }

        if ($seoDesc !== '' && $seoDesc !== '--') {
            echo '<meta name="description" content="' . htmlspecialchars($seoDesc) . '">' . PHP_EOL;
            echo '<meta property="og:description" content="' . htmlspecialchars($seoDesc) . '">' . PHP_EOL;
        }

        if ($seoKey !== '' && $seoKey !== '--') {
            echo '<meta name="keywords" content="' . htmlspecialchars($seoKey) . '">' . PHP_EOL;
        }

        if ($analyticsCode !== '' && $analyticsCode !== '--') {
            echo $analyticsCode;
        }

        $bgStyle = 'background-color:#f8f9fa;';
        if (!empty($data['options']['enable_bg_image']) &&$data['options']['enable_bg_image'] === 'enabled' &&!empty($data['options']['background_image'])) {
            $bgImage = $data['options']['background_image'];
            $bgStyle = "
                background-image: url('{$bgImage}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
            ";
        }
    ?>
</head>
<body style="<?= $bgStyle ?>" loading="lazy">
    <div class="container container-tight py-4">
        <div class="card">
          <div class="card-body">
              <div class="d-flex align-items-center justify-content-between border rounded p-2">
                  <div onclick="location.href='<?php echo pp_checkout_address();?>?cancel'" style="text-align: right; cursor: pointer; color: <?php echo $data['options']['primary_color'];?>"><svg xmlns="http://www.w3.org/2000/svg" style=" padding: 6px; background-color: <?php echo pp_hexToRgba($data['options']['primary_color'], 0.05)?>; border-radius: 100%; width: 32px; height: 32px; " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg></div>
                  <div class="btns-group d-flex gap-2">
                      <div class="btns" data-tab="support" style="text-align: right; cursor: pointer; color: <?php echo $data['options']['primary_color'];?>"><svg xmlns="http://www.w3.org/2000/svg" style=" padding: 6px; background-color: <?php echo pp_hexToRgba($data['options']['primary_color'], 0.05)?>; border-radius: 100%; width: 32px; height: 32px; " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-headphones"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 15a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2l0 -3" /><path d="M15 15a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2l0 -3" /><path d="M4 15v-3a8 8 0 0 1 16 0v3" /></svg></div>
                      <div class="btns" data-tab="details" style="text-align: right; cursor: pointer; color: <?php echo $data['options']['primary_color'];?>"><svg xmlns="http://www.w3.org/2000/svg" style=" padding: 6px; background-color: <?php echo pp_hexToRgba($data['options']['primary_color'], 0.05)?>; border-radius: 100%; width: 32px; height: 32px; " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-info-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg></div>
                      <div class="btns" data-tab="faq" style="text-align: right; cursor: pointer; color: <?php echo $data['options']['primary_color'];?>"><svg xmlns="http://www.w3.org/2000/svg" style=" padding: 6px; background-color: <?php echo pp_hexToRgba($data['options']['primary_color'], 0.05)?>; border-radius: 100%; width: 32px; height: 32px; " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-help-hexagon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033" /><path d="M12 16v.01" /><path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg></div>
                      <div style="text-align: right; cursor: pointer; color: <?php echo $data['options']['primary_color'];?>" data-bs-target="#modal-language" data-bs-toggle="modal"><svg xmlns="http://www.w3.org/2000/svg" style=" padding: 6px; background-color: <?php echo pp_hexToRgba($data['options']['primary_color'], 0.05)?>; border-radius: 100%; width: 32px; height: 32px; " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-language"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6.371c0 4.418 -2.239 6.629 -5 6.629" /><path d="M4 6.371h7" /><path d="M5 9c0 2.144 2.252 3.908 6 4" /><path d="M12 20l4 -9l4 9" /><path d="M19.1 18h-6.2" /><path d="M6.694 3l.793 .582" /></svg></div>
                  </div>
              </div>

              <center>
                  <img src="<?php echo $data['brand']['favicon'];?>" alt="" class="company-logo">

                  <p class="company-name"><?php echo $data['brand']['name'];?></p>
              </center>

              <div class="btn-group w-100" role="group">
                  <?php
                      if ($pp_gateways_mfs['status'] === true && !empty($pp_gateways_mfs['gateway'])) {
                  ?>
                          <div class="btn btn-mfs w-100" data-tab="mfs"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-mobile"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 5a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-14" /><path d="M11 4h2" /><path d="M12 17v.01" /></svg> <span class="d-none d-sm-block"><?php echo $data['lang']['mobile_banking']?></span></div>
                  <?php
                      }
                  ?>

                  <?php
                      if ($pp_gateways_bank['status'] === true && !empty($pp_gateways_bank['gateway'])) {
                  ?>
                          <div class="btn btn-net-banking w-100" data-tab="bank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-bank"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 10l18 0" /><path d="M5 6l7 -3l7 3" /><path d="M4 10l0 11" /><path d="M20 10l0 11" /><path d="M8 14l0 3" /><path d="M12 14l0 3" /><path d="M16 14l0 3" /></svg> <span class="d-none d-sm-block"><?php echo $data['lang']['net_banking']?></span></div>
                  <?php
                      }
                  ?>

                  <?php
                      if ($pp_gateways_global['status'] === true && !empty($pp_gateways_global['gateway'])) {
                  ?>
                          <div class="btn btn-global w-100" data-tab="global"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-world"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg> <span class="d-none d-sm-block"><?php echo $data['lang']['global']?></span></div>
                  <?php
                      }
                  ?>

                </div>

                <div id="gateways-mfs" class="mt-1 row g-3 text-center"  style="display: none;">
                    <?php
                        if ($pp_gateways_mfs['status'] === true && !empty($pp_gateways_mfs['gateway'])) {
                            foreach($pp_gateways_mfs['gateway'] as $row){
                    ?>
                                <div class="col-6 col-md-4" style="cursor: pointer" onclick="location.href='<?php echo pp_checkout_address()?>?gateway=<?php echo $row['gateway_id']?>'">
                                    <div class="border rounded">
                                        <div style="height: 70px;display: flex;align-items: center;justify-content: center;">
                                            <img src="<?php echo $row['logo']?>" alt="" class="img-fluid mb-2" style="max-width: 100px;max-height: 40px;margin: 0 !important;">
                                        </div>

                                        <!-- vertical border -->
                                        <div class="mx-auto border-top" style="height: 1px;"></div>

                                        <div class="fw-semibold small mt-2 mb-2"><?php echo htmlspecialchars($row['display'])?></div>
                                    </div>
                                </div>
                    <?php
                            }
                        }else{
                    ?>
                           <style>
                                .btn-mfs{
                                    display: none;
                                }
                           </style>
                    <?php
                        }
                    ?>
                </div>

                <div id="gateways-bank" class="mt-1 row g-3 text-center"  style="display: none;">
                    <?php
                        if ($pp_gateways_bank['status'] === true && !empty($pp_gateways_bank['gateway'])) {
                            foreach($pp_gateways_bank['gateway'] as $row){
                    ?>
                                <div class="col-6 col-md-4" style="cursor: pointer" onclick="location.href='<?php echo pp_checkout_address()?>?gateway=<?php echo $row['gateway_id']?>'">
                                    <div class="border rounded">
                                        <div style="height: 70px;display: flex;align-items: center;justify-content: center;">
                                            <img src="<?php echo $row['logo']?>" alt="" class="img-fluid mb-2" style="max-width: 100px;max-height: 40px;margin: 0 !important;">
                                        </div>

                                        <!-- vertical border -->
                                        <div class="mx-auto border-top" style="height: 1px;"></div>

                                        <div class="fw-semibold small mt-2 mb-2"><?php echo htmlspecialchars($row['display'])?></div>
                                    </div>
                                </div>
                    <?php
                            }
                        }else{
                    ?>
                           <style>
                                .btn-net-banking{
                                    display: none;
                                }
                           </style>
                    <?php
                        }
                    ?>
                </div>

                <div id="gateways-global" class="mt-1 row g-3 text-center"  style="display: none;">
                    <?php
                        if ($pp_gateways_global['status'] === true && !empty($pp_gateways_global['gateway'])) {
                            foreach($pp_gateways_global['gateway'] as $row){
                    ?>
                                <div class="col-6 col-md-4" style="cursor: pointer" onclick="location.href='<?php echo pp_checkout_address()?>?gateway=<?php echo $row['gateway_id']?>'">
                                    <div class="border rounded">
                                        <div style="height: 70px;display: flex;align-items: center;justify-content: center;">
                                            <img src="<?php echo $row['logo']?>" alt="" class="img-fluid mb-2" style="max-width: 100px;max-height: 40px;margin: 0 !important;">
                                        </div>

                                        <!-- vertical border -->
                                        <div class="mx-auto border-top" style="height: 1px;"></div>

                                        <div class="fw-semibold small mt-2 mb-2"><?php echo htmlspecialchars($row['display'])?></div>
                                    </div>
                                </div>
                    <?php
                            }
                        }else{
                    ?>
                           <style>
                                .btn-global{
                                    display: none;
                                }
                           </style>
                    <?php
                        }
                    ?>
                </div>


                <?php
                    // Example support JSON for the gateway
                    $support = $data['brand']['support']; // assuming $row['support'] is already decoded JSON array/object
                ?>

                <div id="gateways-support" class="mt-1 row g-3 text-center"  style="display: none;">
                    <?php if(!empty($support['email']) && $support['email'] != '--'): ?>
                        <div class="col-6 col-md-4" style="cursor: pointer">
                            <a href="mailto:<?php echo $support['email']?>" target="_blank">
                                <div class="border rounded">
                                    <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:30px; height: 30px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" /><path d="M3 7l9 6l9 -6" /></svg>
                                    </div>

                                    <div class="mx-auto border-top" style="height:1px;"></div>
                                    <div class="fw-semibold small mt-2 mb-2"><?php echo $data['lang']['contact_email']?></div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($support['phone']) && $support['phone'] != '--'): ?>
                        <div class="col-6 col-md-4" style="cursor: pointer">
                            <a href="tel:<?php echo $support['phone']?>" target="_blank">
                                <div class="border rounded">
                                    <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:30px; height: 30px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-phone-calling"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /><path d="M15 7l0 .01" /><path d="M18 7l0 .01" /><path d="M21 7l0 .01" /></svg>
                                    </div>

                                    <div class="mx-auto border-top" style="height:1px;"></div>
                                    <div class="fw-semibold small mt-2 mb-2"><?php echo $data['lang']['contact_phone']?></div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($support['whatsapp']) && $support['whatsapp'] != '--'): ?>
                        <div class="col-6 col-md-4" style="cursor: pointer">
                            <a href="https://wa.me/<?php echo $support['whatsapp']?>" target="_blank">
                                <div class="border rounded">
                                    <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:30px; height: 30px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-whatsapp"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                                    </div>

                                    <div class="mx-auto border-top" style="height:1px;"></div>
                                    <div class="fw-semibold small mt-2 mb-2"><?php echo $data['lang']['contact_whatsapp']?></div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($support['telegram']) && $support['telegram'] != '--'): ?>
                        <div class="col-6 col-md-4" style="cursor: pointer">
                            <a href="<?php echo $support['telegram']?>" target="_blank">
                                <div class="border rounded">
                                    <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:30px; height: 30px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-telegram"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4" /></svg>
                                    </div>

                                    <div class="mx-auto border-top" style="height:1px;"></div>
                                    <div class="fw-semibold small mt-2 mb-2"><?php echo $data['lang']['contact_telegram']?></div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($support['website']) && $support['website'] != '--'): ?>
                        <div class="col-6 col-md-4" style="cursor: pointer">
                            <a href="<?php echo $support['website']?>" target="_blank">
                                <div class="border rounded">
                                    <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:30px; height: 30px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-world-www"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 7a9 9 0 0 0 -7.5 -4a8.991 8.991 0 0 0 -7.484 4" /><path d="M11.5 3a16.989 16.989 0 0 0 -1.826 4" /><path d="M12.5 3a16.989 16.989 0 0 1 1.828 4" /><path d="M19.5 17a9 9 0 0 1 -7.5 4a8.991 8.991 0 0 1 -7.484 -4" /><path d="M11.5 21a16.989 16.989 0 0 1 -1.826 -4" /><path d="M12.5 21a16.989 16.989 0 0 0 1.828 -4" /><path d="M2 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M17 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M9.5 10l1 4l1.5 -4l1.5 4l1 -4" /></svg>
                                    </div>

                                    <div class="mx-auto border-top" style="height:1px;"></div>
                                    <div class="fw-semibold small mt-2 mb-2"> <?php echo $data['lang']['contact_website']?></div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($support['messenger']) && $support['messenger'] != '--'): ?>
                        <div class="col-6 col-md-4" style="cursor: pointer">
                            <a href="<?php echo $support['messenger']?>" target="_blank">
                                <div class="border rounded">
                                    <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:30px; height: 30px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-messenger"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1" /><path d="M8 13l3 -2l2 2l3 -2" /></svg>
                                    </div>

                                    <div class="mx-auto border-top" style="height:1px;"></div>
                                    <div class="fw-semibold small mt-2 mb-2">
                                        <?php echo $data['lang']['contact_messenger']?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($support['fb_page']) && $support['fb_page'] != '--'): ?>
                        <div class="col-6 col-md-4" style="cursor: pointer">
                            <a href="<?php echo $support['fb_page']?>" target="_blank">
                                <div class="border rounded">
                                    <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:30px; height: 30px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-facebook"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" /></svg>
                                    </div>

                                    <div class="mx-auto border-top" style="height:1px;"></div>
                                    <div class="fw-semibold small mt-2 mb-2">
                                        <?php echo $data['lang']['contact_fb_page']?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>

                <div id="gateways-details" class="mt-1" style="display: none;">
                    <div style=" width: 100%; margin-top: 15px; ">
                        <ul class="list-unstyled" style=" padding: 0px; margin: 0; ">
                            <li class="d-flex justify-content-between py-1 border-bottom" style="height: 40px;align-items: center;">
                                <span><?php echo $data['lang']['currency']?></span>
                                <span class="fw-semibold"><?php echo htmlspecialchars($data['transaction']['currency']); ?></span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom" style="height: 40px;align-items: center;">
                                <span><?php echo $data['lang']['subtotal']?></span>
                                <span class="fw-semibold"><?php echo money_round(($data['transaction']['amount'] ?? 0) - ($data['transaction']['discount_amount'] ?? 0), 2).$data['transaction']['currency']; ?></span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom" style="height: 40px;align-items: center;">
                                <span><?php echo $data['lang']['discount']?></span>
                                <span class="fw-semibold"><?php echo money_round($data['transaction']['discount_amount'] ?? 0, 2).$data['transaction']['currency']; ?></span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom" style="height: 40px;align-items: center;">
                                <span><?php echo $data['lang']['total']?></span>
                                <span class="fw-semibold"><?php echo money_round($data['transaction']['amount'], 2).$data['transaction']['currency']; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div id="gateways-faq" class="mt-1" style="display: none;">
                    <div style=" width: 100%; margin-top: 15px; ">
                        <div class="accordion" id="accordion-default">
                            <?php
                                $count = 0;
                                foreach($data['faqs'] ?? [] as $faq){
                                    $count = $count+1;
                            ?>
                                    <div class="accordion-item">
                                        <div class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $count?>-default"
                                                aria-expanded="true">
                                                <?php echo htmlspecialchars($faq['title'])?>
                                                <div class="accordion-button-toggle">
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M6 9l6 6l6 -6"></path>
                                                </svg>
                                                </div>
                                            </button>
                                        </div>
                                        <div id="collapse-<?php echo $count?>-default" class="accordion-collapse collapse <?php echo ($count == 1) ? 'show' : ''?>" data-bs-parent="#accordion-default">
                                            <div class="accordion-body">
                                                <?php echo $faq['description']?>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>

              <div class="text-center mt-3 py-2 px-3 rounded" style="background-color:<?php echo pp_hexToRgba($data['options']['primary_color'], 0.08);?>;color:<?php echo $data['options']['primary_color'];?>;font-weight:600;font-size:1rem;">
                  <?php echo $data['lang']['total']?>: <?php echo money_round($data['transaction']['amount'], 2).$data['transaction']['currency'];?>
              </div>
          </div>
        </div>

        <center class="footer-branding" style="margin-top: 20px;"><?php echo $data['options']['watermark_text'];?></center>
    </div>

    <div class="modal fade" id="modal-language" data-bs-keyboard="false" tabindex="-1" aria-labelledby="scrollableLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableLabel"><?php echo $data['lang']['select_language']?></h5> 
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> 
                    <div class="form-group mt-1">
                        <label for="" class="form-label"><?php echo $data['lang']['language']?> <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <select class="form-select" id="model-languages" onchange="hitLanguage()">
                                <option value="" selected><?php echo $data['lang']['select_a_language']?></option>
                                <?php 
                                $supportedLanguages = !empty($data['supported_languages']) ? $data['supported_languages'] : [
                                    'en' => 'English',
                                    'bn' => 'বাংলা',
                                    'hi' => 'हिन्दी',
                                    'ur' => 'اردو',
                                    'ar' => 'العربية',
                                ];
                                foreach ($supportedLanguages as $code => $language): ?>
                                    <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($language) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal"><?php echo $data['lang']['close']?></button>
                </div>
            </div>
        </div>
    </div>

    <?php
       echo pp_assets('footer');
    ?>

    <script data-cfasync="false">
        document.addEventListener('DOMContentLoaded', function() {
            const autoButtons = document.querySelectorAll('.btn-group .btn');

            const allButtons = document.querySelectorAll('.btn-group .btn, .btns-group .btns');

            const rows = {};

            allButtons.forEach(btn => {
                const tab = btn.dataset.tab;
                if (!tab) return; 

                const row = document.getElementById('gateways-' + tab);
                if (row) rows[tab] = row;

                btn.addEventListener('click', function() {
                    allButtons.forEach(b => b.classList.remove('active'));

                    this.classList.add('active');

                    Object.values(rows).forEach(r => r.style.display = 'none');

                    if (rows[tab]) rows[tab].style.display = rows[tab].classList.contains('row') ? 'flex' : 'block';
                });
            });

            if (autoButtons.length > 0) {
                autoButtons[0].click();
            }
        });

        function hitLanguage(){
            var language = document.querySelector("#model-languages").value;

            if(language !== ""){
                location.href = '?lang='+language;
            }
        }
    </script>
</body>
</html>
