# Workshop de Testes - Beer and Code

Projeto Laravel desenvolvido para o workshop de testes do Beer and Code. Uma aplicação de e-commerce simples com CRUD de produtos e fluxo completo de checkout integrado com Stripe.

## Sobre o Projeto

Este projeto demonstra práticas modernas de teste em Laravel, incluindo:

- **Testes de Feature**: Controllers, rotas, autenticação e autorização
- **Testes de Unidade**: Services, policies e lógica de negócio
- **Testes de Integração**: Fluxos completos com banco de dados
- **Browser Testing**: Testes E2E com Pest v4
- **Mocking**: APIs externas (Stripe, ViaCEP)
- **Cobertura de Código**: XDebug/PCOV para análise de cobertura

### Funcionalidades Implementadas

- **CRUD de Produtos**: Gerenciamento completo de produtos (apenas para admins)
- **Fluxo de Checkout**: Carrinho, endereço de entrega e pagamento com Stripe
- **Sistema de Pedidos**: Criação e rastreamento de pedidos
- **Autenticação**: Login, registro e recuperação de senha (Laravel Breeze)
- **Autorização**: Policies para controle de acesso (apenas admins podem gerenciar produtos)
- **Integração Stripe**: Pagamentos via Laravel Cashier v16
- **Webhooks**: Processamento de eventos de pagamento do Stripe
- **Jobs Assíncronos**: E-mails de confirmação de pedido
- **Consulta CEP**: Integração com API ViaCEP

## Stack Tecnológica

- **PHP**: 8.4.15
- **Laravel**: 12.x
- **Database**: PostgreSQL
- **Testing**: Pest 4 + PHPUnit 12
- **Payment**: Laravel Cashier (Stripe) v16
- **Frontend**: Tailwind CSS v3 + Alpine.js v3
- **Environment**: Laravel Sail (Docker)

## Requisitos

### Para Desenvolvimento

- Docker + Docker Compose (via Laravel Sail)
- Conta Stripe (para testes de pagamento)

### Para Cobertura de Testes

**IMPORTANTE**: Para gerar relatórios de cobertura de código, você precisa ter **XDebug 3.0+** ou **PCOV** instalado.

#### Instalando XDebug com Sail

O Laravel Sail já inclui o XDebug, mas você precisa habilitá-lo:

1. Publique o arquivo Docker do Sail (se ainda não fez):
```bash
vendor/bin/sail artisan sail:publish
```

2. Adicione a variável de ambiente no `.env`:
```env
SAIL_XDEBUG_MODE=coverage
```

3. Reconstrua os containers:
```bash
vendor/bin/sail build --no-cache
vendor/bin/sail up -d
```

#### Instalando PCOV (alternativa mais rápida)

PCOV é mais rápido que XDebug para cobertura de código. Para usá-lo com Sail:

1. Acesse o container:
```bash
vendor/bin/sail shell
```

2. Instale o PCOV:
```bash
pecl install pcov
docker-php-ext-enable pcov
```

3. Reinicie o container

## Instalação

### 1. Clone o Repositório

```bash
git clone <repository-url>
cd workshop-tests
```

### 2. Instale as Dependências

**Se você NÃO tem PHP instalado localmente** (recomendado para usuários Docker):

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

**Se você JÁ tem PHP 8.4 instalado localmente**:

```bash
composer install
```

### 3. Configure o Ambiente

```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure:

```env
APP_NAME="Workshop Tests"
APP_URL=http://localhost

# Database (PostgreSQL via Sail)
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=workshop_tests
DB_USERNAME=sail
DB_PASSWORD=password

