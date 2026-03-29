// Wait for the page to finish loading
document.addEventListener('DOMContentLoaded', () => {
    // Get references to the product detail page elements
    const form = document.getElementById('options-form');
    const quantityInput = document.getElementById('quantity');
    const basePriceInput = document.getElementById('base-price');
    const displayPrice = document.getElementById('display-price');
    const totalPrice = document.getElementById('total-price');
    const cartMsg = document.getElementById('cart-msg');
    const mainImage = document.getElementById('main-image');
    
    // Stop running if important elements are missing
    if (!form || !quantityInput || !basePriceInput || !displayPrice || !totalPrice) {
        return;
    }

    // Get the base price from the data attribute
    const basePrice = parseFloat(basePriceInput.value) || 0;
    // Store the price changes for selected options
    const selectedDeltas = {};
    // Store the default product image
    const defaultImage = mainImage ? mainImage.dataset.defaultImage : '';

    // Update the displayed total price
    function updatePrice() {
        const qty = parseInt(quantityInput.value, 10) || 1;
        // Calculate the final total price
        const delta = Object.values(selectedDeltas).reduce((sum, value) => sum + value, 0);
        const unit = basePrice + delta;
        // Update the total price display
        const total = unit * qty;

        // Update the displayed prices
        displayPrice.textContent = '$' + unit.toFixed(2);
        totalPrice.textContent = '$' + total.toFixed(2);
    }

    // Update the main product image when a color option is selected
    function updateImage() {
        if (!mainImage) return;
        let selectedImage = '';
        document.querySelectorAll('.option-btn.selected').forEach((button) => {
            if (button.dataset.type === 'color' && button.dataset.image) {
                selectedImage = 'images/' + button.dataset.image;
            }
        });
        // Use the selected image if available, otherwise keep the default image
        mainImage.src = selectedImage || defaultImage;
    }

    // Select an option and update the product price/image
    function selectOption(button, type) {
        // Remove the selected class from all buttons in the same option group
        document.querySelectorAll(`.option-btn[data-type="${type}"]`).forEach((btn) => {
            btn.classList.remove('selected');
        });

        // Mark the clicked option as selected
        button.classList.add('selected');
        // Save the price adjustment for the selected option
        selectedDeltas[type] = parseFloat(button.dataset.delta || '0') || 0;
        // Refresh the displayed price and image
        updatePrice();
        updateImage();
    }

    // Increase or decrease the selected quantity
    function changeQty(amount) {
        const max = parseInt(quantityInput.max, 10) || 99;
        let value = parseInt(quantityInput.value, 10) || 1;

        value += amount;
        value = Math.max(1, Math.min(value, max));

        quantityInput.value = value;
        // Update the displayed total price
        updatePrice();
    }

    // Add click handlers to each option button
    document.querySelectorAll('.option-btn').forEach((button) => {
        button.addEventListener('click', () => {
            selectOption(button, button.dataset.type);
        });
    });
    
    // Store any option that is already selected when the page loads
    document.querySelectorAll('.option-btn.selected').forEach((button) => {
        selectedDeltas[button.dataset.type] = parseFloat(button.dataset.delta || '0') || 0;
    });

    // Get the quantity control buttons
    const decreaseBtn = document.getElementById('qty-decrease');
    const increaseBtn = document.getElementById('qty-increase');

    // Reduce quantity when the minus button is clicked
    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', () => changeQty(-1));
    }

    // Increase quantity when the plus button is clicked
    if (increaseBtn) {
        increaseBtn.addEventListener('click', () => changeQty(1));
    }

    // Get the cart bell sound effect
    const bell = document.getElementById('bell');

    // Handle Add to Cart form submission
    form.addEventListener('submit', (event) => {
        // Prevent the page from reloading
        event.preventDefault();

        // Play the bell sound if it exists
        if (bell) {
            bell.currentTime = 0;
            bell.play();
        }

        // Show the confirmation message
        if (cartMsg) {
            cartMsg.style.display = 'block';
            // Hide the confirmation message after 3 seconds
            setTimeout(() => {
                cartMsg.style.display = 'none';
            }, 3000);
        }
    });

    // Set the initial price and image when the page first loads
    updatePrice();
    updateImage();
});