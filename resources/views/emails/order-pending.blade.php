<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2563eb;">Pedido Recebido!</h2>

        <p>Olá {{ $order->user->name }},</p>

        <p>Recebemos seu pedido e estamos aguardando a confirmação do pagamento.</p>

        <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0;">Detalhes do Pedido</h3>
            <p><strong>Número do Pedido:</strong> {{ $order->id }}</p>
            <p><strong>Status:</strong> Aguardando confirmação</p>

            <h4>Itens:</h4>
            <ul>
                @foreach($order->orderItems as $item)
                <li>{{ $item->product->name }} - R$ {{ $item->price_in_reais }}</li>
                @endforeach
            </ul>

            <p style="font-size: 18px; font-weight: bold;">
                Total: R$ {{ $order->total_in_reais }}
            </p>
        </div>

        <p>Você receberá um email de confirmação assim que o pagamento for aprovado.</p>

        <p>Obrigado!</p>
    </div>
</body>
</html>
