<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Checkout - {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">Produto</h3>
                        <p class="mt-2">{{ $product->name }}</p>
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                    </div>

                    <form id="checkout-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div>
                            <h3 class="text-lg font-medium mb-4">Endereço de Entrega</h3>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">CEP</label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="cep-input"
                                            name="address[zip]"
                                            placeholder="00000-000"
                                            maxlength="9"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        >
                                        <div id="cep-loading" class="hidden absolute right-3 top-1/2 transform -translate-y-1/2">
                                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div id="cep-error" class="text-red-600 text-sm mt-1"></div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Rua</label>
                                        <input
                                            type="text"
                                            id="street-input"
                                            name="address[street]"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Número</label>
                                        <input
                                            type="text"
                                            id="number-input"
                                            name="address[number]"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        >
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Cidade</label>
                                        <input
                                            type="text"
                                            id="city-input"
                                            name="address[city]"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Estado (UF)</label>
                                        <input
                                            type="text"
                                            id="state-input"
                                            name="address[state]"
                                            maxlength="2"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm uppercase"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium mb-4">Pagamento</h3>
                            <div id="card-element" class="p-3 border rounded-md"></div>
                            <div id="card-errors" class="text-red-600 text-sm mt-2"></div>
                        </div>

                        <div>
                            <button type="submit" id="submit-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                                Finalizar Pagamento
                            </button>
                        </div>

                        <div id="error-message" class="text-red-600 text-sm"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // CEP functionality
        const cepInput = document.getElementById('cep-input');
        const cepLoading = document.getElementById('cep-loading');
        const cepError = document.getElementById('cep-error');
        const streetInput = document.getElementById('street-input');
        const cityInput = document.getElementById('city-input');
        const stateInput = document.getElementById('state-input');
        const numberInput = document.getElementById('number-input');

        // Máscara de CEP
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
        });

        // Buscar CEP quando completo
        cepInput.addEventListener('blur', async function() {
            const cep = this.value.replace(/\D/g, '');

            if (cep.length !== 8) {
                return;
            }

            cepError.textContent = '';
            cepLoading.classList.remove('hidden');
            streetInput.value = '';
            cityInput.value = '';
            stateInput.value = '';

            try {
                const response = await fetch(`/api/cep/${cep}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    cepError.textContent = data.error || 'Erro ao buscar CEP';
                    return;
                }

                streetInput.value = data.street || '';
                cityInput.value = data.city || '';
                stateInput.value = data.state || '';

                if (data.street) {
                    numberInput.focus();
                }
            } catch (error) {
                cepError.textContent = 'Erro ao buscar CEP. Verifique sua conexão.';
            } finally {
                cepLoading.classList.add('hidden');
            }
        });

        // Stripe
        const stripe = Stripe('{{ config('cashier.key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card', {
            hidePostalCode: true
        });
        cardElement.mount('#card-element');

        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        const form = document.getElementById('checkout-form');
        const submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            submitButton.disabled = true;
            submitButton.textContent = 'Processando...';

            const {paymentMethod, error} = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'Finalizar Pagamento';
                return;
            }

            const formData = new FormData(form);
            formData.append('payment_method_id', paymentMethod.id);

            const response = await fetch('{{ route('checkout.pay') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const result = await response.json();

            if (result.status === 'success' || result.redirect_url) {
                window.location.href = result.redirect_url;
            } else if (result.status === 'requires_action') {
                const {error: confirmError} = await stripe.confirmCardPayment(result.client_secret);
                if (confirmError) {
                    document.getElementById('error-message').textContent = confirmError.message;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Finalizar Pagamento';
                } else {
                    window.location.href = '/checkout/success/' + result.payment_id;
                }
            } else {
                document.getElementById('error-message').textContent = result.message || 'Erro ao processar pagamento';
                submitButton.disabled = false;
                submitButton.textContent = 'Finalizar Pagamento';
            }
        });
    </script>
</x-app-layout>
