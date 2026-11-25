<?php
// Panel sections. One page per module, grouped in folders under panel/ ('file' is relative to panel/).
// 'roles' = who can see and open it. 'group' = heading in the side menu.
return [
    ['id' => 'inicio', 'label' => 'Inicio', 'file' => 'index.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Principal', 'icon' => 'home'],
    ['id' => 'carrusel', 'label' => 'Carrusel del inicio', 'file' => 'contenido/carrusel/index.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'image'],
    ['id' => 'noticias', 'label' => 'Noticias y avisos', 'file' => 'contenido/noticias.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'news'],
    ['id' => 'clubes', 'label' => 'Clubes', 'file' => 'contenido/clubes.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'club'],
    ['id' => 'actividades', 'label' => 'Actividades', 'file' => 'contenido/actividades.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'calendar'],
    ['id' => 'oferta', 'label' => 'Oferta y nuevo ingreso', 'file' => 'contenido/oferta.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'book'],
    ['id' => 'productos', 'label' => 'Productos', 'file' => 'contenido/productos.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'box'],
    ['id' => 'servicios', 'label' => 'Servicios', 'file' => 'contenido/servicios.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'tool'],
    ['id' => 'nosotros', 'label' => 'Nosotros', 'file' => 'informacion/nosotros.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'info'],
    ['id' => 'personal', 'label' => 'Personal y directorio', 'file' => 'informacion/personal.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'staff'],
    ['id' => 'contacto', 'label' => 'Datos de contacto', 'file' => 'informacion/contacto.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'phone'],
    ['id' => 'documentos', 'label' => 'Documentos y materiales', 'file' => 'informacion/documentos.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'file'],
    ['id' => 'galeria', 'label' => 'Galería', 'file' => 'informacion/galeria/index.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'image'],
    ['id' => 'mensajes', 'label' => 'Mensajes y solicitudes', 'file' => 'atencion/mensajes.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Atención', 'icon' => 'mail'],
    ['id' => 'alumnos', 'label' => 'Cuentas de alumnos', 'file' => 'administracion/alumnos.php', 'roles' => ['Administrador'], 'group' => 'Administración', 'icon' => 'user'],
    ['id' => 'usuarios', 'label' => 'Usuarios del panel', 'file' => 'administracion/usuarios.php', 'roles' => ['Administrador'], 'group' => 'Administración', 'icon' => 'shield'],
    ['id' => 'bitacora', 'label' => 'Bitácora', 'file' => 'administracion/bitacora.php', 'roles' => ['Administrador'], 'group' => 'Administración', 'icon' => 'list'],
];
