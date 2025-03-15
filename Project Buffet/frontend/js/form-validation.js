document.querySelector("form").addEventListener("submit", function (e) {
  const dishName = document
    .querySelector('input[name="dish_name"]')
    .value.trim();
  const price = document.querySelector('input[name="price"]').value.trim();

  if (!dishName || !price) {
    e.preventDefault();
    alert("Please fill in all required fields.");
  }

  if (isNaN(price)) {
    e.preventDefault();
    alert("Price must be a number.");
  }
});
