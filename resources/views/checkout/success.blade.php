<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pagamento Confirmado
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-6">
                        @if($order->status === \App\OrderStatus::Paid)
                            <svg class="mx-auto h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-2xl font-bold text-gray-900">PEDIDO CONFIRMADO!</h3>
                        @elseif($order->status === \App\OrderStatus::Pending)
                            <svg class="mx-auto h-16 w-16 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-2xl font-bold text-gray-900">PEDIDO RECEBIDO</h3>
                        @else
                            <svg class="mx-auto h-16 w-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-2xl font-bold text-gray-900">FALHA NO PAGAMENTO</h3>
                        @endif

                        <div class="mt-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border {{ $order->status->badgeClasses() }}">
                                {{ $order->status->label() }}
                            </span>
                        </div>
                        <p class="mt-2 text-gray-600">{{ $order->status->description() }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="font-semibold text-lg mb-4">Resumo do Pedido</h4>

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pedido:</span>
                                <span class="font-medium">{{ $order->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $order->user->email }}</span>
                            </div>
                            @foreach($order->orderItems as $item)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $item->product->name }}</span>
                                <span class="font-medium">R$ {{ $item->price_in_reais }}</span>
                            </div>
                            @endforeach
                            <div class="flex justify-between text-lg font-bold border-t pt-2">
                                <span>Total:</span>
                                <span>R$ {{ $order->total_in_reais }}</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('products.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-3 px-4 rounded">
                                Voltar para Produtos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
