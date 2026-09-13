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
                location.href = '<?php echo pp_checkout_address().'?gateway='.$_GET['gateway'];?>';
            </script>
<?php
            exit();
        }
    }

    if(isset($_GET['gateway'])){
        $gateway_info = pp_gateway_info($_GET['gateway'], $data);

        if($gateway_info['status'] == false){
            http_response_code(403);
            exit('Direct access not allowed');
        }
    }else{
        http_response_code(403);
        exit('Direct access not allowed');
    }

    $primaryColor = !empty($gateway_info['gateway']['primary_color']) && $gateway_info['gateway']['primary_color'] !== '--' ? $gateway_info['gateway']['primary_color'] : '#4f46e5';
    $textColor    = !empty($gateway_info['gateway']['text_color']) && $gateway_info['gateway']['text_color'] !== '--' ? $gateway_info['gateway']['text_color'] : '#ffffff';
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
            --mp-text-btn: <?php echo $textColor; ?>;
            --mp-card-bg: rgba(255, 255, 255, 0.96);
            --mp-card-border: rgba(226, 232, 240, 0.9);
            --mp-card-shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.08), 0 0 1px 1px rgba(0, 0, 0, 0.04);
            --mp-radius: 22px;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            color: #1e293b;
            <?= $bgStyle ?>
            margin: 0;
            padding: 24px 12px;
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
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--mp-card-border);
            border-radius: var(--mp-radius);
            box-shadow: var(--mp-card-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .mp-topbar {
            padding: 14px 20px;
            background: rgba(248, 250, 252, 0.85);
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
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .mp-icon-btn:hover {
            background: var(--mp-primary);
            border-color: var(--mp-primary);
            color: var(--mp-text-btn);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .mp-body {
            padding: 26px 24px;
        }

        .mp-gateway-header {
            text-align: center;
            margin-bottom: 22px;
        }

        .mp-gw-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.07), 0 0 0 1px rgba(0, 0, 0, 0.04);
            margin-bottom: 12px;
        }

        .mp-gw-logo {
            height: 46px;
            max-width: 140px;
            object-fit: contain;
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

        /* Payment Instructions Box */
        .payment-instructions {
            background: <?php echo $gateway_info['gateway']['primary_color'];?>;
            background: linear-gradient(145deg, <?php echo $gateway_info['gateway']['primary_color'];?>, <?php echo pp_hexToRgba($gateway_info['gateway']['primary_color'], 0.92)?>);
            color: <?php echo $gateway_info['gateway']['text_color'];?>;
            border-radius: 18px;
            padding: 16px 20px;
            margin: 16px 0 22px 0;
            list-style: none;
            box-shadow: 0 12px 28px -6px <?php echo pp_hexToRgba($gateway_info['gateway']['primary_color'], 0.38); ?>;
        }

        .payment-instructions li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            word-break: break-word;
            border-bottom: 1px solid <?php echo pp_hexToRgba($gateway_info['gateway']['text_color'], 0.18)?>;
            font-size: 0.93rem;
            font-weight: 500;
            line-height: 1.45;
        }

        .payment-instructions li:first-child {
            padding-top: 4px;
        }

        .payment-instructions li:last-child {
            border-bottom: none;
            padding-bottom: 4px;
        }

        .payment-instructions li .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: <?php echo $gateway_info['gateway']['text_color'];?>;
            min-width: 8px;
            box-shadow: 0 0 8px <?php echo pp_hexToRgba($gateway_info['gateway']['text_color'], 0.6)?>;
        }

        .payment-instructions li p {
            margin: 0;
            flex: 1;
            display: inline;
        }

        .payment-instructions li .dynamic-value {
            font-weight: 800;
            background: rgba(255, 255, 255, 0.22);
            padding: 3px 8px;
            border-radius: 7px;
            letter-spacing: 0.4px;
            display: inline-block;
            margin: 0 3px;
        }

        /* Modern Copy & Action Button */
        .payment-instructions li .button-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 5px 10px;
            margin-left: 6px;
            background-color: <?php echo $gateway_info['gateway']['text_color'];?>;
            color: <?php echo $gateway_info['gateway']['primary_color'];?>;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.78rem;
            line-height: 1;
            vertical-align: middle;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.14);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .payment-instructions li .button-icon:hover {
            transform: translateY(-1px) scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            opacity: 0.95;
        }

        .payment-instructions li .button-icon:active {
            transform: translateY(0) scale(0.95);
        }

        /* Fix SVG size inside copy button and instructions */
        .payment-instructions li .button-icon svg,
        .payment-instructions li svg {
            width: 15px !important;
            height: 15px !important;
            min-width: 15px !important;
            min-height: 15px !important;
            stroke: currentColor;
            stroke-width: 2.2;
            display: inline-block;
            vertical-align: middle;
        }

        /* Form Controls & Verification Submit */
        .payment-form-submit {
            margin-top: 10px;
        }

        .form-label {
            font-weight: 700;
            font-size: 0.88rem;
            color: #334155;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            border-radius: 12px;
            padding: 13px 16px;
            font-size: 0.96rem;
            border: 1.5px solid #cbd5e1;
            background-color: #f8fafc;
            color: #0f172a;
            transition: all 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: var(--mp-primary);
            box-shadow: 0 0 0 4px <?php echo pp_hexToRgba($primaryColor, 0.16)?>;
            outline: none;
        }

        .payment-form-btn, .btn-primary {
            --tblr-btn-border-color: transparent;
            --tblr-btn-hover-border-color: transparent;
            --tblr-btn-active-border-color: transparent;
            --tblr-btn-color: var(--mp-text-btn);
            --tblr-btn-bg: var(--mp-primary);
            --tblr-btn-hover-color: var(--mp-text-btn);
            --tblr-btn-hover-bg: <?php echo pp_hexToRgba($primaryColor, 0.9)?>;
            border-radius: 12px;
            padding: 13px 24px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.3px;
            background: var(--mp-primary);
            color: var(--mp-text-btn);
            border: none;
            width: 100%;
            box-shadow: 0 8px 20px -4px <?php echo pp_hexToRgba($primaryColor, 0.4); ?>;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .payment-form-btn:hover, .btn-primary:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 12px 24px -4px <?php echo pp_hexToRgba($primaryColor, 0.5); ?>;
            color: var(--mp-text-btn);
        }

        .payment-form-btn:active, .btn-primary:active {
            transform: translateY(0);
        }

        /* Modal Preview */
        .bp-modal, .pp-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 15px;
        }

        .bp-modal-content, .pp-modal-content {
            position: relative;
            background: #ffffff;
            border-radius: 18px;
            padding: 16px;
            max-width: 95vw;
            max-height: 95vh;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: bpZoomIn 0.25s ease-out;
        }

        #bp-modal-image, #pp-modal-image {
            display: block;
            max-width: 320px;
            border-radius: 12px;
            width: 100%;
        }

        .bp-close, .pp-close {
            position: absolute;
            top: -12px;
            right: -12px;
            width: 36px;
            height: 36px;
            background: #ef4444;
            color: #ffffff;
            font-size: 20px;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);
            transition: transform 0.2s ease;
        }

        .bp-close:hover, .pp-close:hover {
            transform: scale(1.1);
        }

        @keyframes bpZoomIn {
            from { transform: scale(0.94); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .mp-footer-branding {
            text-align: center;
            margin-top: 20px;
            font-size: 0.82rem;
            color: #64748b;
            font-weight: 500;
        }

        @media (max-width: 576px) {
            body { padding: 12px 8px; }
            .mp-card { border-radius: 18px; }
            .mp-body { padding: 20px 16px; }
            .payment-instructions { padding: 14px 16px; }
        }
    </style>
</head>
<body>

    <div class="mp-container">
        <div class="mp-card">
            <!-- Top Action Bar -->
            <div class="mp-topbar">
                <div class="mp-icon-btn" onclick="location.href='<?php echo pp_checkout_address();?>'" title="<?php echo $data['lang']['close']?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                </div>
                
                <div class="mp-amount-badge">
                    <span><?php echo $data['lang']['amount'] ?? 'Amount'; ?>:</span>
                    <span class="amount"><?php echo money_round($data['transaction']['amount'] ?? 0, 2); ?> <?php echo htmlspecialchars($data['transaction']['currency'] ?? 'BDT'); ?></span>
                </div>

                <div class="mp-icon-btn" data-bs-target="#modal-language" data-bs-toggle="modal" title="<?php echo $data['lang']['language']?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6.371c0 4.418 -2.239 6.629 -5 6.629" /><path d="M4 6.371h7" /><path d="M5 9c0 2.144 2.252 3.908 6 4" /><path d="M12 20l4 -9l4 9" /><path d="M19.1 18h-6.2" /><path d="M6.694 3l.793 .582" /></svg>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="mp-body">
                <div class="mp-gateway-header">
                    <div class="mp-gw-logo-wrap">
                        <img src="<?php echo $gateway_info['gateway']['logo'];?>" alt="<?php echo htmlspecialchars($gateway_info['gateway']['display'] ?? ''); ?>" class="mp-gw-logo">
                    </div>
                </div>

                <?php
                   pp_gateway_render($_GET['gateway'] ?? '', $data);
                ?>
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
                        <?php 
                        $supportedLanguages = !empty($gateway_info['supported_languages']) ? $gateway_info['supported_languages'] : (!empty($data['supported_languages']) ? $data['supported_languages'] : [
                            'en' => 'English',
                            'bn' => 'বাংলা',
                            'hi' => 'हिन्दी',
                            'ur' => 'اردو',
                            'ar' => 'العربية',
                        ]);
                        foreach ($supportedLanguages as $code => $language): ?>
                            <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($language) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <?php echo pp_assets('footer'); ?>

    <script data-cfasync="false">
        var ppLang = {
            copied:        '<?php echo addslashes($data['lang']['copied_successfully'] ?? 'Copied!')?>',
            copiedDesc:    '<?php echo addslashes($data['lang']['copy_content_copied'] ?? 'Copied to clipboard.')?>',
            copyFailed:    '<?php echo addslashes($data['lang']['copy_failed'] ?? 'Copy failed')?>',
            copyFailedDesc:'<?php echo addslashes($data['lang']['copy_failed_text'] ?? 'Could not copy.')?>',
            noContent:     '<?php echo addslashes($data['lang']['copy_no_content'] ?? 'No content to copy')?>',
            somethingWrong:'<?php echo addslashes($data['lang']['something_wrong'] ?? 'Something went wrong')?>',
            supportText:   '<?php echo addslashes($data['lang']['support_contact_text'] ?? 'Please contact support.')?>',
        };

        function copy_value(content){
            if (!content) {
                createToast({
                    title: ppLang.somethingWrong,
                    description: ppLang.noContent,
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 4000,
                    top: 20
                });
                return;
            }

            navigator.clipboard.writeText(content).then(() => {
                createToast({
                    title: ppLang.copied,
                    description: ppLang.copiedDesc,
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                    timeout: 3000,
                    top: 20
                });
            }).catch((err) => {
                createToast({
                    title: ppLang.copyFailed,
                    description: ppLang.copyFailedDesc,
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 4000,
                    top: 20
                });
            });
        }

        function failed(title, message){
            createToast({
                title: title,
                description: message,
                svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                timeout: 6000,
                top: 20
            });
        }

        function success(){
            location.href = "<?php echo pp_checkout_address();?>";
        }

        function hitLanguage(){
            var language = document.querySelector("#model-languages").value;
            if(language !== ""){
                location.href = '<?php echo pp_checkout_address().'?gateway='.$_GET['gateway'];?>&lang=' + language;
            }
        }

        $(document).ready(function() {
            $('#form').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                var payBtn = document.querySelector("#payButton");
                if (payBtn) {
                    payBtn.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
                }

                $.ajax({
                    url: '<?php echo pp_site_address(); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    success: function(data) {
                        if (payBtn) {
                            payBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3l0 -8" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg> <?php echo $data['lang']['pay_now']?>';
                        }

                        if (data.status == "true") {
                            location.href = data.redirect;
                        } else {
                            createToast({
                                title: data.title,
                                description: data.message,
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
                            title: ppLang.somethingWrong,
                            description: ppLang.supportText,
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
