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

    $primaryColor = !empty($data['options']['primary_color']) && $data['options']['primary_color'] !== '--' ? $data['options']['primary_color'] : '#4f46e5';
    $accentColor  = !empty($data['options']['accent_color']) && $data['options']['accent_color'] !== '--' ? $data['options']['accent_color'] : '#06b6d4';
    $textColor    = !empty($data['options']['text_color']) && $data['options']['text_color'] !== '--' ? $data['options']['text_color'] : '#ffffff';
    $themeMode    = $data['options']['theme_mode'] ?? 'glassmorphism';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $data['lang']['checkout']?> - <?php echo htmlspecialchars($data['brand']['name']);?></title>
    <link rel="shortcut icon" href="<?php echo $data['brand']['favicon'];?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php echo pp_assets('head'); ?>

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

        $bgStyle = 'background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 50%, #f1f5f9 100%);';
        if (!empty($data['options']['enable_bg_image']) && $data['options']['enable_bg_image'] === 'enabled' && !empty($data['options']['background_image'])) {
            $bgImage = $data['options']['background_image'];
            $bgStyle = "background-image: url('{$bgImage}'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;";
        }
    ?>

    <style>
        :root {
            --mp-primary: <?php echo $primaryColor; ?>;
            --mp-accent: <?php echo $accentColor; ?>;
            --mp-text-btn: <?php echo $textColor; ?>;
            --mp-card-bg: rgba(255, 255, 255, 0.94);
            --mp-card-border: rgba(226, 232, 240, 0.9);
            --mp-card-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07), 0 0 1px 1px rgba(0, 0, 0, 0.04);
            --mp-radius: 20px;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            color: #1e293b;
            <?= $bgStyle ?>
            margin: 0;
            padding: 20px 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .mp-container {
            max-width: 580px;
            width: 100%;
            margin: 0 auto;
        }

        .mp-card {
            background: var(--mp-card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--mp-card-border);
            border-radius: var(--mp-radius);
            box-shadow: var(--mp-card-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .mp-topbar {
            padding: 14px 20px;
            background: rgba(248, 250, 252, 0.8);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mp-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .mp-amount-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            background: #f1f5f9;
            border-radius: 20px;
            font-size: 0.88rem;
            font-weight: 700;
            color: #334155;
            border: 1px solid #e2e8f0;
        }

        .mp-amount-badge span.amount {
            color: var(--mp-primary);
            font-size: 1rem;
            font-weight: 800;
        }

        .mp-icon-btn:hover {
            background: var(--mp-primary);
            border-color: var(--mp-primary);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px <?php echo pp_hexToRgba($primaryColor, 0.25); ?>;
        }

        .mp-icon-btn.active {
            background: var(--mp-primary);
            border-color: var(--mp-primary);
            color: #ffffff;
        }

        .mp-body {
            padding: 24px 24px;
        }

        .mp-brand-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .mp-logo-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 12px;
        }

        .mp-brand-logo {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ffffff;
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.12);
        }

        .mp-brand-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: -0.02em;
        }

        .mp-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: <?php echo pp_hexToRgba($primaryColor, 0.08); ?>;
            color: var(--mp-primary);
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 20px;
        }

        .mp-nav-pills {
            display: flex;
            gap: 8px;
            background: #f1f5f9;
            padding: 5px;
            border-radius: 14px;
            margin-bottom: 20px;
        }

        .mp-nav-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            border: none;
            background: transparent;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        .mp-nav-btn svg {
            width: 18px;
            height: 18px;
        }

        .mp-nav-btn.active {
            background: #ffffff;
            color: var(--mp-primary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .mp-gateway-card {
            background: #ffffff;
            border: 1.5px solid #f1f5f9;
            border-radius: 16px;
            padding: 16px 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 110px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .mp-gateway-card:hover {
            border-color: var(--mp-primary);
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px <?php echo pp_hexToRgba($primaryColor, 0.2); ?>;
        }

        .mp-gateway-logo-container {
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .mp-gateway-logo {
            max-height: 40px;
            max-width: 90px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.04));
        }

        .mp-gateway-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            line-height: 1.2;
            margin-top: 2px;
        }

        .mp-amount-banner {
            background: linear-gradient(135deg, <?php echo $primaryColor; ?> 0%, <?php echo $accentColor; ?> 100%);
            border-radius: 16px;
            padding: 16px 20px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 24px;
            box-shadow: 0 10px 25px -5px <?php echo pp_hexToRgba($primaryColor, 0.35); ?>;
        }

        .mp-amount-label {
            font-size: 0.85rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .mp-amount-val {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .mp-footer-branding {
            text-align: center;
            margin-top: 18px;
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }

        .mp-info-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 0.9rem;
        }

        .mp-info-list li:last-child {
            border-bottom: none;
        }

        .mp-support-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px;
            text-align: center;
            transition: all 0.2s ease;
            text-decoration: none !important;
            display: block;
            color: #1e293b;
        }

        .mp-support-card:hover {
            border-color: var(--mp-primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.05);
            color: var(--mp-primary);
        }

        .mp-support-card svg {
            width: 28px;
            height: 28px;
            margin-bottom: 6px;
            color: var(--mp-primary);
        }

        @media (max-width: 576px) {
            body {
                padding: 10px 6px;
            }
            .mp-card {
                border-radius: 16px;
            }
            .mp-body {
                padding: 18px 14px;
            }
            .mp-nav-btn {
                padding: 8px 8px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

    <div class="mp-container">
        <div class="mp-card">
            <!-- Top Action Bar -->
            <div class="mp-topbar">
                <div class="mp-icon-btn" onclick="location.href='<?php echo pp_checkout_address();?>?cancel'" title="<?php echo $data['lang']['close']?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                </div>
                
                <div class="mp-amount-badge">
                    <span><?php echo $data['lang']['amount'] ?? 'Amount'; ?>:</span>
                    <span class="amount"><?php echo money_round($data['transaction']['amount'] ?? 0, 2); ?> <?php echo htmlspecialchars($data['transaction']['currency'] ?? 'BDT'); ?></span>
                </div>

                <div class="d-flex gap-2">
                    <div class="mp-icon-btn tab-trigger" data-tab="support" title="<?php echo $data['lang']['support']?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 15a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2l0 -3" /><path d="M15 15a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2l0 -3" /><path d="M4 15v-3a8 8 0 0 1 16 0v3" /></svg>
                    </div>
                    <div class="mp-icon-btn tab-trigger" data-tab="details" title="<?php echo $data['lang']['details']?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                    </div>
                    <div class="mp-icon-btn tab-trigger" data-tab="faq" title="<?php echo $data['lang']['faq']?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033" /><path d="M12 16v.01" /><path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg>
                    </div>
                    <div class="mp-icon-btn" data-bs-target="#modal-language" data-bs-toggle="modal" title="<?php echo $data['lang']['language']?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6.371c0 4.418 -2.239 6.629 -5 6.629" /><path d="M4 6.371h7" /><path d="M5 9c0 2.144 2.252 3.908 6 4" /><path d="M12 20l4 -9l4 9" /><path d="M19.1 18h-6.2" /><path d="M6.694 3l.793 .582" /></svg>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="mp-body">
                <div class="mp-brand-header">
                    <div class="mp-logo-wrapper">
                        <img src="<?php echo $data['brand']['favicon'];?>" alt="<?php echo htmlspecialchars($data['brand']['name']);?>" class="mp-brand-logo">
                    </div>
                    <h3 class="mp-brand-name"><?php echo htmlspecialchars($data['brand']['name']);?></h3>
                    <div class="mp-brand-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12l0 2.5" /></svg>
                        <?php echo $data['lang']['checkout']?>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="mp-nav-pills" role="tablist">
                    <?php if ($pp_gateways_mfs['status'] === true && !empty($pp_gateways_mfs['gateway'])): ?>
                        <button type="button" class="mp-nav-btn active btn-mfs" data-tab="mfs">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 5a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-14" /><path d="M11 4h2" /><path d="M12 17v.01" /></svg>
                            <span><?php echo $data['lang']['mobile_banking']?></span>
                        </button>
                    <?php endif; ?>

                    <?php if ($pp_gateways_bank['status'] === true && !empty($pp_gateways_bank['gateway'])): ?>
                        <button type="button" class="mp-nav-btn btn-net-banking" data-tab="bank">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 10l18 0" /><path d="M5 6l7 -3l7 3" /><path d="M4 10l0 11" /><path d="M20 10l0 11" /><path d="M8 14l0 3" /><path d="M12 14l0 3" /><path d="M16 14l0 3" /></svg>
                            <span><?php echo $data['lang']['net_banking']?></span>
                        </button>
                    <?php endif; ?>

                    <?php if ($pp_gateways_global['status'] === true && !empty($pp_gateways_global['gateway'])): ?>
                        <button type="button" class="mp-nav-btn btn-global" data-tab="global">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                            <span><?php echo $data['lang']['global']?></span>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- MFS Tab Gateways -->
                <div id="gateways-mfs" class="row g-3" style="display: flex;">
                    <?php
                        if ($pp_gateways_mfs['status'] === true && !empty($pp_gateways_mfs['gateway'])) {
                            foreach($pp_gateways_mfs['gateway'] as $row){
                    ?>
                                <div class="col-6 col-sm-4">
                                    <div class="mp-gateway-card" onclick="location.href='<?php echo pp_checkout_address()?>?gateway=<?php echo $row['gateway_id']?>'">
                                        <div class="mp-gateway-logo-container">
                                            <img src="<?php echo $row['logo']?>" alt="<?php echo htmlspecialchars($row['display'])?>" class="mp-gateway-logo">
                                        </div>
                                        <div class="mp-gateway-name"><?php echo htmlspecialchars($row['display'])?></div>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                    ?>
                </div>

                <!-- Bank Tab Gateways -->
                <div id="gateways-bank" class="row g-3" style="display: none;">
                    <?php
                        if ($pp_gateways_bank['status'] === true && !empty($pp_gateways_bank['gateway'])) {
                            foreach($pp_gateways_bank['gateway'] as $row){
                    ?>
                                <div class="col-6 col-sm-4">
                                    <div class="mp-gateway-card" onclick="location.href='<?php echo pp_checkout_address()?>?gateway=<?php echo $row['gateway_id']?>'">
                                        <div class="mp-gateway-logo-container">
                                            <img src="<?php echo $row['logo']?>" alt="<?php echo htmlspecialchars($row['display'])?>" class="mp-gateway-logo">
                                        </div>
                                        <div class="mp-gateway-name"><?php echo htmlspecialchars($row['display'])?></div>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                    ?>
                </div>

                <!-- Global Tab Gateways -->
                <div id="gateways-global" class="row g-3" style="display: none;">
                    <?php
                        if ($pp_gateways_global['status'] === true && !empty($pp_gateways_global['gateway'])) {
                            foreach($pp_gateways_global['gateway'] as $row){
                    ?>
                                <div class="col-6 col-sm-4">
                                    <div class="mp-gateway-card" onclick="location.href='<?php echo pp_checkout_address()?>?gateway=<?php echo $row['gateway_id']?>'">
                                        <div class="mp-gateway-logo-container">
                                            <img src="<?php echo $row['logo']?>" alt="<?php echo htmlspecialchars($row['display'])?>" class="mp-gateway-logo">
                                        </div>
                                        <div class="mp-gateway-name"><?php echo htmlspecialchars($row['display'])?></div>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                    ?>
                </div>

                <!-- Support Channels Tab -->
                <?php $support = $data['brand']['support'] ?? []; ?>
                <div id="gateways-support" class="row g-3" style="display: none;">
                    <?php if(!empty($support['email']) && $support['email'] != '--'): ?>
                        <div class="col-6">
                            <a href="mailto:<?php echo $support['email']?>" target="_blank" class="mp-support-card">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" /><path d="M3 7l9 6l9 -6" /></svg>
                                <div class="fw-semibold small"><?php echo $data['lang']['contact_email']?></div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($support['phone']) && $support['phone'] != '--'): ?>
                        <div class="col-6">
                            <a href="tel:<?php echo $support['phone']?>" target="_blank" class="mp-support-card">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                <div class="fw-semibold small"><?php echo $data['lang']['contact_phone']?></div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($support['whatsapp']) && $support['whatsapp'] != '--'): ?>
                        <div class="col-6">
                            <a href="https://wa.me/<?php echo $support['whatsapp']?>" target="_blank" class="mp-support-card">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                                <div class="fw-semibold small"><?php echo $data['lang']['contact_whatsapp']?></div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($support['telegram']) && $support['telegram'] != '--'): ?>
                        <div class="col-6">
                            <a href="<?php echo $support['telegram']?>" target="_blank" class="mp-support-card">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4" /></svg>
                                <div class="fw-semibold small"><?php echo $data['lang']['contact_telegram']?></div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Transaction Details Tab -->
                <div id="gateways-details" style="display: none;">
                    <ul class="list-unstyled mp-info-list mb-0">
                        <li>
                            <span class="text-muted"><?php echo $data['lang']['currency']?></span>
                            <span class="fw-bold"><?php echo htmlspecialchars($data['transaction']['currency']); ?></span>
                        </li>
                        <li>
                            <span class="text-muted"><?php echo $data['lang']['subtotal']?></span>
                            <span class="fw-semibold"><?php echo money_round(($data['transaction']['amount'] ?? 0) - ($data['transaction']['discount_amount'] ?? 0), 2) . ' ' . $data['transaction']['currency']; ?></span>
                        </li>
                        <?php if(!empty($data['transaction']['discount_amount']) && $data['transaction']['discount_amount'] > 0): ?>
                        <li>
                            <span class="text-muted"><?php echo $data['lang']['discount']?></span>
                            <span class="text-success fw-semibold">-<?php echo money_round($data['transaction']['discount_amount'], 2) . ' ' . $data['transaction']['currency']; ?></span>
                        </li>
                        <?php endif; ?>
                        <li>
                            <span class="text-muted"><?php echo $data['lang']['total']?></span>
                            <span class="fw-bold" style="color: var(--mp-primary);"><?php echo money_round($data['transaction']['amount'], 2) . ' ' . $data['transaction']['currency']; ?></span>
                        </li>
                    </ul>
                </div>

                <!-- FAQ Tab -->
                <div id="gateways-faq" style="display: none;">
                    <div class="accordion" id="accordion-mp">
                        <?php
                            $faqCount = 0;
                            foreach($data['faqs'] ?? [] as $faq){
                                $faqCount++;
                        ?>
                                <div class="accordion-item mb-2 border rounded-3 overflow-hidden">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?php echo ($faqCount == 1) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-mp-<?php echo $faqCount?>">
                                            <?php echo htmlspecialchars($faq['title'])?>
                                        </button>
                                    </h2>
                                    <div id="collapse-mp-<?php echo $faqCount?>" class="accordion-collapse collapse <?php echo ($faqCount == 1) ? 'show' : ''; ?>" data-bs-parent="#accordion-mp">
                                        <div class="accordion-body text-muted small">
                                            <?php echo $faq['description']?>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>

                <!-- Amount Payable Banner -->
                <div class="mp-amount-banner">
                    <div>
                        <div class="mp-amount-label"><?php echo $data['lang']['total']?></div>
                        <div class="small opacity-75"><?php echo $data['transaction']['currency'];?></div>
                    </div>
                    <div class="mp-amount-val">
                        <?php echo money_round($data['transaction']['amount'], 2); ?> <span style="font-size: 0.9rem; font-weight: 600;"><?php echo $data['transaction']['currency'];?></span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer Watermark Branding -->
        <div class="mp-footer-branding">
            <?php echo htmlspecialchars($data['options']['watermark_text'] ?? 'Secured by PipraPay'); ?>
        </div>
    </div>

    <!-- Language Selector Modal -->
    <div class="modal fade" id="modal-language" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px; border: 1px solid #e2e8f0;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><?php echo $data['lang']['select_language']?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <label class="form-label text-muted small fw-semibold"><?php echo $data['lang']['language']?></label>
                    <select class="form-select form-select-lg" id="model-languages" onchange="hitLanguage()" style="border-radius: 12px;">
                        <option value="" selected><?php echo $data['lang']['select_a_language']?></option>
                        <?php foreach ($data['supported_languages'] ?? [] as $code => $language): ?>
                            <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($language) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <?php echo pp_assets('footer'); ?>

    <script data-cfasync="false">
        document.addEventListener('DOMContentLoaded', function() {
            const navButtons = document.querySelectorAll('.mp-nav-pills .mp-nav-btn');
            const topBarButtons = document.querySelectorAll('.mp-topbar .tab-trigger');
            const allTriggers = document.querySelectorAll('.mp-nav-btn, .tab-trigger');

            const tabSections = {
                'mfs': document.getElementById('gateways-mfs'),
                'bank': document.getElementById('gateways-bank'),
                'global': document.getElementById('gateways-global'),
                'support': document.getElementById('gateways-support'),
                'details': document.getElementById('gateways-details'),
                'faq': document.getElementById('gateways-faq')
            };

            function switchTab(targetTab) {
                allTriggers.forEach(btn => btn.classList.remove('active'));
                
                Object.values(tabSections).forEach(section => {
                    if (section) section.style.display = 'none';
                });

                allTriggers.forEach(btn => {
                    if (btn.dataset.tab === targetTab) {
                        btn.classList.add('active');
                    }
                });

                if (tabSections[targetTab]) {
                    const isRow = tabSections[targetTab].classList.contains('row');
                    tabSections[targetTab].style.display = isRow ? 'flex' : 'block';
                }
            }

            allTriggers.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tab = this.dataset.tab;
                    if (tab) switchTab(tab);
                });
            });

            if (navButtons.length > 0) {
                navButtons[0].click();
            }
        });

        function hitLanguage() {
            var language = document.querySelector("#model-languages").value;
            if (language !== "") {
                location.href = '?lang=' + language;
            }
        }
    </script>
</body>
</html>
