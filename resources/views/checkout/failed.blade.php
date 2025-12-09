<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pagamento Falhou
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <svg class="mx-auto h-16 w-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-4 text-2xl font-bold text-gray-900">Falha no Pagamento</h3>
                        <p class="mt-2 text-gray-600">Não foi possível processar seu pagamento</p>
                    </div>

                    @if($order)
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="font-semibold text-lg mb-4">Detalhes</h4>
                        <div class="space-y-2">
                            @foreach($order->orderItems as $item)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $item->product->name }}</span>
                                <span class="font-medium">R$ {{ number_format($item->price, 2, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mt-6 space-y-3">
                        @if($order)
                        <a href="{{ route('checkout.form', $order->orderItems->first()->product) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-3 px-4 rounded">
                            Tentar Novamente
                        </a>
                        @endif
                        <a href="{{ route('products.index') }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center font-bold py-3 px-4 rounded">
                            Voltar para Produtos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
