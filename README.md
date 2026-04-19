# Projeto_CN

Projeto da cadeira de Computação em Nuvem (Mestrado).

Este repositório contém uma aplicação full-stack composta por:

- Backend em **Laravel** (PHP) com API, jobs, filas e broadcast.
- Frontend em **Vue 3** usando **Vite**.
- Orquestração com **Docker Compose** para executar serviços como MySQL, Redis, MinIO, Adminer e um proxy reverso.

## Arquitetura do Projeto

- `backend/` - API Laravel.
- `frontend/` - Vue 3.
- `database/` - Inicialização do MySQL.
- `data/` - Volume persistente do Nginx Proxy Manager.
- `minio_storage/` - Armazenamento de objetos MinIO para arquivos e imagens.

## Tecnologias Principais

- Laravel
- Vue 3
- Vite
- MySQL
- Redis
- MinIO
- Nginx Proxy Manager
- Docker / Docker Compose

## Serviços em Docker Compose

- `frontend` - Build da aplicação Vue no container.
- `backend` - Servidor Laravel com ambiente PHP e migrations.
- `queue-worker` - Worker Laravel para processar jobs.
- `reverb` - Serviço de websocket/broadcast em Laravel (porta 6001).
- `db` - Base de dados MySQL.
- `redis` - Cache e ajuda no worker.
- `adminer` - Ferramenta de administração da base de dados em web.
- `minio` - Armazenamento de objetos compatível com S3.
- `proxy-manager` - Nginx Proxy Manager.

## Portas Usadas

- Frontend: `80`
- Backend: `8000`
- WebSocket / reverb: `6001`
- Adminer: `8081`
- Proxy Manager UI: `81`
- MinIO Console: `9001`

## Configuração Inicial

1. Duplicar o ficheiro .env.example e guardar como .env

2. Ajustar as variáveis de ambiente necessárias em `.env`.

3. Gerar uma key para o 'OPENWEATHER_API_KEY' atraves https://home.openweathermap.org/users/sign_in

4. Iniciar o Docker:

```sh
docker compose up -d --build
```


- Inspecionar banco via Adminer:

Acesse `http://localhost:8081`.

## Observações

- As `migrações` correm automaticamente
- O serviço `create-bucket` cria automaticamente um bucket público `images` no MinIO.
- O container `queue-worker` processa jobs em background usando `php artisan queue:work`.
- Ajuste as variáveis `MINIO_ROOT_USER` e `MINIO_ROOT_PASSWORD` em `.env` para o MinIO.

## Objetivo

Esta aplicação serve como um projeto acadêmico para demonstrar uma solução cloud-native que integra API, frontend moderno, filas, armazenamento de objetos e infraestrutura em contêineres.
