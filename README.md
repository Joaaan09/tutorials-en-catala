# Aplicación Laravel para importar tutoriales de iFixit

Esta aplicación permite importar tutoriales de la API de iFixit y almacenarlos en una base de datos SQLite con traducción automática al catalán.

---

## 🚀 REQUISITOS

- **PHP** ≥ 8.1
- **Composer** instalado
- **Extensión PHP SQLite** habilitada

---

## 🛠 INSTALACIÓN

### 1️⃣ Clonar el repositorio:
```bash
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio
```

### 2️⃣ Instalar dependencias:
```bash
composer install
```

---

## ⚙️ CONFIGURACIÓN

### 1️⃣ Crear la base de datos SQLite:
```bash
touch database/database.sqlite
```

### 2️⃣ Configurar el archivo `.env`:
- Abrir el archivo `.env`
- Buscar la sección de base de datos y dejar solo:
```env
DB_CONNECTION=sqlite
```
- Comentar o eliminar las configuraciones de MySQL/PostgreSQL

### 3️⃣ Ejecutar migraciones:
```bash
php artisan migrate
```

---

## 📌 USO BÁSICO

### Configurar los tutoriales a importar:
Abrir el archivo:
```bash
app/Console/Commands/ImportTutorials.php
```
Buscar esta línea:
```php
$tutorials = array_slice($service->getTutorials('Electronics'), 6, 5);
```
Modificar los parámetros:
```php
getTutorials("CATEGORIA"), POSICIÓN INCIAL, CANTIDAD
```
Ejemplo para importar 3 tutoriales de **Smartphones** desde la posición 2:
```php
array_slice($service->getTutorials('Smartphones'), 2, 3);
```

### Ejecutar el importador:
```bash
php artisan import:tutorials
```

---

## 📚 CATEGORÍAS DISPONIBLES
Las categorías válidas se pueden consultar en:
[API de iFixit](https://www.ifixit.com/api/2.0/categories)

---

## ⚡ AJUSTES AVANZADOS

### Cambiar el idioma de traducción:
Editar esta línea en `ImportTutorials.php`:
```php
new GoogleTranslate('ca'); // Cambiar 'ca' por otro código de idioma (ej: 'es')
```

### Aumentar el tiempo entre solicitudes (evitar errores de límite API):
Agregar en el bucle de pasos:
```php
sleep(5); // Aumentar el número de segundos
```

---

## 🗄️ ESTRUCTURA DE LA BASE DE DATOS

- **Tutoriales** → Tabla `tutorials`
- **Pasos** → Tabla `steps`
- **Imágenes** → Tabla `images`

---

## ❌ SOLUCIÓN DE PROBLEMAS

### 🛑 Error "Database disk image is malformed":
```bash
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate
```

### 🛑 Error "Too Many Requests" (API):
- Aumentar el tiempo de espera entre pasos.
- Reducir la cantidad de tutoriales importados.

---

✅ ¡Listo! Ahora puedes importar tutoriales de iFixit y almacenarlos en tu base de datos SQLite con traducción automática al catalán. 🚀

