<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>

<div class="hero">
    <h2>Bienvenido al Sistema de Inventario de Infraestructura Mayor</h2>
    <p class="lead">Gestión de inventario de infraestructura hidráulica según normativa ANA</p>
</div>

<div class="cards-grid">
    <div class="info-card">
        <div class="card-icon">📋</div>
        <h3>Inventario</h3>
        <p>Consulta y gestiona el registro completo de infraestructuras hidráulicas mayores</p>
        <a href="<?php echo APP_URL; ?>/infraestructura" class="btn btn-primary">Ver Inventario</a>
    </div>

    <div class="info-card">
        <div class="card-icon">➕</div>
        <h3>Nuevo Registro</h3>
        <p>Registra nuevas infraestructuras según los lineamientos de la ANA</p>
        <a href="<?php echo APP_URL; ?>/infraestructura/crear" class="btn btn-primary">Registrar</a>
    </div>

    <div class="info-card">
        <div class="card-icon">📊</div>
        <h3>Estadísticas</h3>
        <p>Visualiza estadísticas y reportes del inventario de infraestructuras</p>
        <a href="<?php echo APP_URL; ?>/infraestructura/estadisticas" class="btn btn-primary">Ver Estadísticas</a>
    </div>

    <div class="info-card">
        <div class="card-icon">🔌</div>
        <h3>API REST</h3>
        <p>Accede a los datos mediante nuestra API REST documentada</p>
        <a href="<?php echo APP_URL; ?>/api/infraestructuras" class="btn btn-secondary" target="_blank">Ver API</a>
    </div>
</div>

<div class="card mt-4">
    <h3>Tipos de Infraestructura Soportados</h3>
    <div class="tipos-grid">
        <div class="tipo-item">🏗️ Presas y Represas</div>
        <div class="tipo-item">🚰 Bocatomas</div>
        <div class="tipo-item">🌊 Canales Principales</div>
        <div class="tipo-item">🚇 Túneles</div>
        <div class="tipo-item">🔄 Sifones</div>
        <div class="tipo-item">🌉 Acueductos</div>
        <div class="tipo-item">⚙️ Desarenadores</div>
        <div class="tipo-item">📏 Estructuras de Medición</div>
    </div>
</div>

<div class="card mt-4">
    <h3>Características del Sistema</h3>
    <ul class="features-list">
        <li>✅ Cumple con la normativa de la Autoridad Nacional del Agua (ANA)</li>
        <li>✅ Registro completo de datos técnicos y administrativos</li>
        <li>✅ Georreferenciación con coordenadas UTM</li>
        <li>✅ Validaciones automáticas de datos</li>
        <li>✅ Sistema de filtros y búsqueda avanzada</li>
        <li>✅ Estadísticas y reportes visuales</li>
        <li>✅ API REST para integración con otros sistemas</li>
        <li>✅ Interfaz intuitiva y responsive</li>
    </ul>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
