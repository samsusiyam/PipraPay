<?php
if (!defined('PipraPay_INIT')) {
    http_response_code(403);
    exit('Direct access not allowed');
}

if (!canAccessPage(json_decode($global_response_permission['response'][0]['permission'], true), 'system_settings', $global_user_response['response'][0]['role'])) {
    http_response_code(403);
    exit('Access denied. You need permission to perform this action. Please contact the admin.');
}

if (!hasPermission(json_decode($global_response_permission['response'][0]['permission'], true), 'system_settings', 'manage_update', $global_user_response['response'][0]['role'])) {
    http_response_code(403);
    exit('Access denied. You need permission to perform this action. Please contact the admin.');
}

$update_available = false;
$update_channel = get_env('system-settings-update_channel') === '--' || (get_env('system-settings-update_channel') === '') ? 'stable' : get_env('system-settings-update_channel');
$lasted_update_version_name = get_env('last-update-version-name');
$lasted_update_version = get_env('last-update-version');
$lasted_update_release_notes = get_env('last-update-release-notes');
$lasted_update_release_date = get_env('last-update-release-date');
$last_check = get_env('last-auto-update-check');

if (!empty($lasted_update_version) && $lasted_update_version !== "--") {
    if (version_compare($lasted_update_version, $piprapay_current_version['version_code'], '>')) {
        $update_available = true;
    }
}

// Check if update zip file is already downloaded
$update_zip_file = __DIR__ . '/../../../../pp-media/storage/updates/' . ($lasted_update_version ?: 'temp') . '.zip';
$is_zip_downloaded = ($update_available && file_exists($update_zip_file) && filesize($update_zip_file) > 1000);

// Server Environment Health Check
$php_version = phpversion();
$php_ok = version_compare($php_version, '8.1.0', '>=');
$ext_zip = extension_loaded('zip');
$ext_curl = extension_loaded('curl');
$ext_openssl = extension_loaded('openssl');
$ext_pdo = extension_loaded('pdo');
$storage_dir = __DIR__ . '/../../../../pp-media/storage';
$storage_writable = is_writable($storage_dir);
$memory_limit = ini_get('memory_limit') ?: 'N/A';

// Check existing backups
$backup_dir = $storage_dir . '/backup';
$backups = [];
if (is_dir($backup_dir)) {
    $files = scandir($backup_dir);
    foreach ($files as $f) {
        if ($f !== '.' && $f !== '..' && (str_ends_with($f, '.zip') || str_ends_with($f, '.sql'))) {
            $filePath = $backup_dir . '/' . $f;
            $backups[] = [
                'name' => $f,
                'size' => round(filesize($filePath) / 1024 / 1024, 2) . ' MB',
                'date' => date('M d, Y h:i A', filemtime($filePath)),
                'type' => str_ends_with($f, '.zip') ? 'System Files' : 'Database SQL'
            ];
        }
    }
}
?>

