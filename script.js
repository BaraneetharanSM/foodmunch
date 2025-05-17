document.addEventListener('DOMContentLoaded', () => {
    // ... (Get elements - buttons, cart table, etc.)

    const cart = []; // Your cart array

    function updateCartSummary() {
        // ... (Your existing code to update cart table and total)
    }

    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            // ... (Your existing code to add to cart)
            updateCartSummary();
        });
    });

    emptyCartButton.addEventListener('click', () => {
        // ... (Your existing code to empty cart)
        updateCartSummary();
    });

    // Image Lightbox (Keep this)
    // ...

    // Basic Search (Filtering by Title)
    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        const menuItems = document.querySelectorAll('.menu-item-card');

        menuItems.forEach(item => {
            const title = item.querySelector('.menu-card-title').textContent.toLowerCase();
            if (title.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    updateCartSummary(); // Initial cart rendering
});
backButton.addEventListener('click', () => {
  if (window.history.length > 1) { // Check if there's a previous page
    window.history.back();
  } else {
    // Handle the case where there's no history (e.g., redirect to a default page)
    window.location.href = "index.html"; // Or any other default page
  }
});
document.getElementById('orderButton').addEventListener('click', function() {
    window.location.href = 'order.php';
});