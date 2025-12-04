<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico del Sistema</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .check {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 5px solid #666;
        }
        .check.success {
            border-left-color: #10b981;
        }
        .check.error {
            border-left-color: #ef4444;
        }
        .check.warning {
            border-left-color: #f59e0b;
        }
        h1 {
            color: #1f2937;
        }
        h3 {
            margin: 0 0 10px 0;
            color: #374151;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
        }
        .status.ok {
            background: #d1fae5;
            color: #065f46;
        }
        .status.fail {
            background: #fee2e2;
            color: #991b1b;
        }
        .status.warn {
            background: #fef3c7;
            color: #92400e;
        }
        pre {
            background: #f9fafb;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .code {
            background: #1f2937;
            color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico del Sistema - Inventario ANA</h1>

    <?php
    // 1. Verificar versión de PHP
    $phpVersion = phpversion();
    $phpOk = version_compare($phpVersion, '7.4.0', '>=');
    ?>
    <div class="check <?php echo $phpOk ? 'success' : 'error'; ?>">
        <h3>1. Versión de PHP</h3>
        <p>
            <span class="status <?php echo $phpOk ? 'ok' : 'fail'; ?>">
                <?php echo $phpOk ? '✓' : '✗'; ?> PHP <?php echo $phpVersion; ?>
            </span>
        </p>
        <?php if (!$phpOk): ?>
            <p><strong>Error:</strong> Se requiere PHP 7.4 o superior</p>
        <?php endif; ?>
    </div>

    <?php
    // 2. Verificar mod_rewrite
    $modRewrite = function_exists('apache_get_modules') ? in_array('mod_rewrite', apache_get_modules()) : 'No se puede determinar';
    ?>
    <div class="check <?php echo $modRewrite === true ? 'success' : 'warning'; ?>">
        <h3>2. Apache mod_rewrite</h3>
        <p>
            <span class="status <?php echo $modRewrite === true ? 'ok' : 'warn'; ?>">
                <?php
                if ($modRewrite === true) {
                    echo '✓ Habilitado';
                } elseif ($modRewrite === false) {
                    echo '✗ Deshabilitado';
                } else {
                    echo '? No se puede verificar (esto es normal en algunos servidores)';
                }
                ?>
            </span>
        </p>
        <?php if ($modRewrite === false): ?>
            <p><strong>Solución:</strong></p>
            <div class="code">
                # En Ubuntu/Debian:<br>
                sudo a2enmod rewrite<br>
                sudo service apache2 restart<br><br>
                # En XAMPP (Windows): Ya viene habilitado por defecto
            </div>
        <?php endif; ?>
    </div>

    <?php
    // 3. Verificar extensiones PHP
    $extensions = [
        'pdo' => extension_loaded('pdo'),
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'mbstring' => extension_loaded('mbstring'),
        'json' => extension_loaded('json')
    ];
    $allExtensions = !in_array(false, $extensions);
    ?>
    <div class="check <?php echo $allExtensions ? 'success' : 'error'; ?>">
        <h3>3. Extensiones PHP Requeridas</h3>
        <?php foreach ($extensions as $ext => $loaded): ?>
            <p>
                <span class="status <?php echo $loaded ? 'ok' : 'fail'; ?>">
                    <?php echo $loaded ? '✓' : '✗'; ?> <?php echo $ext; ?>
                </span>
            </p>
        <?php endforeach; ?>
    </div>

    <?php
    // 4. Verificar archivo de configuración
    $configFile = __DIR__ . '/config/config.php';
    $configExists = file_exists($configFile);
    ?>
    <div class="check <?php echo $configExists ? 'success' : 'error'; ?>">
        <h3>4. Archivo de Configuración</h3>
        <p>
            <span class="status <?php echo $configExists ? 'ok' : 'fail'; ?>">
                <?php echo $configExists ? '✓' : '✗'; ?> config/config.php
            </span>
        </p>
        <?php if ($configExists): ?>
            <?php
            require_once $configFile;
            ?>
            <pre>APP_URL: <?php echo APP_URL; ?>
DB_HOST: <?php echo DB_HOST; ?>
DB_PORT: <?php echo DB_PORT; ?>
DB_NAME: <?php echo DB_NAME; ?>
DB_USER: <?php echo DB_USER; ?></pre>
        <?php endif; ?>
    </div>

    <?php
    // 5. Verificar conexión a base de datos
    $dbConnected = false;
    $dbError = '';
    if ($configExists) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $dbConnected = true;

            // Contar registros
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM infraestructuras");
            $totalInfra = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            $dbError = $e->getMessage();
        }
    }
    ?>
    <div class="check <?php echo $dbConnected ? 'success' : 'error'; ?>">
        <h3>5. Conexión a Base de Datos MySQL</h3>
        <p>
            <span class="status <?php echo $dbConnected ? 'ok' : 'fail'; ?>">
                <?php echo $dbConnected ? '✓ Conectado' : '✗ Error de conexión'; ?>
            </span>
        </p>
        <?php if ($dbConnected): ?>
            <p>📊 Infraestructuras registradas: <strong><?php echo $totalInfra; ?></strong></p>
        <?php else: ?>
            <p><strong>Error:</strong> <?php echo htmlspecialchars($dbError); ?></p>
            <p><strong>Solución:</strong></p>
            <ol>
                <li>Verifica que MySQL esté corriendo</li>
                <li>Ejecuta el script de base de datos:
                    <div class="code">mysql -u root -p &lt; config/database.sql</div>
                </li>
                <li>Verifica las credenciales en config/config.php</li>
            </ol>
        <?php endif; ?>
    </div>

    <?php
    // 6. Verificar .htaccess
    $htaccessFile = __DIR__ . '/.htaccess';
    $htaccessExists = file_exists($htaccessFile);
    ?>
    <div class="check <?php echo $htaccessExists ? 'success' : 'error'; ?>">
        <h3>6. Archivo .htaccess</h3>
        <p>
            <span class="status <?php echo $htaccessExists ? 'ok' : 'fail'; ?>">
                <?php echo $htaccessExists ? '✓ Existe' : '✗ No encontrado'; ?>
            </span>
        </p>
        <?php if ($htaccessExists): ?>
            <p>Contenido actual:</p>
            <pre><?php echo htmlspecialchars(file_get_contents($htaccessFile)); ?></pre>
        <?php endif; ?>
    </div>

    <?php
    // 7. Verificar estructura de directorios
    $dirs = [
        'app' => __DIR__ . '/app',
        'app/controllers' => __DIR__ . '/app/controllers',
        'app/models' => __DIR__ . '/app/models',
        'app/views' => __DIR__ . '/app/views',
        'core' => __DIR__ . '/core',
        'config' => __DIR__ . '/config',
        'public' => __DIR__ . '/public'
    ];
    $allDirsExist = true;
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            $allDirsExist = false;
            break;
        }
    }
    ?>
    <div class="check <?php echo $allDirsExist ? 'success' : 'error'; ?>">
        <h3>7. Estructura de Directorios</h3>
        <?php foreach ($dirs as $name => $path): ?>
            <?php $exists = is_dir($path); ?>
            <p>
                <span class="status <?php echo $exists ? 'ok' : 'fail'; ?>">
                    <?php echo $exists ? '✓' : '✗'; ?> <?php echo $name; ?>
                </span>
            </p>
        <?php endforeach; ?>
    </div>

    <?php
    // 8. Test de routing
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    ?>
    <div class="check success">
        <h3>8. Información de Routing</h3>
        <pre>URL Actual: <?php echo htmlspecialchars($currentUrl); ?>
