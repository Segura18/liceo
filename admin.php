<?php
// ------------------------------------------------------------
// Panel administrativo protegido por sesión
// ------------------------------------------------------------
require_once __DIR__ . '/auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración | C.N. Educativo Pedro Lucas Urribarri</title>
    <style>
        /* [Se mantiene intacto todo tu bloque CSS original para no alterar tu UI] */
        :root {
            --primary: #1E3A5F;
            --primary-light: #2C5282;
            --accent: #E63946;
            --success: #2ECC71;
            --bg-admin: #F8FAFC;
            --bg-card: #FFFFFF;
            --text-main: #2D3748;
            --text-muted: #718096;
            --border-color: #E2E8F0;
            --radius-lg: 16px;
            --radius-md: 10px;
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        body { background-color: var(--bg-admin); color: var(--text-main); min-height: 100vh; display: flex; }
        .sidebar { width: 280px; background: #102A43; color: #FFFFFF; display: flex; flex-direction: column; border-right: 1px solid rgba(255, 255, 255, 0.05); position: fixed; height: 100vh; left: 0; top: 0; }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-header h2 { font-size: 1.2rem; font-weight: 800; letter-spacing: -0.5px; line-height: 1.3; }
        .sidebar-header p { color: #9FB3C8; font-size: 0.8rem; margin-top: 0.25rem; }
        .sidebar-menu { list-style: none; padding: 1.5rem 1rem; display: flex; flex-direction: column; gap: 0.5rem; flex: 1; }
        .menu-btn { width: 100%; background: transparent; border: none; color: #D9E2EC; padding: 0.85rem 1rem; text-align: left; font-size: 0.95rem; font-weight: 500; border-radius: var(--radius-md); cursor: pointer; display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s ease; }
        .menu-btn:hover { background: rgba(255, 255, 255, 0.05); color: #FFFFFF; }
        .menu-btn.active { background: var(--primary-light); color: #FFFFFF; font-weight: 600; box-shadow: var(--shadow-sm); }
        .logout-container { padding: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1); }
        .main-content { margin-left: 280px; flex: 1; padding: 2.5rem; max-width: 1200px; }
        .view-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; }
        .view-title h1 { font-size: 1.75rem; color: var(--primary); font-weight: 700; }
        .view-title p { font-size: 0.9rem; color: var(--text-muted); margin-top: 0.25rem; }
        .admin-view { display: none; animation: fadeIn 0.3s ease-out forwards; }
        .admin-view.active-view { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        .form-card { background: var(--bg-card); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); margin-bottom: 2.5rem; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { font-size: 0.85rem; font-weight: 600; color: var(--text-main); }
        .form-control { width: 100%; padding: 0.75rem 1rem; font-size: 0.95rem; background-color: #F8FAFC; border: 1px solid #CBD5E1; border-radius: var(--radius-md); color: var(--text-main); outline: none; transition: all 0.2s ease; }
        .form-control:focus { background-color: #FFFFFF; border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.15); }
        textarea.form-control { resize: vertical; min-height: 80px; }
        .password-field { position: relative; }
        .password-input { padding-right: 2.5rem; }
        .password-toggle { position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; font-size: 1rem; color: var(--text-muted); }
        .password-toggle svg { display: block; }
        .password-toggle:active { color: var(--primary); }
        .btn { padding: 0.75rem 1.5rem; font-size: 0.95rem; font-weight: 600; border-radius: var(--radius-md); border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease; }
        .btn-success { background-color: var(--success); color: #FFFFFF; }
        .btn-success:hover { background-color: #27AE60; transform: translateY(-1px); }
        .btn-secondary { background-color: #718096; color: #FFFFFF; }
        .btn-secondary:hover { background-color: #4A5568; }
        .btn-action { padding: 0.4rem 0.75rem; font-size: 0.85rem; border-radius: 6px; }
        .btn-edit { background-color: #EDF2F7; color: var(--primary-light); }
        .btn-edit:hover { background-color: #E2E8F0; }
        .btn-delete { background-color: #FFF5F5; color: var(--accent); }
        .btn-delete:hover { background-color: #FED7D7; }
        .table-card { background: var(--bg-card); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); overflow: hidden; }
        .admin-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; }
        .admin-table th { background: #F8FAFC; color: var(--primary); font-weight: 700; padding: 1rem 1.5rem; border-bottom: 2px solid var(--border-color); }
        .admin-table td { padding: 1rem 1.5rem; border-bottom: 1px solid #F1F5F9; color: #4A5568; vertical-align: middle; }
        .admin-table tr:last-child td { border-bottom: none; }
        .actions-cell { display: flex; gap: 0.5rem; justify-content: flex-end; }
        @media (max-width: 992px) { body { flex-direction: column; } .sidebar { width: 100%; height: auto; position: relative; } .main-content { margin-left: 0; padding: 1.5rem; } }
    </style>
</head>
<body>

    <!-- Barra lateral de navegación (módulos del panel) -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h2>Liceo Pedro Lucas Urribarri</h2>
            <p>Panel de Administración v1.0</p>
        </div>
        <ul class="sidebar-menu">
            <li><button class="menu-btn active" data-view="reuniones">📢 Reuniones</button></li>
            <li><button class="menu-btn" data-view="horarios">⏰ Horarios</button></li>
            <li><button class="menu-btn" data-view="notas">📝 Entrega de Notas</button></li>
            <li><button class="menu-btn" data-view="efemerides">📅 Efemérides</button></li>
            <li><button class="menu-btn" data-view="actividades">🎨 Actividades</button></li>
            <li><button class="menu-btn" data-view="festivos">🎉 Días Festivos</button></li>
            <li><button class="menu-btn" data-view="usuarios">👤 Usuarios</button></li>
        </ul>
        <div class="logout-container">
            <button class="btn btn-delete style-block" style="width:100%" onclick="cerrarSesion()">🚪 Cerrar Sesión</button>
        </div>
    </nav>

    <main class="main-content">

        <!-- Módulo: Reuniones -->
        <section id="reunionesView" class="admin-view active-view">
            <div class="view-header">
                <div class="view-title">
                    <h1>Gestionar Próximas Reuniones</h1>
                    <p>Agrega, edita o elimina las convocatorias a reuniones en la cartelera.</p>
                </div>
            </div>
            <div class="form-card">
                <form id="formReuniones" method="post">
                    <input type="hidden" id="reuniones-id" name="id">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="reunion-titulo">Título de la Reunión</label>
                            <input type="text" id="reunion-titulo" name="titulo" class="form-control" placeholder="Ej. Consejo de Profesores" required>
                        </div>
                        <div class="form-group">
                            <label for="reunion-fecha">Fecha</label>
                            <input type="text" id="reunion-fecha" name="fecha" class="form-control" placeholder="Ej. 20 de Mayo, 2026" required>
                        </div>
                        <div class="form-group">
                            <label for="reunion-hora">Hora</label>
                            <input type="text" id="reunion-hora" name="hora" class="form-control" placeholder="Ej. 14:00 hs" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="reunion-desc">Descripción / Objetivo</label>
                            <textarea id="reunion-desc" name="descripcion" class="form-control" placeholder="Escribe los puntos clave a tratar..." required></textarea>
                        </div>
                    </div>
                    <button type="submit" id="btn-submit-reuniones" class="btn btn-success">➕ Publicar Reunión</button>
                    <button type="button" id="btn-cancel-reuniones" class="btn btn-secondary" style="display:none;" onclick="cancelEdit('reuniones')">Cancelar</button>
                </form>
            </div>
            <div class="table-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Reunión</th>
                            <th>Fecha y Hora</th>
                            <th>Descripción</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="adminReunionesTableBody"></tbody>
                </table>
            </div>
        </section>

        <!-- Módulo: Horarios -->
        <section id="horariosView" class="admin-view">
            <div class="view-header">
                <div class="view-title">
                    <h1>Gestionar Distribución de Horarios</h1>
                    <p>Administra los cronogramas vigentes para el personal docente.</p>
                </div>
            </div>
            <div class="form-card">
                <form id="formHorarios" method="post">
                    <input type="hidden" id="horarios-id" name="id">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="horario-dia">Día / Jornada</label>
                            <input type="text" id="horario-dia" name="dia" class="form-control" placeholder="Ej. Lunes a Miércoles" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="horario-detalle">Horario Registrado</label>
                            <input type="text" id="horario-detalle" name="horario" class="form-control" placeholder="Ej. 07:30 - 13:00 (clases)" required>
                        </div>
                    </div>
                    <button type="submit" id="btn-submit-horarios" class="btn btn-success">➕ Guardar Horario</button>
                    <button type="button" id="btn-cancel-horarios" class="btn btn-secondary" style="display:none;" onclick="cancelEdit('horarios')">Cancelar</button>
                </form>
            </div>
            <div class="table-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Día / Jornada</th>
                            <th>Horario Registrado</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="adminHorarioMaestrosBody"></tbody>
                </table>
            </div>
        </section>

        <!-- Módulo: Entrega de notas -->
        <section id="notasView" class="admin-view">
            <div class="view-header">
                <div class="view-title">
                    <h1>Gestionar Fechas de Entrega de Notas</h1>
                    <p>Asigna y actualiza los días de entrega de boletas y evaluaciones por año/sección.</p>
                </div>
            </div>
            <div class="form-card">
                <form id="formNotas" method="post">
                    <input type="hidden" id="notas-id" name="id">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nota-lapso">Momento / Lapso</label>
                            <input type="text" id="nota-lapso" name="lapso" class="form-control" placeholder="Ej. Primer Momento" required>
                        </div>
                        <div class="form-group">
                            <label for="nota-fecha">Fecha Estipulada</label>
                            <input type="text" id="nota-fecha" name="fecha" class="form-control" placeholder="Ej. 15 de Julio, 2026" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="nota-detalles">Grados involucrados u Observaciones</label>
                            <input type="text" id="nota-detalles" name="detalles" class="form-control" placeholder="Ej. De 1er a 5to Año - Entrega general" required>
                        </div>
                    </div>
                    <button type="submit" id="btn-submit-notas" class="btn btn-success">➕ Publicar Cronograma</button>
                    <button type="button" id="btn-cancel-notas" class="btn btn-secondary" style="display:none;" onclick="cancelEdit('notas')">Cancelar</button>
                </form>
            </div>
            <div class="table-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Lapso</th>
                            <th>Fecha</th>
                            <th>Detalles</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="adminNotasBody"></tbody>
                </table>
            </div>
        </section>

        <!-- Módulo: Efemérides -->
        <section id="efemeridesView" class="admin-view">
            <div class="view-header">
                <div class="view-title">
                    <h1>Gestionar Efemérides del Mes</h1>
                    <p>Mantén al día los recordatorios históricos y fechas patrias de Venezuela.</p>
                </div>
            </div>
            <div class="form-card">
                <form id="formEfemerides" method="post">
                    <input type="hidden" id="efemerides-id" name="id">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="efemeride-fecha">Día y Mes</label>
                            <input type="text" id="efemeride-fecha" name="fecha" class="form-control" placeholder="Ej. 05 de Julio" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="efemeride-evento">Celebración o Acontecimiento</label>
                            <input type="text" id="efemeride-evento" name="evento" class="form-control" placeholder="Ej. Firma del Acta de la Independencia" required>
                        </div>
                    </div>
                    <button type="submit" id="btn-submit-efemerides" class="btn btn-success">➕ Añadir Efeméride</button>
                    <button type="button" id="btn-cancel-efemerides" class="btn btn-secondary" style="display:none;" onclick="cancelEdit('efemerides')">Cancelar</button>
                </form>
            </div>
            <div class="table-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Evento Histórico</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="adminEfemeridesBody"></tbody>
                </table>
            </div>
        </section>

        <!-- Módulo: Actividades -->
        <section id="actividadesView" class="admin-view">
            <div class="view-header">
                <div class="view-title">
                    <h1>Gestionar Actividades Institucionales</h1>
                    <p>Publica eventos culturales, deportivos y proyectos científicos del liceo.</p>
                </div>
            </div>
            <div class="form-card">
                <form id="formActividades" method="post">
                    <input type="hidden" id="actividades-id" name="id">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="actividad-nombre">Nombre del Evento</label>
                            <input type="text" id="actividad-nombre" name="nombre" class="form-control" placeholder="Ej. Feria Científica 2026" required>
                        </div>
                        <div class="form-group">
                            <label for="actividad-fecha">Fecha Programada</label>
                            <input type="text" id="actividad-fecha" name="fecha" class="form-control" placeholder="Ej. 12 de Junio" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="actividad-desc">Breve Resumen</label>
                            <textarea id="actividad-desc" name="descripcion" class="form-control" placeholder="Indica los requerimientos o de qué trata la actividad..." required></textarea>
                        </div>
                    </div>
                    <button type="submit" id="btn-submit-actividades" class="btn btn-success">➕ Registrar Actividad</button>
                    <button type="button" id="btn-cancel-actividades" class="btn btn-secondary" style="display:none;" onclick="cancelEdit('actividades')">Cancelar</button>
                </form>
            </div>
            <div class="table-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Actividad</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="adminActividadesBody"></tbody>
                </table>
            </div>
        </section>

        <!-- Módulo: Días festivos -->
        <section id="festivosView" class="admin-view">
            <div class="view-header">
                <div class="view-title">
                    <h1>Gestionar Feriados y Días Festivos</h1>
                    <p>Registra las fechas en las que se suspenderán las actividades escolares ordinarias.</p>
                </div>
            </div>
            <div class="form-card">
                <form id="formFestivos" method="post">
                    <input type="hidden" id="festivos-id" name="id">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="festivo-fecha">Fecha del Feriado</label>
                            <input type="text" id="festivo-fecha" name="fecha" class="form-control" placeholder="Ej. 24 de Junio" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="festivo-motivo">Motivo del Asueto</label>
                            <input type="text" id="festivo-motivo" name="motivo" class="form-control" placeholder="Ej. Batalla de Carabobo (No laborable)" required>
                        </div>
                    </div>
                    <button type="submit" id="btn-submit-festivos" class="btn btn-success">➕ Añadir Día Festivo</button>
                    <button type="button" id="btn-cancel-festivos" class="btn btn-secondary" style="display:none;" onclick="cancelEdit('festivos')">Cancelar</button>
                </form>
            </div>
            <div class="table-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Motivo / Razón</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="adminFestivosBody"></tbody>
                </table>
            </div>
        </section>

        <!-- Módulo: Usuarios y contraseñas -->
        <section id="usuariosView" class="admin-view">
            <div class="view-header">
                <div class="view-title">
                    <h1>Gestionar Usuarios Administrativos</h1>
                    <p>Crea, edita o elimina usuarios que pueden ingresar al panel.</p>
                </div>
            </div>
            <div class="form-card">
                <form id="formUsuarios" method="post">
                    <input type="hidden" id="usuarios-id" name="id">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="usuario-nombre">Usuario</label>
                            <input type="text" id="usuario-nombre" name="username" class="form-control" placeholder="Ej. admin_liceo" required>
                        </div>
                        <div class="form-group">
                            <label for="usuario-clave">Contraseña</label>
                            <div class="password-field">
                                <input type="password" id="usuario-clave" name="password" class="form-control password-input" placeholder="••••••••">
                                <button type="button" id="toggle-usuario-clave" class="password-toggle" aria-label="Mostrar contraseña" title="Mostrar/Ocultar contraseña">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M2 12C4.5 7 8 4 12 4C16 4 19.5 7 22 12C19.5 17 16 20 12 20C8 20 4.5 17 2 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <small style="color: var(--text-muted);">Si estás editando, deja la contraseña en blanco para mantenerla sin cambios.</small>
                        </div>
                    </div>
                    <button type="submit" id="btn-submit-usuarios" class="btn btn-success">➕ Crear Usuario</button>
                    <button type="button" id="btn-cancel-usuarios" class="btn btn-secondary" style="display:none;" onclick="cancelEdit('usuarios')">Cancelar</button>
                </form>
            </div>
            <div class="table-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Creado</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="adminUsuariosBody"></tbody>
                </table>
            </div>
        </section>

    </main>

    <script>
        // ------------------------------------------------------------
        // Estado local en memoria (cargado desde la API)
        // ------------------------------------------------------------
        const state = {
            reuniones: [],
            horarios: [],
            notas: [],
            efemerides: [],
            actividades: [],
            festivos: [],
            usuarios: []
        };

        // ------------------------------------------------------------
        // Helpers de comunicación con api.php
        // ------------------------------------------------------------
        function resolveEntity(key) {
            return key === 'usuarios' ? 'admin_users' : key;
        }

        async function apiGet(entityKey) {
            const entity = resolveEntity(entityKey);
            const res = await fetch(`api.php?entity=${entity}&action=list`);
            const json = await res.json();
            if (!json.ok) throw new Error(json.message || 'Error de lectura');
            return json.data;
        }

        async function apiPost(entityKey, action, payload) {
            const entity = resolveEntity(entityKey);
            const res = await fetch(`api.php?entity=${entity}&action=${action}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (!json.ok) throw new Error(json.message || 'Error de escritura');
            return json.data;
        }

        // Alternar entre Vistas usando el Menú Lateral
        function initNavigation() {
            const menuBtns = document.querySelectorAll('.menu-btn');
            const views = document.querySelectorAll('.admin-view');

            menuBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    menuBtns.forEach(b => b.classList.remove('active'));
                    views.forEach(v => v.classList.remove('active-view'));

                    btn.classList.add('active');
                    const targetView = document.getElementById(`${btn.getAttribute('data-view')}View`);
                    if (targetView) targetView.classList.add('active-view');
                });
            });
        }

        /* RENDERS DINÁMICOS (datos desde MySQL vía API) */
        async function renderAll() {
            await Promise.all([
                refreshReuniones(),
                refreshHorarios(),
                refreshNotas(),
                refreshEfemerides(),
                refreshActividades(),
                refreshFestivos(),
                refreshUsuarios()
            ]);
        }

        async function refreshReuniones() {
            state.reuniones = await apiGet('reuniones');
            renderReuniones(state.reuniones);
        }

        async function refreshHorarios() {
            state.horarios = await apiGet('horarios');
            renderHorarios(state.horarios);
        }

        async function refreshNotas() {
            state.notas = await apiGet('notas');
            renderNotas(state.notas);
        }

        async function refreshEfemerides() {
            // Si no existen, el backend crea efemérides por defecto
            state.efemerides = await apiGet('efemerides');
            renderEfemerides(state.efemerides);
        }

        async function refreshActividades() {
            state.actividades = await apiGet('actividades');
            renderActividades(state.actividades);
        }

        async function refreshFestivos() {
            state.festivos = await apiGet('festivos');
            renderFestivos(state.festivos);
        }

        async function refreshUsuarios() {
            state.usuarios = await apiGet('usuarios');
            renderUsuarios(state.usuarios);
        }

        function renderReuniones(data) {
            const tbody = document.getElementById('adminReunionesTableBody');
            if(tbody) {
                tbody.innerHTML = data.map(r => `
                    <tr>
                        <td><strong>${r.titulo}</strong></td>
                        <td>${r.fecha}<br><small style="color: var(--text-muted)">${r.hora}</small></td>
                        <td>${r.descripcion}</td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn btn-action btn-edit" onclick="startEdit('reuniones', ${r.id})">Editar</button>
                                <button class="btn btn-action btn-delete" onclick="deleteItem('reuniones', ${r.id})">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
        }

        function renderHorarios(data) {
            const tbody = document.getElementById('adminHorarioMaestrosBody');
            if(tbody) {
                tbody.innerHTML = data.map(m => `
                    <tr>
                        <td><strong>${m.dia}</strong></td>
                        <td>${m.horario}</td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn btn-action btn-edit" onclick="startEdit('horarios', ${m.id})">Editar</button>
                                <button class="btn btn-action btn-delete" onclick="deleteItem('horarios', ${m.id})">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
        }

        function renderNotas(data) {
            const tbody = document.getElementById('adminNotasBody');
            if(tbody) {
                tbody.innerHTML = data.map(n => `
                    <tr>
                        <td><strong>${n.lapso}</strong></td>
                        <td>${n.fecha}</td>
                        <td>${n.detalles}</td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn btn-action btn-edit" onclick="startEdit('notas', ${n.id})">Editar</button>
                                <button class="btn btn-action btn-delete" onclick="deleteItem('notas', ${n.id})">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
        }

        function renderEfemerides(data) {
            const tbody = document.getElementById('adminEfemeridesBody');
            if(tbody) {
                tbody.innerHTML = data.map(e => `
                    <tr>
                        <td><strong>${e.fecha}</strong></td>
                        <td>${e.evento}</td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn btn-action btn-edit" onclick="startEdit('efemerides', ${e.id})">Editar</button>
                                <button class="btn btn-action btn-delete" onclick="deleteItem('efemerides', ${e.id})">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
        }

        function renderActividades(data) {
            const tbody = document.getElementById('adminActividadesBody');
            if(tbody) {
                tbody.innerHTML = data.map(a => `
                    <tr>
                        <td><strong>${a.nombre}</strong></td>
                        <td>${a.fecha}</td>
                        <td>${a.descripcion}</td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn btn-action btn-edit" onclick="startEdit('actividades', ${a.id})">Editar</button>
                                <button class="btn btn-action btn-delete" onclick="deleteItem('actividades', ${a.id})">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
        }

        function renderFestivos(data) {
            const tbody = document.getElementById('adminFestivosBody');
            if(tbody) {
                tbody.innerHTML = data.map(f => `
                    <tr>
                        <td><strong>${f.fecha}</strong></td>
                        <td>${f.motivo}</td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn btn-action btn-edit" onclick="startEdit('festivos', ${f.id})">Editar</button>
                                <button class="btn btn-action btn-delete" onclick="deleteItem('festivos', ${f.id})">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
        }

        function renderUsuarios(data) {
            const tbody = document.getElementById('adminUsuariosBody');
            if(tbody) {
                tbody.innerHTML = data.map(u => `
                    <tr>
                        <td><strong>${u.username}</strong></td>
                        <td>${u.created_at}</td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn btn-action btn-edit" onclick="startEdit('usuarios', ${u.id})">Editar</button>
                                <button class="btn btn-action btn-delete" onclick="deleteItem('usuarios', ${u.id})">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            }
        }


        /* LOGICA DE ACCIONES FORMULARIOS (CREAR / EDITAR) */
        function configurarFormulario(key, parseInputsFields) {
            document.getElementById(`form${key.charAt(0).toUpperCase() + key.slice(1)}`)?.addEventListener('submit', async function(e) {
                e.preventDefault();

                const idField = document.getElementById(`${key}-id`).value;
                const itemObj = parseInputsFields();

                try {
                    if (idField) {
                        // Modo Edición -> UPDATE
                        await apiPost(key, 'update', { id: idField, ...itemObj });
                    } else {
                        // Modo Nuevo registro -> CREATE
                        await apiPost(key, 'create', itemObj);
                    }

                    await renderAll();
                    this.reset();
                    cancelEdit(key);
                } catch (err) {
                    alert(err.message || 'No se pudo guardar.');
                }
            });
        }

        // Suscribir los formularios a sus procesamientos lógicos
        configurarFormulario('reuniones', () => ({
            titulo: document.getElementById('reunion-titulo').value,
            fecha: document.getElementById('reunion-fecha').value,
            hora: document.getElementById('reunion-hora').value,
            descripcion: document.getElementById('reunion-desc').value
        }));

        configurarFormulario('horarios', () => ({
            dia: document.getElementById('horario-dia').value,
            horario: document.getElementById('horario-detalle').value
        }));

        configurarFormulario('notas', () => ({
            lapso: document.getElementById('nota-lapso').value,
            fecha: document.getElementById('nota-fecha').value,
            detalles: document.getElementById('nota-detalles').value
        }));

        configurarFormulario('efemerides', () => ({
            fecha: document.getElementById('efemeride-fecha').value,
            evento: document.getElementById('efemeride-evento').value
        }));

        configurarFormulario('actividades', () => ({
            nombre: document.getElementById('actividad-nombre').value,
            fecha: document.getElementById('actividad-fecha').value,
            descripcion: document.getElementById('actividad-desc').value
        }));

        configurarFormulario('festivos', () => ({
            fecha: document.getElementById('festivo-fecha').value,
            motivo: document.getElementById('festivo-motivo').value
        }));

        // Formulario específico para usuarios (contraseña opcional en edición)
        document.getElementById('formUsuarios')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const idField = document.getElementById('usuarios-id').value;
            const username = document.getElementById('usuario-nombre').value;
            const password = document.getElementById('usuario-clave').value;

            try {
                if (idField) {
                    await apiPost('usuarios', 'update', { id: idField, username, password });
                } else {
                    await apiPost('usuarios', 'create', { username, password });
                }

                await renderAll();
                this.reset();
                cancelEdit('usuarios');
            } catch (err) {
                alert(err.message || 'No se pudo guardar.');
            }
        });


        /* INTERRUPTORES DE EDICIÓN Y ELIMINACIÓN */
        function startEdit(key, id) {
            const currentData = state[key] || [];
            const item = currentData.find(i => i.id === id);
            if(!item) return;

            document.getElementById(`${key}-id`).value = item.id;

            // Rellenar campos dependiendo del módulo
            if (key === 'reuniones') {
                document.getElementById('reunion-titulo').value = item.titulo;
                document.getElementById('reunion-fecha').value = item.fecha;
                document.getElementById('reunion-hora').value = item.hora;
                document.getElementById('reunion-desc').value = item.descripcion;
            } else if (key === 'horarios') {
                document.getElementById('horario-dia').value = item.dia;
                document.getElementById('horario-detalle').value = item.horario;
            } else if (key === 'notas') {
                document.getElementById('nota-lapso').value = item.lapso;
                document.getElementById('nota-fecha').value = item.fecha;
                document.getElementById('nota-detalles').value = item.detalles;
            } else if (key === 'efemerides') {
                document.getElementById('efemeride-fecha').value = item.fecha;
                document.getElementById('efemeride-evento').value = item.evento;
            } else if (key === 'actividades') {
                document.getElementById('actividad-nombre').value = item.nombre;
                document.getElementById('actividad-fecha').value = item.fecha;
                document.getElementById('actividad-desc').value = item.descripcion;
            } else if (key === 'festivos') {
                document.getElementById('festivo-fecha').value = item.fecha;
                document.getElementById('festivo-motivo').value = item.motivo;
            } else if (key === 'usuarios') {
                document.getElementById('usuario-nombre').value = item.username;
                document.getElementById('usuario-clave').value = '';
            }

            // Cambiar textos visuales del botón
            document.getElementById(`btn-submit-${key}`).innerText = "💾 Actualizar Información";
            document.getElementById(`btn-cancel-${key}`).style.display = "inline-flex";
            window.scrollTo({ top: document.getElementById(`form${key.charAt(0).toUpperCase() + key.slice(1)}`).offsetTop - 50, behavior: 'smooth' });
        }

        function cancelEdit(key) {
            document.getElementById(`${key}-id`).value = "";
            document.getElementById(`form${key.charAt(0).toUpperCase() + key.slice(1)}`).reset();
            
            // Reestablecer botones
            const labels = { reuniones: "➕ Publicar Reunión", horarios: "➕ Guardar Horario", notas: "➕ Publicar Cronograma", efemerides: "➕ Añadir Efeméride", actividades: "➕ Registrar Actividad", festivos: "➕ Añadir Día Festivo", usuarios: "➕ Crear Usuario" };
            document.getElementById(`btn-submit-${key}`).innerText = labels[key];
            document.getElementById(`btn-cancel-${key}`).style.display = "none";
        }

        async function deleteItem(key, id) {
            if(confirm('¿Seguro que deseas remover este registro? Los cambios afectarán la cartelera principal inmediatamente.')) {
                try {
                    await apiPost(key, 'delete', { id });
                    await renderAll();
                } catch (err) {
                    alert(err.message || 'No se pudo eliminar.');
                }
            }
        }

        // Inicialización General
        document.addEventListener('DOMContentLoaded', async () => {
            initNavigation();
            await renderAll();
        });

        // Cierre de sesión (servidor)
        function cerrarSesion() {
            window.location.href = "logout.php";
        }

        // Mostrar/Ocultar contraseña en el módulo de usuarios
        (function setupPasswordPeek() {
            const input = document.getElementById('usuario-clave');
            const btn = document.getElementById('toggle-usuario-clave');
            if (!input || !btn) return;

            const toggle = () => {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                btn.setAttribute('aria-label', isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña');
            };

            btn.addEventListener('click', toggle);
            btn.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    toggle();
                }
            });
        })();
    </script>
</body>
</html>