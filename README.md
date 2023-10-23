Test-itarget-api
test-itarget

Passo a passo
Clone Repositório

git clone https://github.com/rodrigojustinodevs/test-api-itarget
cd test-itarget-api
Crie imagem docker

docker build -t nome-da-sua-imagem (test-itarget-api).
docker-compose up -d --force-recreate.
docker exec -it test-itarget-api-app bash 
Crie o Arquivo .env, variáveis de ambiente do arquivo .env está atualizada

cp .env.example .env
Instalar as dependências do projeto

composer install
Gerar a key do projeto Laravel

php artisan migrate
php artisan db:seed

php artisan key:generate

Acesse o projeto http://127.0.0.1:8004