REQUEST_URI: <?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>
SCRIPT_NAME: <?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>
DOCUMENT_ROOT: <?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT']); ?></pre>
    </div>

    <?php
    // Resumen
    $allOk = $phpOk && $allExtensions && $configExists && $dbConnected && $htaccessExists && $allDirsExist;
    ?>
    <div class="check <?php echo $allOk ? 'success' : 'warning'; ?>">
        <h3>✅ Resumen</h3>
        <?php if ($allOk): ?>
            <p style="font-size: 1.2em; color: #10b981;">
                <strong>¡Todo está configurado correctamente!</strong>
            </p>
            <p>El sistema debería funcionar sin problemas.</p>
            <p>
                <a href="<?php echo APP_URL; ?>" style="display: inline-block; padding: 12px 24px; background: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 10px;">
                    🚀 Ir al Sistema
                </a>
            </p>
        <?php else: ?>
            <p style="font-size: 1.1em; color: #f59e0b;">
                <strong>Hay algunos problemas que necesitan ser corregidos.</strong>
            </p>
            <p>Revisa los checks marcados con ✗ arriba y sigue las soluciones propuestas.</p>
        <?php endif; ?>
    </div>

    <div class="check" style="background: #eff6ff; border-left-color: #3b82f6;">
        <h3>📝 Enlaces Rápidos</h3>
        <ul>
            <li><a href="<?php echo APP_URL; ?>">Inicio del Sistema</a></li>
            <li><a href="<?php echo APP_URL; ?>/infraestructura">Ver Inventario</a></li>
            <li><a href="<?php echo APP_URL; ?>/api/infraestructuras">API REST</a></li>
            <li><a href="<?php echo APP_URL; ?>/diagnostico.php">Volver a ejecutar diagnóstico</a></li>
        </ul>
    </div>

    <div style="text-align: center; margin-top: 30px; padding: 20px; color: #6b7280;">
        <p>Sistema de Inventario ANA - Diagnóstico v1.0</p>
        <p>Autoridad Nacional del Agua del Perú</p>
    </div>
</body>
</html>
