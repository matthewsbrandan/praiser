#!/bin/sh
# ativa o modo de manutenção 
php artisan down
# atualiza o código fonte 
git pull
# atualizar dependências do PHP 
composer install --no-interaction --no-dev --prefer-dist
# --no-interaction Não faça nenhuma pergunta interativa 
# --no-dev Desativa a instalação de pacotes require-dev. 
# --prefer-dist Força a instalação do pacote dist mesmo para versões dev.
# atualiza banco de dados 
php artesão migrar --force
# --force Necessário para execução em produção.
# pare o modo de manutenção 
php artisan up