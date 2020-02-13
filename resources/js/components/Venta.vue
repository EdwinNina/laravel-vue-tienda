<template>
    <main>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ventas</li>
        </ol>
        <div>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ventas
                    <template v-if="listado == 1">
                        <button type="button" class="btn btn-primary" @click="mostrarDetalle()">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </template>
                    <template v-else>
                        <button type="button" class="btn btn-primary" @click="ocultarDetalle()">
                            <i class="fa fa-book"></i>&nbsp;Listar
                        </button>
                    </template>
                </div>
                <!-- Ejemplo de tabla Listado -->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                    <option value="tipo_comprobante">Tipo Comprobante</option>
                                    <option value="num_comprobante">Numero Comprobante</option>
                                    <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" 
                                        class="form-control" 
                                        placeholder="Texto a buscar" 
                                        v-model="buscar"
                                        @keyup.enter="listarVenta(1, buscar, criterio)"
                                    >
                                    <button type="submit" class="btn btn-primary" @click="listarVenta(1, buscar, criterio)">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th scope="col">Opciones</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Tipo Comprobante</th>
                                        <th scope="col">Serie Comproante</th>
                                        <th scope="col">Numero Comproante</th>
                                        <th scope="col">Fecha hora</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Impuesto</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr v-for="venta in ventas" :key="venta.id">
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" @click="verVenta(venta.id)">
                                            <i class="icon-eye"></i>
                                            </button>
                                            <template v-if="venta.estado == 'registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                            <button type="button" class="btn btn-info btn-sm" @click="verPdf(venta.id)">
                                            <i class="icon-doc"></i>
                                            </button>
                                        </td>
                                        <td>{{ venta.name }}</td>
                                        <td>{{ venta.nombre }}</td>
                                        <td>{{ venta.tipo_comprobante }}</td>
                                        <td>{{ venta.serie_comprobante }}</td>
                                        <td>{{ venta.num_comprobante }}</td>
                                        <td>{{ venta.fecha_hora }}</td>
                                        <td>{{ venta.total }}</td>
                                        <td>{{ venta.impuesto }}</td>
                                        <td>{{ venta.estado }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item"
                                    v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)">{{ page }}</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!-- Fin ejemplo de tabla Listado -->
                <!-- detalle venta -->
                <template v-else-if="listado == 0">
                    <div v-if="this.detalles.length == 0 || 
                        this.venta.cliente_id == 0 ||
                        this.venta.num_comprobante == 0"
                        class="col-md-12 mt-3">
                        <transition name="fade">
                            <div class="alert alert-danger" role="alert">
                                <p class="text-error text-center">Debes llenar todos los campos para registrar la venta</p> 
                            </div>
                        </transition>
                    </div>
                    <div class="card-body quitarPading">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="proveedor">Cliente <span class="text-danger">(*)</span></label>
                                    <v-select
                                        v-model="venta.cliente_id"
                                        :options="clientes"
                                        label="nombre"
                                        :reduce="cliente => cliente.id"
                                        placeholder="Seleccione un cliente"
                                        class="style-chooser"
                                        @search="seleccionarCliente"
                                    >
                                    <template slot="no-options">
                                        Escriba el nombre del cliente
                                    </template>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Impuesto</label>
                                <input type="text" class="form-control" v-model="venta.impuesto">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_comprobante">Tipo Comprobante <span class="text-danger">(*)</span></label>
                                    <select id="tipo_comprobante" class="custom-select" v-model="venta.tipo_comprobante">
                                        <option value="">Seleccione Comprobante</option>
                                        <option value="boleta">Boleta</option>
                                        <option value="factura">Factura</option>
                                        <option value="ticket">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="serie_comprobante">Serie Comprobante</label>
                                    <input type="text" id="serie_comprobante" class="form-control" v-model="venta.serie_comprobante" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="num_comprobante">Numero Comprobante <span class="text-danger">(*)</span></label>
                                    <input type="text" id="num_comprobante" class="form-control" v-model="venta.num_comprobante" placeholder="000x">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Producto <span class="text-danger" v-if="this.producto.product_id == 0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" 
                                            class="form-control" 
                                            v-model="producto.codigo" 
                                            @keypress.enter="buscarProducto()" 
                                            placeholder="Ingrese el producto"
                                        >
                                        <button class="btn btn-primary" @click="abrirModal()"><i class="fa fa-search"></i></button>
                                        <input type="text" class="form-control text-center" v-model="producto.nombre" readonly>
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Stock disponible del Producto</label>
                                    <input type="text" class="form-control" v-model="producto.stock" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Precio <span class="text-danger" v-if="this.producto.precio == 0">(*Seleccione)</span></label>
                                    <input type="number" step="any" class="form-control" v-model="producto.precio">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cantidad <span class="text-danger" v-if="this.producto.cantidad == 0">(*Seleccione)</span></label>
                                    <input type="number" class="form-control" v-model="producto.cantidad">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Descuento</label>
                                    <input type="number" class="form-control" v-model="descuento">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button 
                                        class="btn btn-success form-control btnAgregar"
                                        @click="agregarDetalle()"
                                    ><i class="icon-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="detalles.length">
                                        <tr v-for="(detalle, index) in detalles" :key="detalle.id">
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" @click="eliminarProductoDetalle(index)">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>{{ detalle.nombre}}</td>
                                            <td>
                                                <input type="number" class="form-control" v-model="detalle.precio">
                                            </td>
                                            <td>
                                                <span class="text-danger" v-show="detalle.cantidad > detalle.stock">Stock: {{ detalle.stock }}</span>
                                                <input type="number" class="form-control" v-model="detalle.cantidad">
                                            </td>
                                            <td>
                                                <span class="text-danger" v-show="detalle.descuento >= (detalle.precio * detalle.cantidad)">El descuento no puede ser mayor al subtotal</span>
                                                <input type="number" class="form-control" v-model="detalle.descuento">
                                            </td>
                                            <td>{{ detalle.precio * detalle.cantidad - detalle.descuento }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="5" align="right"><strong>Total Parcial</strong></td>
                                            <td>{{ totalParcial = (total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="5" align="right"><strong>Total Impuesto</strong></td>
                                            <td>{{ totalImpuesto = ((total * venta.impuesto) / (1 + venta.impuesto)).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="5" align="right"><strong>Total Neto</strong></td>
                                            <td>$ {{ total = calcularTotal}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">No hay productos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                                <button type="button" 
                                        class="btn btn-primary" 
                                        @click="registarVenta()"
                                        v-if="this.detalles.length > 0 && 
                                              this.venta.cliente_id != 0 && 
                                              this.venta.num_comprobante != 0"
                                        >Registrar Venta
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Ver venta -->
                <template v-else-if="listado == 2">
                    <div class="card-body quitarPading">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <p>{{ cliente }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Impuesto</label>
                                <p>{{ venta.impuesto }}</p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p>{{ venta.tipo_comprobante }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p>{{ venta.serie_comprobante }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero Comprobante</label>
                                    <p>{{ venta.num_comprobante }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="detalle in detalles" :key="detalle.id">
                                            <td>{{ detalle.nombre}}</td>
                                            <td>{{ detalle.precio }}</td>
                                            <td>{{ detalle.cantidad }}</td>
                                            <td>{{ detalle.descuento }}</td>
                                            <td>{{ detalle.precio * detalle.cantidad - detalle.descuento }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"><strong>Total Parcial</strong></td>
                                            <td>{{ totalParcial = (total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"><strong>Total Impuesto</strong></td>
                                            <td>{{ totalImpuesto = ((total * venta.impuesto)).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"><strong>Total Neto</strong></td>
                                            <td>$ {{ total }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="cerrarVerVenta()">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- fin venta -->
            </div>
        </div>
        <!--Inicio del modal agregarProducto -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true"
            :class="{'mostrar' : modal}">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioProducto">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" 
                                        class="form-control" 
                                        placeholder="Texto a buscar" 
                                        v-model="buscarProductos"
                                        @keyup.enter="listarProducto(buscarProductos, criterioProducto)"
                                    >
                                    <button type="submit" class="btn btn-primary" @click="listarProducto(buscarProductos, criterioProducto)">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Categoria</th>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>Precio Venta</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr v-for="producto in productosModal" :key="producto.id">
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" @click="agregarDetalleModal(producto)">
                                            <i class="icon-check"></i>
                                            </button> 
                                        </td>
                                        <td>{{ producto.category.nombre }}</td>
                                        <td>{{ producto.codigo }}</td>
                                        <td>{{ producto.nombre }}</td>
                                        <td>{{ producto.precio_venta }}</td>
                                        <td>{{ producto.stock }}</td>
                                        <td>
                                            <span class="badge badge-success" v-if="producto.condicion == '1'">activo</span>
                                            <span class="badge badge-warning" v-if="producto.condicion == '0'">desactivado</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    import vSelect from 'vue-select';
    import Swal from 'sweetalert2';
    export default {
        mounted () {
            this.listarVenta(1,'','num_comprobante');
        },
        data() {
            return {
                venta : {
                    cliente_id : 0,
                    tipo_comprobante: 'boleta',
                    serie_comprobante: '',
                    num_comprobante: 0,
                    impuesto: '0.18',
                },
                producto:{
                    product_id: 0,
                    codigo: '',
                    nombre: '',
                    precio: 0,
                    cantidad: 0,
                    stock: 0
                },
                ventas : [],
                detalles: [],
                clientes : [],
                productos: [],
                productosModal: [],
                totalParcial: 0.0,
                totalImpuesto: 0.0,
                total : 0.0,
                descuento: 0,
                cliente : '',
                listado: 1,
                modal : 0,
                tituloModal: '',
                pagination: {
                    'total'        : 0,
                    'current_page' : 0,
                    'per_page'     : 0,
                    'last_page'    : 0,
                    'from'         : 0,
                    'to'           : 0
                },
                offset: 3,
                criterio: 'num_comprobante',
                buscar : '',
                buscarProductos: '',
                criterioProducto: 'nombre',
            }
        },
        components:{ vSelect },
        computed: {
            isActived() {
                return this.pagination.current_page; 
            },
            pagesNumber(){
                if(!this.pagination.to){
                    return [];
                }
                let from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                
                let to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                let pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularTotal(){
                let resultado = 0;
                for(let i = 0; i < this.detalles.length ; i++){
                    resultado = resultado + (this.detalles[i].cantidad * this.detalles[i].precio - this.detalles[i].descuento);
                }
                return resultado;
            }
        },
        methods: {
            cambiarPagina(page, buscar, criterio){
                //actualiza la pagina actual
                this.pagination.current_page = page;
                //envia la peticion para visualizar la data de la pagina
                this.listarVenta(page, buscar, criterio)
            },
            async listarVenta(page,buscar,criterio) {
                let url = `ventas?page=${page}&buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.ventas = response.data.ventas.data;
                this.pagination = response.data.pagination;
            },
            async listarProducto(buscar,criterio) {
                let url = `producto/listarProductosVenta?buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.productosModal = response.data.data;
            },
            async seleccionarCliente(filtro){
                let url = `clientes/seleccionarCliente?filtro=${filtro}`;
                const response = await axios.get(url);
                this.clientes = response.data;
            },
            async buscarProducto(){
                let url = `producto/buscarProductoVenta?filtro=${this.producto.codigo}`;
                const response = await axios.get(url);
                this.productos = response.data;
                console.log(this.productos);
                if(this.productos.length > 0) {
                    this.producto.nombre = this.productos[0]['nombre'];
                    this.producto.product_id = this.productos[0]['id'];
                    this.producto.stock = this.productos[0]['stock'];
                    this.producto.precio = this.productos[0]['precio_venta'];
                } else{
                    this.producto.nombre = 'No existe el producto';
                    this.producto.product_id = 0;
                    this.producto.precio = 0;
                }
            },
            validarDuplicadoProductoDetalle(id){
                if(this.detalles.length > 0){
                    let duplicado = false;
                    for(let i = 0; i < this.detalles.length; i++){
                        if(this.detalles[i].product_id == id){
                            duplicado = true;
                        }
                    }
                    return duplicado;
                }
            },
            agregarDetalle(){
                if(this.producto.product_id == 0 || this.producto.precio == 0 || this.producto.cantidad == 0){
                }else{
                    if(this.validarDuplicadoProductoDetalle(this.producto.product_id)){
                        Swal.fire({
                            icon : 'error',
                            title: 'Error...',
                            text: 'El articulo ya fue agregado'
                        });
                        this.limpiarProductosDetalle();
                    }else{
                        if(this.producto.cantidad > this.producto.stock){
                            Swal.fire({
                                icon : 'error',
                                title: 'Error...',
                                text: 'No hay stock disponible'
                            });
                        }else{
                            this.detalles.push({
                                'product_id' : this.producto.product_id,
                                'nombre' : this.producto.nombre,
                                'precio' : this.producto.precio,
                                'cantidad' : this.producto.cantidad,
                                'stock' : this.producto.stock,
                                'descuento' : this.descuento
                            })
                            this.limpiarProductosDetalle();
                        }
                    }
                }
            },            
            agregarDetalleModal(productos){
                if(this.validarDuplicadoProductoDetalle(productos.id)){
                    Swal.fire({
                        icon : 'error',
                        title: 'Error...',
                        text: 'El articulo ya fue agregado'
                    });
                }else{
                    this.detalles.push({
                        'product_id' : productos.id,
                        'nombre' : productos.nombre,
                        'precio' : productos.precio_venta,
                        'cantidad' : 1,
                        'stock' : productos.stock,
                        'descuento' : 0
                    })
                    this.buscarProductos = '';
                }
            },
            eliminarProductoDetalle(index){
                this.detalles.splice(index, 1);
            },
            async registarVenta(){
                const response = await axios.post('venta/agregar',{
                    'cliente_id' : this.venta.cliente_id,
                    'tipo_comprobante' : this.venta.tipo_comprobante,
                    'num_comprobante' : this.venta.num_comprobante,
                    'serie_comprobante' : this.venta.serie_comprobante,
                    'impuesto' : this.venta.impuesto,
                    'descuento' : this.descuento,
                    'total' : this.total,
                    'data' : this.detalles
                });
                this.listado = 1;
                this.venta.cliente_id = 0;
                this.venta.tipo_comprobante= 'boleta';
                this.venta.serie_comprobante= '';
                this.venta.num_comprobante= 0;
                this.venta.impuesto= '0.18';
                this.limpiarProductosDetalle();
                this.detalles = [];
                this.listarVenta(1,'','num_comprobante');
            },
            desactivarVenta(id){
                Swal.fire({
                    title: 'Esta seguro de desactivar esta venta?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('venta/desactivar', {
                            'id' : id
                        })
                        .then( response => {
                            this.listarVenta(1,'','num_comprobante');
                            Swal.fire(
                            'Anulado!',
                            'La venta ha sido desactivado con exito',
                            'success'
                            )
                        })
                    }
                })
            },
            abrirModal(){
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios productos';
            },
            cerrarModal(){
                this.modal = 0;
            },
            mostrarDetalle(){
                this.listado = 0;
            },
            ocultarDetalle(){
                this.listado = 1;
                this.detalles = [];
            },
            limpiarProductosDetalle(){
                this.producto.product_id = 0;
                this.producto.codigo = '';
                this.producto.nombre = '';
                this.producto.cantidad = 0;
                this.producto.precio = 0;
                this.producto.stock = 0;
            },
            async verVenta(id){
                this.detalles = [];
                this.listado = 2;
                const venta = await axios.get(`venta/obtenerVenta?id=${id}`);
                console.log(venta.data);
                this.cliente = venta.data[0]['nombre'];
                this.venta.tipo_comprobante = venta.data[0]['tipo_comprobante'];
                this.venta.serie_comprobante = venta.data[0]['serie_comprobante'];
                this.venta.num_comprobante = venta.data[0]['num_comprobante'];
                this.venta.impuesto = venta.data[0]['impuesto'];
                this.total = venta.data[0]['total'];
                this.descuento = venta.data[0]['descuento'];
                const detalle = await axios.get(`venta/obtenerDetalleVenta?id=${id}`);
                this.detalles = detalle.data;
            },
            cerrarVerVenta(){
                this.listado = 1;
            },
            async verPdf(id){
                window.open(`venta/pdf/${id}`);               
            }
        },
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
        margin-top: 5em;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnAgregar{
            margin-top: 2rem;
        }
    }
    .style-chooser .vs__search::placeholder,
    .style-chooser .vs__dropdown-toggle,
    .style-chooser .vs__dropdown-menu {
        background: #dfe5fb;
        border: none;
        color: #394066;
        text-transform: lowercase;
        font-variant: small-caps;
    }

    .style-chooser .vs__clear,
    .style-chooser .vs__open-indicator {
        fill: #394066;
    }
    .quitarPading{
        padding-top: 0;
    }
    .quitarMargen{
        margin-top: 0;
    }
</style>
