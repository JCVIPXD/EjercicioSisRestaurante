function setupSearch(inputId, tableSelector) {
    const input = document.getElementById(inputId);
    const rows = document.querySelectorAll(`${tableSelector} tbody tr`);
    if (!input || !rows.length) {
        return;
    }

    input.addEventListener("input", () => {
        const term = input.value.trim().toLowerCase();
        rows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? "" : "none";
        });
    });
}

setupSearch("searchPedido", "#tablaPedidos");
setupSearch("searchCliente", "#tablaClientes");
setupSearch("searchProducto", "#tablaProductos");
