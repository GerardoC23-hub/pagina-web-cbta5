<?php
// Panel sections. One page per module; each one is made by a single person.
// 'roles' = who can see and open it. 'group' = heading in the side menu.
return [
    ['id' => 'inicio', 'label' => 'Inicio', 'file' => 'index.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Principal', 'icon' => 'home'],
    ['id' => 'noticias', 'label' => 'Noticias y avisos', 'file' => 'noticias.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'news'],
    ['id' => 'clubes', 'label' => 'Clubes', 'file' => 'clubes.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'club'],
    ['id' => 'actividades', 'label' => 'Actividades', 'file' => 'actividades.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'calendar'],
    ['id' => 'oferta', 'label' => 'Oferta y nuevo ingreso', 'file' => 'oferta.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'book'],
    ['id' => 'productos', 'label' => 'Productos', 'file' => 'productos.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'box'],
    ['id' => 'servicios', 'label' => 'Servicios', 'file' => 'servicios.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Contenido', 'icon' => 'tool'],
    ['id' => 'nosotros', 'label' => 'Nosotros', 'file' => 'nosotros.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'info'],
    ['id' => 'personal', 'label' => 'Personal y directorio', 'file' => 'personal.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'staff'],
    ['id' => 'contacto', 'label' => 'Datos de contacto', 'file' => 'contacto.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'phone'],
    ['id' => 'documentos', 'label' => 'Documentos y materiales', 'file' => 'documentos.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'file'],
    ['id' => 'galeria', 'label' => 'Galería', 'file' => 'galeria.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Información', 'icon' => 'image'],
    ['id' => 'mensajes', 'label' => 'Mensajes y solicitudes', 'file' => 'mensajes.php', 'roles' => ['Administrador', 'Editor'], 'group' => 'Atención', 'icon' => 'mail'],
    ['id' => 'alumnos', 'label' => 'Cuentas de alumnos', 'file' => 'alumnos.php', 'roles' => ['Administrador'], 'group' => 'Administración', 'icon' => 'user'],
    ['id' => 'usuarios', 'label' => 'Usuarios del panel', 'file' => 'usuarios.php', 'roles' => ['Administrador'], 'group' => 'Administración', 'icon' => 'shield'],
    ['id' => 'bitacora', 'label' => 'Bitácora', 'file' => 'bitacora.php', 'roles' => ['Administrador'], 'group' => 'Administración', 'icon' => 'list'],
];
