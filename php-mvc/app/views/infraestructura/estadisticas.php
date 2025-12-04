<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>

<h2><?php echo $title; ?></h2>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total de Infraestructuras</h3>
        <div class="stat-number"><?php echo $stats['total']; ?></div>
    </div>

    <div class="stat-card">
        <h3>Tipos Registrados</h3>
        <div class="stat-number"><?php echo count($stats['porTipo']); ?></div>
    </div>

    <div class="stat-card">
        <h3>Regiones Cubiertas</h3>
        <div class="stat-number"><?php echo count($stats['porRegion']); ?></div>
    </div>
</div>

<!-- Por Tipo -->
<div class="card">
    <h3>Infraestructuras por Tipo</h3>
    <?php if (!empty($stats['porTipo'])): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo de Infraestructura</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['porTipo'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['tipo']); ?></td>
                            <td><strong><?php echo $item['count']; ?></strong></td>
                            <td>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: <?php echo ($item['count'] / $stats['total']) * 100; ?>%"></div>
                                </div>
                                <?php echo number_format(($item['count'] / $stats['total']) * 100, 1); ?>%
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No hay datos disponibles</p>
    <?php endif; ?>
</div>

<!-- Por Región -->
<div class="card">
    <h3>Infraestructuras por Región (Top 10)</h3>
    <?php if (!empty($stats['porRegion'])): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Región</th>
                        <th>Cantidad</th>
                        <th>Distribución</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['porRegion'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['region']); ?></td>
                            <td><strong><?php echo $item['count']; ?></strong></td>
                            <td>
                                <div class="progress-bar-container">
                                    <div class="progress-bar progress-bar-success" style="width: <?php echo ($item['count'] / $stats['total']) * 100; ?>%"></div>
                                </div>
                                <?php echo number_format(($item['count'] / $stats['total']) * 100, 1); ?>%
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No hay datos disponibles</p>
    <?php endif; ?>
</div>

<!-- Estado de Conservación -->
<div class="card">
    <h3>Estado de Conservación</h3>
    <?php if (!empty($stats['porEstado'])): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['porEstado'] as $item): ?>
                        <?php
                        $badgeClass = 'badge-info';
                        if (in_array($item['estado_conservacion'], ['Muy Bueno', 'Bueno'])) {
                            $badgeClass = 'badge-success';
                        } elseif ($item['estado_conservacion'] === 'Regular') {
                            $badgeClass = 'badge-warning';
                        } else {
                            $badgeClass = 'badge-danger';
                        }
                        ?>
                        <tr>
                            <td>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo htmlspecialchars($item['estado_conservacion']); ?>
                                </span>
                            </td>
                            <td><strong><?php echo $item['count']; ?></strong></td>
                            <td><?php echo number_format(($item['count'] / $stats['total']) * 100, 1); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No hay datos disponibles</p>
    <?php endif; ?>
</div>

<!-- Uso Principal -->
<div class="card">
    <h3>Uso Principal del Agua</h3>
    <?php if (!empty($stats['porUso'])): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Uso</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['porUso'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['uso_principal']); ?></td>
                            <td><strong><?php echo $item['count']; ?></strong></td>
                            <td><?php echo number_format(($item['count'] / $stats['total']) * 100, 1); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No hay datos disponibles</p>
    <?php endif; ?>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
