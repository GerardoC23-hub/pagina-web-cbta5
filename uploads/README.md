# Archivos subidos desde el panel

La base de datos guarda **solo la ruta** del archivo; el archivo vive en estas carpetas.

| Carpeta                | Qué guarda                          | Tabla / columna                          |
|------------------------|-------------------------------------|------------------------------------------|
| `uploads/publicaciones`| imagen de noticias y avisos         | `tblpublicaciones.imagen`                |
| `uploads/carreras`     | imagen de cada carrera              | `tblcarreras.imagen`                     |
| `uploads/personal`     | foto del personal autorizado        | `tblpersonal.foto`                       |
| `uploads/galeria/fotos`| fotos de la galería                 | `tblgaleria.archivo` (tipo Foto)         |
| `uploads/galeria/videos`| videos de la galería               | `tblgaleria.archivo` (tipo Video)        |
| `uploads/galeria/miniaturas`| portada de cada video          | `tblgaleria.miniatura`                   |
| `uploads/productos`    | imagen de productos                 | `tblproductos.imagen`                    |
| `uploads/actividades`  | imagen de clubes, actividades y servicios | `tblactividades.imagen`            |
| `uploads/secciones`    | imágenes de secciones informativas  | `tblsecciones.imagen`                    |
| `uploads/documentos`   | documentos y materiales **públicos**| `tbldocumentos.archivo` (solo_alumnos=0) |
| `storage/documentos-alumnos` | documentos **solo para alumnos** | `tbldocumentos.archivo` (solo_alumnos=1) |
| `storage/solicitudes`  | adjuntos de solicitudes en línea    | `tblsolicitud_archivos.ruta`             |

## Reglas

- La ruta se guarda **relativa a la raíz del sitio**, con diagonales `/`:
  `uploads/galeria/fotos/graduacion-2025-a1b2c3.jpg`.
- Nombre del archivo: el texto en minúsculas sin acentos ni espacios + código aleatorio corto
  (`bin2hex(random_bytes(3))`). Nunca se usa el nombre original de quien lo sube (se guarda en
  `tblsolicitud_archivos.nombre_original` si hace falta mostrarlo).
- Al reemplazar o eliminar un registro, el panel también borra el archivo anterior.
- Validar al subir: extensión y tipo MIME permitidos, peso máximo y que sea de verdad una imagen
  (`getimagesize`). Permitidos: imágenes `jpg`, `png`, `webp`; videos `mp4`; documentos `pdf`, `docx`, `xlsx`.
- Los archivos de `storage/` **no se abren por URL**: se entregan con un PHP que revisa la sesión del alumno.
- Toda foto lleva su crédito en la base de datos (`imagen_credito` o `credito`).
- Los archivos subidos no se suben a Git (solo las carpetas, con `.gitkeep`).
