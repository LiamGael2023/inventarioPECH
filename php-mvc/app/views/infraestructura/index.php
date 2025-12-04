<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>

<h2><?php echo $title; ?></h2>

<div class="card">
    <h3>Filtros de Búsqueda</h3>
    <form method="GET" action="<?php echo APP_URL; ?>/infraestructura" class="filters-form">
        <div class="form-row">
            <div class="form-group">
                <label>Tipo de Infraestructura:</label>
                <select name="tipo">
                    <option value="">Todos</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?php echo $tipo; ?>" <?php echo $filters['tipo'] === $tipo ? 'selected' : ''; ?>>
                            <?php echo $tipo; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Región:</label>
                <input type="text" name="region" value="<?php echo htmlspecialchars($filters['region']); ?>" placeholder="Ej: Lima">
            </div>

            <div class="form-group">
                <label>Estado de Conservación:</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?php echo $estado; ?>" <?php echo $filters['estado'] === $estado ? 'selected' : ''; ?>>
                            <?php echo $estado; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="<?php echo APP_URL; ?>/infraestructura" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3>Infraestructuras Registradas (<?php echo count($infraestructuras); ?>)</h3>
        <a href="<?php echo APP_URL; ?>/infraestructura/crear" class="btn btn-success">Nuevo Registro</a>
    </div>

    <?php if (empty($infraestructuras)): ?>
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <p>No se encontraron infraestructuras con los filtros aplicados</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Ubicación</th>
                        <th>Cuenca</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($infraestructuras as $infra): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($infra['codigo']); ?></strong></td>
                            <td><?php echo htmlspecialchars($infra['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($infra['tipo']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($infra['distrito']); ?>, <?php echo htmlspecialchars($infra['provincia']); ?>
                                <br><small><?php echo htmlspecialchars($infra['region']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($infra['cuenca_hidrografica'] ?? '-'); ?></td>
                            <td>
                                <?php
                                $badgeClass = 'badge-info';
                                if ($infra['estado_conservacion']) {
                                    if (in_array($infra['estado_conservacion'], ['Muy Bueno', 'Bueno'])) {
                                        $badgeClass = 'badge-success';
                                    } elseif ($infra['estado_conservacion'] === 'Regular') {
                                        $badgeClass = 'badge-warning';
                                    } else {
                                        $badgeClass = 'badge-danger';
                                    }
                                }
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo htmlspecialchars($infra['estado_conservacion'] ?? 'No especificado'); ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="<?php echo APP_URL; ?>/infraestructura/ver/<?php echo $infra['id']; ?>"
                                   class="btn btn-sm btn-info">Ver</a>
                                <a href="<?php echo APP_URL; ?>/infraestructura/editar/<?php echo $infra['id']; ?>"
                                   class="btn btn-sm btn-primary">Editar</a>
                                <button onclick="eliminarInfraestructura(<?php echo $infra['id']; ?>, '<?php echo htmlspecialchars($infra['nombre']); ?>')"
                                        class="btn btn-sm btn-danger">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function eliminarInfraestructura(id, nombre) {
    if (confirm('¿Está seguro de eliminar "' + nombre + '"?')) {
        fetch('<?php echo APP_URL; ?>/api/infraestructuras/' + id, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Infraestructura eliminada exitosamente');
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error al eliminar: ' + error);
        });
    }
}
</script>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
