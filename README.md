# Despliegue de la Aplicación

## Requisitos

- PHP 8.1 o superior
- Composer

## Instalación

1. Clona el repositorio:
    ```sh
    git clone https://github.com/JoseAtencio/user-role-service.git
    cd user-role-service
    ```

2. Instala las dependencias de Composer:
    ```sh
    composer install
    ```

3. Copia el archivo de configuración de entorno y genera la clave de la aplicación:
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

4. Configura tu archivo `.env` con las credenciales de tu base de datos y otros parámetros necesarios.

5. Ejecuta las migraciones y los seeders para preparar la base de datos:
    ```sh
    php artisan migrate:fresh --seed 
    ```

## Ejecución

Para ejecutar la aplicación localmente, usa el siguiente comando:

```sh
php artisan serve

# Roles y Permisos de la Aplicación

## Roles

La aplicación define los siguientes roles, cada uno con una descripción específica:

1. **Owner**
   - Descripción: Owner GOOD rank
2. **Admin**
   - Descripción: Admin CEO rank
3. **Assistant**
   - Descripción: Assistant Supervisor rank
4. **Guest**
   - Descripción: Guest Guest rank
5. **No Access**
   - Descripción: Guest Blocked rank

## Permisos

La aplicación define los siguientes permisos, organizados por categorías:

### Permisos Generales
- **Send Email**: Permiso para enviar correos electrónicos.
- **View Users**: Permiso para ver usuarios.
- **Create Users**: Permiso para crear usuarios.
- **Edit Users**: Permiso para editar usuarios.
- **Delete Users**: Permiso para eliminar usuarios.

### Permisos para Enterprise
- **View Enterprise**: Permiso para ver empresas.
- **Create Enterprise**: Permiso para crear empresas.
- **Edit Enterprise**: Permiso para editar empresas.
- **Delete Enterprise**: Permiso para eliminar empresas.

### Permisos para Notifies
- **View Notifies**: Permiso para ver notificaciones.
- **Create Notifies**: Permiso para crear notificaciones.
- **Edit Notifies**: Permiso para editar notificaciones.
- **Delete Notifies**: Permiso para eliminar notificaciones.

### Permisos para Role
- **View Roles**: Permiso para ver roles.
- **Create Roles**: Permiso para crear roles.
- **Edit Roles**: Permiso para editar roles.
- **Delete Roles**: Permiso para eliminar roles.

### Permisos para Abilities
- **View Abilities**: Permiso para ver habilidades.
- **Create Abilities**: Permiso para crear habilidades.
- **Edit Abilities**: Permiso para editar habilidades.
- **Delete Abilities**: Permiso para eliminar habilidades.

## Asignación de Permisos a Roles

Los permisos se asignan a los roles de la siguiente manera:

- **Owner**: Tiene todos los permisos.
- **Admin**: Tiene todos los permisos excepto el de eliminar usuarios.
- **Assistant**: No tiene permisos para editar o eliminar usuarios y empresas.
- **Guest**: Solo tiene permisos para ver usuarios y empresas, y para crear notificaciones.
- **No Access**: No tiene ningún permiso.
