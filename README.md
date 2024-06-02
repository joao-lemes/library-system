# Library System

Projeto para gerenciamento de uma biblioteca


## Dados técnicos

```
PHP: 8.3
Laravel: 11.9
Mysql: 8.0
Nginx: 1.25
RabbitMQ: 3.0
```


## Configuração

### Copiar arquivo .env

Faça uma cópia dos arquivos `.env.example` para `.env` que estão na raiz do projeto


### Subir docker

```
docker-compose up -d
```

### Instalar dependências

```
docker-compose exec app composer install
```


### Gerar key do laravel

```
docker-compose exec app php artisan key:generate
```

### Gerar JWT Secret

```
docker-compose exec app php artisan jwt:secret
```

### Migrar banco de dados

```
docker-compose exec app php artisan migrate
```

### Popular banco de dados

```
docker-compose exec app php artisan db:seed
```

### Adicionar permissão para a pasta storage

```
sudo chmod 777 -R backend/storage/
```

## Comandos extras

### Limpar o cache

```
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan cache:clear
```

### Rollback no banco de dados

```
docker-compose exec app php artisan migrate:rollback --step=1
```

### Executar testes

```
docker-compose exec app vendor/bin/phpunit
```

## Links úteis

### RabbitMQ

RabbitMQ local: <a href="http://localhost:15672">http://localhost:15672</a>
