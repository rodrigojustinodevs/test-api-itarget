Test-itarget-api
test-itarget

Passo a passo
Clone Repositório

```sh
git clone https://github.com/rodrigojustinodevs/test-api-itarget
```
```sh
cd test-itarget-api
```
Crie imagem docker
```sh
docker build -t nome-da-sua-imagem (test-itarget-api).
```
```sh
docker-compose up -d --force-recreate.
```
```sh
docker exec -it test-itarget-api-app bash 
```

Crie o Arquivo .env, variáveis de ambiente do arquivo .env está atualizada
```sh
cp .env.example .env
```
Instalar as dependências do projeto
```sh
composer install
```
Gerar a key do projeto Laravel
```sh
php artisan migrate
```
```sh
php artisan db:seed
```
```sh
php artisan key:generate
```
Acesse o projeto http://127.0.0.1:8004
