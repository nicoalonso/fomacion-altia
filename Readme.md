# Formaciones Altia

Esqueleto básico para realizar las formaciones de Altia (05/2020).

Consiste en dos contenedores.

1. Apache + PHP 7.4 y un esqueleto de Symfony 4
2. Base de datos MariaDb v10.4



## 1. Instrucciones para crear una máquina virtual

Si no vas a usar una máquina virtual sáltate este paso y vete hasta el punto 2.

Vamos a crear una máquina virtual usando VirtualBox con una imagen base de debian.

Lo primero que vamos a hacer es descargarnos la `iso` desde `debian.org` para realizar la instalación. Yo suelo usar la opción de ***instalar debian a través de Internet***. En concreto he decido utilizar la siguiente imagen:

https://cdimage.debian.org/debian-cd/current/amd64/iso-cd/debian-10.3.0-amd64-netinst.iso

Una vez que ya tenemos descargada la imagen vamos a crear la máquina virtual en el VirtualBox, entiendo que ya lo tienes instalado en tu equipo.

En la barra principal de VirtualBox pinchamos en "Nueva". Aquí voy a poner los siguientes valores, puedes cambiarlo si lo deseas.

* Nombre: VMAMPDCK
* Tipo: Linux
* Versión: Debian (64bit)

Ten en cuenta que en el cliente usamos Ubuntu, pero a grandes rasgos es lo mismo.

Le damos a Siguiente. En tamaño de memoria, aquí deberías de poner un valor con respeto a las características de tu PC. Yo como tengo mucha memoria libre le voy a poner 2GB.

En la siguientes pantallas vamos a seleccionar:

1. "*Crear un disco duro virtual ahora*"
2. "*VDI (VirtualBox Disk Image)*"
3. "*Reservado dinámicamente*"

Deberíamos de estar en la etapa del asistente "Ubicación del archivo y tamaño", como en la parte de memoria va a depender de las características de tu PC, por lo que escoge sabiamente. Yo he marcado **20GB**, que es lo mismo que tenemos en las VM del cliente.

Ya debemos de tener la máquina virtual creada, ahora toca instalar el SO.

Antes de arrancar la máquina virtual. La seleccionamos en la lista de la izquierda y pulsamos sobre el botón de **configuración**. En el menú de la izquierda nos vamos a donde pone **Almacenamiento**. Ahí localizamos el "*Controlador: IDE*" y debería de aparecer una unidad de CD que pone "*Vacío*". A la derecha donde pone "Atríbutos" pulsamos sobre el botón del disco que nos debería de desplegar un submenu, y pulsamos sobre "*seleccione un archivo de disco óptico virtual*". Buscamos la imagen de debian que hemos descargado antes "*debian-10.3.0-amd64-netins.iso*". Pulsamos sobre Abrir y luego en Aceptar. Estamos listos para arrancar la máquina virtual.

Iniciamos la máquina virtual con el CD y en las siguientes pantallas vamos a seleccionar.

* Graphical install (por que es más rápido y sencillo)
* Spanish - en la parte de idioma, a no ser que tengáis otra preferencia.
* España
* Español

Esperamos a que nos detecte el hardware. Y configuramos:

* Nombre de la máquina: **VMAMPDCK**
* Nombre de dominio: (en blanco)
* Clave de superusuario: **vmamp** (super mega segura)
* Nombre completo del nuevo usuario: **vmamp**
* Contraseña, bueno supongo que ya os la sabéis.
* Seleccionamos Península

Toca la parte del particionado. Aquí podemos hacerlo de diferentes modos. Lo que se esta montando en los clouds y es lo mejor evidentemente seleccionaríamos como "Guiado - utilizar el disco completo y configurar LVM". Pero para esta máquina virtual nos vale la primera opción "Guiado - utilizar todo el disco". A gusto del consumidor. Yo he seleccionado la segunda.

Seleccionamos el único disco duro que tenemos y luego la primera opción. En la siguiente pantalla pulsamos sobre "Sí" (que no estamos locos, que sabemos lo que hacemos) y le damos a Continuar. En la siguiente donde nos pregunta la cantidad, dejamos el valor que tiene por defecto y volvemos a pulsar sobre Continuar. Esto se esta poniendo un poco tedioso, pero volvemos a pulsar sobre "Sí" y continuar.

En este punto nos deberíamos de encontrar con el disco duro particionado y con el sistema base instalado. Ahora tenemos que instalar algunos paquete extras y configurar los repositorios.

* En la pantalla de "Desea analizar otro CD o DVD" seleccionamos "No". 
* País de la réplica de Debian: España (valor por defecto)
* Réplica de Debian: "deb.debian.org" (valor por defecto)
* Proxy: en blanco (yo por lo menos no uso)
* ¿Desea participar en la encuesta ... ?: No

Estamos en la etapa "Selección de programas", aquí vamos a dejar sólo las casillas marcadas:

1. SSH server
2. Utilidades estándar del sistema

No necesitamos escritorio y ocuparía demasiado espacio en el disco. Continuar para instalar los paquetes.

