const products = [
    {
        id: 1,
        category: "hamburguesas",
        name: "Burger Fuego",
        description: "Carne angus, cheddar, cebolla crispy y salsa de la casa.",
        price: 22.9,
        image:
            "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=900&q=80"
    },
    {
        id: 2,
        category: "pizzas",
        name: "Pizza Andina",
        description: "Queso mozzarella, pepperoni y vegetales asados.",
        price: 35.5,
        image:
            "https://images.unsplash.com/photo-1548365328-8b849e1e2f2d?auto=format&fit=crop&w=900&q=80"
    },
    {
        id: 3,
        category: "bebidas",
        name: "Limonada frozen",
        description: "Limon natural, hierbabuena y toque de gengibre.",
        price: 9.9,
        image:
            "https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=900&q=80"
    },
    {
        id: 4,
        category: "postres",
        name: "Brownie volcan",
        description: "Brownie tibio con helado de vainilla y fudge.",
        price: 14.5,
        image:
            "https://images.unsplash.com/photo-1606313564200-e75d5e30476a?auto=format&fit=crop&w=900&q=80"
    },
    {
        id: 5,
        category: "almuerzos",
        name: "Lomo saltado",
        description: "Filete salteado, papas crocantes y arroz graneado.",
        price: 28,
        image:
            "https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=900&q=80"
    },
    {
        id: 6,
        category: "combos",
        name: "Combo Local",
        description: "Hamburguesa clasica, papas y bebida mediana.",
        price: 29.9,
        image:
            "https://images.unsplash.com/photo-1571091718767-18b5b1457add?auto=format&fit=crop&w=900&q=80"
    }
];

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

orderForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const formData = new FormData(orderForm);
    const requiredFields = ["nombre", "telefono", "direccion", "producto", "cantidad", "entrega"];
    const hasEmpty = requiredFields.some((field) => !formData.get(field));
    if (hasEmpty) {
        formMsg.textContent = "Completa todos los campos obligatorios.";
        formMsg.style.color = "#8c2618";
        return;
    }
    formMsg.textContent = "Pedido enviado correctamente.";
    formMsg.style.color = "#2b8742";
    orderForm.reset();
});

renderMenu(products);
loadProductOptions();
