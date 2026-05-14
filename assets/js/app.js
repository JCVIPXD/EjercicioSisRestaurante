// Productos cargados desde BD via PHP (ver index.php)
const products = window.menuProducts || [];

const menuGrid = document.getElementById("menuGrid");
const chips = document.querySelectorAll(".chip");
const productSelect = document.getElementById("producto");
const orderForm = document.getElementById("orderForm");
const formMsg = document.getElementById("formMsg");

function formatPrice(value) {
    return new Intl.NumberFormat("es-PE", {
        style: "currency",
        currency: "PEN"
    }).format(value);
}

function renderMenu(items) {
    menuGrid.innerHTML = items
        .map(
            (item, index) => `
      <div class="col-12 col-md-6 col-lg-4 reveal" style="animation-delay:${index * 60}ms">
        <article class="card-food" data-category="${item.category}">
          <div class="photo" style="background-image:url('${item.image}')" aria-label="${item.name}"></div>
          <div class="p-3 d-grid gap-2">
            <p class="mb-0 text-uppercase small fw-bold text-secondary">${item.category}</p>
            <h3 class="h5 mb-0">${item.name}</h3>
            <p class="mb-1 text-secondary">${item.description}</p>
            <div class="d-flex justify-content-between align-items-center gap-2">
              <span class="price-tag">${formatPrice(item.price)}</span>
              <button class="btn btn-main btn-sm quick-order" data-id="${item.id}" type="button">Realizar pedido</button>
            </div>
          </div>
        </article>
      </div>
    `
        )
        .join("");
}

function loadProductOptions() {
    productSelect.innerHTML =
        '<option value="">Seleccione producto</option>' +
        products
            .map(
                (item) =>
                    `<option value="${item.id}">${item.name} - ${formatPrice(item.price)}</option>`
            )
            .join("");
}

function setActiveChip(button) {
    chips.forEach((chip) => chip.classList.remove("active"));
    button.classList.add("active");
}

chips.forEach((chip) => {
    chip.addEventListener("click", () => {
        const filter = chip.dataset.filter;
        setActiveChip(chip);
        if (filter === "all") {
            renderMenu(products);
            return;
        }
        const filtered = products.filter((item) => item.category === filter);
        renderMenu(filtered);
    });
});

menuGrid.addEventListener("click", (event) => {
    const button = event.target.closest(".quick-order");
    if (!button) {
        return;
    }
    productSelect.value = button.dataset.id;
    document.getElementById("pedido").scrollIntoView({ behavior: "smooth" });
});

// La validacion y el guardado del pedido se manejan en PHP (POST a index.php)

renderMenu(products);
loadProductOptions();