<div class="page-header d-print-none" aria-label="Page header">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="load_content('System Settings','<?php echo $site_url.$path_admin ?>/system-settings','nav-item-system-settings')">System Settings</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">System Update</a></li>
                    </ol>
                </div>
                <h2 class="page-title d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                    System Update & Maintenance
                    <span class="badge bg-primary-lt ms-2"><?php echo htmlspecialchars($piprapay_current_version['version_name'] ?? 'v3.0.7'); ?></span>
                </h2>
            </div>

            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-updateSettings">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                        Update Settings
                    </button>

                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-manualUpload">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg>
                        Manual ZIP Upload
                    </button>

                    <button type="button" class="btn btn-primary btn-check-update">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                        <span>Check for Updates</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">

        <!-- System Environment & Health Stats Row -->
        <div class="row row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">Installed Version</div>
                                <div class="text-muted d-flex align-items-center gap-1">
                                    <strong><?php echo htmlspecialchars($piprapay_current_version['version_name'] ?? 'v3.0.7'); ?></strong>
                                    <span class="badge bg-blue-lt ms-1"><?php echo ucfirst($update_channel); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="<?php echo $php_ok ? 'bg-success' : 'bg-danger'; ?> text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">PHP Environment</div>
                                <div class="text-muted">
                                    PHP <?php echo htmlspecialchars($php_version); ?>
                                    <span class="badge <?php echo $php_ok ? 'bg-green-lt' : 'bg-red-lt'; ?> ms-1"><?php echo $php_ok ? 'Supported' : 'Upgrade Required'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="<?php echo ($ext_zip && $ext_curl) ? 'bg-info' : 'bg-warning'; ?> text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10v4h4v-4z" /><path d="M17 10v4h4v-4z" /><path d="M7 4v4h4v-4z" /><path d="M17 4v4h4v-4z" /><path d="M7 16v4h4v-4z" /><path d="M17 16v4h4v-4z" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">System Extensions</div>
                                <div class="text-muted small">
                                    Zip: <?php echo $ext_zip ? '✓' : '✗'; ?> | cURL: <?php echo $ext_curl ? '✓' : '✗'; ?> | SSL: <?php echo $ext_openssl ? '✓' : '✗'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="<?php echo $storage_writable ? 'bg-teal' : 'bg-danger'; ?> text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">Storage & Backup</div>
                                <div class="text-muted">
                                    <?php echo $storage_writable ? '<span class="text-success fw-medium">Writeable (Ready)</span>' : '<span class="text-danger">Permission Error</span>'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATE 1: System is Up to Date Card -->
        <div id="card-up-to-date" class="card mb-4 shadow-sm border-0 <?php echo ($update_available ? 'd-none' : ''); ?>">
            <div class="card-body p-4 text-center py-5">
                <div class="mb-3">
                    <span class="avatar avatar-xl rounded-circle bg-green-lt text-success shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M9 12l2 2l4 -4" /></svg>
                    </span>
                </div>
                <h2 class="card-title h1 text-dark mb-2">System is Up to Date</h2>
                <p class="text-muted max-w-md mx-auto mb-4" style="max-width: 550px;">
                    You are running the latest version <strong class="text-primary"><?php echo htmlspecialchars($piprapay_current_version['version_name'] ?? 'v3.0.7'); ?></strong> on the <strong><?php echo ucfirst($update_channel); ?></strong> channel. All features, modules, security patches, and database structures are current.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-4 text-muted small mb-4">
                    <div class="d-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
                        Last Checked: 
                        <strong>
                            <?php
                            if (empty($last_check) || $last_check === '--') {
                                echo 'Never / Just now';
                            } else {
                                echo convertUTCtoUserTZ($last_check, ($global_response_brand['response'][0]['timezone'] === '--' || $global_response_brand['response'][0]['timezone'] === '') ? 'Asia/Dhaka' : $global_response_brand['response'][0]['timezone'], "M d, Y h:i A");
                            }
                            ?>
                        </strong>
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M9 19h-2a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6" /><path d="M15 15l2 2l4 -4" /></svg>
                        Update Channel: <strong><?php echo ucfirst($update_channel); ?> Releases</strong>
                    </div>
                </div>

                <div>
                    <button type="button" class="btn btn-primary px-4 btn-check-update">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                        Check Again for Updates
                    </button>
                </div>
            </div>
        </div>

        <!-- STATE 2: Update Available Card -->
        <div id="card-update-available" class="card mb-4 border-2 border-primary shadow-sm <?php echo ($update_available ? '' : 'd-none'); ?>">
            <div class="card-status-top bg-primary"></div>
            
            <!-- Hero Update Banner -->
            <div class="card-body p-4 border-bottom bg-primary-lt">
                <div class="row align-items-center gy-3">
                    <div class="col-12 col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary text-white px-2 py-1">🎉 New Release Available</span>
                            <span class="badge bg-azure-lt"><?php echo ucfirst($update_channel); ?> Channel</span>
                            <?php if (!empty($lasted_update_release_date)): ?>
                                <span class="text-muted small">Released: <?php echo htmlspecialchars($lasted_update_release_date); ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="card-title h1 text-primary mb-1" id="update-target-title">
                            Upgrade to <span id="lbl-target-version"><?php echo htmlspecialchars($lasted_update_version_name ?: 'v3.0.7'); ?></span>
                        </h2>
                        <div class="text-muted d-flex align-items-center gap-2">
                            <span>Current: <strong class="badge bg-secondary-lt"><?php echo htmlspecialchars($piprapay_current_version['version_name'] ?? 'v3.0.7'); ?></strong></span>
                            <span>➔</span>
                            <span>Target: <strong class="badge bg-primary-lt" id="lbl-target-version-badge"><?php echo htmlspecialchars($lasted_update_version_name ?: 'v3.0.7'); ?></strong></span>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 text-start text-md-end">
                        <div class="btn-list justify-content-md-end">
                            <button type="button" class="btn btn-outline-primary downloadUpdate <?php echo $is_zip_downloaded ? 'd-none' : ''; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                Download Archive
                            </button>

                            <button type="button" class="btn btn-success installUpdate <?php echo $is_zip_downloaded ? '' : 'd-none'; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /></svg>
                                Install Update Now
                            </button>

                            <button type="button" class="btn btn-primary oneClickUpdate">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 10l7.383 7.418a2.091 2.091 0 0 1 0 2.955a2.088 2.088 0 0 1 -2.951 0l-7.432 -7.373" /><path d="M15 8l1.5 1.5" /><path d="M4 20l5 -5" /><path d="M17 4a3 3 0 0 0 -4.242 0l-8.486 8.485a3 3 0 0 0 0 4.243l.707 .707l12.728 -12.728l-.707 -.707z" /></svg>
                                1-Click Fast Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Visual 4-Step Stepper -->
                <div class="mb-4">
                    <h4 class="card-title mb-3">Update Progress Workflow</h4>
                    <div class="row g-2 text-center">
                        <div class="col-6 col-md-3">
                            <div class="p-2 p-md-3 border rounded bg-success-lt border-success-subtle h-100 d-flex flex-column justify-content-center" id="step-1-box">
                                <div><span class="badge bg-success text-white mb-1" id="step-1-badge">✓ Step 1</span></div>
                                <div class="fw-semibold small">Version Detected</div>
                                <div class="text-muted small">Update available</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 p-md-3 border rounded bg-success-lt border-success-subtle h-100 d-flex flex-column justify-content-center" id="step-2-box">
                                <div><span class="badge bg-success text-white mb-1" id="step-2-badge">✓ Step 2</span></div>
                                <div class="fw-semibold small">Review Changes</div>
                                <div class="text-muted small">Changelog ready</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 p-md-3 border rounded <?php echo $is_zip_downloaded ? 'bg-success-lt border-success-subtle' : 'bg-primary-lt border-primary-subtle'; ?> h-100 d-flex flex-column justify-content-center" id="step-3-box">
                                <div><span class="badge <?php echo $is_zip_downloaded ? 'bg-success' : 'bg-primary'; ?> text-white mb-1" id="step-3-badge"><?php echo $is_zip_downloaded ? '✓ Step 3' : 'Step 3'; ?></span></div>
                                <div class="fw-semibold small">Download Archive</div>
                                <div class="text-muted small" id="step-3-status"><?php echo $is_zip_downloaded ? 'Package ready' : 'Pending download'; ?></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 p-md-3 border rounded <?php echo $is_zip_downloaded ? 'bg-primary-lt border-primary-subtle' : 'bg-light'; ?> h-100 d-flex flex-column justify-content-center" id="step-4-box">
                                <div><span class="badge <?php echo $is_zip_downloaded ? 'bg-primary' : 'bg-secondary'; ?> text-white mb-1" id="step-4-badge">Step 4</span></div>
                                <div class="fw-semibold small">Backup & Install</div>
                                <div class="text-muted small" id="step-4-status"><?php echo $is_zip_downloaded ? 'Ready to install' : 'Awaiting download'; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Progress Box (Hidden until action begins) -->
                <div id="update-progress-card" class="mb-4 d-none">
                    <div class="card bg-dark text-white p-3 border-0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="d-flex align-items-center gap-2">
                                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                                <strong id="update-progress-title">Processing System Update...</strong>
                            </span>
                            <span id="update-progress-pct" class="badge bg-primary text-white">0%</span>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div id="update-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="update-status-log" class="text-secondary small font-monospace">
                            Initialising update process...
                        </div>
                    </div>
                </div>

                <!-- Changelog & Release Notes Section -->
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex align-items-center justify-content-between">
                        <h4 class="card-title d-flex align-items-center gap-2 mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                            What's New in this Version
                        </h4>
                        <a href="https://github.com/samsusiyam/PipraPay/releases" target="_blank" class="btn btn-sm btn-link link-primary">
                            View on GitHub ↗
                        </a>
                    </div>
                    <div class="card-body p-4" id="changelog-container">
                        <?php
                        if (!empty($lasted_update_release_notes)) {
                            $normalizedNotes = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $lasted_update_release_notes);
                            $lines = explode("\n", $normalizedNotes);
                            echo '<ul class="list-unstyled space-y-2 mb-0">';
                            foreach ($lines as $line) {
                                $trimmed = trim($line);
                                if (empty($trimmed)) continue;
                                
                                // Format bullets
                                if (str_starts_with($trimmed, '•') || str_starts_with($trimmed, '-') || str_starts_with($trimmed, '*')) {
                                    $content = ltrim($trimmed, '•-* ');
                                    
                                    // Assign smart contextual badge
                                    $badge = '✨';
                                    $badgeClass = 'bg-primary-lt';
                                    if (stripos($content, 'UI/UX') !== false || stripos($content, 'Overhaul') !== false || stripos($content, 'Design') !== false) {
                                        $badge = '🎨';
                                        $badgeClass = 'bg-purple-lt';
                                    } elseif (stripos($content, 'Backup') !== false || stripos($content, 'Snapshot') !== false) {
                                        $badge = '💾';
                                        $badgeClass = 'bg-teal-lt';
                                    } elseif (stripos($content, 'Notification') !== false || stripos($content, 'Alert') !== false || stripos($content, 'Telegram') !== false || stripos($content, 'Discord') !== false || stripos($content, 'WhatsApp') !== false) {
                                        $badge = '🔔';
                                        $badgeClass = 'bg-azure-lt';
                                    } elseif (stripos($content, 'Health') !== false || stripos($content, 'Compatibility') !== false || stripos($content, 'Security') !== false) {
                                        $badge = '🛡️';
                                        $badgeClass = 'bg-green-lt';
                                    } elseif (stripos($content, 'Upload') !== false || stripos($content, 'Manual') !== false || stripos($content, 'Offline') !== false) {
                                        $badge = '📦';
                                        $badgeClass = 'bg-yellow-lt';
                                    } elseif (stripos($content, 'Fix') !== false || stripos($content, 'Performance') !== false || stripos($content, 'Improvement') !== false) {
                                        $badge = '⚡';
                                        $badgeClass = 'bg-orange-lt';
                                    }

                                    echo '<li class="d-flex align-items-start gap-2 mb-2">
                                            <span class="badge ' . $badgeClass . ' mt-1">' . $badge . '</span>
                                            <span class="text-secondary">' . htmlspecialchars($content) . '</span>
                                          </li>';
                                } else {
                                    echo '<h5 class="fw-bold text-dark mb-2">' . htmlspecialchars($trimmed) . '</h5>';
                                }
                            }
                            echo '</ul>';
                        } else {
                            echo '
                            <ul class="list-unstyled space-y-2 mb-0">
                                <li class="d-flex align-items-start gap-2 mb-2">
                                    <span class="badge bg-purple-lt mt-1">🎨</span>
                                    <span class="text-secondary">Major UI/UX redesign of update system with interactive 4-step stepper and animated progress bar.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2">
                                    <span class="badge bg-azure-lt mt-1">🔔</span>
                                    <span class="text-secondary">Multi-Channel Notification Engine (Telegram Bot, Discord Webhook, WhatsApp API, SMTP Email, SMS Gateway).</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2">
                                    <span class="badge bg-teal-lt mt-1">💾</span>
                                    <span class="text-secondary">Safety Backup Snapshot management with one-click direct file download & delete options.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2">
                                    <span class="badge bg-green-lt mt-1">🛡️</span>
                                    <span class="text-secondary">Pre-flight system health and PHP environment compatibility diagnostics.</span>
                                </li>
                            </ul>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Pre-update Safety Notice -->
                <div class="alert alert-info d-flex align-items-center mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
                    <div>
                        <h4 class="alert-title mb-1">Automated Pre-Update Backup Guarantee</h4>
                        <div class="text-muted">PipraPay will automatically generate a snapshot archive of your application files and database in <code>pp-media/storage/backup/</code> before applying the update.</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Automated Backup Snapshots Card -->
        <div class="card mb-4" id="card-backup-snapshots">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4 class="card-title d-flex align-items-center gap-2 mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Safety Backup Snapshots
                    <span class="badge bg-teal-lt ms-1" id="backup-count-badge"><?php echo count($backups); ?> Saved</span>
                </h4>
                
                <?php if (!empty($backups)): ?>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-all-backups">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                        Delete All Snapshots
                    </button>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($backups)): ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-hover" id="table-backup-snapshots">
                            <thead>
                                <tr>
                                    <th>Snapshot File</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Created Date</th>
                                    <th class="text-end" style="min-width: 170px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($backups as $b): ?>
                                    <tr id="row-backup-<?php echo md5($b['name']); ?>">
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                                                <code><?php echo htmlspecialchars($b['name']); ?></code>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-blue-lt"><?php echo htmlspecialchars($b['type']); ?></span></td>
                                        <td><?php echo htmlspecialchars($b['size']); ?></td>
                                        <td class="text-muted"><?php echo htmlspecialchars($b['date']); ?></td>
                                        <td class="text-end text-nowrap">
                                            <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                                <a href="<?php echo $site_url.$path_admin; ?>/dashboard?action=system-settings-update-backup-download&file=<?php echo urlencode($b['name']); ?>" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 px-2 py-1 shadow-none" title="Download Snapshot" download>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                                    <span class="small">Download</span>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-2 py-1 btn-delete-backup shadow-none" data-file="<?php echo htmlspecialchars($b['name']); ?>" data-row="row-backup-<?php echo md5($b['name']); ?>" title="Delete Snapshot">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                    <span class="small">Delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-muted" id="backup-empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2 text-secondary" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /></svg>
                        <div>No backup archives yet. A backup will be generated automatically before each update.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- Modal 1: Update Settings Modal -->
