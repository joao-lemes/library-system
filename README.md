# Library System

Projeto para gerenciamento de uma biblioteca


## Dados técnicos

```
PHP: 8.3
Laravel: 11.9
Mysql: 8.0
RabbitMQ: 3.0
```


## Configuração

### Copiar arquivo .env

Faça uma cópia dos arquivos `.env.example` para `.env` que estão na raiz do projeto, configurar os dados para envio de email


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
sudo chmod 777 -R storage/
```

### Limpar o cache

```
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan cache:clear
```

## Comandos extras

### Rollback no banco de dados

```
docker-compose exec app php artisan migrate:rollback --step=1
```

### Executar testes

```
docker-compose exec app vendor/bin/phpunit
```

### Executar fila

#### Enviar notificações de empréstimos
```
docker-compose exec app php artisan queue:work --queue=send_loan_notification
```

## Links úteis

### Documentação Postman

Documentação: [https://documenter.getpostman.com/view/14196384/2sA3QnhuBt](https://documenter.getpostman.com/view/14196384/2sA3Qy4oBc)

### ER do Database
![library](https://github.com/joao-lemes/library-system/assets/56354575/b781bc64-0d17-4419-93ac-bf5d91358f3d)

### RabbitMQ

RabbitMQ local: <a href="http://localhost:15672">http://localhost:15672</a>

## To Do
Adicionar cache com Redis e sistema de log com ELK para monitoramento com Kibana. Estas tarefas estavam planejadas, mas não foram concluídas devido ao tempo limitado.
