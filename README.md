# Jawalry

El proyecto Jawalry surge como una iniciativa creativa y altruista inspirada en los Jawas, esos misteriosos personajes de Star Wars que se dedicaban a traficar con cacharros electrónicos. Al igual que los Jawas, nosotros nos embarcaremos en la misión de rescatar cacharros obsoletos y descatalogados para transformarlos en auténticas joyas y abalorios con un enfoque artesanal y sin ánimo de lucro.

Este proyecto es la parte del backend, donde recibirá todas las peticiones.

## Requisitos del sistema

* Tener PHP instalado
* Tener Composer instalado
* Una base de datos, con el nombre jewelry, si se desea usar otra base de datos, con otra configuración, se deberá de configurar el fichero `.env`.

## Instalación

Para la instalación del proyecto, deberemos de ejecutar el siguiente comando para poder descargarse y trabajar con Laravel:

```sh
composer update
```

Así mismo, mientras se nos descarga la carpeta Vendor, pásaremos a copiar el archivo .env.example a .env y deberemos de configura la conexión a la base de datos.
Ejemplo:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3333
DB_DATABASE=jewelry
DB_USERNAME=root
DB_PASSWORD=1234
```

Por último deberemos de generar unas claves para que Laravel pueda usarlo para cifrar en distintos lugares de uso interno.

```sh
php artisan key:generate
```

## Base de datos

Es posible importarse el script de la base de datos o, si se prefiere, se puede realizar la migración.

### Migración

Para ejecutar la migracion con sus respectivos seeders, ejecutar los siguiente scripts, en función del sistema operativo.

En linux:

```sh
chmod +x run.sh
./run.sh
```

En Windows:

```bat
script.bat
```

El usuario que se crea para poder iniciar sesión:

```
    'email' => 'fernando@cifp.com',
    'password' => "Chubaca2024",
