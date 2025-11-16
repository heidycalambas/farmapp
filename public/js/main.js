/**
 * FarmApp - JavaScript Principal
 */

// Agregar producto al carrito
document.addEventListener('DOMContentLoaded', function() {
    // Manejar agregar al carrito desde catálogo
    const botonesAgregar = document.querySelectorAll('.agregar-carrito');
    botonesAgregar.forEach(btn => {
        btn.addEventListener('click', function() {
            const productoId = this.getAttribute('data-producto-id');
            const precio = this.getAttribute('data-precio');
            mostrarModalCarrito(productoId, precio);
        });
    });
    
    // Formulario de agregar al carrito desde detalle
    const formAgregarDetalle = document.getElementById('formAgregarCarritoDetalle');
    if (formAgregarDetalle) {
        formAgregarDetalle.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            agregarAlCarrito(formData);
        });
    }
    
    // Formulario modal de agregar al carrito
    const formAgregarModal = document.getElementById('formAgregarCarrito');
    if (formAgregarModal) {
        formAgregarModal.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            agregarAlCarrito(formData);
        });
    }
    
    // Actualizar cantidad en carrito
    const cantidadInputs = document.querySelectorAll('.cantidad-input');
    cantidadInputs.forEach(input => {
        input.addEventListener('change', function() {
            const index = this.getAttribute('data-index');
            const cantidad = this.value;
            const precio = this.getAttribute('data-precio');
            actualizarCantidadCarrito(index, cantidad, precio);
        });
    });
    
    // Eliminar item del carrito
    const botonesEliminar = document.querySelectorAll('.eliminar-item');
    botonesEliminar.forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            eliminarDelCarrito(index);
        });
    });
    
    // Vaciar carrito
    const btnVaciar = document.getElementById('vaciar-carrito');
    if (btnVaciar) {
        btnVaciar.addEventListener('click', function() {
            if (confirm('¿Estás seguro de vaciar el carrito?')) {
                vaciarCarrito();
            }
        });
    }
    
    // Cerrar modales
    const closes = document.querySelectorAll('.close');
    closes.forEach(close => {
        close.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });
});

function mostrarModalCarrito(productoId, precio) {
    const modal = document.getElementById('modalCarrito');
    if (modal) {
        document.getElementById('modal_producto_id').value = productoId;
        document.getElementById('modal_cantidad').value = 1;
        modal.style.display = 'block';
    }
}

function agregarAlCarrito(formData) {
    const baseUrl = typeof BASE_URL_JS !== 'undefined' ? BASE_URL_JS : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    fetch(baseUrl + '/index.php?action=carrito_agregar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Cerrar modal si existe
            const modal = document.getElementById('modalCarrito');
            if (modal) {
                modal.style.display = 'none';
            }
            // Recargar página para actualizar carrito
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al agregar producto al carrito');
    });
}

function actualizarCantidadCarrito(index, cantidad, precio) {
    const baseUrl = typeof BASE_URL_JS !== 'undefined' ? BASE_URL_JS : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    const formData = new FormData();
    formData.append('index', index);
    formData.append('cantidad', cantidad);
    
    fetch(baseUrl + '/index.php?action=carrito_actualizar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar subtotal en la fila
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (row) {
                const subtotalCell = row.querySelector('.subtotal');
                if (subtotalCell) {
                    const nuevoSubtotal = (cantidad * precio).toFixed(2);
                    subtotalCell.textContent = '$' + nuevoSubtotal;
                }
            }
            // Recargar para actualizar total
            location.reload();
        } else {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar carrito');
    });
}

function eliminarDelCarrito(index) {
    if (!confirm('¿Eliminar este producto del carrito?')) {
        return;
    }
    
    const baseUrl = typeof BASE_URL_JS !== 'undefined' ? BASE_URL_JS : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    const formData = new FormData();
    formData.append('index', index);
    
    fetch(baseUrl + '/index.php?action=carrito_eliminar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar producto');
    });
}

function vaciarCarrito() {
    const baseUrl = typeof BASE_URL_JS !== 'undefined' ? BASE_URL_JS : window.location.origin + window.location.pathname.replace(/\/[^/]*$/, '');
    fetch(baseUrl + '/index.php?action=carrito_vaciar', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al vaciar carrito');
    });
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

