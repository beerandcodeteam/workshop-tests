<?php

namespace App;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Paid => 'Pago',
            self::Failed => 'Falhou',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Paid => 'green',
            self::Failed => 'red',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            self::Paid => 'bg-green-100 text-green-800 border-green-300',
            self::Failed => 'bg-red-100 text-red-800 border-red-300',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Pending => 'Seu pedido está aguardando confirmação do pagamento.',
            self::Paid => 'Seu pagamento foi confirmado com sucesso!',
            self::Failed => 'Não foi possível processar o pagamento.',
        };
    }
}
