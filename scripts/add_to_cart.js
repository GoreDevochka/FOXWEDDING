function addToCart(dressId) {
    let quantity = quantityInput.value;

    // Проверяем, что значения dressId и quantity корректные
    console.log('Sending to server:', { dressId: dressId, quantity: quantity });

    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ dressId: dressId, quantity: quantity })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Товар добавлен в корзину!');
            } else {
                alert('Произошла ошибка: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка при отправке данных:', error);
        });
}
