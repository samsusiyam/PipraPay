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
    <title><?php echo $data['lang']['invoice']?> - <?php echo htmlspecialchars($data['brand']['name']);?></title>
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
            --mp-card-bg: #ffffff;
            --mp-radius: 20px;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            color: #1e293b;
            <?= $bgStyle ?>
            margin: 0;
            padding: 30px 10px;
        }

        .mp-invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: var(--mp-card-bg);
            border-radius: var(--mp-radius);
            box-shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.08), 0 0 1px 1px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
        }

        .mp-invoice-header {
            padding: 28px 36px;
            background: rgba(248, 250, 252, 0.8);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .mp-invoice-body {
            padding: 36px;
        }

        .mp-info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            height: 100%;
        }

        .mp-info-box-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .mp-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            margin-top: 24px;
        }

        .mp-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.84rem;
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .mp-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .mp-table tr:last-child td {
            border-bottom: none;
        }

        .mp-summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 24px;
            margin-top: 28px;
        }

        .btn-pay-now {
            background: var(--mp-primary);
            color: var(--mp-text-btn);
            font-weight: 700;
            border-radius: 12px;
            padding: 12px 28px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 20px -4px <?php echo pp_hexToRgba($primaryColor, 0.4); ?>;
            transition: all 0.2s ease;
        }

        .btn-pay-now:hover {
            transform: translateY(-1px);
            color: var(--mp-text-btn);
            box-shadow: 0 12px 24px -4px <?php echo pp_hexToRgba($primaryColor, 0.5); ?>;
        }

        .mp-badge-paid {
            padding: 6px 14px;
            background: #dcfce7;
            color: #16a34a;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .mp-badge-unpaid {
            padding: 6px 14px;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
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
            background: <?php echo pp_hexToRgba($primaryColor, 0.1); ?>;
            color: var(--mp-primary);
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
            border-color: var(--mp-primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px <?php echo pp_hexToRgba($primaryColor, 0.12); ?>;
        }
        .mp-lang-card.active {
            background: <?php echo pp_hexToRgba($primaryColor, 0.06); ?>;
            border-color: var(--mp-primary);
            box-shadow: 0 0 0 1px var(--mp-primary);
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
            background: var(--mp-primary);
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
            border-color: var(--mp-primary);
        }

        .mp-footer-branding {
            text-align: center;
            margin-top: 24px;
            font-size: 0.82rem;
            color: #64748b;
        }

        @media (max-width: 768px) {
            body { padding: 10px 6px; }
            .mp-invoice-header, .mp-invoice-body { padding: 20px 16px; }
        }
    </style>
</head>
<body>

    <div class="mp-invoice-container">
        <!-- Invoice Header -->
        <div class="mp-invoice-header">
            <div>
                <img src="<?php echo $data['brand']['logo'];?>" alt="" style="max-height: 42px;">
            </div>

            <div class="d-flex align-items-center gap-3">
                <?php if($data['invoice']['status'] == "paid"): ?>
                    <span class="mp-badge-paid"><?php echo $data['lang']['badge_' . $data['invoice']['status']] ?? 'PAID'; ?></span>
                <?php else: ?>
                    <span class="mp-badge-unpaid"><?php echo $data['lang']['badge_' . $data['invoice']['status']] ?? 'UNPAID'; ?></span>
                <?php endif; ?>

                <div class="cursor-pointer" data-bs-target="#modal-language" data-bs-toggle="modal" style="cursor: pointer; color: var(--mp-primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6.371c0 4.418 -2.239 6.629 -5 6.629" /><path d="M4 6.371h7" /><path d="M5 9c0 2.144 2.252 3.908 6 4" /><path d="M12 20l4 -9l4 9" /><path d="M19.1 18h-6.2" /><path d="M6.694 3l.793 .582" /></svg>
                </div>
            </div>
        </div>

        <!-- Invoice Body -->
        <div class="mp-invoice-body">
            <!-- Dates & Methods Meta -->
            <div class="row g-3 mb-4">
                <div class="col-sm-4">
                    <div class="text-muted small fw-semibold text-uppercase"><?php echo $data['lang']['invoice_date']?></div>
                    <div class="fw-bold text-dark mt-1"><?php echo $data['invoice']['created_date']?></div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small fw-semibold text-uppercase"><?php echo $data['lang']['due_date']?></div>
                    <div class="fw-bold text-dark mt-1"><?php echo $data['invoice']['due_date']?></div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small fw-semibold text-uppercase"><?php echo $data['lang']['payment_method']?></div>
                    <div class="fw-bold text-dark mt-1"><?php echo ($data['invoice']['status'] == "paid") ? htmlspecialchars($data['invoice']['gateway'] ?? 'N/A') : 'Pending'; ?></div>
                </div>
            </div>

            <!-- Bill From / Bill To -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mp-info-box">
                        <div class="mp-info-box-title"><?php echo $data['lang']['bill_from']?></div>
                        <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($data['brand']['name']);?></h5>
                        <div class="small text-muted">
                            <?php if(!empty($data['brand']['support']['email'])): ?>
                                <div><strong><?php echo $data['lang']['email']?>:</strong> <?php echo $data['brand']['support']['email'];?></div>
                            <?php endif; ?>
                            <?php if(!empty($data['brand']['support']['phone'])): ?>
                                <div><strong><?php echo $data['lang']['phone']?>:</strong> <?php echo $data['brand']['support']['phone'];?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mp-info-box">
                        <div class="mp-info-box-title"><?php echo $data['lang']['bill_to']?></div>
                        <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($data['invoice']['customer']['name'] ?? 'Customer');?></h5>
                        <div class="small text-muted">
                            <?php if(!empty($data['invoice']['customer']['email'])): ?>
                                <div><strong><?php echo $data['lang']['email']?>:</strong> <?php echo $data['invoice']['customer']['email'];?></div>
                            <?php endif; ?>
                            <?php if(!empty($data['invoice']['customer']['mobile'])): ?>
                                <div><strong><?php echo $data['lang']['phone']?>:</strong> <?php echo $data['invoice']['customer']['mobile'];?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive">
                <table class="mp-table">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">#</th>
                            <th style="width: 50%;"><?php echo $data['lang']['description']?></th>
                            <th style="width: 10%; text-align: center;"><?php echo $data['lang']['qty']?></th>
                            <th style="width: 15%; text-align: right;"><?php echo $data['lang']['unit_price']?></th>
                            <th style="width: 20%; text-align: right;"><?php echo $data['lang']['amount']?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $subtotal = 0;
                            $totalDiscount = 0;
                            $totalVAT = 0;
                            $grandTotal = 0;

                            if (!empty($data['items'])):
                                $counter = 1;
                                foreach ($data['items'] as $item):
                                    $itemTotalBeforeDiscount = ($item['unitPrice'] ?? 0) * ($item['quantity'] ?? 0);
                                    $discountAmount = $item['discount'] ?? 0;
                                    $priceAfterDiscount = $itemTotalBeforeDiscount - $discountAmount;
                                    $vatAmount = $priceAfterDiscount * (($item['vat'] ?? 0) / 100);
                                    $itemTotal = $priceAfterDiscount + $vatAmount;

                                    $subtotal += $itemTotalBeforeDiscount;
                                    $totalDiscount += $discountAmount;
                                    $totalVAT += $vatAmount;
                                    $grandTotal += $itemTotal;
                        ?>
                                    <tr>
                                        <td style="text-align: center;" class="text-muted"><?php echo $counter++; ?></td>
                                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($item['description']); ?></td>
                                        <td style="text-align: center;"><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td style="text-align: right;"><?php echo money_round($item['unitPrice'] ?? 0, 2) . ' ' . $data['invoice']['currency']; ?></td>
                                        <td style="text-align: right;" class="fw-bold"><?php echo money_round($itemTotal, 2) . ' ' . $data['invoice']['currency']; ?></td>
                                    </tr>
                        <?php
                                endforeach;
                            endif;
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Notes and Summary -->
            <div class="mp-summary-card">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-3 mb-md-0">
                        <?php if(!empty($data['invoice']['note'])): ?>
                            <div class="small fw-bold text-muted text-uppercase mb-1"><?php echo $data['lang']['note']?></div>
                            <p class="text-muted small mb-0"><?php echo htmlspecialchars($data['invoice']['note']);?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted"><?php echo $data['lang']['subtotal']?>:</span>
                            <span class="fw-semibold"><?php echo money_round($subtotal, 2) . ' ' . $data['invoice']['currency']; ?></span>
                        </div>
                        <?php if($totalDiscount > 0): ?>
                            <div class="d-flex justify-content-between mb-2 small text-success">
                                <span><?php echo $data['lang']['discount']?>:</span>
                                <span>-<?php echo money_round($totalDiscount, 2) . ' ' . $data['invoice']['currency']; ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($totalVAT > 0): ?>
                            <div class="d-flex justify-content-between mb-2 small text-muted">
                                <span>VAT/Tax:</span>
                                <span>+<?php echo money_round($totalVAT, 2) . ' ' . $data['invoice']['currency']; ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between pt-2 border-top border-2 fw-bold text-dark fs-5">
                            <span><?php echo $data['lang']['total']?>:</span>
                            <span style="color: var(--mp-primary);"><?php echo money_round($grandTotal, 2) . ' ' . $data['invoice']['currency']; ?></span>
                        </div>
                    </div>
            <!-- Totals -->
            <div class="row justify-content-end mb-4">
                <div class="col-md-5">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-1">
                            <span class="text-muted"><?php echo $data['lang']['subtotal'] ?? 'Subtotal'; ?></span>
                            <span class="fw-semibold"><?php echo money_round($data['totals']['subtotal'] ?? 0, 2) . ' ' . $data['invoice']['currency']; ?></span>
                        </li>
                        <?php if(!empty($data['totals']['discount']) && $data['totals']['discount'] > 0): ?>
                            <li class="d-flex justify-content-between py-1 text-success">
                                <span><?php echo $data['lang']['discount'] ?? 'Discount'; ?></span>
                                <span>-<?php echo money_round($data['totals']['discount'], 2) . ' ' . $data['invoice']['currency']; ?></span>
                            </li>
                        <?php endif; ?>
                        <li class="d-flex justify-content-between py-2 border-top mt-2">
                            <span class="fw-bold"><?php echo $data['lang']['total'] ?? 'Total'; ?></span>
                            <span class="fw-bold fs-5" style="color: var(--mp-primary);"><?php echo money_round($data['totals']['total'] ?? 0, 2) . ' ' . $data['invoice']['currency']; ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if($data['invoice']['status'] !== "paid"): ?>
                <form action="" method="POST" id="form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="invoice-process">
                    <input type="hidden" name="ref" value="<?php echo htmlspecialchars($data['invoice']['id']);?>">

                    <?php pp_renderFormFields('invoice', $data); ?>
                    <div class="mt-4">
                        <button type="submit" id="payButton" class="btn btn-primary w-100 py-3 fw-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3l0 -8" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                            <?php echo $data['lang']['pay_now'] ?? 'Pay Invoice'; ?>
                        </button>
                    </div>
                </form>
            <?php endif; ?>

        </div>
    </div>

    <!-- Footer Watermark Branding -->
    <div class="mp-footer-branding">
        <?php echo htmlspecialchars($data['options']['watermark_text'] ?? 'Secured by PipraPay'); ?>
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