```

## Testing

Hay test para probar:

```sh
php artisan test
```

## EndPoints

## Registro y inicio de sesión

* **POST /signup**
  * Controlador: `APIAuthController@signup`
  JSON esperado:

  ```json
  {
    "name": "NombreUsuario",
    "email": "testing@foo.tld",
    "password": "P@ssword123",
    "name_empresa": "Repsol"
  }```

  Respuesta 200:

  ```json
  {
    "user": [
      {
        "id": 1,
        "name": "NombreUsuario",
        "email": "usuario@example.com",
        "name_empresa": "NombreEmpresa",
        "start_at": "2023-01-01 12:00:00",
        "created_at": "2023-01-01 12:00:00",
        "updated_at": "2023-01-01 12:00:00"
      },
      {
        "id": 1,
        "user_id": 1,
        "rol_id": 1,
        "created_at": "2023-01-01 12:00:00",
        "updated_at": "2023-01-01 12:00:00"
      }
    ],
    "msg": "Usuario registrado",
    "status": 200
  }```

  Si ya está registrado, código 400:

  ```json
  {
    "msg": {
      "email": ["El email ya está registrado."],
      "name_empresa": ["El nombre de la empresa ya está en uso"]
    },
    "status": 400
  }```

* **POST /login**
  * Controlador: `APIAuthController@login`

  JSON esperado:

  ```json
  {
    "email": "usuario@example.com",
    "password": "contraseña123"
  }```

Respuesta 200:

```json
{
  "token": "access_token",
  "user_id": 1,
  "user_name": "NombreUsuario"
}
```

Respuesta 401:

```json
"Unauthorized"
```

En caso de que al hacer el login está de baja.
Respuesta 400:

```json
{
  "msg": "Este usuario está de baja desde el 2023-01-01 12:00:00"
}
```

En caso de que al hacer el login, la fecha de activación de la cuenta está en el futuro.
Respuesta:

```json
{
  "msg": "Este usuario está de baja desde el 2023-01-01 12:00:00"
}
```

* **POST /logout/{userId}**
  * Controlador: `APIAuthController@logout`
  Respuesta esperada:

  ```json
  {
  "msg": "Sesión actual cerrada"
  }
  ```

* **POST /full-logout/{userId}**
  * Controlador: `APIAuthController@fullLogout`

  ```json
  {
    "msg": "Se ha cerrado sesión en todos sus dispositivos."
  }
  ```

## Cambio de imagen para el registro

* **POST /signup/image**
  * Controlador: `UserUserController@uploadImage`
  JSON esperado:
   ```json
  {
    "image": [Archivo de imagen]
  }
  ```

## Recuperación de contraseña

* **POST /forget-pass**
  * Controlador: `UserUserController@forgetPass`

* **GET /**
  * Controlador: Closure (Respuesta JSON "Unauthorized")
  * Nombre: `nologin`

## Información de despiece para Colaborador y Clasificador

* **GET /colaborador/info-despiece/{loteId}**
  * Controlador: `ClasificadorController@infoDespiece`
  * Middleware: `colaborador`
* **GET /clasificador/info-despiece/{loteId}**
  * Controlador: `ClasificadorController@infoDespiece`
  * Middleware: `clasificador`

## Funciones específicas para Colaborador

* **GET /colaborador/{userId}/mis-lotes**
  * Controlador: `ColaboradorController@index`
* **GET /colaborador/{userId}/lote/{loteId}**
  * Controlador: `ColaboradorController@show`
* **POST /colaborador/lote**
  * Controlador: `ColaboradorController@store`
* **PUT /colaborador/lote/{loteId}/cancelar**
  * Controlador: `ColaboradorController@cancelar`

## Rechazar lote por Clasificador

* **PUT /lote/{loteId}/rechazar**
  * Controlador: `ClasificadorController@rechazar`
  * Middleware: `colaborador`

## Funciones específicas para Clasificador

* **GET /clasificador/lotes**
  * Controlador: `ClasificadorController@todos`
* **GET /clasificador/disponibles**
  * Controlador: `ClasificadorController@disponible`
* **GET /clasificador/{userId}/mis-lotes**
  * Controlador: `ClasificadorController@index`
* **GET /clasificador/{userId}/mis-clasificados**
  * Controlador: `ClasificadorController@clasificados`
* **GET /clasificador/lote/{loteId}**
  * Controlador: `ClasificadorController@show`
* **POST /clasificador/{userId}/asign**
  * Controlador: `ClasificadorController@store`
* **PUT /clasificador/{loteId}/clasificar**
  * Controlador: `ClasificadorController@clasificar`

## Funciones específicas para Clasificador relacionadas con Componentes

* **GET /clasificador/componentes**
  * Controlador: `ComponentesController@index`
* **GET /clasificador/componentes/{userId}**
  * Controlador: `ComponentesController@showByUser`

## Funciones generales para Clasificador relacionadas con Componentes

* **GET /componentes**
  * Controlador: `ComponentesController@allComponentes`

  ```json
  [
    {
      "id": 1,
      "name": "CPU",
      "desc": "Unidad Central de Procesamiento",
      "is_hardware": 1,
      "created_user_id": 1
    },
    {
      "id": 2,
      "name": "RAM",
      "desc": "Memoria de Acceso Aleatorio",
      "is_hardware": 1,
      "created_user_id": 1
    },
    {
      "id": 3,
      "name": "GPU",
      "desc": "Unidad de Procesamiento de Gráficos",
      "is_hardware": 1,
      "created_user_id": 1
    },
  ...
  ]```

* **GET /componentes/{componenteId}**
  * Controlador: `ComponentesController@show`
    JSON esperado:

  ```json
    {
      "id": 1,
      "name": "CPU",
      "desc": "Unidad Central de Procesamiento",
      "is_hardware": 1,
      "created_user_id": 1
  }```

* **PUT /componentes/{componenteId}**
  * Controlador: `ComponentesController@update`
  JSON esperado:

```json
{
    "id": 1,
    "name": "CPU2",
    "desc": "Unidad Central de Procesamiento2",
    "is_hardware": 1,
    "created_user_id": 1
  }
```

Respuesta:

```json
{
    "id": 1,
    "name": "CPU2",
    "desc": "Unidad Central de Procesamiento2",
    "is_hardware": 1,
    "created_user_id": 1
  }
