<!DOCTYPE html>
<html lang="<?= ($_SESSION['install_lang'] ?? 'tr') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $L['baslik'] ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $autoUrl ?>/assets/css/install.css">
</head>
<body>
    <div class="install-wrapper">
        <!-- Header -->
        <div class="install-header">
            <div class="brand-box">
                <div class="brand-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="brand-titles">
                    <h1>Modulix-CMS</h1>
                    <p><?= $L['alt_baslik'] ?></p>
                </div>
            </div>
            <div class="lang-pills">
                <a href="?lang=tr" class="<?= ($_SESSION['install_lang'] ?? 'tr') === 'tr' ? 'active' : '' ?>">TR</a>
                <a href="?lang=en" class="<?= ($_SESSION['install_lang'] ?? 'tr') === 'en' ? 'active' : '' ?>">EN</a>
            </div>
        </div>

        <?php if ($alreadyInstalled): ?>
            <!-- Önceden Kurulmuş Uyarısı -->
            <div class="reinstall-banner">
                <h2><i class="fa-solid fa-triangle-exclamation"></i> <?= $L['zaten_kurulu_baslik'] ?></h2>
                <p><?= $L['zaten_kurulu_aciklama'] ?><br><strong><?= $L['zaten_kurulu_soru'] ?></strong></p>
                <div style="display:flex; gap:12px; justify-content:center;">
                    <form action="<?= $autoUrl ?>/install-reset" method="POST" style="margin:0;">
                        <button type="submit" class="btn btn-primary" style="background:#f43f5e;"><?= $L['sifirla_yeniden_kur'] ?></button>
                    </form>
                    <a href="<?= rtrim($autoUrl, '/') ?>/giris" class="btn btn-secondary"><?= $L['vazgec_giris_yap'] ?></a>
                </div>
            </div>
        <?php else: ?>
            <!-- Stepper Progress -->
            <div class="stepper">
                <div class="step-node active" id="node1">
                    <div class="step-circle" id="circle1">1</div>
                    <div class="step-label"><?= $L['adim_1'] ?></div>
                </div>
                <div class="step-node" id="node2">
                    <div class="step-circle" id="circle2">2</div>
                    <div class="step-label"><?= $L['adim_2'] ?></div>
                </div>
                <div class="step-node" id="node3">
                    <div class="step-circle" id="circle3">3</div>
                    <div class="step-label"><?= $L['adim_3'] ?></div>
                </div>
            </div>

            <form action="<?= $autoUrl ?>/install-kaydet" method="POST" id="installForm">
                <div class="wizard-content">
                    <!-- ADIM 1: Teşhis -->
                    <div class="step-pane active" id="pane1">
                        <div class="diag-grid">
                            <div class="diag-card">
                                <span><?= $L['php_surum'] ?></span>
                                <?= $phpOK ? '<span class="status-pill ok"><i class="fa-solid fa-check"></i> Uyumlu</span>' : '<span class="status-pill fail"><i class="fa-solid fa-xmark"></i> Yetersiz</span>' ?>
                            </div>
                            <div class="diag-card">
                                <span><?= $L['pdo_mysql'] ?></span>
                                <?= $pdoOK ? '<span class="status-pill ok"><i class="fa-solid fa-check"></i> Uyumlu</span>' : '<span class="status-pill fail"><i class="fa-solid fa-xmark"></i> Eksik</span>' ?>
                            </div>
                            <div class="diag-card">
                                <span><?= $L['gd_eklentisi'] ?></span>
                                <?= $gdOK ? '<span class="status-pill ok"><i class="fa-solid fa-check"></i> Aktif</span>' : '<span class="status-pill fail"><i class="fa-solid fa-xmark"></i> Pasif</span>' ?>
                            </div>
                            <div class="diag-card">
                                <span><?= $L['zip_eklentisi'] ?></span>
                                <?= $zipOK ? '<span class="status-pill ok"><i class="fa-solid fa-check"></i> Aktif</span>' : '<span class="status-pill fail"><i class="fa-solid fa-xmark"></i> Pasif</span>' ?>
                            </div>
                        </div>
                        <div class="actions-bar" style="justify-content: flex-end;">
                            <button type="button" class="btn btn-primary" onclick="changeStep(2)"><?= $L['ileri'] ?> <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- ADIM 2: Veritabanı ve Yol -->
                    <div class="step-pane" id="pane2">
                        <div class="form-grid-2">
                            <div class="form-group"><label><i class="fa-solid fa-server"></i> DB Host</label><input type="text" name="db_host" id="db_host" class="form-control" value="localhost" required></div>
                            <div class="form-group"><label><i class="fa-solid fa-network-wired"></i> DB Port</label><input type="text" name="db_port" id="db_port" class="form-control" value="3306" required></div>
                        </div>
                        <div class="form-grid-2">
                            <div class="form-group"><label><i class="fa-solid fa-database"></i> DB Name</label><input type="text" name="db_name" id="db_name" class="form-control" value="modulix_cms" required></div>
                            <div class="form-group"><label><i class="fa-solid fa-user"></i> DB User</label><input type="text" name="db_user" id="db_user" class="form-control" value="root" required></div>
                        </div>
                        <div class="form-group"><label><i class="fa-solid fa-key"></i> DB Password</label><input type="password" name="db_pass" id="db_pass" class="form-control"></div>
                        
                        <button type="button" class="btn btn-secondary" id="btnTestDb" style="width:100%; margin-bottom:16px;"><i class="fa-solid fa-plug"></i> <?= $L['db_test_btn'] ?></button>
                        <div id="testResult" style="font-size:13px; font-weight:600; margin-bottom:16px; text-align:center;"></div>

                        <div class="form-group">
                            <label><i class="fa-solid fa-location-dot"></i> <?= $L['kurulum_hedevi'] ?? 'Kurulum Konumu Seçimi' ?></label>
                            <div class="target-cards">
                                <div class="target-card selected" id="cardKlasor" onclick="selectInstallMode('klasor')">
                                    <div class="target-card-header">
                                        <input type="radio" name="install_mode" value="klasor" id="radioKlasor" checked>
                                        <i class="fa-solid fa-folder-tree"></i> Alt Klasör (Subfolder)
                                    </div>
                                    <div class="target-card-desc">Sistem bir alt klasör içinde çalışır.</div>
                                </div>
                                <div class="target-card" id="cardKok" onclick="selectInstallMode('kok')">
                                    <div class="target-card-header">
                                        <input type="radio" name="install_mode" value="kok" id="radioKok">
                                        <i class="fa-solid fa-globe"></i> Kök Dizin (Root Domain)
                                    </div>
                                    <div class="target-card-desc">Sistem doğrudan domain ana dizinine kurulur.</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group"><label><i class="fa-solid fa-link"></i> <?= $L['site_url'] ?></label><input type="text" name="app_url" id="app_url" class="form-control" value="<?= $autoUrl ?>" required></div>
                        <div class="form-group"><label><i class="fa-solid fa-folder-open"></i> <?= $L['base_path'] ?></label><input type="text" name="base_path" id="base_path" class="form-control" value="<?= $autoBasePath ?>" required></div>

                        <div class="actions-bar">
                            <button type="button" class="btn btn-secondary" onclick="changeStep(1)"><i class="fa-solid fa-arrow-left"></i> <?= $L['geri'] ?></button>
                            <button type="button" class="btn btn-primary" onclick="changeStep(3)"><?= $L['ileri'] ?> <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- ADIM 3: Yönetici Güvenliği -->
                    <div class="step-pane" id="pane3">
                        <div class="form-group"><label><i class="fa-solid fa-user-gear"></i> <?= $L['admin_kadi'] ?></label><input type="text" name="admin_user" class="form-control" value="admin" required></div>
                        <div class="form-group"><label><i class="fa-solid fa-envelope"></i> <?= $L['admin_eposta'] ?></label><input type="email" name="admin_email" class="form-control" value="root@abc.com" required></div>
                        <div class="form-group">
                            <label><i class="fa-solid fa-lock"></i> <?= $L['admin_sifre'] ?></label>
                            <input type="password" name="admin_pass" id="admin_pass" class="form-control" value="Admin123!" required>
                            <div class="pass-meter-wrap"><div class="pass-meter-bg"><div id="meterBar" class="pass-meter-fill"></div></div></div>
                            <div id="strengthText" style="font-size:12px; margin-top:6px; font-weight:600;"></div>
                        </div>
                        <div class="form-group">
                            <label><i class="fa-solid fa-check-double"></i> <?= $L['admin_sifre_tekrar'] ?></label>
                            <input type="password" name="admin_pass_confirm" id="admin_pass_confirm" class="form-control" value="Admin123!" required>
                            <div id="matchText" style="font-size:12px; margin-top:6px; font-weight:600;"></div>
                        </div>

                        <div class="actions-bar">
                            <button type="button" class="btn btn-secondary" onclick="changeStep(2)"><i class="fa-solid fa-arrow-left"></i> <?= $L['geri'] ?></button>
                            <button type="submit" id="btnSubmit" class="btn btn-primary"><i class="fa-solid fa-rocket"></i> <?= $L['kurulumu_tamamla'] ?></button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        var autoUrl = "<?= $autoUrl ?>";
        var autoBasePath = "<?= $autoBasePath ?>";
        var rootUrl = "<?= $rootUrl ?>";

        function selectInstallMode(mode) {
            var rKlasor = document.getElementById('radioKlasor');
            var rKok = document.getElementById('radioKok');
            var cKlasor = document.getElementById('cardKlasor');
            var cKok = document.getElementById('cardKok');
            var inputUrl = document.getElementById('app_url');
            var inputPath = document.getElementById('base_path');

            if (mode === 'kok') {
                rKok.checked = true;
                cKok.classList.add('selected');
                cKlasor.classList.remove('selected');
                inputUrl.value = rootUrl;
                inputPath.value = '/';
            } else {
                rKlasor.checked = true;
                cKlasor.classList.add('selected');
                cKok.classList.remove('selected');
                inputUrl.value = autoUrl;
                inputPath.value = autoBasePath;
            }
        }

        function changeStep(targetStep) {
            document.querySelectorAll('.step-pane').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.step-node').forEach(el => el.classList.remove('active'));

            document.getElementById('pane' + targetStep).classList.add('active');
            
            for (var i = 1; i <= 3; i++) {
                var node = document.getElementById('node' + i);
                var circle = document.getElementById('circle' + i);
                if (i < targetStep) {
                    node.classList.add('completed');
                    circle.innerHTML = '<i class="fa-solid fa-check"></i>';
                } else if (i === targetStep) {
                    node.classList.add('active');
                    node.classList.remove('completed');
                    circle.innerText = i;
                } else {
                    node.classList.remove('active', 'completed');
                    circle.innerText = i;
                }
            }

            if (targetStep === 3) {
                validatePasswordState();
            }
        }

        // AJAX DB Test
        var btnTest = document.getElementById('btnTestDb');
        if (btnTest) {
            btnTest.addEventListener('click', function() {
                var formData = new FormData();
                formData.append('db_host', document.getElementById('db_host').value);
                formData.append('db_port', document.getElementById('db_port').value);
                formData.append('db_user', document.getElementById('db_user').value);
                formData.append('db_pass', document.getElementById('db_pass').value);

                fetch('<?= $autoUrl ?>/install-db-test', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    var resDiv = document.getElementById('testResult');
                    resDiv.innerText = data.message;
                    resDiv.style.color = data.success ? '#10b981' : '#f43f5e';
                });
            });
        }

        // Güvenli Şifre Doğrulama (Kilitlenmeyen Mantık)
        var passInput = document.getElementById('admin_pass');
        var confirmInput = document.getElementById('admin_pass_confirm');
        var meterBar = document.getElementById('meterBar');
        var strengthText = document.getElementById('strengthText');
        var matchText = document.getElementById('matchText');
        var submitBtn = document.getElementById('btnSubmit');

        function validatePasswordState() {
            if (!passInput || !submitBtn) return;
            
            var val = passInput.value;
            var score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score++;

            meterBar.className = 'pass-meter-fill';
            if (score <= 1) {
                meterBar.classList.add('red');
                strengthText.innerText = '🔴 Zayıf Parola';
                strengthText.style.color = '#f43f5e';
            } else if (score === 2) {
                meterBar.classList.add('yellow');
                strengthText.innerText = '🟡 Orta Seviye Parola';
                strengthText.style.color = '#f59e0b';
            } else {
                meterBar.classList.add('green');
                strengthText.innerText = '🟢 Mükemmel ve Güçlü Parola';
                strengthText.style.color = '#10b981';
            }

            if (passInput.value !== confirmInput.value) {
                matchText.innerText = '✖ Şifreler birbiriyle eşleşmiyor';
                matchText.style.color = '#f43f5e';
                submitBtn.disabled = true;
            } else {
                matchText.innerText = '✔ Şifreler eşleşiyor ve uygun';
                matchText.style.color = '#10b981';
                submitBtn.disabled = false; // Şifreler eşleştiğinde buton kesin aktif edilir
            }
        }

        if (passInput && confirmInput) {
            passInput.addEventListener('input', validatePasswordState);
            confirmInput.addEventListener('input', validatePasswordState);
            validatePasswordState();
        }
    </script>
</body>
</html>
