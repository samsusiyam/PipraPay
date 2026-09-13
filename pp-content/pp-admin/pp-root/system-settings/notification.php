<?php
if (!defined('PipraPay_INIT')) {
    http_response_code(403);
    exit('Direct access not allowed');
}

if (!canAccessPage(json_decode($global_response_permission['response'][0]['permission'], true), 'system_settings', $global_user_response['response'][0]['role'])) {
    http_response_code(403);
    exit('Access denied. You need permission to perform this action. Please contact the admin.');
}

if (!hasPermission(json_decode($global_response_permission['response'][0]['permission'], true), 'system_settings', 'manage_notification', $global_user_response['response'][0]['role'])) {
    http_response_code(403);
    exit('Access denied. You need permission to perform this action. Please contact the admin.');
}

// Check configuration status for tab badges
$tg_configured = !empty(get_env('notification_telegram_token')) && get_env('notification_telegram_token') !== '--' && !empty(get_env('notification_telegram_chat_id')) && get_env('notification_telegram_chat_id') !== '--';
$dc_configured = !empty(get_env('notification_discord_webhook')) && get_env('notification_discord_webhook') !== '--';
$wa_configured = !empty(get_env('notification_whatsapp_api_url')) && get_env('notification_whatsapp_api_url') !== '--';
$em_configured = !empty(get_env('notification_email_smtp_host')) && get_env('notification_email_smtp_host') !== '--';
$sms_configured = !empty(get_env('notification_sms_api_url')) && get_env('notification_sms_api_url') !== '--';
?>

