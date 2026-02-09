<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa fa-american-sign-language-interpreting" aria-hidden="true"></i>
        </div>
        <div class="sidebar-brand-text mx-3">JAVH Colpensiones</div>
    </a>
    <div class="sidebar-heading">
        Admin
    </div>
    @can('dashboard.view')
        <li class="nav-item active">
            <a class="nav-link" href="/">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-grid-1x2" viewBox="0 0 16 16">
                    <path
                        d="M6 1H1v14h5zm9 0h-5v5h5zm0 9v5h-5v-5zM0 1a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm9 0a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1zm1 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1z" />
                </svg>
                <span>Dashboard</span></a>
        </li>
    @endcan
    @can('roles.view')
        <li class="nav-item active">
            <a class="nav-link" href="/roles">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-incognito" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="m4.736 1.968-.892 3.269-.014.058C2.113 5.568 1 6.006 1 6.5 1 7.328 4.134 8 8 8s7-.672 7-1.5c0-.494-1.113-.932-2.83-1.205l-.014-.058-.892-3.27c-.146-.533-.698-.849-1.239-.734C9.411 1.363 8.62 1.5 8 1.5s-1.411-.136-2.025-.267c-.541-.115-1.093.2-1.239.735m.015 3.867a.25.25 0 0 1 .274-.224c.9.092 1.91.143 2.975.143a30 30 0 0 0 2.975-.143.25.25 0 0 1 .05.498c-.918.093-1.944.145-3.025.145s-2.107-.052-3.025-.145a.25.25 0 0 1-.224-.274M3.5 10h2a.5.5 0 0 1 .5.5v1a1.5 1.5 0 0 1-3 0v-1a.5.5 0 0 1 .5-.5m-1.5.5q.001-.264.085-.5H2a.5.5 0 0 1 0-1h3.5a1.5 1.5 0 0 1 1.488 1.312 3.5 3.5 0 0 1 2.024 0A1.5 1.5 0 0 1 10.5 9H14a.5.5 0 0 1 0 1h-.085q.084.236.085.5v1a2.5 2.5 0 0 1-5 0v-.14l-.21-.07a2.5 2.5 0 0 0-1.58 0l-.21.07v.14a2.5 2.5 0 0 1-5 0zm8.5-.5h2a.5.5 0 0 1 .5.5v1a1.5 1.5 0 0 1-3 0v-1a.5.5 0 0 1 .5-.5" />
                </svg>
                <span>Roles</span></a>
        </li>
    @endcan
    @can('permisos.view')
        <li class="nav-item active">
            <a class="nav-link" href="/permisos">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-lock-fill" viewBox="0 0 16 16">
                    <path
                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2" />
                </svg>
                <span>Permisos</span></a>
        </li>
    @endcan
    @can('secciones.view')
        <li class="nav-item active">
            <a class="nav-link" href="/secciones">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-bar-chart-steps" viewBox="0 0 16 16">
                    <path
                        d="M.5 0a.5.5 0 0 1 .5.5v15a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 .5 0M2 1.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z" />
                </svg>
                <span>Secciones</span></a>
        </li>
    @endcan
    @can('seccionesFormulario.view')
        <li class="nav-item active">
            <a class="nav-link" href="/seccionesformulario">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-book-half" viewBox="0 0 16 16">
                    <path
                        d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783" />
                </svg>
                <span>Formularios</span></a>
        </li>
    @endcan
    @can('centroCostos.view')
        <li class="nav-item active">
            <a class="nav-link" href="/centrocostos">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-basket" viewBox="0 0 16 16">
                    <path
                        d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9zM1 7v1h14V7zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5" />
                </svg>
                <span>Centro de costos</span></a>
        </li>
    @endcan
    @can('diaFestivo.view')
        <li class="nav-item active">
            <a class="nav-link" href="/diafestivo">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-calendar-event-fill" viewBox="0 0 16 16">
                    <path
                        d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5" />
                </svg>
                <span>Control días festivos</span></a>
        </li>
    @endcan
    @can('usuarios.view')
        <li class="nav-item active">
            <a class="nav-link" href="/usuarios">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-people-fill" viewBox="0 0 16 16">
                    <path
                        d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                </svg>
                <span>Usuarios</span></a>
        </li>
    @endcan
    @can('servicios.view')
        <li class="nav-item active">
            <a class="nav-link" href="/servicios">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tags"
                    viewBox="0 0 16 16">
                    <path
                        d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z" />
                    <path
                        d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z" />
                </svg>
                <span>Servicios</span></a>
        </li>
    @endcan


	@can('comisiones.view')
        <li class="nav-item active">
            <a class="nav-link" href="/comision">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" viewBox="0 0 512 512" width="16" height="16"  fill="currentColor" style="enable-background:new 0 0 512 512;" xml:space="preserve">
				<g><path d="M212.6,256h-41.2c-2.8,0-5,2.2-5,5v41.2c0,2.8,2.2,5,5,5h41.2c2.8,0,5-2.2,5-5V261C217.6,258.2,215.4,256,212.6,256z"/><path d="M212.6,345.6h-41.2c-2.8,0-5,2.2-5,5v41.2c0,2.8,2.2,5,5,5h41.2c2.8,0,5-2.2,5-5v-41.2   C217.6,347.8,215.4,345.6,212.6,345.6z"/><path d="M186.7,210.1c1.5,1.5,3.4,2.2,5.3,2.2s3.8-0.7,5.3-2.2l32-32c2.9-2.9,2.9-7.7,0-10.6c-2.9-2.9-7.7-2.9-10.6,0L192,194.2   l-13.9-13.9c-2.9-2.9-7.7-2.9-10.6,0c-2.9,2.9-2.9,7.7,0,10.6L186.7,210.1z"/><path d="M256,276.3h89.6c4.1,0,7.5-3.4,7.5-7.5s-3.4-7.5-7.5-7.5H256c-4.1,0-7.5,3.4-7.5,7.5S251.9,276.3,256,276.3z"/><path d="M256,365.9h89.6c4.1,0,7.5-3.4,7.5-7.5s-3.4-7.5-7.5-7.5H256c-4.1,0-7.5,3.4-7.5,7.5S251.9,365.9,256,365.9z"/><path d="M256,186.7h89.6c4.1,0,7.5-3.4,7.5-7.5s-3.4-7.5-7.5-7.5H256c-4.1,0-7.5,3.4-7.5,7.5S251.9,186.7,256,186.7z"/><path d="M256,301.9h51.2c4.1,0,7.5-3.4,7.5-7.5s-3.4-7.5-7.5-7.5H256c-4.1,0-7.5,3.4-7.5,7.5S251.9,301.9,256,301.9z"/><path d="M256,391.5h51.2c4.1,0,7.5-3.4,7.5-7.5s-3.4-7.5-7.5-7.5H256c-4.1,0-7.5,3.4-7.5,7.5S251.9,391.5,256,391.5z"/><path d="M256,212.3h51.2c4.1,0,7.5-3.4,7.5-7.5s-3.4-7.5-7.5-7.5H256c-4.1,0-7.5,3.4-7.5,7.5S251.9,212.3,256,212.3z"/><path d="M402.4,69.3h-49.3v-8.1c0-9.6-7.9-17.5-17.5-17.5h-23.8l-5.2-10.4c-4.7-9.4-14.1-15.2-24.6-15.2H230   c-10.5,0-19.9,5.8-24.6,15.2l-5.2,10.4h-23.8c-9.6,0-17.5,7.9-17.5,17.5v8.1h-49.3c-15.2,0-27.5,12.3-27.5,27.5v369.6   c0,15.2,12.3,27.5,27.5,27.5h292.8c15.2,0,27.5-12.3,27.5-27.5V96.8C429.9,81.6,417.6,69.3,402.4,69.3z M196.4,135.5h119.2   c11.2,0,21.3-5,28.2-12.8H374c1.4,0,2.5,1.1,2.5,2.5V438c0,1.4-1.1,2.5-2.5,2.5H138c-1.4,0-2.5-1.1-2.5-2.5V125.2   c0-1.4,1.1-2.5,2.5-2.5h30.2C175.1,130.5,185.2,135.5,196.4,135.5z M173.9,61.2c0-1.4,1.1-2.5,2.5-2.5h28.4c2.8,0,5.4-1.6,6.7-4.1   l7.3-14.5c2.1-4.3,6.4-6.9,11.2-6.9H282c4.8,0,9,2.6,11.2,6.9l7.3,14.5c1.3,2.5,3.9,4.1,6.7,4.1h28.4c1.4,0,2.5,1.1,2.5,2.5V98   c0,12.4-10.1,22.5-22.5,22.5H196.4c-12.4,0-22.5-10.1-22.5-22.5V61.2z M414.9,466.4c0,6.9-5.6,12.5-12.5,12.5H109.6   c-6.9,0-12.5-5.6-12.5-12.5V96.8c0-6.9,5.6-12.5,12.5-12.5h49.3V98c0,3.4,0.4,6.6,1.3,9.7H138c-9.6,0-17.5,7.9-17.5,17.5V438   c0,9.6,7.9,17.5,17.5,17.5h236c9.6,0,17.5-7.9,17.5-17.5V125.2c0-9.6-7.9-17.5-17.5-17.5h-22.2c0.8-3.1,1.3-6.3,1.3-9.7V84.3h49.3   c6.9,0,12.5,5.6,12.5,12.5V466.4z"/></g></svg>
                <span>Liquidación de Comisiones</span></a>
        </li>
    @endcan
    @can('facturacion.view')
        <li class="nav-item active">
            <a class="nav-link" href="/facturacion">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" viewBox="0 0 512 512" width="16" height="16"  fill="currentColor" style="enable-background:new 0 0 512 512;" xml:space="preserve">
				<g><path d="M442.7,89.6v-49c0-12.4-10.1-22.5-22.5-22.5H91.8c-12.4,0-22.5,10.1-22.5,22.5v49c0,8.9,5.2,16.7,12.8,20.3v350.5   c0,18.5,15,33.5,33.5,33.5h280.8c18.5,0,33.5-15,33.5-33.5V244.1l4.7-4.7c5.2-5.2,8.1-12.1,8.1-19.4c0-7.3-2.9-14.3-8.1-19.4   l-4.7-4.7v-85.8C437.5,106.3,442.7,98.5,442.7,89.6z M84.3,40.6c0-4.1,3.4-7.5,7.5-7.5h328.4c4.1,0,7.5,3.4,7.5,7.5v49   c0,4.1-3.4,7.5-7.5,7.5H91.8c-4.1,0-7.5-3.4-7.5-7.5V40.6z M327.5,468.3V410c0-10.2,8.3-18.5,18.5-18.5h58.3L327.5,468.3z    M396.4,478.9h-58.3l76.8-76.8v58.3C414.9,470.6,406.6,478.9,396.4,478.9z M414.9,376.5H346c-18.5,0-33.5,15-33.5,33.5v68.9H115.6   c-10.2,0-18.5-8.3-18.5-18.5V112.1h317.8v73.5c-9.3-2.7-19.7-0.3-27.1,7l-49.5,49.5h-33.3c-4.7,0-9.1,1.8-12.4,5.1l-9.2,9.2   c-1,1-2.6,1-3.5,0l-5.1-5.1c-6.8-6.8-17.9-6.8-24.7,0l-17.9,17.9c-1,1-2.6,1-3.5,0l-37.1-37.1c-6.8-6.8-17.9-6.8-24.7,0l-44.1,44.1   c-2.9,2.9-2.9,7.7,0,10.6c1.5,1.5,3.4,2.2,5.3,2.2c1.9,0,3.8-0.7,5.3-2.2l44.1-44.1c1-1,2.6-1,3.5,0l37.1,37.1   c6.8,6.8,17.9,6.8,24.7,0l17.9-17.9c0.6-0.6,1.4-0.7,1.8-0.7c0.4,0,1.1,0.1,1.8,0.7l5.1,5.1c3.3,3.3,7.7,5.1,12.4,5.1   c4.7,0,9.1-1.8,12.4-5.1l9.2-9.2c0.5-0.5,1.1-0.7,1.8-0.7h18.3l-75.2,75.2l-6.5,6.5c-2.5,2.5-4.3,5.5-5.4,8.8l-4.8,14.5l-5.8,17.5   c-2.1,6.3-0.5,13.2,4.2,17.9c3.3,3.4,7.8,5.1,12.3,5.1c1.9,0,3.7-0.3,5.6-0.9l17.5-5.8l14.5-4.8c3.3-1.1,6.3-3,8.8-5.4l6.5-6.5   l117.7-117.7l2.3-2.3V376.5z M289.6,363.2L264,337.6l82.6-82.6c0,0,0.1-0.1,0.1-0.1l24.3-24.3l25.6,25.6L289.6,363.2z M259.6,381.9   c-3.4-6-8.4-10.9-14.3-14.3l0.2-0.7l4.8-14.5c0.4-1.1,1-2.1,1.8-2.9l1.2-1.2l25.6,25.6l-1.2,1.2c-0.8,0.8-1.8,1.4-2.9,1.8   L259.6,381.9z"/><path d="M128,221c1.9,0,3.8-0.7,5.3-2.2l44.1-44.1c1-1,2.6-1,3.5,0l37.1,37.1c6.8,6.8,17.9,6.8,24.7,0l17.9-17.9   c0.6-0.6,1.4-0.7,1.8-0.7c0.4,0,1.1,0.1,1.8,0.7l5.1,5.1c6.8,6.8,17.9,6.8,24.7,0l9.2-9.2c0.5-0.5,1.1-0.7,1.8-0.7h53.5   c4.1,0,7.5-3.4,7.5-7.5s-3.4-7.5-7.5-7.5h-53.5c-4.7,0-9.1,1.8-12.4,5.1l-9.2,9.2c-1,1-2.6,1-3.5,0l-5.1-5.1   c-6.8-6.8-17.9-6.8-24.7,0l-17.9,17.9c-1,1-2.6,1-3.5,0L191.6,164c-3.3-3.3-7.7-5.1-12.4-5.1s-9.1,1.8-12.4,5.1l-44.1,44.1   c-2.9,2.9-2.9,7.7,0,10.6C124.2,220.2,126.1,221,128,221z"/></g></svg>
                <span>Facturación</span></a>
        </li>
    @endcan		


    {{--     <div class="sidebar-heading">
        Colpensiones
    </div> --}}
    @can('investigaciones.lista.view')
        <hr class="sidebar-divider d-none d-md-block">
        <li class="nav-item active">
            <a class="nav-link" href="/investigacionesTodas">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-sort-alpha-down" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z" />
                    <path
                        d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z" />
                </svg>
                <span>Investigaciones</span></a>
        </li>
    @endcan
    @php
        use App\Models\Servicios;
        $servicios = Servicios::all();
    @endphp
    @foreach ($servicios as $servicio)
        @can('investigaciones.view')
            <li class="nav-item active">
                <a class="nav-link" href="/investigacionesLista/{{ $servicio->codigo }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-border-bottom" viewBox="0 0 16 16">
                        <path
                            d="M.969 0H0v.969h.5V1h.469V.969H1V.5H.969zm.937 1h.938V0h-.938zm1.875 0h.938V0H3.78v1zm1.875 0h.938V0h-.938zM7.531.969V1h.938V.969H8.5V.5h-.031V0H7.53v.5H7.5v.469zM9.406 1h.938V0h-.938zm1.875 0h.938V0h-.938zm1.875 0h.938V0h-.938zm1.875 0h.469V.969h.5V0h-.969v.5H15v.469h.031zM1 2.844v-.938H0v.938zm6.5-.938v.938h1v-.938zm7.5 0v.938h1v-.938zM1 4.719V3.78H0v.938h1zm6.5-.938v.938h1V3.78h-1zm7.5 0v.938h1V3.78h-1zM1 6.594v-.938H0v.938zm6.5-.938v.938h1v-.938zm7.5 0v.938h1v-.938zM.5 8.5h.469v-.031H1V7.53H.969V7.5H.5v.031H0v.938h.5zm1.406 0h.938v-1h-.938zm1.875 0h.938v-1H3.78v1zm1.875 0h.938v-1h-.938zm2.813 0v-.031H8.5V7.53h-.031V7.5H7.53v.031H7.5v.938h.031V8.5zm.937 0h.938v-1h-.938zm1.875 0h.938v-1h-.938zm1.875 0h.938v-1h-.938zm1.875 0h.469v-.031h.5V7.53h-.5V7.5h-.469v.031H15v.938h.031zM0 9.406v.938h1v-.938zm7.5 0v.938h1v-.938zm8.5.938v-.938h-1v.938zm-16 .937v.938h1v-.938zm7.5 0v.938h1v-.938zm8.5.938v-.938h-1v.938zm-16 .937v.938h1v-.938zm7.5 0v.938h1v-.938zm8.5.938v-.938h-1v.938zM0 15h16v1H0z" />
                    </svg>
                    <span>{{ $servicio->nombre }}</span></a>
            </li>
        @endcan
    @endforeach
    <hr>
    @can('misinvestigaciones.view')
        <li class="nav-item active">
            <a class="nav-link" href="/misinvestigaciones">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-border-outer" viewBox="0 0 16 16">
                    <path
                        d="M7.5 1.906v.938h1v-.938zm0 1.875v.938h1V3.78h-1zm0 1.875v.938h1v-.938zM1.906 8.5h.938v-1h-.938zm1.875 0h.938v-1H3.78v1zm1.875 0h.938v-1h-.938zm2.813 0v-.031H8.5V7.53h-.031V7.5H7.53v.031H7.5v.938h.031V8.5zm.937 0h.938v-1h-.938zm1.875 0h.938v-1h-.938zm1.875 0h.938v-1h-.938zM7.5 9.406v.938h1v-.938zm0 1.875v.938h1v-.938zm0 1.875v.938h1v-.938z" />
                    <path d="M0 0v16h16V0zm1 1h14v14H1z" />
                </svg>
                <span>Mis investigaciones</span></a>
        </li>
    @endcan
    @can('migrupo.view')
        <li class="nav-item active">
            <a class="nav-link" href="/migrupo">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-diagram-3-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5zm-6 8A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5zm6 0A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5zm6 0a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5z" />
                </svg>
                <span>Mi grupo</span></a>
        </li>
    @endcan
    @can('miCentroCosto.view')
        <li class="nav-item active">
            <a class="nav-link" href="/miCentroCosto">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-person-rolodex" viewBox="0 0 16 16">
                    <path d="M8 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                    <path
                        d="M1 1a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h.5a.5.5 0 0 0 .5-.5.5.5 0 0 1 1 0 .5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5.5.5 0 0 1 1 0 .5.5 0 0 0 .5.5h.5a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H6.707L6 1.293A1 1 0 0 0 5.293 1zm0 1h4.293L6 2.707A1 1 0 0 0 6.707 3H15v10h-.085a1.5 1.5 0 0 0-2.4-.63C11.885 11.223 10.554 10 8 10c-2.555 0-3.886 1.224-4.514 2.37a1.5 1.5 0 0 0-2.4.63H1z" />
                </svg>
                <span>Mi centro de costo</span></a>
        </li>
    @endcan
    {{-- <hr class="sidebar-divider d-none d-md-block"> --}}
    <small class="text-center">V. 1.0</small>
    {{-- <hr class="sidebar-divider d-none d-md-block"> --}}
    <!-- Divider -->
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
