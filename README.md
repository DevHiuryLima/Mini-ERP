# Mini ERP Laravel

Simple mini ERP for product, inventory, coupon and order management, built with Laravel 11.

## Features

* CRUD de Produtos e Estoques (com variações)
* Carrinho em Sessão com regras de frete
* Validação de CEP via ViaCEP
* Aplicação de Cupons com valor mínimo e validade
* Finalização de Pedidos com geração de `Pedido` e `ItemPedido`
* Webhook de atualização de status de pedido (cancelar ou atualizar)
* Envio de email ao atualizar status do pedido

---

## Tecnologias

* PHP 8.2+
* Laravel 11
* MySQL
* Bootstrap 5
* jQuery

---

## Instalação

1. Clone o repositório:

   ```bash
   git clone https://github.com/SEU_USUARIO/mini-erp-laravel.git
   cd Mini-ERP
   ```

2. Instale dependências via Composer:

   ```bash
   composer install
   ```

3. Copie o `.env.example` e configure:

   ```bash
   cp .env.example .env
   ```

    * Defina `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` para conectar ao MySQL.
    * Configure `MAIL_` para envio de emails.
    * Defina também o `WEBHOOK_TOKEN=seu_token_secreto`.

4. Gere a chave da aplicação:

   ```bash
   php artisan key:generate
   ```

5. Execute as migrations:

   ```bash
   php artisan migrate
   ```

6. Inicie o servidor:

   ```bash
   php artisan serve
   ```

---

## Uso

* Acesse `http://localhost:8000/produtos` para gerenciar produtos e estoques.
* Adicione produtos ao carrinho via botão **Comprar**.
* Acesse **Carrinho** para calcular frete e finalizar pedido.
* No carrinho, insira um email e clique em **Finalizar**.
* Acesse **Pedidos** para visualizar pedidos realizados.

---

## Regras de Frete

* Subtotal entre R\$52,00 e R\$166,59: frete fixo R\$15,00.
* Subtotal maior que R\$200,00: frete grátis.
* Outros casos: frete R\$20,00.

---

## Webhook de Pedidos

Endpoint para atualizar status externo de pedidos:

```
POST /api/webhook/pedidos
Headers:
  X-Webhook-Token: seu_token_secreto
Body JSON:
  {
    "id": <pedido_id>,
    "status": "pendente|processando|enviado|entregue|cancelado"
  }
```

* Se `status` for **cancelado**, o pedido é removido.
* Caso contrário, o campo `status` do pedido é atualizado e email de notificação é enviado.

---

## Licença

Este projeto está sob a licença [MIT](LICENSE).

---

Boa implementação! :)