```

* **POST /componentes**
  * Controlador: `ComponentesController@store`

  Cuerpo de la solicitud:

```json
{
    "id": 6,
    "name": "CPU5",
    "desc": "Unidad Central de Procesamiento6",
    "is_hardware": 1,
    "created_user_id": 1
}
```

Respuesta:

```json
{
    "id": 6,
    "name": "CPU5",
    "desc": "Unidad Central de Procesamiento6",
    "is_hardware": 1,
    "created_user_id": 1
}
```

* **DELETE /componentes/{componenteId}**
  * Controlador: `ComponentesController@destroy`
 /componetes/1
JSON respuesta:
  ```json
    {
      "message": "Componente eliminado con éxito"
    }```

## Funciones generales para Administrador relacionadas con Componentes

### Son las mismas que para Componentes

* **GET /admin/componentes**
  * Controlador: `ComponentesController@allComponentes`
* **GET /admin/componentes/{componenteId}**
  * Controlador: `ComponentesController@show`
* **PUT /admin/componentes/{componenteId}**
  * Controlador: `ComponentesController@update`
* **POST /admin/componentes**
  * Controlador: `ComponentesController@store`
* **DELETE /admin/componentes/{componenteId}**
  * Controlador: `ComponentesController@destroy`

## Operaciones relacionadas con Lotes

* **GET /lote/{loteId}**
  * Controlador: `LoteController@show`

  
* **POST /lote/ **
  * Controlador: `LoteController@store`

# Endpoints para la administración de usuarios

## Operaciones de administrador

* **GET /admin**
  * Controlador: `UserUserController@index`
  ```json 
   Esperado 200:

```json
  [
    {
      "id": 1,
      "name": "Usuario1",
      "email": "usuario1@example.com",
      "name_empresa": "Empresa1",
      "start_at": "2023-01-01 12:00:00",
      "end_at": null,
      "created_at": "2023-01-01 12:00:00",
      "updated_at": "2023-01-01 12:00:00"
    },
    {
      "id": 2,
      "name": "Usuario2",
      "email": "usuario2@example.com",
      "name_empresa": "Empresa2",
      "start_at": "2023-01-01 12:00:00",
      "end_at": null,
      "created_at": "2023-01-01 12:00:00",
      "updated_at": "2023-01-01 12:00:00"
    }
  ]
  ```

* **GET /admin/{userId}**
  * Controlador: `UserUserController@show`
* **GET /admin/{userId}/activate-account**
  * Controlador: `UserUserController@activeUser`
* **POST /admin/search**
  * Controlador: `UserUserController@searchUserByEmail`
* **POST /admin**
  * Controlador: `UserUserController@store`
* **PUT /admin/{userId}/program-desactivate**
  * Controlador: `UserUserController@programBaja`
* **PUT /admin/{userId}/desactivate-account**
  * Controlador: `UserUserController@darBaja`
* **PUT /admin/{userId}**
  * Controlador: `UserUserController@update`
* **PUT /admin/{userId}/roles**
  * Controlador: `UserUserController@updateRoles`
* **DELETE /admin/{userId}**
  * Controlador: `UserUserController@destroy`

## Operaciones de usuario

* **GET /user/{id}/mis-roles**
  * Controlador: `UserUserController@roles`

  Esperado 200:
  ```json
    [
    {
      "id": 1,
      "name": "Colaborador",
      "created_at": "2023-01-01 12:00:00",
      "updated_at": "2023-01-01 12:00:00"
    },
    {
      "id": 2,
      "name": "Clasificador",
      "created_at": "2023-01-01 12:00:00",
      "updated_at": "2023-01-01 12:00:00"
    }
  ]```

* **GET /user/{userId}**
  * Controlador: `UserUserController@show`

```json
  {
  "msg": {
    "id": 1,
    "name": "Usuario1",
    "email": "usuario1@example.com",
    "name_empresa": "Empresa1",
    "start_at": "2023-01-01 12:00:00",
    "end_at": null,
    "created_at": "2023-01-01 12:00:00",
    "updated_at": "2023-01-01 12:00:00",
    "roles": [
      {
        "id": 1,
        "name": "Colaborador",
        "created_at": "2023-01-01 12:00:00",
        "updated_at": "2023-01-01 12:00:00"
      },
      {
        "id": 2,
        "name": "Clasificador",
        "created_at": "2023-01-01 12:00:00",
        "updated_at": "2023-01-01 12:00:00"
      }
    ]
  },
  "status": 200
}```

* **PUT /user/{userId}**
  * Controlador: `UserUserController@update`
   JSON esperado:
  
  ```json
    {
    "email": "nuevousuario@example.com",
    "name_empresa": "NuevaEmpresa"
  }```

  Respuesta 200:

  ```json
    {
    "id": 1,
    "name": "Usuario1",
    "email": "nuevousuario@example.com",
    "name_empresa": "NuevaEmpresa",
    "start_at": "2023-01-01 12:00:00",
    "end_at": null,
    "created_at": "2023-01-01 12:00:00",
    "updated_at": "2023-01-01 12:00:00"
  }```

  Error 400:

  ```json
    {
    "msg": {
      "email": ["El email ya está registrado."],
      "name_empresa": ["El nombre de la empresa ya está en uso"]
    },
    "status": 400
  }```

