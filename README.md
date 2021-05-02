# Introducao

Bem vinda a Documentação do SEL.

# Instalacao

## Pre-requisitos

-   Git
-   Docker
-   Docker-Composer

## Download e Instalacao da Imagem do Docker

Entre com o comando na pasta de sua prefêrencia:

1. Entre na sua pasta:  
   `cd seu_diretorio/sua_pasta/`

2. Faça o clone do repositório para a sua pasta (Digite a sua senha de acesso ao BitBucket quando for requisitado.):  
   `https://github.com/aryangomes/sel.git`

3. Entre na pasta que foi criada:  
   `cd sel`

4. Construa a imagem do Docker:  
   `docker-compose -p sel -f .docker/docker-compose.yml up -d --build`

## Inciar Docker do Apache e do MariaDB(Mysql)

### Apache

-   `docker start sel-apache`

### MariaDB(Banco de dados Mysql)

-   `docker start sel-mariadb`

## Acessar o prompt do Apache

-   `docker exec -it sel-apache bash -c "/docker-entrypoint.sh"`

## Acessar o prompt do MariaDB(Banco de dados Mysql)

-   `docker exec -it sel-mariadb bash`

## Instalar dependencias do Laravel

**Lembre-se de executar os seguintes comandos pelo prompt do Apache!**

1. Crie o arquivo de variáveis de desenvolvimento do Laravel copiando ele de um examplo já pré definido:  
   `cp .env.example .env`

2. Instale as dependências do Laravel com o composer:  
   `composer install`

3. Gere a chave da aplicação local do Laravel:  
   `php artisan key:generate`

## Migrar/Gerar banco de dados

**Copie e cole as seguintes variáveis no arquivo** **_.env_**

1. Copie e cole as configurações de variáveis do banco de dados:  
   `DB_CONNECTION=mysql`  
    `DB_HOST=db`  
    `DB_PORT=3306`  
    `DB_DATABASE=sel`  
    `DB_USERNAME=sel`  
    `DB_PASSWORD=`

2. Copie e cole as configurações de variáveis do padrão da senha dos usuários(as senhas padrões podem ser alteradas):  
   `APP_URL=http://localhost:8042`

3. Rode o comando para gerar o banco de dados com algumas informações já previamente criadas:  
   `php artisan migrate --seed`

4. Instale o pacote Passport para a geração do token para a autenticação do Usuário:  
   `php artisan passport:install`

## Comandos Uteis

-   Deletar e criar novamente o banco de dados:  
     `php artisan migrate:fresh`

-   Deletar e criar novamente o banco de dados, populando o banco com algumas informações previamente cadastradas:  
     `php artisan migrate:fresh --seed`

-   Popular o banco de dados com algumas informações previamente cadastradas:  
     `php artisan db:seed`

-   Dar permissão de escrita e leitura para a pasta storage():  
     `chmod -R 775 storage/`
