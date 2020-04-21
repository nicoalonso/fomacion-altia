# Formaciones Altia

Esqueleto básico para realizar las formaciones de Altia (05/2020).



Consiste en dos contenedores.

1. Apache + PHP 7.4 y un esqueleto de Symfony 4
2. Base de datos MariaDb v10.4



## Instrucciones para instalar la máquina virtual

Si no vas a usar una máquina virtual sáltate este paso.





## Instrucciones para crear los contenedores

Para estar alineados con el entorno de trabajo que nos vamos a encontrar en el cliente, vamos usar por convención el usuario: `vmamp`

Si no has seguido las instrucciones para crear la máquina virtual y usas otro usuario tenlo en cuenta a la hora de introducir las ordenes en el bash. En el `Dockerfile` en la línea 30 se crea un usuario para poder usarlo dentro del contenedor para que sea idéntico al de la máquina virtual. Es un patch que uso para evitar problemas de permisos entre el host y el contenedor. Tenlo en cuenta por si lo tienes que modificar antes de compilar las imágenes.

Lo primer que tienes que hacer es crear la carpeta que contendrá el proyecto, situarte en ella y clonar el repositorio:

```bash
mkdir /home/vmamp/projects/altia
cd /home/vmamp/projects/altia
git clone git@github.com:nicoalonso/fomacion-altia.git .
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

