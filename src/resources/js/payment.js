function updatePaymentMethod() {
    const paymentSelect = document.getElementById('paymentSelect');
    const paymentMethod = document.getElementById('paymentMethod');
    paymentMethod.textContent = paymentSelect.value;
    }