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

    $primaryColor = !empty($data['options']['primary_color']) && $data['options']['primary_color'] !== '--' ? $data['options']['primary_color'] : '#4f46e5';
    $textColor    = !empty($data['options']['text_color']) && $data['options']['text_color'] !== '--' ? $data['options']['text_color'] : '#ffffff';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $data['lang']['payment_link']?> - <?php echo htmlspecialchars($data['brand']['name']);?></title>
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
            --mp-text-btn: <?php echo $textColor; ?>;
            --mp-card-bg: rgba(255, 255, 255, 0.96);
            --mp-radius: 20px;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            color: #1e293b;
            <?= $bgStyle ?>
            margin: 0;
            padding: 30px 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .mp-container {
            max-width: 520px;
            width: 100%;
            margin: 0 auto;
        }

        .mp-card {
            background: var(--mp-card-bg);
            backdrop-filter: blur(20px);
            border-radius: var(--mp-radius);
            box-shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.08), 0 0 1px 1px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
        }

        .mp-card-topbar {
            padding: 14px 20px;
            background: rgba(248, 250, 252, 0.8);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mp-body {
            padding: 28px 24px;
        }

        .mp-product-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .mp-product-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .mp-product-desc {
            font-size: 0.9rem;
            color: #64748b;
            margin: 0;
        }

        .btn-primary {
            --tblr-btn-border-color: transparent;
            --tblr-btn-hover-border-color: transparent;
            --tblr-btn-active-border-color: transparent;
            --tblr-btn-color: var(--mp-text-btn);
            --tblr-btn-bg: var(--mp-primary);
            --tblr-btn-hover-color: var(--mp-text-btn);
            --tblr-btn-hover-bg: <?php echo pp_hexToRgba($primaryColor, 0.88)?>;
            --tblr-btn-active-color: var(--mp-text-btn);
            --tblr-btn-active-bg: <?php echo pp_hexToRgba($primaryColor, 0.88)?>;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 20px -4px <?php echo pp_hexToRgba($primaryColor, 0.35); ?>;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px -4px <?php echo pp_hexToRgba($primaryColor, 0.45); ?>;
        }

        .form-label {
            font-weight: 700;
            font-size: 0.88rem;
            color: #334155;
            margin-bottom: 6px;
            display: block;
        }

        .input-group-text {
            border-radius: 10px 0 0 10px !important;
            background: #f1f5f9;
            font-weight: 700;
            color: #475569;
            border: 1px solid #cbd5e1;
            border-right: none;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0 !important;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 11px 14px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--mp-primary);
            box-shadow: 0 0 0 3px <?php echo pp_hexToRgba($primaryColor, 0.15); ?>;
        }

        .mp-footer-branding {
            text-align: center;
            margin-top: 18px;
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="mp-container">
        <!-- Logo -->
        <div class="text-center mb-3">
            <img src="<?php echo $data['brand']['logo'];?>" alt="" style="max-height: 42px;">
        </div>

        <div class="mp-card">
            <div class="mp-card-topbar">
                <div class="fw-bold small text-muted"><?php echo $data['lang']['payment_link']?></div>
                <div style="cursor: pointer; color: var(--mp-primary);" data-bs-target="#modal-language" data-bs-toggle="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6.371c0 4.418 -2.239 6.629 -5 6.629" /><path d="M4 6.371h7" /><path d="M5 9c0 2.144 2.252 3.908 6 4" /><path d="M12 20l4 -9l4 9" /><path d="M19.1 18h-6.2" /><path d="M6.694 3l.793 .582" /></svg>
                </div>
            </div>

            <div class="mp-body">
                <?php if($data['paymentLink']['status'] !== "active"): ?>
                    <div class="text-center py-4">
                        <div class="mb-3 text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 16v.01" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                        </div>
                        <h3 class="fw-bold text-dark"><?php echo $data['lang']['product_not_active']?></h3>
                        <p class="text-muted small"><?php echo $data['lang']['product_not_active_text'] ?? 'This link is not active.'; ?></p>
                    </div>
                <?php else: ?>
                    <form action="" method="POST" id="form" enctype="multipart/form-data">
                        <?php pp_renderFormFields('payment-link', $data); ?>
                        <div class="mt-4">
                            <button type="submit" id="payButton" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3l0 -8" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                                <?php echo $data['lang']['pay_now']?>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

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
        
        $(document).ready(function() {
            $('#form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var payBtn = document.querySelector("#payButton");
                if (payBtn) {
                    payBtn.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
                }

                $.ajax({
                    url: '<?php echo pp_site_address(); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (payBtn) {
                            payBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3l0 -8" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg> <?php echo $data['lang']['pay_now']?>';
                        }

                        if (data.status == "true") {
                            location.href = data.redirect;
                        } else {
                            createToast({
                                title: data.title || 'Error',
                                description: data.message || 'Payment failed',
                                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                                timeout: 6000
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        if (payBtn) {
                            payBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3l0 -8" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg> <?php echo $data['lang']['pay_now']?>';
                        }
                        createToast({
                            title: 'Error',
                            description: 'Something went wrong. Please try again.',
                            svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                            timeout: 6000
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
