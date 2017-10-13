# Instalació�n
## Requisitos
- Entorno con Apache, Mysql, PHP 5.6.30+, Composer y Git instalado
- Base de datos de mysql creada y un usuario para su uso
- Configurar apache para servir el sitio en la carpeta donde desee instalarse
- Configuración del dominio hacia el servidor web
- Módulos de php instalados:
  - bcmath
  - bz2
  - calendar
  - Core
  - ctype
  - curl
  - date
  - dba
  - dom
  - ereg
  - exif
  - fileinfo
  - filter
  - ftp
  - gd
  - gettext
  - hash
  - iconv
  - json
  - libxml
  - mbstring
  - mhash
  - mysql
  - mysqli
  - openssl
  - pcntl
  - pcre
  - PDO
  - pdo_mysql
  - Phar
  - posix
  - readline
  - Reflection
  - session
  - shmop
  - SimpleXML
  - soap
  - sockets
  - SPL
  - standard
  - sysvmsg
  - sysvsem
  - sysvshm
  - tokenizer
  - wddx
  - xml
  - xmlreader
  - xmlrpc
  - xmlwriter
  - Zend OPcache
  - zip
  - zlib

## Instrucciones

Posicionarse en la carpeta donde desea instalarse y ejecutar:

```
$git clone https://github.com/CongresoT/infopublicagt.git
```

Entrar a la carpeta “infopublicagt” y ejecutar el comando:
```
$composer install
```

Usando la base de datos creada para el proyecto (y el usuario de la base de datos), cargar la información basica:
```
$mysql -u dbuser -p dbname < seed.sql
```

Descargar el archivo de configuración de entorno de Laravel
```
$wget https://raw.githubusercontent.com/laravel/laravel/master/.env.example
```

Renombrar el archivo de configuración de entorno de Laravel a .env
```
$mv .env.example .env
```

Abrir el archivo .env y modificar los siguientes parámetros:

- APP_NAME	Poner el nombre del sitio
- APP_KEY	Ejecutar el comando 
- $php artisan key:generate
- Y poner el código generado en esta línea
- APP_DEBUG	Poner el valor ‘False’
- APP_URL	Poner la url donde estará disponible el sitio
- DB_DATABASE	Nombre de la base de datos
- DB_USERNAME	Nombre de usuario con permisos de lectura/escritura de la base de datos
- DB_PASSWORD	Contraseña para el usuario con permisos en la base de datos
- QUEUE_DRIVER	Poner el valor ‘database’
- MAIL_DRIVER	Configurar dependiendo de la servidor de correos a usar, por ejemplo ‘stmp’
- MAIL_HOST	url del servidor de correos, por ejemplo: ‘smtp.gmail.com’
- MAIL_USERNAME	Dirección de correo electrónico para poder enviar el correo y con el que se puede ingresar en el servidor configurado en MAIL_HOST
- MAIL_PASSWORD	Contraseña para acceder al correo y poder hacer el envío
- MAIL_SENDERNAME	Nombre con el que se enviarán los correos

Para crear el usuario administrador ejecutar el comando
```
$php artisan tinker
```

Se desplegará una consola interactiva, ejecutar los siguientes comandos:
```
>>>$user = new App\User();
>>>$user->password = Hash::make(‘password deseado’);
>>>$user->email = ‘email@dominio.com’;
>>>$user->sabe();
>>>exit
```

En este momento ya es posible usar el sitio en la URL que haya sido configurada para Apache.
Se puede entrar a la parte administrativa en la URL/admin, con el usuario y contraseña configurado en los últimos comandos.
