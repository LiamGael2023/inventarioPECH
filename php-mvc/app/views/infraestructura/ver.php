<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2><?php echo $title; ?></h2>
        <div>
            <a href="<?php echo APP_URL; ?>/infraestructura/editar/<?php echo $infraestructura['id']; ?>"
               class="btn btn-primary">Editar</a>
            <a href="<?php echo APP_URL; ?>/infraestructura" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <!-- Datos Generales -->
    <div class="info-section">
        <h3>Datos Generales</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Código ANA:</strong>
                <span><?php echo htmlspecialchars($infraestructura['codigo']); ?></span>
            </div>
            <div class="info-item">
                <strong>Nombre:</strong>
                <span><?php echo htmlspecialchars($infraestructura['nombre']); ?></span>
            </div>
            <div class="info-item">
                <strong>Tipo:</strong>
                <span><?php echo htmlspecialchars($infraestructura['tipo']); ?></span>
            </div>
        </div>
    </div>

    <!-- Ubicación -->
    <div class="info-section">
        <h3>Ubicación Geográfica</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Región:</strong>
                <span><?php echo htmlspecialchars($infraestructura['region']); ?></span>
            </div>
            <div class="info-item">
                <strong>Provincia:</strong>
                <span><?php echo htmlspecialchars($infraestructura['provincia']); ?></span>
            </div>
            <div class="info-item">
                <strong>Distrito:</strong>
                <span><?php echo htmlspecialchars($infraestructura['distrito']); ?></span>
            </div>
            <?php if ($infraestructura['coordenada_este']): ?>
                <div class="info-item">
                    <strong>Coordenada Este:</strong>
                    <span><?php echo number_format($infraestructura['coordenada_este'], 2); ?> m</span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['coordenada_norte']): ?>
                <div class="info-item">
                    <strong>Coordenada Norte:</strong>
                    <span><?php echo number_format($infraestructura['coordenada_norte'], 2); ?> m</span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['zona_utm']): ?>
                <div class="info-item">
                    <strong>Zona UTM:</strong>
                    <span><?php echo $infraestructura['zona_utm']; ?></span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['altitud']): ?>
                <div class="info-item">
                    <strong>Altitud:</strong>
                    <span><?php echo number_format($infraestructura['altitud'], 1); ?> msnm</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Hidrografía -->
    <?php if ($infraestructura['cuenca_hidrografica'] || $infraestructura['subcuenca'] || $infraestructura['cuerpo_agua']): ?>
    <div class="info-section">
        <h3>Hidrografía</h3>
        <div class="info-grid">
            <?php if ($infraestructura['cuenca_hidrografica']): ?>
                <div class="info-item">
                    <strong>Cuenca Hidrográfica:</strong>
                    <span><?php echo htmlspecialchars($infraestructura['cuenca_hidrografica']); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['subcuenca']): ?>
                <div class="info-item">
                    <strong>Subcuenca:</strong>
                    <span><?php echo htmlspecialchars($infraestructura['subcuenca']); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['cuerpo_agua']): ?>
                <div class="info-item">
                    <strong>Cuerpo de Agua:</strong>
                    <span><?php echo htmlspecialchars($infraestructura['cuerpo_agua']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Características Técnicas -->
    <div class="info-section">
        <h3>Características Técnicas</h3>
        <div class="info-grid">
            <?php if ($infraestructura['capacidad']): ?>
                <div class="info-item">
                    <strong>Capacidad:</strong>
                    <span><?php echo number_format($infraestructura['capacidad'], 2); ?> <?php echo $infraestructura['unidad_capacidad']; ?></span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['longitud']): ?>
                <div class="info-item">
                    <strong>Longitud:</strong>
                    <span><?php echo number_format($infraestructura['longitud'], 2); ?> m</span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['ancho']): ?>
                <div class="info-item">
                    <strong>Ancho:</strong>
                    <span><?php echo number_format($infraestructura['ancho'], 2); ?> m</span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['altura']): ?>
                <div class="info-item">
                    <strong>Altura:</strong>
                    <span><?php echo number_format($infraestructura['altura'], 2); ?> m</span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['area_influencia']): ?>
                <div class="info-item">
                    <strong>Área de Influencia:</strong>
                    <span><?php echo number_format($infraestructura['area_influencia'], 2); ?> ha</span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['material_construccion']): ?>
                <div class="info-item">
                    <strong>Material de Construcción:</strong>
                    <span><?php echo htmlspecialchars($infraestructura['material_construccion']); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['anio_construccion']): ?>
                <div class="info-item">
                    <strong>Año de Construcción:</strong>
                    <span><?php echo $infraestructura['anio_construccion']; ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Información Administrativa -->
    <div class="info-section">
        <h3>Información Administrativa</h3>
        <div class="info-grid">
            <?php if ($infraestructura['titular_propietario']): ?>
                <div class="info-item">
                    <strong>Titular/Propietario:</strong>
                    <span><?php echo htmlspecialchars($infraestructura['titular_propietario']); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['operador_actual']): ?>
                <div class="info-item">
                    <strong>Operador Actual:</strong>
                    <span><?php echo htmlspecialchars($infraestructura['operador_actual']); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['uso_principal']): ?>
                <div class="info-item">
                    <strong>Uso Principal:</strong>
                    <span><?php echo htmlspecialchars($infraestructura['uso_principal']); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($infraestructura['licencia_agua']): ?>
                <div class="info-item">
                    <strong>Licencia de Agua:</strong>
                    <span><?php echo htmlspecialchars($infraestructura['licencia_agua']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Estado -->
    <div class="info-section">
        <h3>Estado y Operación</h3>
        <div class="info-grid">
            <?php if ($infraestructura['estado_conservacion']): ?>
                <div class="info-item">
                    <strong>Estado de Conservación:</strong>
                    <?php
                    $badgeClass = 'badge-info';
                    if (in_array($infraestructura['estado_conservacion'], ['Muy Bueno', 'Bueno'])) {
                        $badgeClass = 'badge-success';
                    } elseif ($infraestructura['estado_conservacion'] === 'Regular') {
                        $badgeClass = 'badge-warning';
                    } else {
                        $badgeClass = 'badge-danger';
                    }
                    ?>
                    <span class="badge <?php echo $badgeClass; ?>">
                        <?php echo htmlspecialchars($infraestructura['estado_conservacion']); ?>
                    </span>
                </div>
            <?php endif; ?>
            <div class="info-item">
                <strong>Estado Operativo:</strong>
                <span><?php echo htmlspecialchars($infraestructura['estado_operativo']); ?></span>
            </div>
            <?php if ($infraestructura['fecha_ultima_inspeccion']): ?>
                <div class="info-item">
                    <strong>Fecha Última Inspección:</strong>
                    <span><?php echo date('d/m/Y', strtotime($infraestructura['fecha_ultima_inspeccion'])); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($infraestructura['observaciones']): ?>
            <div class="info-item" style="margin-top: 20px;">
                <strong>Observaciones:</strong>
                <p style="margin-top: 10px; padding: 15px; background: #f9fafb; border-radius: 6px;">
                    <?php echo nl2br(htmlspecialchars($infraestructura['observaciones'])); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Metadatos -->
    <div class="info-section" style="border-top: 2px solid #e5e7eb; padding-top: 20px; margin-top: 20px;">
        <h4 style="font-size: 0.9rem; color: #6b7280; text-transform: uppercase;">Metadatos</h4>
        <div class="info-grid">
            <div class="info-item">
                <strong>Fecha de Registro:</strong>
                <span><?php echo date('d/m/Y H:i', strtotime($infraestructura['fecha_registro'])); ?></span>
            </div>
            <?php if ($infraestructura['fecha_actualizacion']): ?>
                <div class="info-item">
                    <strong>Última Actualización:</strong>
                    <span><?php echo date('d/m/Y H:i', strtotime($infraestructura['fecha_actualizacion'])); ?></span>
                </div>
            <?php endif; ?>
            <div class="info-item">
                <strong>Registrado por:</strong>
                <span><?php echo htmlspecialchars($infraestructura['usuario_registro']); ?></span>
            </div>
        </div>
    </div>
</div>

<style>
.info-section {
    padding: 20px 0;
    border-bottom: 1px solid #e5e7eb;
}

.info-section:last-child {
    border-bottom: none;
}

.info-section h3 {
    font-size: 1.3rem;
    margin-bottom: 15px;
    color: #1f2937;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item strong {
    color: #6b7280;
    font-size: 0.9rem;
}

.info-item span {
    color: #1f2937;
    font-size: 1rem;
}
</style>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
