<template>
    <main>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ingresos</li>
        </ol>
        <div>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ingresos
                    <template v-if="listado == 1">
                        <button type="button" class="btn btn-primary" @click="mostrarDetalle()">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </template>
                    <template v-else>
                        <button type="button" class="btn btn-primary" @click="ocultarDetalle()">
                            <i class="fa fas-book"></i>&nbsp;Listar
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
                                    <option value="num_documento">CI</option>
                                    </select>
                                    <input type="text" 
                                        class="form-control" 
                                        placeholder="Texto a buscar" 
                                        v-model="buscar"
                                        @keyup.enter="listarIngreso(1, buscar, criterio)"
                                    >
                                    <button type="submit" class="btn btn-primary" @click="listarIngreso(1, buscar, criterio)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th scope="col">Opciones</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Proveedor</th>
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
                                    <tr v-for="ingreso in ingresos" :key="ingreso.id">
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" @click="verIngreso(ingreso.id)">
                                            <i class="icon-eye"></i>
                                            </button>
                                            <template v-if="ingreso.estado == 'registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td>{{ ingreso.name }}</td>
                                        <td>{{ ingreso.nombre }}</td>
                                        <td>{{ ingreso.tipo_comprobante }}</td>
                                        <td>{{ ingreso.serie_comprobante }}</td>
                                        <td>{{ ingreso.num_comprobante }}</td>
                                        <td>{{ ingreso.fecha_hora }}</td>
                                        <td>{{ ingreso.total }}</td>
                                        <td>{{ ingreso.impuesto }}</td>
                                        <td>{{ ingreso.estado }}</td>
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
                <!-- detalle ingreso -->
                <template v-else-if="listado == 0">
                    <div v-if="this.detalles.length == 0 || 
                        this.ingreso.provider_id == 0 ||
                        this.ingreso.num_comprobante == 0"
                        class="col-md-12 mt-3">
                        <transition name="fade">
                            <div class="alert alert-danger" role="alert">
                                <p class="text-error text-center">Debes llenar todos los campos para registrar el ingreso</p> 
                            </div>
                        </transition>
                    </div>
                    <div class="card-body quitarPading">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="proveedor">Proveedor (*)</label>
                                    <v-select
                                        v-model="ingreso.provider_id"
                                        :options="proveedores"
                                        label="nombre"
                                        :reduce="proveedor => proveedor.id"
                                        placeholder="Seleccione un proveedor"
                                        class="style-chooser"
                                        @search="seleccionarProveedores"
                                    >
                                    <template slot="no-options">
                                        Escriba el nombre del proveedor
                                    </template>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="impuesto">Impuesto</label>
                                <input type="text" class="form-control" v-model="ingreso.impuesto">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_comprobante">Tipo Comprobante (*)</label>
                                    <select id="tipo_comprobante" class="custom-select" v-model="ingreso.tipo_comprobante">
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
                                    <input type="text" id="serie_comprobante" class="form-control" v-model="ingreso.serie_comprobante" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="num_comprobante">Numero Comprobante (*)</label>
                                    <input type="text" id="num_comprobante" class="form-control" v-model="ingreso.num_comprobante" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-12">
                               <div class="form-group row div-error" v-show="errorIngreso">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMensajeIngreso" :key="error">
                                            {{ error }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span class="text-danger" v-if="this.producto.precio == 0">(*Seleccione)</span></label>
                                    <input type="number" step="any" class="form-control" v-model="producto.precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span class="text-danger" v-if="this.producto.cantidad == 0">(*Seleccione)</span></label>
                                    <input type="number" class="form-control" v-model="producto.cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
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
                                                <input type="number" value="2" class="form-control" v-model="detalle.cantidad">
                                            </td>
                                            <td>{{ detalle.precio * detalle.cantidad }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"><strong>Total Parcial</strong></td>
                                            <td>{{ totalParcial = (total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"><strong>Total Impuesto</strong></td>
                                            <td>{{ totalImpuesto = ((total * ingreso.impuesto) / (1 + ingreso.impuesto)).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"><strong>Total Neto</strong></td>
                                            <td>$ {{ total = calcularTotal}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">No hay productos agregados</td>
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
                                        @click="registarIngreso()"
                                        v-if="this.detalles.length > 0 && 
                                              this.ingreso.provider_id != 0 && 
                                              this.ingreso.num_comprobante != 0"
                                        >Registrar Compra
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Ver ingreso -->
                <template v-else-if="listado == 2">
                    <div class="card-body quitarPading">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="proveedor">Proveedor (*)</label>
                                    <p>{{ proveedor }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="impuesto">Impuesto</label>
                                <p>{{ ingreso.impuesto }}</p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_comprobante">Tipo Comprobante (*)</label>
                                    <p>{{ ingreso.tipo_comprobante }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="serie_comprobante">Serie Comprobante</label>
                                    <p>{{ ingreso.serie_comprobante }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="num_comprobante">Numero Comprobante (*)</label>
                                    <p>{{ ingreso.num_comprobante }}</p>
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
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="detalle in detalles" :key="detalle.id">
                                            <td>{{ detalle.nombre}}</td>
                                            <td>{{ detalle.precio }}</td>
                                            <td>{{ detalle.cantidad }}</td>
                                            <td>{{ detalle.precio * detalle.cantidad }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3" align="right"><strong>Total Parcial</strong></td>
                                            <td>{{ totalParcial = (total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3" align="right"><strong>Total Impuesto</strong></td>
                                            <td>{{ totalImpuesto = ((total * ingreso.impuesto)).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3" align="right"><strong>Total Neto</strong></td>
                                            <td>$ {{ total }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="cerrarVerIngreso()">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- fin ingreso -->
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
            this.listarIngreso(1,'','num_comprobante');
        },
        data() {
            return {
                ingreso : {
                    provider_id : 0,
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
                    cantidad: 0
                },
                ingresos : [],
                detalles: [],
                proveedores : [],
                productos: [],
                productosModal: [],
                totalParcial: 0.0,
                totalImpuesto: 0.0,
                total : 0.0,
                proveedor : '',
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
                errorIngreso : 0,
                errorMostrarMensajeIngreso: []
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
                    resultado = resultado + (this.detalles[i].cantidad * this.detalles[i].precio);
                }
                return resultado;
            }
        },
        methods: {
            cambiarPagina(page, buscar, criterio){
                //actualiza la pagina actual
                this.pagination.current_page = page;
                //envia la peticion para visualizar la data de la pagina
                this.listarIngreso(page, buscar, criterio)
            },
            async listarIngreso(page,buscar,criterio) {
                let url = `ingresos?page=${page}&buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.ingresos = response.data.ingresos.data;
                this.pagination = response.data.pagination;
            },
            async listarProducto(buscar,criterio) {
                let url = `producto/listarProductos?buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.productosModal = response.data.data;
            },
            async seleccionarProveedores(filtro){
                let url = `proveedores/seleccionarProveedores?filtro=${filtro}`;
                const response = await axios.get(url);
                this.proveedores = response.data;
            },
            async buscarProducto(){
                let url = `producto/buscarProducto?filtro=${this.producto.codigo}`;
                const response = await axios.get(url);
                this.productos = response.data;
                if(this.productos.length > 0) {
                    this.producto.nombre = this.productos[0]['nombre'];
                    this.producto.product_id = this.productos[0]['id'];
                } else{
                    this.producto.nombre = 'No existe el producto';
                    this.producto.product_id = 0;
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
                        this.detalles.push({
                            'product_id' : this.producto.product_id,
                            'nombre' : this.producto.nombre,
                            'precio' : this.producto.precio,
                            'cantidad' : this.producto.cantidad
                        })
                        this.limpiarProductosDetalle();
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
                        'precio' : 1,
                        'cantidad' : 1
                    })
                    this.buscarProductos = '';
                }
            },
            eliminarProductoDetalle(index){
                this.detalles.splice(index, 1);
            },
            async registarIngreso(){
                const response = await axios.post('ingreso/agregar',{
                    'provider_id' : this.ingreso.provider_id,
                    'tipo_comprobante' : this.ingreso.tipo_comprobante,
                    'num_comprobante' : this.ingreso.num_comprobante,
                    'serie_comprobante' : this.ingreso.serie_comprobante,
                    'impuesto' : this.ingreso.impuesto,
                    'total' : this.total,
                    'data' : this.detalles
                });
                this.listado = 1;
                this.ingreso.provider_id = 0;
                this.ingreso.provider_id = 0,
                this.ingreso.tipo_comprobante= 'boleta',
                this.ingreso.serie_comprobante= '',
                this.ingreso.num_comprobante= 0,
                this.ingreso.impuesto= '0.18',
                this.limpiarProductosDetalle();
                this.detalles = [];
                this.listarIngreso(1,'','num_comprobante');
            },
            desactivarIngreso(id){
                Swal.fire({
                    title: 'Esta seguro de desactivar esta ingreso?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('ingreso/desactivar', {
                            'id' : id
                        })
                        .then( response => {
                            this.listarIngreso(1,'','num_comprobante');
                            Swal.fire(
                            'Anulado!',
                            'El ingreso ha sido desactivado con exito',
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
            },
            limpiarProductosDetalle(){
                this.producto.product_id = 0;
                this.producto.codigo = '';
                this.producto.nombre = '';
                this.producto.cantidad = 0;
                this.producto.precio = 0;
            },
            async verIngreso(id){
                this.detalles = [];
                this.listado = 2;
                const ingreso = await axios.get(`ingreso/obtenerIngreso?id=${id}`);
                this.proveedor = ingreso.data[0]['nombre'];
                this.ingreso.tipo_comprobante = ingreso.data[0]['tipo_comprobante'];
                this.ingreso.serie_comprobante = ingreso.data[0]['serie_comprobante'];
                this.ingreso.num_comprobante = ingreso.data[0]['num_comprobante'];
                this.ingreso.impuesto = ingreso.data[0]['impuesto'];
                this.total = ingreso.data[0]['total'];
                const detalle = await axios.get(`ingreso/obtenerDetalleIngreso?id=${id}`);
                this.detalles = detalle.data;
            },
            cerrarVerIngreso(){
                this.listado = 1;
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
    .fade-enter-active, .fade-leave-active {
        transition: opacity 1s;
    }
    .fade-enter, .fade-leave-to{
        opacity: 0;
    }
    .quitarPading{
        padding-top: 0;
    }
</style>
