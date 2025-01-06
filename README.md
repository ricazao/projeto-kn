# Processamento de boletos

Este projeto consiste em um sistema para processamento de débitos e geração de boletos.

## Ambientes

O projeto está organizado para utilizar os seguintes containers:

-   **app:** Container principal responsável por receber as requisições HTTP
-   **db:** Banco de dados MySQL
-   **redis:** Redis
-   **queue-worker:** Container responsável por executar os comandos de processamento das filas
-   **schedule-worker:** Container responsável por executar o comando de processamento das tarefas Cron

## Visão geral

-   Foi utilizado no projeto a biblioteca [Laravel Actions](https://www.laravelactions.com/) para poder organizar as regras de negócio. Esta biblioteca é útil pois permite criar apenas uma classe contendo a lógica e a partir dela é possível executar comandos, jobs, listeners entre outros.
-   O endpoint que recebe o arquivo CSV salva o arquivo no servidor e despacha um job responsável por ler o conteúdo do arquivo. Os registros são persistidos no banco. A aplicação então roda um Cron para poder pegar todos os débitos que não possuem boletos e ir gradualemente processando os boletos.
-   Caso o usuário tente acessar um débito que ainda não possui boleto gerado, a aplicação gera este boleto em tempo de execução.

## Instalação

1. Clone o repositório:

```
git clone git@github.com:ricazao/projeto-kn.git
```

2. No diretório raiz do projeto, execute o comando para subir os containers:

```
docker compose up -d --build
```

3. Aguarde até que todos os containers estejam prontos. No processo de build, a aplicação irá executar os comandos para instalação das bibliotecas via composer e migrações.

## Testes

Os testes podem ser executados de dentro do container da aplicação:

```
docker compose exec app bash
php artisan test
```
