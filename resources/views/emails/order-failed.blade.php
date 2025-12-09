<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #dc2626;">Falha no Pagamento</h2>

        <p>Olá {{ $order->user->name }},</p>

        <p>Infelizmente, não conseguimos processar seu pagamento.</p>

        <div style="background-color: #fef2f2; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #dc2626;">
            <h3 style="margin-top: 0;">Detalhes do Pedido</h3>
            <p><strong>Número do Pedido:</strong> {{ $order->id }}</p>
            <p><strong>Status:</strong> Pagamento Falhou</p>

            <h4>Itens:</h4>
            <ul>
                @foreach($order->orderItems as $item)
                <li>{{ $item->product->name }} - R$ {{ $item->price_in_reais }}</li>
                @endforeach
            </ul>

            <p style="font-size: 18px; font-weight: bold;">
                Valor: R$ {{ $order->total_in_reais }}
            </p>
        </div>

        <p>Por favor, verifique os dados do seu cartão e tente novamente.</p>

        <p>Se o problema persistir, entre em contato com nosso suporte.</p>

        <p>Atenciosamente,<br>Equipe de Suporte</p>
    </div>
</body>
</html>
