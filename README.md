# Jawalry


El proyecto Jawalry surge como una iniciativa creativa y altruista inspirada en los Jawas, esos misteriosos personajes de Star Wars que se dedicaban a traficar con cacharros electrónicos. Al igual que los Jawas, nosotros nos embarcaremos en la misión de rescatar cacharros obsoletos y descatalogados para transformarlos en auténticas joyas y abalorios con un enfoque artesanal y sin ánimo de lucro.

Este proyecto es la parte del backend, donde recibirá todas las peticiones.

## Requisitos del sistema

* Tener PHP instalado
* Tener Composer instalado
* Una base de datos.

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

## EndPoints

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
}
```

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
Devuelve el nuevo componente.

Eliminar Componente

    Endpoint: /componentes/{id}
    Método: DELETE
    Descripción: Elimina un componente.
    Parámetros:
        id (Parámetro de ruta) - ID del componente.

DELETE /componetes/1

Respuesta:

```json

    {
      "message": "Componente eliminado con éxito"
    }
```

Controlador de autentificación registro
    Endpoint: /registro
    Método: POST
    Descripción: Manda al servidor los datos del formulario de registro y los almacena en la base de datos

Respuesta:
```json
{
    "Mensaje": "Usuario creado con exito" 
}
```

Controlador de autentificación login
    Endpoint: /loggin
    Método: POST
    Descripción: Manda los datos de inicio de sesión al servidor y los compara, si son correctos devuelve el token de sesión y si no manda un error

Respuesta:
```json
{
"Token": "xxxxxxxxxxxxxxxxxxxxxxxxxxx" 
}
o
{
“Mensaje”: “Credenciales no validas” 
}
```