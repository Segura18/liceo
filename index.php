<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera Informativa | C.N. Educativo Pedro Lucas Urribarri</title>
    <style>
        /* -------------------------------------------------------------
           1. RESET & VARIABLES (Colores modernos e institucionales)
        ------------------------------------------------------------- */
        :root {
            --primary: #1E3A5F;       /* Azul Institucional Profundo */
            --primary-light: #2C5282; /* Azul Secundario */
            --accent: #E63946;        /* Rojo para acentos/alertas */
            --warning: #FFD966;       /* Amarillo suave */
            --bg-body: #F4F7FA;       /* Gris extra claro limpio */
            --bg-card: #FFFFFF;       /* Blanco puro para tarjetas */
            --text-main: #2D3748;     /* Gris oscuro para lectura cómoda */
            --text-muted: #718096;    /* Gris medio para fechas/horas */
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        /* -------------------------------------------------------------
           2. CONTENEDOR PRINCIPAL
        ------------------------------------------------------------- */
        .board-container {
            width: 100%;
            max-width: 1200px;
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.03);
        }

        /* -------------------------------------------------------------
           3. HERO HEADER (Alineación perfecta con Flexbox)
        ------------------------------------------------------------- */
        .hero-header {
            background: linear-gradient(135deg, #102A43 0%, #1E3A5F 100%);
            padding: 3rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            position: relative;
        }

        .hero-header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--warning) 0%, var(--warning) 33%, #FFFFFF 33%, #FFFFFF 66%, var(--accent) 66%, var(--accent) 100%);
        }

        .header-info {
            flex: 1;
        }

        .hero-header h1 {
            font-size: 2rem;
            color: #FFFFFF;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .hero-header p {
            color: #E2E8F0;
            margin-top: 0.5rem;
            font-size: 1rem;
            font-weight: 400;
        }

        .header-logos {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .logo-liceo, .bandera-venezuela {
            height: 70px;
            width: auto;
            object-fit: contain;
            border-radius: var(--radius-md);
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
        }

        /* -------------------------------------------------------------
           4. BARRA DE PESTAÑAS (Tabs modernas)
        ------------------------------------------------------------- */
        .tabs-bar {
            display: flex;
            background-color: #F8FAFC;
            border-bottom: 1px solid #E2E8F0;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .tabs-bar::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 1rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            white-space: nowrap;
            border-radius: var(--radius-md) var(--radius-md) 0 0;
            transition: all 0.2s ease;
            position: relative;
            bottom: -1px;
            border: 1px solid transparent;
        }

        .tab-btn:hover {
            color: var(--primary);
            background-color: #EDF2F7;
        }

        .tab-btn.active {
            color: var(--primary);
            background: var(--bg-card);
            border-color: #E2E8F0 #E2E8F0 transparent #E2E8F0;
            box-shadow: 0 -4px 6px -4px rgba(0,0,0,0.05);
        }

        /* -------------------------------------------------------------
           5. ÁREA DE CONTENIDO
        ------------------------------------------------------------- */
        .tabs-content {
            padding: 2.5rem 2rem;
            min-height: 400px;
        }

        .tab-pane {
            display: none;
            animation: slideUp 0.3s ease-out forwards;
        }

        .tab-pane.active-pane {
            display: block;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* -------------------------------------------------------------
           6. TARJETAS DE INFORMACIÓN (UI Limpia)
        ------------------------------------------------------------- */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .info-card {
            background: var(--bg-card);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid #E2E8F0;
            border-top: 4px solid var(--primary);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1A202C;
            margin-bottom: 0.75rem;
        }

        .card-meta-wrapper {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-bottom: 1rem;
        }

        .card-date, .card-hour {
            font-size: 0.85rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .card-desc {
            font-size: 0.95rem;
            color: #4A5568;
            line-height: 1.5;
        }

        /* -------------------------------------------------------------
           7. TABLAS DE HORARIOS (Estilo Minimalista)
        ------------------------------------------------------------- */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: var(--radius-md);
            border: 1px solid #E2E8F0;
            box-shadow: var(--shadow-sm);
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-card);
            text-align: left;
            font-size: 0.95rem;
        }

        .schedule-table th {
            background: #F7FAFC;
            color: var(--primary);
            font-weight: 700;
            padding: 1rem;
            border-bottom: 2px solid #E2E8F0;
        }

        .schedule-table td {
            padding: 1rem;
            border-bottom: 1px solid #EDF2F7;
            color: #4A5568;
        }

        .schedule-table tr:last-child td {
            border-bottom: none;
        }

        /* -------------------------------------------------------------
           8. COMPONENTES ESPECÍFICOS (Efemérides y Festivos)
        ------------------------------------------------------------- */
        .list-efem {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .list-efem li {
            background: #F7FAFC;
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            border-left: 4px solid var(--warning);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .efem-badge {
            background: #FEFCBF;
            color: #744210;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .festivo-card {
            border-top-color: var(--accent);
            background: #FFF5F5;
        }

        /* -------------------------------------------------------------
           9. FOOTER Y RESPONSIVIDAD
        ------------------------------------------------------------- */
        footer {
            background: #102A43;
            text-align: center;
            color: #9FB3C8;
            padding: 1.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        @media (max-width: 768px) {
            .hero-header {
                flex-direction: column;
                text-align: center;
                padding: 2rem 1.5rem;
            }
            .header-logos {
                justify-content: center;
            }
            .tabs-content {
                padding: 1.5rem 1rem;
            }
            .hero-header h1 {
                font-size: 1.6rem;
            }
        }
      
        .btn-floating-admin {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--primary);
            color: #FFFFFF;
            padding: 12px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 25px rgba(30, 58, 95, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.1);
            z-index: 9999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-floating-admin:hover {
            background-color: #162B46;
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(30, 58, 95, 0.4);
            color: #FFFFFF;
        }

        .btn-floating-admin:active {
            transform: translateY(-1px);
        }

        @media (max-width: 576px) {
            .btn-floating-admin {
                bottom: 20px;
                right: 20px;
                padding: 14px;
                border-radius: 50%;
            }
            .btn-floating-admin span {
                display: none;
            }
        }
        
        .empty-message {
            color: var(--text-muted);
            font-style: italic;
            padding: 1rem 0;
        }
    </style>
</head>
<body>

<div class="board-container">
    <div class="hero-header">
        <div class="header-info">
            <h1>C.N. Educativo Pedro Lucas Urribarri</h1>
            <p>Cartelera Informativa Digital · Año Escolar 2026</p>
        </div>
        <div class="header-logos">
            <img src="cne-logo.jpg" class="logo-liceo" alt="Logo Liceo">
            <img src="bandera-circular-venezuela.png" class="bandera-venezuela" alt="Bandera">
        </div>
    </div>
    
    <!-- Barra de pestañas para navegar por la cartelera -->
    <div class="tabs-bar" id="tabsContainer">
        <button class="tab-btn active" data-tab="reuniones">📢 Reuniones</button>
        <button class="tab-btn" data-tab="horarios">⏰ Horarios</button>
        <button class="tab-btn" data-tab="notas">📝 Entrega de Notas</button>
        <button class="tab-btn" data-tab="efemerides">📅 Efemérides</button>
        <button class="tab-btn" data-tab="actividades">🎨 Actividades</button>
        <button class="tab-btn" data-tab="festivos">🎉 Días Festivos</button>
    </div>

    <!-- Contenido de cada pestaña -->
    <div class="tabs-content">
        <!-- Panel: Reuniones -->
        <div class="tab-pane active-pane" id="reunionesPane">
            <div class="section-title">Próximas Reuniones</div>
            <div class="card-grid" id="reunionesGrid"></div>
        </div>

        <!-- Panel: Horarios -->
        <div class="tab-pane" id="horariosPane">
            <div class="section-title">Horarios Escolares</div>
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <div>
                    <h3 style="color: var(--primary); margin-bottom: 0.75rem; font-size: 1.1rem;">👩‍🏫 Distribución de Docentes</h3>
                    <div class="table-responsive">
                        <table class="schedule-table" id="horarioMaestrosTable"></table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel: Notas -->
        <div class="tab-pane" id="notasPane">
            <div class="section-title">Calendario de Evaluaciones y Notas</div>
            <div class="card-grid" id="notasGrid"></div>
        </div>

        <!-- Panel: Efemérides -->
        <div class="tab-pane" id="efemeridesPane">
            <div class="section-title">Efemérides del Mes</div>
            <ul class="list-efem" id="efemeridesList"></ul>
        </div>

        <!-- Panel: Actividades -->
        <div class="tab-pane" id="actividadesPane">
            <div class="section-title">Actividades Complementarias</div>
            <div class="card-grid" id="actividadesGrid"></div>
        </div>

        <!-- Panel: Festivos -->
        <div class="tab-pane" id="festivosPane">
            <div class="section-title">Días Festivos / Asuetos</div>
            <div class="card-grid" id="festivosGrid"></div>
        </div>
    </div>

    <footer>
        © 2026 C.N. Educativo Pedro Lucas Urribarri · Sistema de Información Académica
    </footer>
</div>

<script>
    // ------------------------------------------------------------
    // Lectura pública desde la API (datos en MySQL)
    // ------------------------------------------------------------
    async function apiGet(entity) {
        const res = await fetch(`api.php?entity=${entity}&action=list`);
        const json = await res.json();
        if (!json.ok) throw new Error(json.message || 'Error de lectura');
        return json.data;
    }

    // Funciones de renderizado optimizadas
    function renderReuniones(data) {
        const container = document.getElementById("reunionesGrid");
        if(!container) return;
        
        if (data.length === 0) {
            container.innerHTML = '<p class="empty-message">No hay reuniones programadas.</p>';
            return;
        }

        const colores = ["#1E3A5F", "#4A5568", "#E63946", "#FFD966"];
        container.innerHTML = data.map((r, index) => `
            <div class="info-card" style="border-top-color: ${colores[index % colores.length]}">
                <div class="card-title">📌 ${r.titulo}</div>
                <div class="card-meta-wrapper">
                    <div class="card-date">📅 ${r.fecha}</div>
                    <div class="card-hour">⏱ ${r.hora}</div>
                </div>
                <div class="card-desc">${r.descripcion.replace(/\n/g, '<br>')}</div>
            </div>
        `).join('');
    }

    function renderHorarios(data) {
        const maestrosTable = document.getElementById("horarioMaestrosTable");
        if(!maestrosTable) return;

        if (data.length === 0) {
            maestrosTable.innerHTML = '<tr><td class="empty-message">No hay horarios registrados.</td></tr>';
            return;
        }

        maestrosTable.innerHTML = `
            <tr><th>Día / Jornada</th><th>Horario de Cumplimiento</th></tr>
            ${data.map(m => `<tr><td><strong>${m.dia}</strong></td><td>${m.horario}</td></tr>`).join('')}
        `;
    }

    function renderNotas(data) {
        const container = document.getElementById("notasGrid");
        if(!container) return;

        if (data.length === 0) {
            container.innerHTML = '<p class="empty-message">No hay cronogramas de entrega de notas publicados.</p>';
            return;
        }

        container.innerHTML = data.map(n => `
            <div class="info-card" style="border-top-color: var(--accent)">
                <div class="card-title">📋 ${n.lapso}</div>
                <div class="card-meta-wrapper">
                    <div class="card-date">🗓 Entrega: ${n.fecha}</div>
                </div>
                <div class="card-desc">${n.detalles}</div>
            </div>
        `).join('');
    }

    function renderEfemerides(data) {
        const lista = document.getElementById("efemeridesList");
        if(!lista) return;

        if (data.length === 0) {
            lista.innerHTML = '<li class="empty-message">No hay efemérides registradas para este mes.</li>';
            return;
        }

        lista.innerHTML = data.map(e => `
            <li>
                <span class="efem-badge">${e.fecha}</span> 
                <span>${e.evento}</span>
            </li>
        `).join('');
    }

    function renderActividades(data) {
        const container = document.getElementById("actividadesGrid");
        if(!container) return;

        if (data.length === 0) {
            container.innerHTML = '<p class="empty-message">No hay actividades complementarias programadas.</p>';
            return;
        }

        container.innerHTML = data.map(act => `
            <div class="info-card" style="border-top-color: var(--primary-light)">
                <div class="card-title">🎯 ${act.nombre}</div>
                <div class="card-meta-wrapper">
                    <div class="card-date">📅 ${act.fecha}</div>
                </div>
                <div class="card-desc">${act.descripcion.replace(/\n/g, '<br>')}</div>
            </div>
        `).join('');
    }

    function renderFestivos(data) {
        const container = document.getElementById("festivosGrid");
        if(!container) return;

        if (data.length === 0) {
            container.innerHTML = '<p class="empty-message">No hay días festivos registrados.</p>';
            return;
        }

        container.innerHTML = data.map(f => `
            <div class="info-card festivo-card">
                <div class="card-title">🎊 Asueto</div>
                <div class="card-meta-wrapper">
                    <div class="card-date">📆 Feriado: ${f.fecha}</div>
                </div>
                <div class="card-desc"><em>${f.motivo}</em></div>
            </div>
        `).join('');
    }

    function initTabs() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const panes = document.querySelectorAll('.tab-pane');

        function activateTab(tabId) {
            panes.forEach(pane => pane.classList.remove('active-pane'));
            const activePane = document.getElementById(`${tabId}Pane`);
            if(activePane) activePane.classList.add('active-pane');

            tabBtns.forEach(btn => {
                btn.classList.remove('active');
                if(btn.getAttribute('data-tab') === tabId) btn.classList.add('active');
            });
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tabName = btn.getAttribute('data-tab');
                if(tabName) activateTab(tabName);
            });
        });
    }

    // Cargar todos los módulos desde el backend
    async function renderAll() {
        try {
            const [reuniones, horarios, notas, efemerides, actividades, festivos] = await Promise.all([
                apiGet('reuniones'),
                apiGet('horarios'),
                apiGet('notas'),
                apiGet('efemerides'),
                apiGet('actividades'),
                apiGet('festivos')
            ]);

            renderReuniones(reuniones);
            renderHorarios(horarios);
            renderNotas(notas);
            renderEfemerides(efemerides);
            renderActividades(actividades);
            renderFestivos(festivos);
        } catch (err) {
            console.error(err);
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await renderAll();
        initTabs();
    });
</script>

<a href="login.php" target="_blank" class="btn-floating-admin" title="Acceder al Panel de Control">
    ⚙️ <span>Panel de Administrador</span>
</a>
</body>
</html>
