• Nombre del proyecto:
Tienda online de juegos de mesa Board this way.

• Se trata de la página web para una tienda online que dispone de:
- Un CRUD completo(Create, Read, Update, Delete).
- Filtrado de juegos por género.
- Diseño con tarjetas horizontales.
- Login/logout funcional.
- Roles (admin vs user).
- Botones y menús adaptados a los roles de los usuarios.
- Redirecciones inteligentes.
- Feedback funcional mediante alertas.
- Navegación simple e intuitiva.

• Tecnologías utilizadas:
- Editor de texto Visual Studio Code.
- Docker para lanzar el contenedor donde estará todo el proyecto.
- Base de datos en PostgreSQL.
- PHP.
- HTML y CSS.
- Librería Config para ejecutar el patrón Singleton.
- Carpeta Vendor con todas las librerías necesarias incluidala que permite vincular la base de datos al proyecto en PHP.


• Estructura del proyecto:
En la raíz del proyecto están los ficheros de configuración y las carpetas principales:
📄 .env
📄 composer.lock
📄 composer.json
📄 dockerfile
📄 docker-compose.yml
📁 database (contiene el fichero con la base de datos init.sql)
📁 tests (vacía de momento)
📁 vendor (contiene todas las librerías)
📁 src (en detalle a continuación)

En "src" hay:
📁 config (config.php)
📁 models (contiene las clases: genero.php, producto.php y user.php)
📁 services (contiene los controladores: GeneroServices.php, ProductosServices.php, SessionServices.php y UserServices.php)
📁 uploads (contiene el favicon.png)
📄 create.php (página de creación de un producto)
📄 delete.php (función de borrado de un producto)
📄 details.php (página donde se muestran los detalles de un producto)
📄 footer.php (bloque que muestra el pie de la página web)
📄 header.php (bloque que muestra el encabezado de la página web)
📄 index.php (página principal de la web)
📄 login.php (página de inicio de sesión)
📄 logout.php (función de cerrar sesión)
📄 update.php (página para editar la información de un producto)

• Instrucciones de instalación:
- Primeramente se creó la carpeta del proyecto donde se guardaron los ficheros de configuración iniciales.
- Antes de lanzar Docker, se instalaron las dependencias con composer install en la máquina local para generar la carpeta vendor/.
- Una vez hecho esto, y con los ficheros de dockerfile y docker-compose.yml configurados con el código dado, se ejecutó docker-compose up -d --build y se lanzó el entorno.
- La carpeta src se genera automáticamente y dentro ya se puede crear la estructura de carpetas y ficheros del proyecto.
- En este caso la base de datos se configuró con la estructura adecuada al final, empleándose para el proceso de instalación una previamente proporcionada.

• Uso básico:
- Para acceder a la aplicación se puede hacer desde localhost o clickando en el enlace de Docker.
- Para navegar por ella están las siguientes páginas:
    🏠 El main menú o página principal es la primera página que se ve, desde el encabezado se puede navegar a las demás. El nombre de la web o "Lista de productos" redirigen a esta página.
    📝 Añadir producto redirige a la página de crear un artículo para guardarlo en la BBDD (solo disponible con inicio de sesión y privilegios de administrador).
    🔐 Login redirige a una página para insertar las credenciales de inicio de sesión.
    🔎 La barra de búsqueda de la página principal permite filtrar los productos por género.
    - En las tarjetas de producto se encuentran las siguiente funciones:
    ℹ️ Detalles permite ver toda la información de cada producto (función disponible para todos).
    ✏️ Editar redirige a una página para cambiar la información de un producto (solo disponible con inicio de sesión y privilegios de administrador).
    🗑️ Eliminar abre un modal que hay que aceptar para borrar un producto (solo disponible con inicio de sesión y privilegios de administrador).
    🚪 Logout es un botón que aparece en el encabezado solo si se ha iniciado sesión para cerrarla.

• Requisitos previos: 
- PHP 8.0-apache
- Docker 4.48.0
- PostGres 12
- Apache 2.0
- Ramsey/collection

• Autores y créditos: 
- Ángel José García Ibáñez

• Licencia para uso educativo.