No desesperéis que ya hemos acabado de instalar el debian, tampoco ha sido muy difícil.

Instalar el cargador de arranque GRUB en un disco duro, le damos a que Sí y continuar. En la siguiente pantalla seleccionamos el disco duro que debería de ser /dev/sda.

Perfecto ahora toca reiniciar. Y ya debería de cargar el SO perfectamente.

Vamos a apagarlo y vamos a configurar el redireccionamiento de puertos. Esto creo que ya lo conocéis todo el mundo. Esta en:

​	Configuración > Red > Avanzadas > Reenvío de puertos

Yo he redireccionado los puertos 80, 22 y 3306. Como antes a gusto del consumidor.

Arrancamos la máquina y accedemos con el cliente SSH que más nos guste.

Vamos a instalar unos paquetes básicos, igual se me olvida alguno. Primero nos cambiamos a root.

```bash
su -
apt-get install -y vim git sudo php apt-transport-https ca-certificates curl gnupg2 software-properties-common
```

Instalación de **docker**. Para instalar docker no debemos instalarlo de los repositorios oficiales de debian, ya que los paquetes son muy viejos. Tenemos que hacer lo siguiente. (Recuerda que seguimos como root)

```bash
curl -fsSL https://download.docker.com/linux/debian/gpg | apt-key add -
add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable"
apt-get update
```

Si en a la hora de ejecutar el `apt-get update` tienes un error, ¡para!, algo estas haciendo mal. Continuamos.

```bash
apt-get install -y docker-ce
```

Perfecto ya tenemos docker instalado en nuestro sistema. Pero aun nos falta una tarea para terminar. Queremos que el usuario `vmamp` pueda ejecutar comandos `docker`.

```bash
usermod -aG docker vmamp
```

Ahora sólo falta comprobar que el usuario puede ejecutar comandos. Para eso sal de la sesión y vuelve a entrar, o ejecuta los siguientes comandos:

```bash
su - vmamp
docker -v
```

Listo, en estos momentos deberías de tener la versión 19.03.8



## 2. Instrucciones para crear los contenedores

Para estar alineados con el entorno de trabajo que nos vamos a encontrar en el cliente, vamos usar por convención el usuario: `vmamp`

Si no has seguido las instrucciones para crear la máquina virtual y usas otro usuario tenlo en cuenta a la hora de introducir las ordenes en el bash. En el `Dockerfile` en la línea 30 se crea un usuario para poder usarlo dentro del contenedor para que sea idéntico al de la máquina virtual. Es un patch que uso para evitar problemas de permisos entre el host y el contenedor. Tenlo en cuenta por si lo tienes que modificar antes de compilar las imágenes.

Lo primer que tienes que hacer es crear la carpeta que contendrá el proyecto, situarte en ella y clonar el repositorio:

```bash
mkdir -p /home/vmamp/projects/altia
cd /home/vmamp/projects/altia
git clone git@github.com:nicoalonso/formacion-altia.git .
```

Lo siguiente es levantar los contenedores, es un simple comando. Ten en cuenta que primero `docker` va a hacer un build para crear la imagen de PHP y luego levantará los contenedores.

```bash
cd /home/vmamp/projects/altia/docker
docker-compose up -d
```

El modificar -d es importante no lo omites. Os aparecerá mucha información en pantalla, es normal, no os asustéis. Al final de hacer el build os debería de aparecer algo como esto:

```bash
Creating docker_altia-db_1
Creating docker_altia-php_1
```

Si queremos comprobar si los contenedores están levantados, podemos ejecutar el siguiente comando:

```bash
docker ps -a | grep altia
```

Ahora tenemos que acceder al contenedor. Para eso vamos a ejecutar el siguiente comando. Esto lo que nos va a abrir es un `bash` dentro del contenedor.

```bash
docker exec -it docker_altia-php_1 /bin/bash
```

Os vais a dar cuenta de que estáis dentro del contenedor por el `prompt`. El host es un número hexadecimal. Por ejemplo en mi caso aparece:`root@dbb2c44727b8:/var/www#` donde `dbb2c44727b8` es como identifica docker al contenedor.

Como podéis comprobar estamos accediendo al contenedor como root. A mi en lo particular no me gusta, por que siempre hay problemas con los permisos de los ficheros a la hora de realizar el desarrollo. Por lo que suelo crear un usuario dentro del container. Podéis observarlo en el `Dockerfile` en la línea 30.

Para acceder al container con el usuario que hemos creado sería así:

```bash
docker exec -it --user=vmamp docker_altia-php_1 /bin/bash 
```

El siguiente paso es instalar los vendors, antiguamente Symfony los llamaba bundlers, viene a ser lo mismo. Para eso vamos a ejecutar el siguiente comando:

```bash
composer install
```

Nota: asegúrate estar en la carpeta `/var/www`

Y en principio ya estaría. Si abres un navegador apuntando a localhost debería de aparecerte una bonita página de bienvenida de **Symfony 4.4.7**. Recuerda redireccionar los puertos de tu máquina virtual al localhost.

