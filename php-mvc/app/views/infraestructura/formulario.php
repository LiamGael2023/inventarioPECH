<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>

<h2><?php echo $title; ?></h2>

<form id="formInfraestructura" class="form-infraestructura">
    <input type="hidden" id="infraId" value="<?php echo $infraestructura['id'] ?? ''; ?>">

    <!-- Datos Generales -->
    <div class="card">
        <h3>Datos Generales</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Código ANA: *</label>
                <input type="text" name="codigo" id="codigo"
                       value="<?php echo htmlspecialchars($infraestructura['codigo'] ?? ''); ?>"
                       placeholder="ANA-LIM-PRES-0001"
                       <?php echo isset($infraestructura['id']) ? 'readonly' : 'required'; ?>>
                <small>Formato: ANA-REGIÓN-TIPO-NÚMERO</small>
            </div>

            <div class="form-group">
                <label>Nombre: *</label>
                <input type="text" name="nombre" id="nombre"
                       value="<?php echo htmlspecialchars($infraestructura['nombre'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Tipo de Infraestructura: *</label>
                <select name="tipo" id="tipo" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?php echo $tipo; ?>"
                                <?php echo ($infraestructura['tipo'] ?? '') === $tipo ? 'selected' : ''; ?>>
                            <?php echo $tipo; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Ubicación Geográfica -->
    <div class="card">
        <h3>Ubicación Geográfica</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Región: *</label>
                <input type="text" name="region" id="region"
                       value="<?php echo htmlspecialchars($infraestructura['region'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Provincia: *</label>
                <input type="text" name="provincia" id="provincia"
                       value="<?php echo htmlspecialchars($infraestructura['provincia'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Distrito: *</label>
                <input type="text" name="distrito" id="distrito"
                       value="<?php echo htmlspecialchars($infraestructura['distrito'] ?? ''); ?>" required>
            </div>
        </div>

        <h4>Coordenadas UTM</h4>
        <div class="form-row">
            <div class="form-group">
                <label>Este (m):</label>
                <input type="number" step="0.01" name="coordenada_este" id="coordenada_este"
                       value="<?php echo $infraestructura['coordenada_este'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Norte (m):</label>
                <input type="number" step="0.01" name="coordenada_norte" id="coordenada_norte"
                       value="<?php echo $infraestructura['coordenada_norte'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Zona UTM:</label>
                <select name="zona_utm" id="zona_utm">
                    <option value="">Seleccionar...</option>
                    <option value="17" <?php echo ($infraestructura['zona_utm'] ?? '') == 17 ? 'selected' : ''; ?>>17</option>
                    <option value="18" <?php echo ($infraestructura['zona_utm'] ?? '') == 18 ? 'selected' : ''; ?>>18</option>
                    <option value="19" <?php echo ($infraestructura['zona_utm'] ?? '') == 19 ? 'selected' : ''; ?>>19</option>
                </select>
            </div>

            <div class="form-group">
                <label>Altitud (msnm):</label>
                <input type="number" step="0.1" name="altitud" id="altitud"
                       value="<?php echo $infraestructura['altitud'] ?? ''; ?>">
            </div>
        </div>
    </div>

    <!-- Hidrografía -->
    <div class="card">
        <h3>Hidrografía</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Cuenca Hidrográfica:</label>
                <input type="text" name="cuenca_hidrografica" id="cuenca_hidrografica"
                       value="<?php echo htmlspecialchars($infraestructura['cuenca_hidrografica'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Subcuenca:</label>
                <input type="text" name="subcuenca" id="subcuenca"
                       value="<?php echo htmlspecialchars($infraestructura['subcuenca'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Cuerpo de Agua:</label>
                <input type="text" name="cuerpo_agua" id="cuerpo_agua"
                       value="<?php echo htmlspecialchars($infraestructura['cuerpo_agua'] ?? ''); ?>"
                       placeholder="Río, quebrada, lago...">
            </div>
        </div>
    </div>

    <!-- Características Técnicas -->
    <div class="card">
        <h3>Características Técnicas</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Capacidad:</label>
                <input type="number" step="0.01" name="capacidad" id="capacidad"
                       value="<?php echo $infraestructura['capacidad'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Unidad:</label>
                <select name="unidad_capacidad" id="unidad_capacidad">
                    <option value="m³" <?php echo ($infraestructura['unidad_capacidad'] ?? 'm³') === 'm³' ? 'selected' : ''; ?>>m³</option>
                    <option value="m³/s" <?php echo ($infraestructura['unidad_capacidad'] ?? '') === 'm³/s' ? 'selected' : ''; ?>>m³/s</option>
                    <option value="l/s" <?php echo ($infraestructura['unidad_capacidad'] ?? '') === 'l/s' ? 'selected' : ''; ?>>l/s</option>
                </select>
            </div>

            <div class="form-group">
                <label>Longitud (m):</label>
                <input type="number" step="0.01" name="longitud" id="longitud"
                       value="<?php echo $infraestructura['longitud'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Ancho (m):</label>
                <input type="number" step="0.01" name="ancho" id="ancho"
                       value="<?php echo $infraestructura['ancho'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Altura (m):</label>
                <input type="number" step="0.01" name="altura" id="altura"
                       value="<?php echo $infraestructura['altura'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Área de Influencia (ha):</label>
                <input type="number" step="0.01" name="area_influencia" id="area_influencia"
                       value="<?php echo $infraestructura['area_influencia'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Material de Construcción:</label>
                <select name="material_construccion" id="material_construccion">
                    <option value="">Seleccionar...</option>
                    <?php foreach ($materiales as $material): ?>
                        <option value="<?php echo $material; ?>"
                                <?php echo ($infraestructura['material_construccion'] ?? '') === $material ? 'selected' : ''; ?>>
                            <?php echo $material; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Año de Construcción:</label>
                <input type="number" name="anio_construccion" id="anio_construccion"
                       value="<?php echo $infraestructura['anio_construccion'] ?? ''; ?>"
                       min="1900" max="<?php echo date('Y'); ?>">
            </div>
        </div>
    </div>

    <!-- Información Administrativa -->
    <div class="card">
        <h3>Información Administrativa</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Titular/Propietario:</label>
                <input type="text" name="titular_propietario" id="titular_propietario"
                       value="<?php echo htmlspecialchars($infraestructura['titular_propietario'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Operador Actual:</label>
                <input type="text" name="operador_actual" id="operador_actual"
                       value="<?php echo htmlspecialchars($infraestructura['operador_actual'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Uso Principal:</label>
                <select name="uso_principal" id="uso_principal">
                    <option value="">Seleccionar...</option>
                    <?php foreach ($usos as $uso): ?>
                        <option value="<?php echo $uso; ?>"
                                <?php echo ($infraestructura['uso_principal'] ?? '') === $uso ? 'selected' : ''; ?>>
                            <?php echo $uso; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>N° Licencia de Agua:</label>
                <input type="text" name="licencia_agua" id="licencia_agua"
                       value="<?php echo htmlspecialchars($infraestructura['licencia_agua'] ?? ''); ?>">
            </div>
        </div>
    </div>

    <!-- Estado y Operación -->
    <div class="card">
        <h3>Estado y Operación</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Estado de Conservación:</label>
                <select name="estado_conservacion" id="estado_conservacion">
                    <option value="">Seleccionar...</option>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?php echo $estado; ?>"
                                <?php echo ($infraestructura['estado_conservacion'] ?? '') === $estado ? 'selected' : ''; ?>>
                            <?php echo $estado; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Estado Operativo:</label>
                <select name="estado_operativo" id="estado_operativo">
                    <option value="Operativo" <?php echo ($infraestructura['estado_operativo'] ?? 'Operativo') === 'Operativo' ? 'selected' : ''; ?>>Operativo</option>
                    <option value="Inoperativo" <?php echo ($infraestructura['estado_operativo'] ?? '') === 'Inoperativo' ? 'selected' : ''; ?>>Inoperativo</option>
                    <option value="En mantenimiento" <?php echo ($infraestructura['estado_operativo'] ?? '') === 'En mantenimiento' ? 'selected' : ''; ?>>En mantenimiento</option>
                </select>
            </div>

            <div class="form-group">
                <label>Fecha Última Inspección:</label>
                <input type="date" name="fecha_ultima_inspeccion" id="fecha_ultima_inspeccion"
                       value="<?php echo $infraestructura['fecha_ultima_inspeccion'] ?? ''; ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Observaciones:</label>
            <textarea name="observaciones" id="observaciones" rows="4"><?php echo htmlspecialchars($infraestructura['observaciones'] ?? ''); ?></textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-lg">
            <?php echo isset($infraestructura['id']) ? 'Actualizar' : 'Registrar'; ?> Infraestructura
        </button>
        <a href="<?php echo APP_URL; ?>/infraestructura" class="btn btn-secondary btn-lg">Cancelar</a>
    </div>
</form>

<div id="mensaje" class="mensaje" style="display: none;"></div>

<script src="<?php echo APP_URL; ?>/public/js/formulario.js"></script>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
