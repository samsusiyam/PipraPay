<?php
    if (!defined('PipraPay_INIT')) {
        http_response_code(403);
        exit('Direct access not allowed');
    }

    if(isset($_GET['receipt'])){
        pp_downloadReceiptPDF($data);
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

    $primaryColor = !empty($data['options']['primary_color']) && $data['options']['primary_color'] !== '--' ? $data['options']['primary_color'] : '#4f46e5';
    $textColor    = !empty($data['options']['text_color']) && $data['options']['text_color'] !== '--' ? $data['options']['text_color'] : '#ffffff';
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
        }

        .mp-icon-btn:hover {
            background: var(--mp-primary);
            border-color: var(--mp-primary);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .mp-body {
            padding: 30px 24px;
            text-align: center;
        }

        .mp-status-icon-wrapper {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            animation: mpPulse 2s infinite ease-in-out;
        }

        .mp-status-icon-wrapper.status-completed {
            background: #dcfce7;
            color: #16a34a;
            box-shadow: 0 0 0 8px rgba(22, 163, 74, 0.1);
        }

        .mp-status-icon-wrapper.status-pending {
            background: #fef3c7;
            color: #d97706;
            box-shadow: 0 0 0 8px rgba(217, 119, 6, 0.1);
        }

        .mp-status-icon-wrapper.status-refunded {
            background: #e0f2fe;
            color: #0284c7;
            box-shadow: 0 0 0 8px rgba(2, 132, 199, 0.1);
        }

        .mp-status-icon-wrapper.status-canceled {
            background: #fee2e2;
            color: #dc2626;
            box-shadow: 0 0 0 8px rgba(220, 38, 38, 0.1);
        }

        @keyframes mpPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.04); }
            100% { transform: scale(1); }
        }

        .mp-status-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .mp-status-desc {
            font-size: 0.92rem;
            color: #64748b;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .mp-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 24px;
            text-align: left;
            font-size: 0.88rem;
        }

        .mp-table tr:not(:last-child) td,
        .mp-table tr:not(:last-child) th {
            border-bottom: 1px solid #f1f5f9;
        }

        .mp-table th {
            padding: 12px 16px;
            color: #64748b;
            font-weight: 600;
            background: #f8fafc;
            width: 45%;
        }

        .mp-table td {
            padding: 12px 16px;
            color: #0f172a;
            font-weight: 600;
            text-align: right;
        }

        .btn-receipt {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            background: #16a34a;
            color: #ffffff;
            border: none;
            text-decoration: none !important;
            transition: all 0.2s ease;
            box-shadow: 0 8px 16px -4px rgba(22, 163, 74, 0.35);
        }

        .btn-receipt:hover {
            background: #15803d;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 12px 20px -4px rgba(22, 163, 74, 0.45);
        }

        /* Modern Language Selector Modal */
        .mp-lang-modal .modal-content {
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
            overflow: hidden;
            background: #ffffff;
        }
        .mp-lang-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .mp-lang-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 8px;
        }
        .mp-lang-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-radius: 16px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }
        .mp-lang-card:hover {
            background: #ffffff;
            border-color: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.12);
        }
        .mp-lang-card.active {
            background: rgba(79, 70, 229, 0.06);
            border-color: #4f46e5;
            box-shadow: 0 0 0 1px #4f46e5;
        }
        .mp-lang-card-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .mp-lang-flag {
            font-size: 24px;
            line-height: 1;
            display: inline-block;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.08));
        }
        .mp-lang-native {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
        .mp-lang-english {
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }
        .mp-lang-check {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mp-lang-card.active .mp-lang-check {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #4f46e5;
            color: #ffffff;
        }
        .mp-lang-radio-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #cbd5e1;
            transition: all 0.2s;
        }
        .mp-lang-card:hover .mp-lang-radio-circle {
            border-color: #4f46e5;
        }

        .mp-footer-branding {
            text-align: center;
            margin-top: 18px;
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }

        @media (max-width: 576px) {
            body { padding: 10px 6px; }
            .mp-card { border-radius: 16px; }
            .mp-body { padding: 20px 14px; }
        }
    </style>
</head>
<body>

    <div class="mp-container">
        <div class="mp-card">
            <!-- Top Action Bar -->
            <div class="mp-topbar">
                <div class="d-flex align-items-center gap-2">
                    <img src="<?php echo $data['brand']['favicon'];?>" alt="" style="width: 24px; height: 24px; border-radius: 50%;">
                    <span class="fw-bold small"><?php echo htmlspecialchars($data['brand']['name']);?></span>
                </div>
                
                <div class="mp-icon-btn" data-bs-target="#modal-language" data-bs-toggle="modal" title="<?php echo $data['lang']['language']?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6.371c0 4.418 -2.239 6.629 -5 6.629" /><path d="M4 6.371h7" /><path d="M5 9c0 2.144 2.252 3.908 6 4" /><path d="M12 20l4 -9l4 9" /><path d="M19.1 18h-6.2" /><path d="M6.694 3l.793 .582" /></svg>
                </div>
            </div>

            <!-- Body Status Content -->
            <div class="mp-body">
                <?php
                $status = strtolower($data['transaction']['status'] ?? 'pending');

                $statusMap = [
                    'completed' => [
                        'text' => $data['lang']['payment_successful'], 
                        'class' => 'status-completed', 
                        'textColor' => '#16a34a',
                        'desc' => $data['lang']['change_status_completed']
                    ],
                    'pending'   => [
                        'text' => $data['lang']['payment_pending'], 
                        'class' => 'status-pending', 
                        'textColor' => '#d97706',
                        'desc' => $data['lang']['change_status_pending']
                    ],
                    'refunded'  => [
                        'text' => $data['lang']['payment_refunded'], 
                        'class' => 'status-refunded', 
                        'textColor' => '#0284c7',
                        'desc' => $data['lang']['change_status_refunded']
                    ],
                    'canceled'  => [
                        'text' => $data['lang']['payment_canceled'], 
                        'class' => 'status-canceled', 
                        'textColor' => '#dc2626',
                        'desc' => $data['lang']['change_status_cancled']
                    ],
                ];

                $currentStatus = $statusMap[$status] ?? $statusMap['pending'];
                ?>

                <div class="mp-status-icon-wrapper <?php echo $currentStatus['class']; ?>">
                    <?php if ($status === 'completed'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    <?php elseif ($status === 'pending'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" /><path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" /></svg>
                    <?php elseif ($status === 'refunded'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                    <?php endif; ?>
                </div>

                <h2 class="mp-status-title" style="color: <?php echo $currentStatus['textColor']; ?>;">
                    <?php echo $currentStatus['text']; ?>
                </h2>

                <p class="mp-status-desc">
                    <?php echo $currentStatus['desc']; ?>
                </p>

                <?php if ($status !== 'canceled'): ?>
                    <table class="mp-table">
                        <tbody>
                            <tr>
                                <th><?php echo $data['lang']['payment_method']?></th>
                                <td><?php echo htmlspecialchars($data['transaction']['payment_method'] ?? 'Online Payment'); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $data['lang']['amount']?></th>
                                <td><?php echo money_round($data['transaction']['amount'] ?? 0, 2); ?> <?php echo $data['transaction']['currency'] ?? 'BDT'; ?></td>
                            </tr>
                            <?php if(!empty($data['transaction']['discount_amount']) && $data['transaction']['discount_amount'] > 0): ?>
                            <tr>
                                <th><?php echo $data['lang']['discount']?></th>
                                <td class="text-success">-<?php echo money_round($data['transaction']['discount_amount'], 2); ?> <?php echo $data['transaction']['currency'] ?? 'BDT'; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(!empty($data['transaction']['processing_fee']) && $data['transaction']['processing_fee'] > 0): ?>
                            <tr>
                                <th><?php echo $data['lang']['processing_fee']?></th>
                                <td><?php echo money_round($data['transaction']['processing_fee'], 2); ?> <?php echo $data['transaction']['currency'] ?? 'BDT'; ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th><?php echo $data['lang']['net_amount']?></th>
                                <td><?php echo money_round(($data['transaction']['amount'] ?? 0) - ($data['transaction']['discount_amount'] ?? 0) + ($data['transaction']['processing_fee'] ?? 0), 2); ?> <?php echo $data['transaction']['currency'] ?? 'BDT'; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $data['lang']['net_local_amount']?></th>
                                <td class="fw-bold" style="color: var(--mp-primary);"><?php echo money_round($data['transaction']['local_net_amount'] ?? 0, 2); ?> <?php echo $data['transaction']['local_currency'] ?? 'BDT'; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $data['lang']['status']?></th>
                                <td><span style="color: <?php echo $currentStatus['textColor']; ?>; text-transform: capitalize;"><?php echo $status; ?></span></td>
                            </tr>
                        </tbody>
                    </table>

                    <?php if ($status === 'completed'): ?>
                        <a href="<?php echo pp_checkout_address();?>?receipt" class="btn-receipt">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                            <?php echo $data['lang']['download_receipt']?>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer Watermark Branding -->
        <div class="mp-footer-branding">
            <?php echo htmlspecialchars($data['options']['watermark_text'] ?? 'Secured by PipraPay'); ?>
        </div>
    </div>

    <!-- Language Selector Modal -->
    <div class="modal fade mp-lang-modal" id="modal-language" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-2 pt-4 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="mp-lang-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0 text-dark"><?php echo $data['lang']['select_language'] ?? 'Select Language'; ?></h5>
                            <p class="text-muted small mb-0 mt-1"><?php echo $data['lang']['select_a_language'] ?? 'Choose your preferred language'; ?></p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-2">
                    <div class="mp-lang-grid">
                        <?php
                        $currentLang = $_SESSION['ui_language'] ?? ($data['brand']['locale']['language'] ?? 'en');
                        $languagesMeta = [
                            'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇺🇸', 'code' => 'EN', 'sub' => 'Default'],
                            'bn' => ['name' => 'Bangla', 'native' => 'বাংলা', 'flag' => '🇧🇩', 'code' => 'BN', 'sub' => 'Bengali'],
                            'hi' => ['name' => 'Hindi', 'native' => 'हिन्दी', 'flag' => '🇮🇳', 'code' => 'HI', 'sub' => 'Hindi'],
                            'ur' => ['name' => 'Urdu', 'native' => 'اردو', 'flag' => '🇵🇰', 'code' => 'UR', 'sub' => 'Urdu'],
                            'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'flag' => '🇸🇦', 'code' => 'AR', 'sub' => 'Arabic'],
                        ];
                        $supportedLanguages = !empty($data['supported_languages']) ? $data['supported_languages'] : [
                            'en' => 'English',
                            'bn' => 'বাংলা',
                            'hi' => 'हिन्दी',
                            'ur' => 'اردو',
                            'ar' => 'العربية',
                        ];
                        foreach ($supportedLanguages as $code => $label):
                            $meta = $languagesMeta[$code] ?? ['name' => $label, 'native' => $label, 'flag' => '🌐', 'code' => strtoupper($code), 'sub' => $label];
                            $isActive = ($currentLang === $code);
                        ?>
                            <div class="mp-lang-card <?= $isActive ? 'active' : '' ?>" onclick="selectModernLanguage('<?= htmlspecialchars($code) ?>')">
                                <div class="mp-lang-card-left">
                                    <span class="mp-lang-flag"><?= $meta['flag'] ?></span>
                                    <div class="mp-lang-info">
                                        <div class="mp-lang-native"><?= htmlspecialchars($meta['native']) ?></div>
                                        <div class="mp-lang-english"><?= htmlspecialchars($meta['sub']) ?></div>
                                    </div>
                                </div>
                                <div class="mp-lang-check">
                                    <?php if ($isActive): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    <?php else: ?>
                                        <div class="mp-lang-radio-circle"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo pp_assets('footer'); ?>

    <script data-cfasync="false">
        function selectModernLanguage(code) {
            if (code) {
                location.href = '?lang=' + code;
            }
        }

        function hitLanguage(){
            var language = document.querySelector("#model-languages")?.value;
            if (language) {
                location.href = '?lang=' + language;
            }
        }
    </script>
</body>
</html>
