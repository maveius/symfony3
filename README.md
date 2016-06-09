Rest Api
======

Symfony 3.0 REST API Project Recruitment Test Task
## Task
Your task is to create api for TODO list

GET - list of todos

POST - create new todo

PUT - edit todo item

DELETE - delete todo item

PATCH - to complete todo item

```
GET /todo
PUT /todo/{id}
POST /todo
DELETE /todo/{id}
PATCH /todo/{id}
```

Every todo item have

name - max 255 chars cannot be null

description - text type can be null

deadline - dateTime type cannot be null, only date from future

completed - boolean type cannot be null

To test api you could use Postman.

(https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop)

or any other similar app.

Remember to create Entity, Form and Validation for this collection.

API autodocumentation should be avalible at (http://localhost.dev:8080/doc)

On job interview we perform code review for your code.

## Composer
You need Composer so grab instruction from

(https://getcomposer.org/)

All future commands are assumes composer installed globally

## PHP 7
You need to install php interpreter if you dont heve already :)

### Windows
(http://windows.php.net/qa/)

1. Grab Thread Safe Version for your architecture

2. Right-click on a My Computer icon

3. Click Properties

4. Click Advanced system settings from the left nav

5. Click Advanced tab

6. Click Environment Variables button

7. In the System Variables section, select Path (case-insensitive) and click Edit button

Add a semi-colon (;) to the end of the string, then add the full file system path of your PHP installation (e.g. C:\php\php7)
8. Keep clicking OK etc until all dialog boxes have disappeared

9. Close your command prompt and open it again

10. You should be able to type

```
php -v
```

### MAC OS X
PHP 7.0.0 can be installed using homebrew:
```
brew tap homebrew/dupes
brew tap homebrew/versions
brew tap homebrew/homebrew-php
brew install php70
```
Or you can install it via Liip's php-osx tool:

```
curl -s http://php-osx.liip.ch/install.sh | bash -s 7.0
```

### Linux
(http://www.cyberciti.biz/faq/ubuntu-linux-14-04-install-php7-using-apt-get-command/)

We don't need Nginx part

## Docker
Install Docker from one of instruction

(https://docs.docker.com/linux/)

(https://docs.docker.com/windows/)

(https://docs.docker.com/mac/)

Now We start docker
```
docker-compose build
docker-compose up
```
if everything goes green you should have see all containers started just type

```
docker ps
```
We are interested in php nginx and mysql containers.

Copy mysql CONTAINER_ID

```
docker inspect MYSQL_CONTAINER_ID
```
Now copy ip address of that container

## Install Vendors
Just Type
```
composer install
```
Don't mind post install errors (if you have php < 7).

Your db ip is copied MYSQL_CONTAINER_ID and password is password.

Everything else is defaults from parameters.yml.dist

## Add line in HOST file
add this line to host file in your system
```
127.0.0.1 localhost.dev
```

### Windows
Windows 10, Windows 8, Windows 7, and Windows Vista use User Account Control (UAC), so Notepad must be run as Administrator.

#### Windows 10 and 8
1. Press the Windows key.

2. Type Notepad in the search field.

3. In the search results, right-click Notepad and select Run as administrator.

4. From Notepad, open the following file: c:\Windows\System32\Drivers\etc\hosts

5. Make the necessary changes to the file.

6. Click File > Save to save your changes.

### Linux
1. Open a terminal window.

2. Open the hosts file in a text editor (you can use any text editor) by typing the following line:
```
sudo nano /etc/hosts
```

3. Enter your domain user password.

4. Make the necessary changes to the file.

5. Press Control-x.

6. When asked if you want to save your changes, answer y

### Mac OS X
1. Open Applications > Utilities > Terminal.

2. Open the hosts file by typing the following line in the terminal window:
```
sudo nano /private/etc/hosts
```
3. Type your domain user password when prompted.

4. Edit the hosts file.

The file contains some comments (lines starting with the # symbol), and some default hostname mappings (for example, 127.0.0.1 – local host). Add your new mappings after the default mappings.

6. Save the hosts file by pressing Control+x and answering y.

7. Make your changes take effect by flushing the DNS cache with the following command:
```
dscacheutil -flushcache
```
The new mappings should now take effect.
