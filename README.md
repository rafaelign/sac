# SAC em Symfony PHP

## Instalação

- Download de dependências
```
composer install
```
- Configure a base no arquivo app/config/parameters.yml

- Configuração da base
```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```
- Preenchimento da base com Pedidos iniciais
```
Acessar o link /app_dev.php/seed ou 'Preencher Base' através do menu
```
