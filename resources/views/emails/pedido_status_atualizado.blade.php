<!DOCTYPE html>
<html>
<head>
    <title>Atualização do Status do Seu Pedido</title>
</head>
<body>
<h1>Olá!</h1>
<p>Houve uma atualização no status do seu pedido número {{ $pedido->id }}.</p>
<p>O novo status é: <strong>{{ ucfirst($novoStatus) }}</strong>.</p>

<p>Você pode verificar os detalhes do seu pedido em nossa loja.</p>

<p>Obrigado!</p>
</body>
</html>
