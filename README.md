# Projeto_CN
Projeto da cadeira de mestrado computação em nuvem

Comando para eliminar os containers:
docker compose down -v

Comando para correr o docker
docker compose up -d --build

Comando para correr as migrações em laralel:
docker exec backend php artisan migrate

Ver as rotas:
docker compose exec backend php artisan route:list