<div class="modal modal-blur fade" id="modal-updateSettings" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                    Update Channel & Preferences
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Update Release Channel</label>
                    <select class="form-select" id="update_channel">
                        <option value="stable" <?php echo ($update_channel == "stable") ? 'selected' : ''?>>Stable releases only (Recommended for Production)</option>
                        <option value="beta" <?php echo ($update_channel == "beta") ? 'selected' : ''?>>Beta & Early-Access releases</option>
                    </select>
                    <small class="form-hint">Stable channel receives tested releases. Beta channel gets early access to upcoming features.</small>
                </div>

                <?php
                    $automatic_update = get_env('system-settings-automatic_update') === '--' || (get_env('system-settings-automatic_update') === '') ? '' : get_env('system-settings-automatic_update');
                    $create_backup = get_env('system-settings-create_backup') === '--' || (get_env('system-settings-create_backup') === '') ? '' : get_env('system-settings-create_backup');
                ?>

                <div class="mb-3">
                    <label class="form-check form-switch mb-2">
                        <input class="form-check-input" id="automatic_update" type="checkbox" <?php echo ($automatic_update == "yes") ? 'checked' : ''?>>
                        <span class="form-check-label fw-semibold">Automatic Update Check</span>
                    </label>
                    <small class="text-muted d-block ms-4">Automatically checks GitHub repository every 10 hours and notifies admins.</small>
                </div>

                <div class="mb-3">
                    <label class="form-check form-switch mb-2">
                        <input class="form-check-input" id="create_backup" type="checkbox" <?php echo ($create_backup == "yes" || $create_backup == "") ? 'checked' : ''?>>
                        <span class="form-check-label fw-semibold">Automated Pre-Update Backup</span>
                    </label>
                    <small class="text-muted d-block ms-4">Safely creates full database and file archive snapshots before updating.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-update-setting">Save Preferences</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Manual Update ZIP Upload Modal -->