# Stripe (obtenha em https://dashboard.stripe.com/test/apikeys)
STRIPE_KEY=pk_test_your_key
STRIPE_SECRET=sk_test_your_secret
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# XDebug para cobertura (opcional)
SAIL_XDEBUG_MODE=coverage
```

### 4. Gere a Chave da Aplicação

```bash
vendor/bin/sail artisan key:generate
```

### 5. Suba os Containers

```bash
vendor/bin/sail up -d
```

### 6. Execute as Migrations

```bash
vendor/bin/sail artisan migrate
```

### 7. (Opcional) Popule o Banco com Dados de Teste

```bash
vendor/bin/sail artisan db:seed
```

### 8. Compile os Assets

```bash
vendor/bin/sail npm install
vendor/bin/sail npm run build
```

## Rodando o Projeto

### Iniciar o Servidor

```bash
vendor/bin/sail up -d
```

Acesse: http://localhost

### Abrir no Navegador

```bash
vendor/bin/sail open
```

### Parar o Servidor

```bash
vendor/bin/sail stop
```

## Executando os Testes

### Rodar Todos os Testes

```bash
vendor/bin/sail artisan test
```

### Rodar Testes de um Arquivo Específico

```bash
vendor/bin/sail artisan test tests/Feature/ProductsTest.php
```

### Rodar com Filtro (nome do teste)

```bash
vendor/bin/sail artisan test --filter=test_admin_can_create_product
```

### Rodar com Saída Detalhada

```bash
vendor/bin/sail artisan test --parallel
```

## Cobertura de Testes

### Gerar Relatório de Cobertura (HTML)

Com XDebug ou PCOV habilitado:

```bash
vendor/bin/sail artisan test --coverage --coverage-html=coverage
```

O relatório será gerado em `coverage/index.html`.

### Cobertura no Terminal

```bash
vendor/bin/sail artisan test --coverage
```

### Cobertura Mínima Obrigatória

```bash
vendor/bin/sail artisan test --coverage --min=80
```

Este comando falhará se a cobertura for menor que 80%.

## Estrutura do Projeto

```
workshop-tests/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CheckoutController.php      # Fluxo de checkout
│   │   │   ├── ProductsController.php      # CRUD de produtos
│   │   │   └── CepController.php           # Consulta CEP
│   │   └── Requests/
│   │       ├── CheckoutPaymentRequest.php  # Validação de pagamento
│   │       └── StoreProductRequest.php     # Validação de produto
│   ├── Models/
│   │   ├── User.php                        # Usuário (com Billable)
│   │   ├── Product.php                     # Produto
│   │   ├── Order.php                       # Pedido
│   │   └── OrderItem.php                   # Item do pedido
│   ├── Policies/
│   │   └── ProductPolicy.php               # Autorização de produtos
│   ├── Services/
│   │   ├── CheckoutService.php             # Lógica de checkout
│   │   └── CepService.php                  # Integração ViaCEP
│   ├── Jobs/
│   │   ├── SendOrderPendingEmail.php
│   │   ├── SendOrderPaidEmail.php
│   │   └── SendOrderFailedEmail.php
│   ├── Listeners/
│   │   └── HandleStripeWebhook.php         # Processamento de webhooks
│   └── OrderStatus.php                     # Enum de status do pedido
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   └── migrations/
├── routes/
│   ├── web.php                             # Rotas da aplicação
│   └── console.php
├── tests/
│   ├── Feature/                            # Testes de integração
│   ├── Unit/                               # Testes unitários
│   └── Pest.php                            # Configuração do Pest
└── resources/
    └── views/
        ├── products/                       # Views de produtos
        ├── checkout/                       # Views de checkout
        └── emails/                         # Templates de e-mail
```

## Modelos e Relacionamentos

### User
- `hasMany(Order)` - Um usuário pode ter vários pedidos
- `is_admin` - Flag para identificar administradores
- Usa trait `Billable` do Laravel Cashier

### Product
- `name` (string) - Nome do produto
- `price` (integer) - Preço em centavos

### Order
- `belongsTo(User)` - Pertence a um usuário
- `hasMany(OrderItem)` - Tem vários itens
- `total` (integer) - Total em centavos
- `status` (enum) - pending, paid, failed
- `payment_id` (string) - ID do pagamento no Stripe

### OrderItem
- `belongsTo(Order)` - Pertence a um pedido
- `belongsTo(Product)` - Referência ao produto
- `quantity` (integer) - Quantidade
- `price` (integer) - Preço no momento da compra (em centavos)

## Fluxos Principais

### 1. CRUD de Produtos (Admin)

```
GET  /products           → Lista produtos
GET  /products/create    → Formulário de criação
POST /products           → Cria produto
GET  /products/{id}/edit → Formulário de edição
PUT  /products/{id}      → Atualiza produto
DEL  /products/{id}      → Remove produto
```

**Autorização**: Apenas usuários com `is_admin = true` podem criar/editar/deletar.

### 2. Fluxo de Checkout

```
1. GET /checkout/{product}     → Exibe formulário de checkout
2. POST /checkout/pay          → Processa pagamento via Stripe
   ↓
   CheckoutService::processPayment()
   ↓
   Cria Order (status: pending)
   ↓
   Cria OrderItem
   ↓
   Dispara SendOrderPendingEmail
   ↓