* **POST /user/
  * Controlador: `UserUserController@store`
  JSON esperado:

  ```json
    {
    "name": "NuevoUsuario",
    "email": "nuevousuario@example.com",
    "name_empresa": "NuevaEmpresa",
    "roles": [
      {
        "rol_id": 1
      },
      {
        "rol_id": 2
      }
    ],
    "start_at": "2023-01-01 12:00:00",
    "end_at": null
  }```

Respuesta 200:

  ```json
  {
    "msg": "Usuario registrado",
    "status": 200
  }```

  Error 400:

  ```json
  {
    "msg": {
      "email": ["El email ya está registrado."],
      "name_empresa": ["El nombre de la empresa ya está en uso"]
    },
    "status": 400
  }```

* **POST /user/{userId}/image**
  * Controlador: `UserUserController@updateImage`
  Esperado 200:

  

* **DELETE /user/{userId}**
  * Controlador: `UserUserController@destroy`
 

## Operaciones de usuario relacionadas con Lotes

* **GET /user/{userId}/lote/
  * Controlador: `LoteController@show`

## Operaciones generales de usuario

* **POST /user/image**
  * Controlador: `UserUserController@uploadImage`
  JSON esperado:
  ```json
  {
    "image": [Archivo de imagen]
  }
  ```

  Respuesta 200:
  ```json
    {
    "msg": "Imagen actualizada correctamente",
    "status": 200
  }```


### Controlador de Lote

Obtener Mis Lotes

    Endpoint: /mis-lotes/{userId}
    Método: GET
    Descripción: Recupera una lista de lotes asociados al usuario especificado cuyo estado es recogido.
    Parámetros:
        userId (Parámetro de ruta) - ID del usuario.

Respuesta:

```json
{
    {
    "id": 2,
    "ubi": "test",
    "observation": "test",
    "empresa": "Bins, Sanford and Strosin",
    "status": "Recogido",
    "status_code_id": 2,
    "created_at": "2023-11-29 21:22:08"
  }
}```

Obtener Disponibles

    Endpoint: /lote/disponibles
    Método: GET
    Descripción: Obtiene la lista de lotes disponibles.

Respuesta:

```json
[
  {
    "id": 1,
    "ubi": "test",
    "observation": "test",
    "empresa": "Bins, Sanford and Strosin",
    "status": "Creado",
    "status_code_id": 1,
    "created_at": "2023-11-29T21:21:10.000000Z"
  },
  {
    "id": 3,
    "ubi": "test2",
    "observation": "test2",
    "empresa": "Bins, Sanford and Strosin",
    "status": "Creado",
    "status_code_id": 1,
    "created_at": "2023-11-29T21:38:20.000000Z"
  }
]
```

Mostrar Lote

    Endpoint: /lote/{loteId}
    Método: GET
    Descripción: Obtiene detalles de un lote específico.
    Parámetros:
        loteId (Parámetro de ruta) - ID del lote.
Respuesta:

```json
    {
        "id": 1,
        "user_id": 1,
        "user_name": "Elza Hyatt Sr.",
        "ubi": "test",
        "observation": "test",
        "status": "Se acaba de crear un lote, muy pronto se pasará Chubaca a por el!"
    }
```

Almacenar Lote

    Endpoint: /lote/
    Método: POST
    Descripción: Crea un nuevo lote.

Cuerpo de la solicitud:

```json

{
  "ubi":"testing",
  "observation":"Es enorme",
  "user_id":2
}
```

Respuesta:

```json

 {
  "ubi": "testing",
  "observation": "Es enorme",
  "user_id": 2,
  "status_code_id": 1,
  "created_at": "2023-11-29T21:42:45.000000Z",
  "id": 5,
  "status": "Se acaba de crear un lote, muy pronto se pasará Chubaca a por el!"
}
```

Clasificar Lote

    Endpoint: /lote/clasificador
    Método: POST
    Descripción: Clasifica un lote.

Cuerpo de la solicitud:

```json

{
    "id": ["1","3","4"],
    "cantidad": ["2", "2", "2"],
    "lote_id": 1,
    "user_id": "1"
}
```

Respuesta:

