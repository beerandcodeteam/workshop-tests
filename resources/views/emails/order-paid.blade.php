<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #16a34a;">Pagamento Confirmado!</h2>

        <p>Olá {{ $order->user->name }},</p>

        <p>Ótimas notícias! Seu pagamento foi confirmado com sucesso.</p>

        <div style="background-color: #f0fdf4; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #16a34a;">
            <h3 style="margin-top: 0;">Detalhes do Pedido</h3>
            <p><strong>Número do Pedido:</strong> {{ $order->id }}</p>
            <p><strong>Status:</strong> Pago ✓</p>

            <h4>Itens:</h4>
            <ul>
                @foreach($order->orderItems as $item)
                <li>{{ $item->product->name }} - R$ {{ $item->price_in_reais }}</li>
                @endforeach
            </ul>

            <p style="font-size: 18px; font-weight: bold;">
                Total Pago: R$ {{ $order->total_in_reais }}
            </p>
        </div>

        <p>Seu pedido está sendo processado e você receberá atualizações em breve.</p>

        <p>Obrigado pela sua compra!</p>
    </div>
</body>
</html>
