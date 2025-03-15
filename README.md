Aplicación Laravel para importar tutoriales de la API de iFixit y almacenarlos en una base de datos SQLite con traducción automática al catalán.

REQUISITOS

PHP ≥ 8.1

Composer instalado

Extensión PHP SQLite habilitada

INSTALACIÓN

Clonar el repositorio:
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio

Instalar dependencias:
composer install

CONFIGURACIÓN

Crear la base de datos SQLite:
touch database/database.sqlite

Configurar archivo .env:

Abrir el archivo .env

Buscar la sección de base de datos y dejar solo:
DB_CONNECTION=sqlite

Comentar o eliminar las configuraciones de MySQL/PostgreSQL

Ejecutar migraciones:
php artisan migrate

USO BÁSICO

Configurar los tutoriales a importar:

Abrir el archivo: app/Console/Commands/ImportTutorials.php

Buscar esta línea:
$tutorials = array_slice($service->getTutorials('Electronics'), 6, 5);

Modificar los parámetros en este formato:
getTutorials("CATEGORIA"), POSICIÓN INCIAL, CANTIDAD
Ejemplo para importar 3 tutoriales Smartphones de la posición 2:
array_slice(service->getTutorials('Smartphones'), 2, 3)

Ejecutar el importador:
php artisan import:tutorials

CATEGORÍAS DISPONIBLES
Las categorías válidas se pueden consultar en:
https://www.ifixit.com/api/2.0/categories

AJUSTES AVANZADOS

Para cambiar idioma de traducción:
Editar esta línea en ImportTutorials.php:
new GoogleTranslate('ca'); // Cambiar 'ca' por otro código de idioma (ej: 'es')

Para aumentar el tiempo entre solicitudes (si hay errores de límite API):
Agregar en el bucle de pasos:
sleep(5); // Aumentar el número de segundos

ESTRUCTURA DE LA BASE DE DATOS

Tutoriales: Tabla 'tutorials'

Pasos: Tabla 'steps'

Imágenes: Tabla 'images'

SOLUCIÓN DE PROBLEMAS
Error "Database disk image is malformed":
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate

Error "Too Many Requests" (API):

Aumentar el tiempo de espera entre pasos

Reducir la cantidad de tutoriales importados