3. Webhook do Stripe recebido
   ↓
   HandleStripeWebhook listener
   ↓
   Atualiza status do Order (paid/failed)
   ↓
   Dispara SendOrderPaidEmail ou SendOrderFailedEmail
   ↓
4. Redireciona para:
   - GET /checkout/success/{payment_id}  (sucesso)
   - GET /checkout/failed/{payment_id}   (falha)
```

## Tópicos do Workshop

Durante o workshop, abordaremos:

1. **Fundamentos de Testing**
   - Anatomia de um teste Pest
   - Arrange, Act, Assert
   - RefreshDatabase e DatabaseTransactions

2. **Testes de Feature**
   - Testando rotas e controllers
   - Autenticação em testes
   - Assertions de resposta

3. **Testes de Unidade**
   - Testando services isoladamente
   - Mocking de dependências externas
   - Testando policies

4. **Factories e Seeders**
   - Criando factories para testes
   - Usando states e sequences
   - Relacionamentos em factories

5. **Mocking**
   - Mockando Stripe API
   - Mockando HTTP clients (ViaCEP)
   - Fake de Jobs e Notifications

6. **Cobertura de Código**
   - Configurando XDebug/PCOV
   - Interpretando relatórios
   - Definindo metas de cobertura

7. **Browser Testing (Pest v4)**
   - Testes E2E do checkout
   - Interagindo com Stripe Elements
   - Screenshots e debugging

## Comandos Úteis

### Artisan

```bash
# Listar comandos disponíveis
vendor/bin/sail artisan list

# Limpar caches
vendor/bin/sail artisan cache:clear
vendor/bin/sail artisan config:clear
vendor/bin/sail artisan route:clear

# Verificar rotas
vendor/bin/sail artisan route:list

# Tinker (REPL)
vendor/bin/sail artisan tinker
```

### Laravel Pint (Code Style)

```bash
# Formatar código
vendor/bin/sail bin pint
```

### NPM

```bash
# Modo desenvolvimento (watch)
vendor/bin/sail npm run dev

# Build para produção
vendor/bin/sail npm run build
```

## Testando Pagamentos Stripe

Use os cartões de teste do Stripe:

- **Sucesso**: `4242 4242 4242 4242`
- **Falha**: `4000 0000 0000 0002`
- **Requer 3D Secure**: `4000 0027 6000 3184`
- **CVC**: qualquer 3 dígitos
- **Data**: qualquer data futura
- **CEP**: qualquer 5 dígitos

Mais cartões: https://docs.stripe.com/testing#cards

## Configurando Webhooks do Stripe (Opcional)

Para testar webhooks localmente, use o Stripe CLI:

```bash
# Instalar Stripe CLI
# https://stripe.com/docs/stripe-cli

# Fazer login
stripe login

# Encaminhar webhooks para o projeto
stripe listen --forward-to http://localhost/stripe/webhook

# Copie o webhook secret exibido e adicione no .env
STRIPE_WEBHOOK_SECRET=whsec_...
```

## Troubleshooting

### "Vite manifest not found"

Execute:
```bash
vendor/bin/sail npm run build
```
ou mantenha rodando:
```bash
vendor/bin/sail npm run dev
```

### "Access denied for user"

Verifique as credenciais do banco no `.env` e certifique-se que os containers estão rodando:
```bash
vendor/bin/sail up -d
```

### "Class 'XDebug' not found"

XDebug não está instalado. Siga as instruções na seção "Para Cobertura de Testes".

### Permissões no Linux

Se tiver problemas com permissões:
```bash
sudo chown -R $USER:$USER .
```

## Contribuindo

Este é um projeto educacional. Sinta-se à vontade para:
- Reportar bugs
- Sugerir melhorias
- Adicionar mais testes de exemplo
- Melhorar a documentação

## Licença

Este projeto é open-source e está disponível sob a licença MIT.

## Recursos Adicionais

- [Laravel Documentation](https://laravel.com/docs)
- [Pest Documentation](https://pestphp.com/docs)
- [Laravel Cashier Documentation](https://laravel.com/docs/billing)
- [Stripe Testing Guide](https://docs.stripe.com/testing)
- [Laravel Sail Documentation](https://laravel.com/docs/sail)

---

**Beer and Code** - Workshop de Testes com Laravel
