# SAC in Symfony

## Instalação

1. Download de dependências
```
composer install
```
2. Configure a base no arquivo app/config/parameters.yml
3. Configuração da base
```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```
4. Preenchimento da base com Pedidos iniciais
```
Acessar o link /app_dev.php/seed ou 'Preencher Base' através do menu
```
