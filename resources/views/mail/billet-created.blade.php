<div>
    <h1>Novo boleto disponível</h1>
    <p>Olá, {{ $debt->name }}</p>
    <p>Você possuí um novo boleto disponível para pagamento.</p>
    <p><a href="{{ route('debts.billet', $debt) }}">Visualizar boleto</a></p>
</div>
