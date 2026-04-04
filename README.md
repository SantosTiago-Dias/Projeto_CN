# Projeto_CN
Projeto da cadeira de mestrado computação em nuvem

Comando para eliminar os containers:
docker compose down -v

Comando para correr o docker
docker compose up -d --build

Comando para correr as migrações em larabel:
docker exec backend php artisan migrate

Ver as rotas:
docker compose exec backend php artisan route:list

Portas Usadas

Frontend:80

Backend:8000

webSocket:6001/80