<div class="modal modal-blur fade" id="modal-manualUpload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg>
                    Manual Update ZIP Upload
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">
                    If your server has restricted outbound internet or cannot connect directly to GitHub, you can upload an official <code>.zip</code> package manually.
                </p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Update Archive (.zip)</label>
                    <input type="file" id="manual_update_file" class="form-control" accept=".zip">
                </div>

                <div id="manual-upload-progress-box" class="d-none mb-3">
                    <div class="progress mb-2" style="height: 8px;">
                        <div id="manual-upload-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 0%"></div>
                    </div>
                    <small id="manual-upload-status" class="text-muted">Uploading and applying update...</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-submit-manual-upload">Upload & Apply Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: Delete Single Snapshot Confirmation Modal -->
<div class="modal modal-blur fade" id="modal-deleteSingleBackup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.871l-8.106 -13.534a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                <h3 class="fw-bold">Delete Snapshot?</h3>
                <div class="text-muted">Are you sure you want to delete backup snapshot <code id="delete-single-backup-filename" class="fw-bold text-dark"></code>? This file cannot be recovered.</div>
                <input type="hidden" id="delete-single-backup-file">
                <input type="hidden" id="delete-single-backup-row">
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><button type="button" class="btn w-100 shadow-none" data-bs-dismiss="modal">Cancel</button></div>
                        <div class="col"><button type="button" class="btn btn-danger w-100 shadow-none" id="btn-confirm-delete-single-backup">Yes, Delete</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 4: Delete All Snapshots Confirmation Modal -->
