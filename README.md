# 🚀 API Finance Bot

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4.svg?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20.svg?logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED.svg?logo=docker&logoColor=white)

Uma API escalável projetada para gerenciar transações financeiras, orçamentos e relatórios, servindo como o motor principal para integração com interfaces de bot.

Muito além de um simples CRUD, este projeto foi desenhado com foco em **Clean Code** e **Arquitetura Pragmática**, isolando as regras de negócio complexas e garantindo alta manutenibilidade, segurança e performance na manipulação de dados financeiros.

---

## 🧠 Arquitetura e Design Patterns

Para garantir que a aplicação possa crescer sem acumular débito técnico, a arquitetura foi dividida em camadas lógicas distintas, respeitando o princípio de Responsabilidade Única (SRP):

### 1. Camada de Roteamento (Routes & Middleware)
As rotas da API foram rigorosamente estruturadas seguindo os padrões REST. 
* Implementação de middlewares para validação de tokens de segurança.

### 2. Controllers (A Camada de Apresentação HTTP)
Os Controllers foram mantidos extremamente "magros". A única responsabilidade desta camada é:
* Receber o Request HTTP.
* Repassar os dados validados (via Form Requests) para a camada de Service.
* Retornar o Response formatado (JSON/Resource), padronizando a comunicação com o front-end.

### 3. Camada de Serviços (Service Layer) - *O Coração da Aplicação*
É aqui que a verdadeira complexidade acontece. Para evitar o anti-pattern de "Fat Models" ou "Fat Controllers", toda a lógica de negócio financeira foi abstraída em **Services**.
* **Processamento Transacional:** Cálculo dinâmico de balanços, categorização automática de despesas e geração de métricas.
* **Integração com Bot:** Formatação de payloads complexos para disparo de mensagens e interpretação de comandos financeiros recebidos via Webhook.
* **Tratamento de Exceções:** Regras de fallback para garantir que nenhuma transação financeira seja perdida ou duplicada em caso de falhas de comunicação.

### 4. Models e Camada de Dados (Data Layer)
* Uso correto de relacionamentos (One-to-Many, Belongs-to) para rastrear transações, categorias e usuários.
* Mutators e Accessors para garantir que os valores financeiros sejam sempre armazenados e trafegados no formato correto, sendo esse formato o integer (evitando problemas de ponto flutuante).
* Uso de ENUMS para garantir consistências de alguns dados imprescindíveis.

### 5. Código 100% escrito em inglês para garantir consistência e seguir o padrão do mercado
---

## 🛠️ Tecnologias e Ferramentas

* **Back-end:** PHP / Laravel
* **Banco de Dados:** PostgreSQL / MySQL
* **Infraestrutura:** Docker & Docker Compose (Ambiente padronizado via containers)
* **Integração:** REST APIs, Webhooks, fluxos de automação.

---

## ⚙️ Fluxo de Funcionamento (Bot Integration)

1. O usuário interage com o Bot informando uma despesa (ex: "Gastei 50 reais com almoço").
2. O provedor do Bot envia um payload estruturado para o nosso endpoint.
3. O Controller recebe, valida a integridade da requisição e aciona o `TransactionService`.
4. O Service interpreta os dados, cruza com as categorias no Banco de Dados via Models, e consolida o saldo.
5. A API devolve uma resposta de sucesso processada, que o Bot transforma na mensagem de confirmação para o usuário.

---

## 🚀 Como rodar o projeto localmente (via Laravel Sail)

Este projeto utiliza **Laravel Sail** para gerenciar a infraestrutura Docker de forma fluida e nativa, garantindo que o ambiente de desenvolvimento seja uma réplica do ambiente de produção.

1. Clone o repositório e acesse a pasta:
```
git clone [https://github.com/seu-usuario/api-finance-bot.git](https://github.com/seu-usuario/api-finance-bot.git)
cd api-finance-bot
```

2. Acesse a pasta do projeto e copie o arquivo de ambiente:
```cp .env.example .env```

3. Instale as dependências da aplicação. (Se você não tiver o PHP/Composer instalados localmente, pode usar um container temporário do próprio Sail para isso):
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```


4. Suba os containers com o Docker:
`./vendor/bin/sail up -d`

5. Gere a chave de criptografia do Laravel e rode as migrations para criar as tabelas no banco de dados:
```
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```


---
Desenvolvido com foco em resolver problemas reais de gestão através de código limpo e integrações inteligentes.







