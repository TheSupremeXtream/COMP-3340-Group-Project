document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('options-form');
    const quantityInput = document.getElementById('quantity');
    const basePriceInput = document.getElementById('base-price');
    const displayPrice = document.getElementById('display-price');
    const totalPrice = document.getElementById('total-price');
    const cartMsg = document.getElementById('cart-msg');
    const mainImage = document.getElementById('main-image');

    if (!form || !quantityInput || !basePriceInput || !displayPrice || !totalPrice) {
        return;
    }

    const basePrice = parseFloat(basePriceInput.value) || 0;
    const selectedDeltas = {};
    const defaultImage = mainImage ? mainImage.dataset.defaultImage : '';

    function updatePrice() {
        const qty = parseInt(quantityInput.value, 10) || 1;
        const delta = Object.values(selectedDeltas).reduce((sum, value) => sum + value, 0);
        const unit = basePrice + delta;
        const total = unit * qty;

        displayPrice.textContent = '$' + unit.toFixed(2);
        totalPrice.textContent = '$' + total.toFixed(2);
    }

    function updateImage() {
        if (!mainImage) return;
        let selectedImage = '';
        document.querySelectorAll('.option-btn.selected').forEach((button) => {
            if (button.dataset.type === 'color' && button.dataset.image) {
                selectedImage = 'images/' + button.dataset.image;
            }
        });
        mainImage.src = selectedImage || defaultImage;
    }

    function selectOption(button, type) {
        document.querySelectorAll(`.option-btn[data-type="${type}"]`).forEach((btn) => {
            btn.classList.remove('selected');
        });

        button.classList.add('selected');
        selectedDeltas[type] = parseFloat(button.dataset.delta || '0') || 0;
        updatePrice();
        updateImage();
    }

    function changeQty(amount) {
        const max = parseInt(quantityInput.max, 10) || 99;
        let value = parseInt(quantityInput.value, 10) || 1;

        value += amount;
        value = Math.max(1, Math.min(value, max));

        quantityInput.value = value;
        updatePrice();
    }

    document.querySelectorAll('.option-btn').forEach((button) => {
        button.addEventListener('click', () => {
            selectOption(button, button.dataset.type);
        });
    });

    document.querySelectorAll('.option-btn.selected').forEach((button) => {
        selectedDeltas[button.dataset.type] = parseFloat(button.dataset.delta || '0') || 0;
    });

    const decreaseBtn = document.getElementById('qty-decrease');
    const increaseBtn = document.getElementById('qty-increase');

    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', () => changeQty(-1));
    }

    if (increaseBtn) {
        increaseBtn.addEventListener('click', () => changeQty(1));
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (cartMsg) {
            cartMsg.style.display = 'block';

            setTimeout(() => {
                cartMsg.style.display = 'none';
            }, 3000);
        }
    });

    updatePrice();
    updateImage();
});