<div class="page-header d-print-none" aria-label="Page header">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="load_content('System Settings','<?php echo $site_url.$path_admin ?>/system-settings','nav-item-system-settings')">System Settings</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Notification Settings</a></li>
                    </ol>
                </div>
                <h2 class="page-title d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                    Notification & Instant Alert Channels
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <button type="button" class="btn btn-primary btn-save-notifications d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Save All Settings
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom-0 pb-0 bg-light">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-matrix" class="nav-link active fw-medium d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="true" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"/><path d="M9 4v16"/><path d="M15 4v16"/><path d="M4 9h16"/><path d="M4 15h16"/></svg>
                            Routing Rules
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-telegram" class="nav-link fw-medium d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4" /></svg>
                            Telegram Bot
                            <span class="badge badge-sm <?= $tg_configured ? 'bg-green-lt' : 'bg-secondary-lt' ?> ms-1"><?= $tg_configured ? 'Active' : 'Unconfigured' ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-discord" class="nav-link fw-medium d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-indigo" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M14 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8.5 17c0 1 1.5 3 3.5 3s3.5 -2 3.5 -3" /><path d="M15.5 17c0 1 1.5 3 3.5 3c1.5 0 2.8 -1.1 3.5 -2.5c.7 -1.4 .5 -4.5 0 -8c-.5 -3.5 -2 -5.5 -4 -6c-.7 .5 -1.2 1.3 -1.5 2c-1.5 -.5 -3.5 -.5 -5 0c-.3 -.7 -.8 -1.5 -1.5 -2c-2 .5 -3.5 2.5 -4 6c-.5 3.5 -.7 6.6 0 8c.7 1.4 2 2.5 3.5 2.5c2 0 3.5 -2 3.5 -3" /></svg>
                            Discord Webhook
                            <span class="badge badge-sm <?= $dc_configured ? 'bg-green-lt' : 'bg-secondary-lt' ?> ms-1"><?= $dc_configured ? 'Active' : 'Unconfigured' ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-whatsapp" class="nav-link fw-medium d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                            WhatsApp Bot
                            <span class="badge badge-sm <?= $wa_configured ? 'bg-green-lt' : 'bg-secondary-lt' ?> ms-1"><?= $wa_configured ? 'Active' : 'Unconfigured' ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-email" class="nav-link fw-medium d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-red" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                            Email (SMTP)
                            <span class="badge badge-sm <?= $em_configured ? 'bg-green-lt' : 'bg-secondary-lt' ?> ms-1"><?= $em_configured ? 'Active' : 'Unconfigured' ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-sms" class="nav-link fw-medium d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" /></svg>
                            SMS Gateway
                            <span class="badge badge-sm <?= $sms_configured ? 'bg-green-lt' : 'bg-secondary-lt' ?> ms-1"><?= $sms_configured ? 'Active' : 'Unconfigured' ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content">
                    
                    <!-- TAB 1: NOTIFICATION RULES MATRIX -->
                    <div class="tab-pane active show" id="tab-matrix" role="tabpanel">
                        <div class="mb-4">
                            <h3 class="card-title text-primary mb-1">⚡ Instant Event Triggers & Routing Matrix</h3>
                            <p class="text-secondary small mb-0">Select which notification channels receive alerts for each system event. Make sure to configure the corresponding channel credentials in the tabs above.</p>
                        </div>

                        <!-- Admin Alerts Table -->
                        <div class="card border mb-4">
                            <div class="card-header bg-light py-2">
                                <h4 class="card-title mb-0 fs-5 text-dark d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
                                    Admin Instant Alerts
                                </h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%;">Event Description</th>
                                            <th class="text-center">Telegram</th>
                                            <th class="text-center">Discord</th>
                                            <th class="text-center">WhatsApp</th>
                                            <th class="text-center">Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-success d-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                                                    Payment Received / Success
                                                </div>
                                                <div class="text-muted small">Instant notification when a customer completes a payment.</div>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_admin_payment_success_telegram" <?= get_env('notification_event_admin_payment_success_telegram') !== 'no' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_admin_payment_success_discord" <?= get_env('notification_event_admin_payment_success_discord') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_admin_payment_success_whatsapp" <?= get_env('notification_event_admin_payment_success_whatsapp') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_admin_payment_success_email" <?= get_env('notification_event_admin_payment_success_email') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-danger d-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-x" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M10 10l4 4m0 -4l-4 4" /></svg>
                                                    Payment Failed / Cancelled
                                                </div>
                                                <div class="text-muted small">Notify when a customer cancels or transaction fails.</div>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_admin_payment_failed_telegram" <?= get_env('notification_event_admin_payment_failed_telegram') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_admin_payment_failed_discord" <?= get_env('notification_event_admin_payment_failed_discord') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center"><span class="text-muted small">—</span></td>
                                            <td class="text-center"><span class="text-muted small">—</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-warning d-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wifi-off" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 18l.01 0" /><path d="M9.172 15.172a4 4 0 0 1 5.656 0" /><path d="M6.343 12.343a8 8 0 0 1 11.314 0" /><path d="M3.515 9.515c4.686 -4.687 12.284 -4.687 17 0" /><path d="M3 3l18 18" /></svg>
                                                    Companion Device Offline Alert
                                                </div>
                                                <div class="text-muted small">Alert admin when an SMS gateway device disconnects or goes offline.</div>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_device_offline_telegram" <?= get_env('notification_event_device_offline_telegram') !== 'no' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_device_offline_discord" <?= get_env('notification_event_device_offline_discord') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_device_offline_whatsapp" <?= get_env('notification_event_device_offline_whatsapp') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center"><span class="text-muted small">—</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-orange d-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-battery-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 7h11a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-11a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2" /><path d="M21 10.5v3" /><path d="M7 10v4" /></svg>
                                                    Device Low Battery Alert (&lt; 15%)
                                                </div>
                                                <div class="text-muted small">Notify when phone battery drops below critical levels.</div>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_device_battery_telegram" <?= get_env('notification_event_device_battery_telegram') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_device_battery_discord" <?= get_env('notification_event_device_battery_discord') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center"><span class="text-muted small">—</span></td>
                                            <td class="text-center"><span class="text-muted small">—</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Customer Notifications Table -->
                        <div class="card border">
                            <div class="card-header bg-light py-2">
                                <h4 class="card-title mb-0 fs-5 text-dark d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                    Customer Automated Receipts
                                </h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 60%;">Event Description</th>
                                            <th class="text-center">Customer Email</th>
                                            <th class="text-center">Customer SMS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">Payment Receipt & Invoice Confirmation</div>
                                                <div class="text-muted small">Send instant transaction receipt to customer upon successful payment.</div>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_customer_payment_success_email" <?= get_env('notification_event_customer_payment_success_email') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input" type="checkbox" id="notification_event_customer_payment_success_sms" <?= get_env('notification_event_customer_payment_success_sms') === 'yes' ? 'checked' : '' ?>>
                                                </label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: TELEGRAM BOT -->
                    <div class="tab-pane" id="tab-telegram" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h3 class="card-title text-primary mb-1">Telegram Bot Configuration</h3>
                                <p class="text-secondary small mb-0">Receive instant rich payment alerts and device status reports in your Telegram channel or group.</p>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm btn-test-channel" data-channel="telegram">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4" /></svg>
                                Send Test Telegram
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Bot API Token</label>
                                <div class="input-group input-group-flat">
                                    <input type="password" class="form-control" id="notification_telegram_token" placeholder="1234567890:ABCdefGHIjklMNOpqrsTUVwxyz" value="<?= htmlspecialchars(get_env('notification_telegram_token') ?: '') ?>">
                                    <span class="input-group-text">
                                        <a href="javascript:void(0)" class="link-secondary toggle-pass" data-target="notification_telegram_token" title="Show/Hide">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                        </a>
                                    </span>
                                </div>
                                <small class="form-hint">Create a bot using <a href="https://t.me/BotFather" target="_blank">@BotFather</a> on Telegram.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Chat ID / Channel Username</label>
                                <input type="text" class="form-control" id="notification_telegram_chat_id" placeholder="-1001234567890 or @YourChannel" value="<?= htmlspecialchars(get_env('notification_telegram_chat_id') ?: '') ?>">
                                <small class="form-hint">Your personal Telegram ID or group/channel ID (get from <a href="https://t.me/userinfobot" target="_blank">@userinfobot</a>).</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Message Thread / Topic ID <span class="badge bg-secondary-lt ms-1">Optional</span></label>
                                <input type="text" class="form-control" id="notification_telegram_topic_id" placeholder="e.g. 23 (Leave empty for general)" value="<?= htmlspecialchars(get_env('notification_telegram_topic_id') ?: '') ?>">
                                <small class="form-hint">For supergroups with topics enabled.</small>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: DISCORD WEBHOOK -->
                    <div class="tab-pane" id="tab-discord" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h3 class="card-title text-primary mb-1">Discord Webhook Configuration</h3>
                                <p class="text-secondary small mb-0">Post formatted embed cards with transaction details directly to any Discord server channel.</p>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm btn-test-channel" data-channel="discord">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M14 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /></svg>
                                Send Test Discord
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label required">Discord Webhook URL</label>
                                <input type="url" class="form-control" id="notification_discord_webhook" placeholder="https://discord.com/api/webhooks/1234567890/abcdef..." value="<?= htmlspecialchars(get_env('notification_discord_webhook') ?: '') ?>">
                                <small class="form-hint">Channel Settings &gt; Integrations &gt; Webhooks &gt; New Webhook.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Custom Bot Name</label>
                                <input type="text" class="form-control" id="notification_discord_bot_name" placeholder="PipraPay Alerts" value="<?= htmlspecialchars(get_env('notification_discord_bot_name') ?: 'PipraPay Alerts') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: WHATSAPP BOT -->
                    <div class="tab-pane" id="tab-whatsapp" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h3 class="card-title text-primary mb-1">WhatsApp Bot API Configuration</h3>
                                <p class="text-secondary small mb-0">Integrate custom WhatsApp Gateway API endpoints (e.g. UltraMsg, Wasender, Chat-API, or Node Baileys Server).</p>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm btn-test-channel" data-channel="whatsapp">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /></svg>
                                Send Test WhatsApp
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label required">WhatsApp API Endpoint URL</label>
                                <input type="url" class="form-control" id="notification_whatsapp_api_url" placeholder="https://api.ultramsg.com/instance12345/messages/chat" value="<?= htmlspecialchars(get_env('notification_whatsapp_api_url') ?: '') ?>">
                                <small class="form-hint">
                                    Supported dynamic URL tags: <code>{phone}</code>, <code>{message}</code>, <code>{api_key}</code>.
                                </small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">API Key / Instance Token</label>
                                <input type="text" class="form-control" id="notification_whatsapp_api_key" placeholder="instance_token_xyz" value="<?= htmlspecialchars(get_env('notification_whatsapp_api_key') ?: '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Admin Target WhatsApp Number</label>
                                <input type="text" class="form-control" id="notification_whatsapp_target_phone" placeholder="8801700000000 (with country code)" value="<?= htmlspecialchars(get_env('notification_whatsapp_target_phone') ?: '') ?>">
                                <small class="form-hint">Number that will receive Admin WhatsApp payment alerts.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sender Phone / Instance ID <span class="badge bg-secondary-lt ms-1">Optional</span></label>
                                <input type="text" class="form-control" id="notification_whatsapp_sender" placeholder="8801800000000" value="<?= htmlspecialchars(get_env('notification_whatsapp_sender') ?: '') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: EMAIL SMTP -->
                    <div class="tab-pane" id="tab-email" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h3 class="card-title text-primary mb-1">Email / SMTP Configuration</h3>
                                <p class="text-secondary small mb-0">Configure your custom SMTP mail server for reliable delivery of invoices, customer receipts, and admin reports.</p>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm btn-test-channel" data-channel="email">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /></svg>
                                Send Test Email
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">SMTP Host</label>
                                <input type="text" class="form-control" id="notification_email_smtp_host" placeholder="smtp.gmail.com or mail.yourdomain.com" value="<?= htmlspecialchars(get_env('notification_email_smtp_host') ?: '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">SMTP Port</label>
                                <input type="number" class="form-control" id="notification_email_smtp_port" placeholder="587" value="<?= htmlspecialchars(get_env('notification_email_smtp_port') ?: '587') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">Encryption</label>
                                <?php $smtpEnc = get_env('notification_email_smtp_enc') ?: 'tls'; ?>
                                <select class="form-select" id="notification_email_smtp_enc">
                                    <option value="tls" <?= $smtpEnc === 'tls' ? 'selected' : '' ?>>TLS (Port 587)</option>
                                    <option value="ssl" <?= $smtpEnc === 'ssl' ? 'selected' : '' ?>>SSL (Port 465)</option>
                                    <option value="none" <?= $smtpEnc === 'none' ? 'selected' : '' ?>>None (Port 25)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Username / Email</label>
                                <input type="text" class="form-control" id="notification_email_smtp_user" placeholder="your-email@gmail.com" value="<?= htmlspecialchars(get_env('notification_email_smtp_user') ?: '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Password / App Password</label>
                                <div class="input-group input-group-flat">
                                    <input type="password" class="form-control" id="notification_email_smtp_pass" placeholder="••••••••••••" value="<?= htmlspecialchars(get_env('notification_email_smtp_pass') ?: '') ?>">
                                    <span class="input-group-text">
                                        <a href="javascript:void(0)" class="link-secondary toggle-pass" data-target="notification_email_smtp_pass" title="Show/Hide">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">From Name</label>
                                <input type="text" class="form-control" id="notification_email_from_name" placeholder="PipraPay Billing" value="<?= htmlspecialchars(get_env('notification_email_from_name') ?: '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">From Email Address</label>
                                <input type="email" class="form-control" id="notification_email_from" placeholder="billing@yourdomain.com" value="<?= htmlspecialchars(get_env('notification_email_from') ?: '') ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Admin Alert Email Recipients <span class="badge bg-secondary-lt ms-1">Comma-separated</span></label>
                                <input type="text" class="form-control" id="notification_email_admin_recipients" placeholder="admin@example.com, finance@example.com" value="<?= htmlspecialchars(get_env('notification_email_admin_recipients') ?: '') ?>">
                                <small class="form-hint">Email addresses to receive admin payment summaries and system alerts.</small>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: SMS GATEWAY -->
                    <div class="tab-pane" id="tab-sms" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h3 class="card-title text-primary mb-1">SMS Gateway Configuration</h3>
                                <p class="text-secondary small mb-0">Connect any HTTP GET/POST SMS API (e.g., Greenweb, BulkSMSBD, Twilio, Onnorokom, InfoBip) using dynamic URL placeholders.</p>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm btn-test-channel" data-channel="sms">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /></svg>
                                Send Test SMS
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label required">SMS HTTP API URL</label>
                                <input type="text" class="form-control" id="notification_sms_api_url" placeholder="https://api.sms-provider.com/send?token={api_key}&to={to}&message={message}&sender_id={sender_id}" value="<?= htmlspecialchars(get_env('notification_sms_api_url') ?: '') ?>">
                                <small class="form-hint">
                                    Supported dynamic tags: <code>{to}</code> (Recipient phone), <code>{message}</code> (URL encoded message), <code>{api_key}</code> (API token), <code>{sender_id}</code> (Sender Masking).
                                </small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">API Key / Token</label>
                                <input type="text" class="form-control" id="notification_sms_api_key" placeholder="Your SMS provider token" value="<?= htmlspecialchars(get_env('notification_sms_api_key') ?: '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sender ID / Masking Name <span class="badge bg-secondary-lt ms-1">Optional</span></label>
                                <input type="text" class="form-control" id="notification_sms_sender_id" placeholder="8809600000000 or BrandName" value="<?= htmlspecialchars(get_env('notification_sms_sender_id') ?: '') ?>">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/><path d="M12 9h.01"/><path d="M11 12h1v4h1"/></svg>
                    Credentials and routing rules are saved securely in your system environment.
                </div>
                <button type="button" class="btn btn-primary btn-save-notifications d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Save All Settings
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Password toggle helper
    $('.toggle-pass').off('click').on('click', function(e) {
        e.preventDefault();
        var targetId = $(this).data('target');
        var input = document.getElementById(targetId);
        if (input) {
            input.type = (input.type === 'password') ? 'text' : 'password';
        }
    });

    // Save Notification Settings
    $('.btn-save-notifications').off('click').on('click', function() {
        var csrf_token_default = $('input[name="csrf_token_default"]').val() || $('input[name="csrf_token"]').val();
        var $btn = $(this);
        var originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-1" role="status"></div> Saving...');

        var postData = {
            action: 'system-settings-notification-save',
            csrf_token: csrf_token_default,

            // Telegram
            notification_telegram_token: ($('#notification_telegram_token').val() || '').trim(),
            notification_telegram_chat_id: ($('#notification_telegram_chat_id').val() || '').trim(),
            notification_telegram_topic_id: ($('#notification_telegram_topic_id').val() || '').trim(),

            // Discord
            notification_discord_webhook: ($('#notification_discord_webhook').val() || '').trim(),
            notification_discord_bot_name: ($('#notification_discord_bot_name').val() || '').trim(),

            // WhatsApp
            notification_whatsapp_api_url: ($('#notification_whatsapp_api_url').val() || '').trim(),
            notification_whatsapp_api_key: ($('#notification_whatsapp_api_key').val() || '').trim(),
            notification_whatsapp_sender: ($('#notification_whatsapp_sender').val() || '').trim(),
            notification_whatsapp_target_phone: ($('#notification_whatsapp_target_phone').val() || '').trim(),

            // Email
            notification_email_smtp_host: ($('#notification_email_smtp_host').val() || '').trim(),
            notification_email_smtp_port: ($('#notification_email_smtp_port').val() || '').trim(),
            notification_email_smtp_enc: $('#notification_email_smtp_enc').val(),
            notification_email_smtp_user: ($('#notification_email_smtp_user').val() || '').trim(),
            notification_email_smtp_pass: $('#notification_email_smtp_pass').val() || '',
            notification_email_from_name: ($('#notification_email_from_name').val() || '').trim(),
            notification_email_from: ($('#notification_email_from').val() || '').trim(),
            notification_email_admin_recipients: ($('#notification_email_admin_recipients').val() || '').trim(),

            // SMS
            notification_sms_api_url: ($('#notification_sms_api_url').val() || '').trim(),
            notification_sms_api_key: ($('#notification_sms_api_key').val() || '').trim(),
            notification_sms_sender_id: ($('#notification_sms_sender_id').val() || '').trim(),

            // Event Switches
            notification_event_admin_payment_success_telegram: $('#notification_event_admin_payment_success_telegram').is(':checked') ? 'yes' : 'no',
            notification_event_admin_payment_success_discord: $('#notification_event_admin_payment_success_discord').is(':checked') ? 'yes' : 'no',
            notification_event_admin_payment_success_whatsapp: $('#notification_event_admin_payment_success_whatsapp').is(':checked') ? 'yes' : 'no',
            notification_event_admin_payment_success_email: $('#notification_event_admin_payment_success_email').is(':checked') ? 'yes' : 'no',

            notification_event_admin_payment_failed_telegram: $('#notification_event_admin_payment_failed_telegram').is(':checked') ? 'yes' : 'no',
            notification_event_admin_payment_failed_discord: $('#notification_event_admin_payment_failed_discord').is(':checked') ? 'yes' : 'no',

            notification_event_device_offline_telegram: $('#notification_event_device_offline_telegram').is(':checked') ? 'yes' : 'no',
            notification_event_device_offline_discord: $('#notification_event_device_offline_discord').is(':checked') ? 'yes' : 'no',
            notification_event_device_offline_whatsapp: $('#notification_event_device_offline_whatsapp').is(':checked') ? 'yes' : 'no',

            notification_event_device_battery_telegram: $('#notification_event_device_battery_telegram').is(':checked') ? 'yes' : 'no',
            notification_event_device_battery_discord: $('#notification_event_device_battery_discord').is(':checked') ? 'yes' : 'no',

            notification_event_customer_payment_success_email: $('#notification_event_customer_payment_success_email').is(':checked') ? 'yes' : 'no',
            notification_event_customer_payment_success_sms: $('#notification_event_customer_payment_success_sms').is(':checked') ? 'yes' : 'no'
        };

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: postData,
            dataType: 'json',
            success: function(response) {
                $btn.prop('disabled', false).html(originalHtml);

                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                if (response.status === 'true') {
                    createToast({
                        title: response.title || 'Settings Saved',
                        description: response.message || 'Notification settings have been updated successfully.',
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2fb344" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                        timeout: 5000,
                        top: 70
                    });

                    // Reload page to refresh tab badges
                    load_content('System Settings','<?php echo $site_url.$path_admin ?>/system-settings/notification','nav-item-system-settings');
                } else {
                    createToast({
                        title: response.title || 'Error',
                        description: response.message || 'Failed to save settings.',
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                        timeout: 5000,
                        top: 70
                    });
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(originalHtml);
                createToast({
                    title: 'Something Wrong!',
                    description: 'For further assistance, please contact our support team.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 5000,
                    top: 70
                });
            }
        });
    });

    // Test Channel Notification (Passes live active form inputs so test works before saving)
    $('.btn-test-channel').off('click').on('click', function() {
        var channel = $(this).data('channel');
        var $btn = $(this);
        var originalHtml = $btn.html();
        var csrf_token_default = $('input[name="csrf_token_default"]').val() || $('input[name="csrf_token"]').val();

        $btn.prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-1" role="status"></div> Testing...');

        var postData = {
            action: 'system-settings-notification-test',
            channel: channel,
            csrf_token: csrf_token_default,

            // Live field inputs from DOM
            notification_telegram_token: ($('#notification_telegram_token').val() || '').trim(),
            notification_telegram_chat_id: ($('#notification_telegram_chat_id').val() || '').trim(),
            notification_telegram_topic_id: ($('#notification_telegram_topic_id').val() || '').trim(),

            notification_discord_webhook: ($('#notification_discord_webhook').val() || '').trim(),
            notification_discord_bot_name: ($('#notification_discord_bot_name').val() || '').trim(),

            notification_whatsapp_api_url: ($('#notification_whatsapp_api_url').val() || '').trim(),
            notification_whatsapp_api_key: ($('#notification_whatsapp_api_key').val() || '').trim(),
            notification_whatsapp_sender: ($('#notification_whatsapp_sender').val() || '').trim(),
            notification_whatsapp_target_phone: ($('#notification_whatsapp_target_phone').val() || '').trim(),

            notification_email_smtp_host: ($('#notification_email_smtp_host').val() || '').trim(),
            notification_email_smtp_port: ($('#notification_email_smtp_port').val() || '').trim(),
            notification_email_smtp_enc: $('#notification_email_smtp_enc').val(),
            notification_email_smtp_user: ($('#notification_email_smtp_user').val() || '').trim(),
            notification_email_smtp_pass: $('#notification_email_smtp_pass').val() || '',
            notification_email_from_name: ($('#notification_email_from_name').val() || '').trim(),
            notification_email_from: ($('#notification_email_from').val() || '').trim(),
            notification_email_admin_recipients: ($('#notification_email_admin_recipients').val() || '').trim(),

            notification_sms_api_url: ($('#notification_sms_api_url').val() || '').trim(),
            notification_sms_api_key: ($('#notification_sms_api_key').val() || '').trim(),
            notification_sms_sender_id: ($('#notification_sms_sender_id').val() || '').trim()
        };

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: postData,
            dataType: 'json',
            success: function(response) {
                $btn.prop('disabled', false).html(originalHtml);

                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                if (response.status === 'true') {
                    createToast({
                        title: response.title || 'Test Alert Dispatched',
                        description: response.message || 'Test notification was sent successfully.',
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2fb344" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                        timeout: 5000,
                        top: 70
                    });
                } else {
                    createToast({
                        title: response.title || 'Test Failed',
                        description: response.message || 'Failed to dispatch test notification. Please verify your credentials.',
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                        timeout: 7000,
                        top: 70
                    });
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(originalHtml);
                createToast({
                    title: 'Something Wrong!',
                    description: 'Server error while dispatching test alert.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 5000,
                    top: 70
                });
            }
        });
    });
});
</script>