<div class="modal modal-blur fade" id="modal-deleteAllBackups" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                <h3 class="fw-bold">Delete ALL Snapshots?</h3>
                <div class="text-muted">Are you sure you want to delete <strong>ALL</strong> safety backup snapshots? All stored file and database archives will be permanently removed.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><button type="button" class="btn w-100 shadow-none" data-bs-dismiss="modal">Cancel</button></div>
                        <div class="col"><button type="button" class="btn btn-danger w-100 shadow-none" id="btn-confirm-delete-all-backups">Delete All</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script data-cfasync="false">
    // Helper function to animate progress bar
    function setUpdateProgress(pct, title, logMsg) {
        $('#update-progress-card').removeClass('d-none');
        $('#update-progress-pct').text(pct + '%');
        $('#update-progress-bar').css('width', pct + '%').attr('aria-valuenow', pct);
        if (title) $('#update-progress-title').text(title);
        if (logMsg) $('#update-status-log').html(logMsg);
    }

    // 1. Check for Updates
    $('.btn-check-update').click(function () {
        var csrf_token_default = $('input[name="csrf_token_default"]').val();
        var $btn = $(this);
        var originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-1" role="status"></div> Checking GitHub...');

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: { action: "system-settings-update-check", csrf_token: csrf_token_default },
            dataType: 'json',
            success: function (response) {
                $btn.prop('disabled', false).html(originalHtml);

                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                if (response.status === 'true') {
                    createToast({
                        title: response.title,
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f38f9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                        timeout: 5000,
                        top: 70
                    });

                    // Reload view to reflect updated state
                    load_content('System Settings','<?php echo $site_url.$path_admin ?>/system-settings/update','nav-item-system-settings');
                } else {
                    createToast({
                        title: response.title || 'Check Failed',
                        description: response.message || 'Unable to fetch update details from GitHub.',
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                        timeout: 6000,
                        top: 70
                    });
                }
            },
            error: function () {
                $btn.prop('disabled', false).html(originalHtml);
                createToast({
                    title: 'Connection Error',
                    description: 'Could not connect to update server. Please verify your internet connection.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 6000,
                    top: 70
                });
            }
        });
    });

    // 2. Download Update
    $('.downloadUpdate').click(function () {
        var csrf_token_default = $('input[name="csrf_token_default"]').val();
        var $btn = $(this);
        var originalHtml = $btn.html();

        $btn.prop('disabled', true);
        setUpdateProgress(25, 'Downloading Update Package...', '[1/3] Connecting to GitHub repository archive...');

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: { action: "system-settings-update-download", csrf_token: csrf_token_default },
            dataType: 'json',
            success: function (response) {
                $btn.prop('disabled', false).html(originalHtml);

                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                if (response.status === 'true') {
                    setUpdateProgress(60, 'Download Completed', '[2/3] Package saved to storage. Ready for installation.');
                    $('#step-3-badge').removeClass('bg-primary').addClass('bg-success text-white').text('✓ Step 3');
                    $('#step-3-status').text('Package ready');
                    $('#step-3-box').removeClass('bg-primary-lt border-primary-subtle').addClass('bg-success-lt border-success-subtle');

                    $('#step-4-badge').removeClass('bg-secondary').addClass('bg-primary text-white');
                    $('#step-4-status').text('Ready to install');
                    $('#step-4-box').removeClass('bg-light').addClass('bg-primary-lt border-primary-subtle');

                    createToast({
                        title: response.title,
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f38f9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                        timeout: 5000,
                        top: 70
                    });

                    $('.downloadUpdate').addClass('d-none');
                    $('.installUpdate').removeClass('d-none');
                } else {
                    setUpdateProgress(0, 'Download Failed', '[Error] ' + response.message);
                    createToast({
                        title: response.title,
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                        timeout: 6000,
                        top: 70
                    });
                }
            },
            error: function () {
                $btn.prop('disabled', false).html(originalHtml);
                setUpdateProgress(0, 'Download Error', 'Network error occurred while downloading update archive.');
                createToast({
                    title: 'Download Failed',
                    description: 'Failed to download update package. Please check server cURL settings.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 6000,
                    top: 70
                });
            }
        });
    });

    // 3. Install Update
    function triggerInstallUpdate(callback) {
        var csrf_token_default = $('input[name="csrf_token_default"]').val();
        $('.installUpdate, .oneClickUpdate').prop('disabled', true);

        setUpdateProgress(75, 'Installing System Update...', '[3/4] Creating safety backup snapshot & extracting files...');

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: { action: "system-settings-update-install", csrf_token: csrf_token_default },
            dataType: 'json',
            success: function (response) {
                $('.installUpdate, .oneClickUpdate').prop('disabled', false);

                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                if (response.status === 'true') {
                    setUpdateProgress(100, 'Installation Successful! 🎉', '[4/4] Update completed! Reloading dashboard in 3 seconds...');
                    $('#step-4-badge').removeClass('bg-primary bg-secondary').addClass('bg-success text-white').text('✓ Step 4');
                    $('#step-4-status').text('Installed');
                    $('#step-4-box').removeClass('bg-primary-lt border-primary-subtle bg-light').addClass('bg-success-lt border-success-subtle');

                    createToast({
                        title: response.title,
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f38f9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                        timeout: 6000,
                        top: 70
                    });

                    setTimeout(function () {
                        location.reload();
                    }, 2500);
                } else {
                    setUpdateProgress(0, 'Installation Failed', '[Error] ' + response.message);
                    createToast({
                        title: response.title,
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                        timeout: 6000,
                        top: 70
                    });
                }
            },
            error: function () {
                $('.installUpdate, .oneClickUpdate').prop('disabled', false);
                setUpdateProgress(0, 'Installation Error', 'Failed to complete update extraction.');
                createToast({
                    title: 'Something Wrong!',
                    description: 'For further assistance, please contact our support team.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 6000,
                    top: 70
                });
            }
        });
    }

    $('.installUpdate').click(function () {
        triggerInstallUpdate();
    });

    // 4. One-Click Fast Update (Download -> Install sequentially)
    $('.oneClickUpdate').click(function () {
        var csrf_token_default = $('input[name="csrf_token_default"]').val();
        var $btn = $(this);
        $btn.prop('disabled', true);

        setUpdateProgress(20, 'Downloading Update...', '[1/4] Downloading update archive from GitHub...');

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: { action: "system-settings-update-download", csrf_token: csrf_token_default },
            dataType: 'json',
            success: function (response) {
                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                if (response.status === 'true') {
                    setUpdateProgress(60, 'Download Complete', '[2/4] Package verified. Initiating backup & install...');
                    $('#step-3-badge').removeClass('bg-primary').addClass('bg-success text-white').text('✓ Step 3');
                    $('#step-3-status').text('Package ready');
                    $('#step-3-box').removeClass('bg-primary-lt border-primary-subtle').addClass('bg-success-lt border-success-subtle');

                    $('#step-4-badge').removeClass('bg-secondary').addClass('bg-primary text-white');
                    $('#step-4-status').text('Installing...');
                    $('#step-4-box').removeClass('bg-light').addClass('bg-primary-lt border-primary-subtle');
                    
                    triggerInstallUpdate();
                } else {
                    $btn.prop('disabled', false);
                    setUpdateProgress(0, 'Download Failed', '[Error] ' + response.message);
                    createToast({
                        title: response.title,
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                        timeout: 6000,
                        top: 70
                    });
                }
            },
            error: function () {
                $btn.prop('disabled', false);
                setUpdateProgress(0, 'Download Error', 'Network error during download.');
            }
        });
    });

    // 5. Save Update Preferences
    $('.btn-update-setting').click(function () {
        var csrf_token_default = $('input[name="csrf_token_default"]').val();
        var update_channel = $('#update_channel').val();
        var automatic_update = $('#automatic_update').prop('checked') ? 'yes' : 'no';
        var create_backup = $('#create_backup').prop('checked') ? 'yes' : 'no';
        var $btn = $(this);
        var originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-1" role="status"></div> Saving...');

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: {
                action: "system-settings-update-setting",
                csrf_token: csrf_token_default,
                update_channel: update_channel,
                automatic_update: automatic_update,
                create_backup: create_backup
            },
            dataType: 'json',
            success: function (response) {
                $('#modal-updateSettings').modal('hide');
                $btn.prop('disabled', false).html(originalHtml);

                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                createToast({
                    title: response.title || 'Settings Saved',
                    description: response.message || 'Update preferences saved successfully.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f38f9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                    timeout: 5000,
                    top: 70
                });

                load_content('System Settings','<?php echo $site_url.$path_admin ?>/system-settings/update','nav-item-system-settings');
            },
            error: function () {
                $btn.prop('disabled', false).html(originalHtml);
                createToast({
                    title: 'Save Failed',
                    description: 'Could not save update preferences.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 6000,
                    top: 70
                });
            }
        });
    });

    // 6. Manual Update File Upload
    $('#btn-submit-manual-upload').click(function () {
        var fileInput = document.getElementById('manual_update_file');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Please select an update .zip file to upload.');
            return;
        }

        var csrf_token_default = $('input[name="csrf_token_default"]').val();
        var formData = new FormData();
        formData.append('action', 'system-settings-update-manual-upload');
        formData.append('csrf_token', csrf_token_default);
        formData.append('update_zip', fileInput.files[0]);

        var $btn = $(this);
        $btn.prop('disabled', true);
        $('#manual-upload-progress-box').removeClass('d-none');
        $('#manual-upload-bar').css('width', '50%');
        $('#manual-upload-status').text('Uploading and extracting archive...');

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                $btn.prop('disabled', false);
                $('#modal-manualUpload').modal('hide');

                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                if (response.status === 'true') {
                    createToast({
                        title: response.title,
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f38f9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                        timeout: 6000,
                        top: 70
                    });

                    setTimeout(function () {
                        location.reload();
                    }, 2500);
                } else {
                    createToast({
                        title: response.title,
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                        timeout: 6000,
                        top: 70
                    });
                }
            },
            error: function () {
                $btn.prop('disabled', false);
                createToast({
                    title: 'Upload Error',
                    description: 'Failed to upload and apply manual update package.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 6000,
                    top: 70
                });
            }
        });
    });

    // 7. Delete Single Backup Snapshot (Open Custom Modal)
    $(document).on('click', '.btn-delete-backup', function () {
        var fileName = $(this).data('file');
        var rowId = $(this).data('row');

        $('#delete-single-backup-filename').text(fileName);
        $('#delete-single-backup-file').val(fileName);
        $('#delete-single-backup-row').val(rowId);
        $('#modal-deleteSingleBackup').modal('show');
    });

    // Confirm Single Delete
    $('#btn-confirm-delete-single-backup').click(function () {
        var fileName = $('#delete-single-backup-file').val();
        var rowId = $('#delete-single-backup-row').val();
        var csrf_token_default = $('input[name="csrf_token_default"]').val();
        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Deleting...');

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: {
                action: "system-settings-update-backup-delete",
                csrf_token: csrf_token_default,
                file: fileName
            },
            dataType: 'json',
            success: function (response) {
                $('#modal-deleteSingleBackup').modal('hide');
                $btn.prop('disabled', false).text('Yes, Delete');

                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                if (response.status === 'true') {
                    $('#' + rowId).fadeOut(300, function () {
                        $(this).remove();
                        var count = $('#table-backup-snapshots tbody tr').length;
                        $('#backup-count-badge').text(count + ' Saved');
                        if (count === 0) {
                            load_content('System Settings','<?php echo $site_url.$path_admin ?>/system-settings/update','nav-item-system-settings');
                        }
                    });

                    createToast({
                        title: response.title || 'Deleted',
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f38f9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                        timeout: 5000,
                        top: 70
                    });
                } else {
                    createToast({
                        title: response.title || 'Delete Failed',
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                        timeout: 6000,
                        top: 70
                    });
                }
            },
            error: function () {
                $('#modal-deleteSingleBackup').modal('hide');
                $btn.prop('disabled', false).text('Yes, Delete');
                createToast({
                    title: 'Error',
                    description: 'Could not connect to server to delete snapshot.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 6000,
                    top: 70
                });
            }
        });
    });

    // 8. Delete All Backup Snapshots (Open Custom Modal)
    $('.btn-delete-all-backups').click(function () {
        $('#modal-deleteAllBackups').modal('show');
    });

    // Confirm Delete All
    $('#btn-confirm-delete-all-backups').click(function () {
        var csrf_token_default = $('input[name="csrf_token_default"]').val();
        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Deleting All...');

        $.ajax({
            type: 'POST',
            url: '<?php echo $site_url.$path_admin ?>/dashboard',
            data: {
                action: "system-settings-update-backup-delete",
                csrf_token: csrf_token_default,
                delete_all: "yes"
            },
            dataType: 'json',
            success: function (response) {
                $('#modal-deleteAllBackups').modal('hide');
                $btn.prop('disabled', false).text('Delete All');

                if (response.csrf_token) {
                    $('input[name="csrf_token"], input[name="csrf_token_default"]').val(response.csrf_token);
                }

                if (response.status === 'true') {
                    createToast({
                        title: response.title || 'Backups Cleared',
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f38f9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`,
                        timeout: 5000,
                        top: 70
                    });

                    load_content('System Settings','<?php echo $site_url.$path_admin ?>/system-settings/update','nav-item-system-settings');
                } else {
                    createToast({
                        title: response.title || 'Failed',
                        description: response.message,
                        svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                        timeout: 6000,
                        top: 70
                    });
                }
            },
            error: function () {
                $('#modal-deleteAllBackups').modal('hide');
                $btn.prop('disabled', false).text('Delete All');
                createToast({
                    title: 'Error',
                    description: 'Could not connect to server to delete snapshots.',
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`,
                    timeout: 6000,
                    top: 70
                });
            }
        });
    });
</script>