```json

{
  "message": "Lote clasificado correctamente",
  "despiece": {
    "cantidad": "2",
    "clasificador_id": "1",
    "lote_id": 1,
    "componente_id": "4",
    "id": 4
  }
}
```

### Controlador de Clasificador

Mostrar Lotes del Usuario

    Endpoint: /user/{id}/lotes
    Método: GET
    Descripción: Obtiene la lista de lotes asociados a un usuario.
    Parámetros:
        id (Parámetro de ruta) - ID del usuario.

Respuesta:

```json
[
  {
    "id": 1,
    "user_id": 1,
    "empresa": "Miss Irma Heathcote",
    "ubi": "test23",
    "observation": "test23",
    "status": "Se acaba de crear un lote, muy pronto se pasará Chubaca a por el!",
    "created_at": "2023-11-29T21:47:04.000000Z"
  }
]
```

Almacenar la asignación de un lote a un clasificador

    Endpoint: /user/lote
    Método: POST
    Descripción: Asignar un lote a un usuario.

Cuerpo de la solicitud:

```json

{
    "lote_id": ["1","3"],
    "user_id": 1,
}
```

Esto asignará el lote 1 y 3 al clasificador, que tiene el id 1.

Ejemplo de respuesta:

```json
{
  "msg": "Asignado correctamente!",
  "store": {
    "user_id": 1,
    "lote_id": [
      "1",
      "3"
    ]
  }
}
```

Obtener Roles del Usuario

    Endpoint: /user/{id}/mis-roles
    Método: GET
    Descripción: Obtiene los roles asociados a un usuario.
    Parámetros:
        id (Parámetro de ruta) - ID del usuario.

Respuesta:

```json
[
  {
    "id": 1,
    "user_id": 1,
    "rol_id": 1
  },
  {
    "id": 2,
    "user_id": 1,
    "rol_id": 1
  },
  {
    "id": 3,
    "user_id": 1,
    "rol_id": 1
  }
]
```

## Controlador Compontes - NO FUNCIONAL

Rutas de Componentes UNICO FUNCIONAL
Obtener Componentes

    Endpoint: /componentes/
    Método: GET
    Descripción: Obtiene la lista de componentes.

Respuesta:

```json
[
  {
    "id": 1,
    "name": "CPU",
    "desc": "Unidad Central de Procesamiento",
    "is_hardware": 1,
    "created_user_id": 1
  },
  {
    "id": 2,
    "name": "RAM",
    "desc": "Memoria de Acceso Aleatorio",
    "is_hardware": 1,
    "created_user_id": 1
  },
  {
    "id": 3,
    "name": "GPU",
    "desc": "Unidad de Procesamiento de Gráficos",
    "is_hardware": 1,
    "created_user_id": 1
  },
...
]
```

Mostrar Componente

    Endpoint: /componentes/{id}
    Método: GET
    Descripción: Obtiene detalles de un componente específico.
    Parámetros:
        id (Parámetro de ruta) - ID del componente.

Respuesta:

```json
{
    "id": 1,
    "name": "CPU",
    "desc": "Unidad Central de Procesamiento",
    "is_hardware": 1,
    "created_user_id": 1
  }
```

Actualizar Componente

    Endpoint: /componentes/{id}
    Método: PUT
    Descripción: Actualiza detalles de un componente específico.
    Parámetros:
        id (Parámetro de ruta) - ID del componente.

PUT - Cuerpo de la solicitud:

```json
{
    "id": 1,
    "name": "CPU2",
    "desc": "Unidad Central de Procesamiento2",
    "is_hardware": 1,
    "created_user_id": 1
  }
```

Ejemplo de respuesta:

```json
{
    "id": 1,
    "name": "CPU2",
    "desc": "Unidad Central de Procesamiento2",
    "is_hardware": 1,
    "created_user_id": 1
  }
```

Devuelve el componente actualizado.

Almacenar Componente

    Endpoint: /componentes/
    Método: POST
    Descripción: Crea un nuevo componente.

POST

Cuerpo de la solicitud:

```json
{
    "id": 6,
    "name": "CPU5",
    "desc": "Unidad Central de Procesamiento6",
    "is_hardware": 1,
    "created_user_id": 1
}
```

Respuesta:

```json
{
    "id": 6,
    "name": "CPU5",
    "desc": "Unidad Central de Procesamiento6",
    "is_hardware": 1,
    "created_user_id": 1
}
```

