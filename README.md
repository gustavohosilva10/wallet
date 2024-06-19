## Wallet API

 É uma aplicação Laravel que permite realizar transferências entre usuários, garantindo a integridade das operações financeiras.

## Pré-requisitos
 Antes de executar este projeto, certifique-se de ter instalado:
    Docker
    Docker Compose
## Instalação e Execução
1.Clone o repositório:

git clone https://github.com/seu-usuario/wallet.git
cd wallet

2.Configuração do ambiente:
Copie o arquivo de exemplo .env.example para .env e configure as variáveis de ambiente necessárias:

3.Construa os contêineres Docker:
    docker-compose build

4.Inicie os contêineres Docker:
    docker-compose up -d

5.docker-compose exec app composer install

6.Execute as migrações do banco de dados:
    docker-compose exec app php artisan migrate

7.Gere a chave de aplicação Laravel:
    docker-compose exec app php artisan key:generate

8.Acesse a aplicação:
A aplicação estará disponível em http://localhost:8080

## Documentação da API
Para acessar a documentação da API, utilize o endpoint /api-docs após iniciar a aplicação. Por exemplo: http://localhost:8080/api-docs

## Autores
Gustavo henrique @gustavohosilva10

