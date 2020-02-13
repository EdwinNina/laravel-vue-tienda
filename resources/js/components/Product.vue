<template>
    <main>
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Productos</li>
        </ol>
        <div>
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Productos
                    <button type="button" class="btn btn-secondary" @click="abrirModal('producto','registar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" class="btn btn-info" @click="cargarPDF()">
                        <i class="icon-doc"></i>&nbsp;Reporte
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                  <option value="nombre">Nombre</option>
                                  <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" 
                                    class="form-control" 
                                    placeholder="Texto a buscar" 
                                    v-model="buscar"
                                    @keyup.enter="listarProducto(1, buscar, criterio)"
                                >
                                <button type="submit" class="btn btn-primary" @click="listarProducto(1, buscar, criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                            <tr>
                                <th>Opciones</th>
                                <th>Categoria</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Precio Venta</th>
                                <th>Stock</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="producto in productos" :key="producto.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('producto','actualizar', producto)">
                                      <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="producto.condicion">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarProducto(producto.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activarProducto(producto.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td>{{ producto.category.nombre }}</td>
                                <td>{{ producto.codigo }}</td>
                                <td>{{ producto.nombre }}</td>
                                <td>{{ producto.precio_venta }}</td>
                                <td>{{ producto.stock }}</td>
                                <td>{{ producto.descripcion }}</td>
                                <td>
                                    <span class="badge badge-success" v-if="producto.condicion == '1'">activo</span>
                                    <span class="badge badge-warning" v-if="producto.condicion == '0'">desactivado</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" :class="modal ? 'mostrar' : ''" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <form class="form-horizontal was-validated">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="categoria">Categoria</label>
                                    <div class="col-sm-9">
                                        <select id="categoria" v-model="producto.category_id" class="custom-select" required>
                                            <option value="" disabled>Seleccione una categoria</option>
                                            <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">{{ categoria.nombre }}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            <span>¡!Debes Agregar la categoria del producto</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="codigo">Codigo</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="codigo" v-model="producto.codigo" class="form-control" required>
                                        <div class="invalid-feedback">
                                            <span>¡!Debes Agregar el codigo del producto</span>
                                        </div>
                                        <barcode :value="producto.codigo" :options="{ format : 'EAN-13'}">Generando codigo de barras</barcode>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="nombre">Nombre</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="nombre" v-model="producto.nombre" class="form-control" placeholder="Nombre del producto" required>
                                        <div class="invalid-feedback">
                                            <span>¡!Debes Agregar el nombre del producto</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="precio_venta">Precio venta</label>
                                    <div class="col-sm-9">
                                        <input type="number" id="precio_venta" v-model="producto.precio_venta" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="stock">Stock</label>
                                    <div class="col-sm-9">
                                        <input type="number" id="stock" v-model="producto.stock" class="form-control" min="0" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="descripcion">Descripción</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="descripcion" v-model="producto.descripcion" class="form-control" placeholder="descripcion del producto" required>
                                    </div>
                                </div>
                                <div class="form-group div-error" v-show="errorProducto">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMensajeProducto" :key="error">
                                            {{ error }}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="agregarProducto()" v-if="tipoAccion == 1">Guardar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarProducto()" v-if="tipoAccion == 2">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';
    export default {
        components:{
            'barcode' : VueBarcode
        },

        mounted () {
            this.listarProducto(1,'','nombre');
        },
        data() {
            return {
                producto : {
                    category_id: '',
                    codigo: '',
                    nombre : '',
                    precio_venta: 0,
                    stock: 0,
                    descripcion :'',
                },
                productos : [],
                categorias: [],
                modal : 0,
                tituloModal: '',
                tipoAccion : 0,
                errorProducto : 0,
                errorMostrarMensajeProducto: [],
                pagination: {
                    'total'        : 0,
                    'current_page' : 0,
                    'per_page'     : 0,
                    'last_page'    : 0,
                    'from'         : 0,
                    'to'           : 0
                },
                offset: 3,
                criterio: 'nombre',
                buscar : ''
            }
        },
        computed: {
            isActived() {
                return this.pagination.current_page; 
            },
            pagesNumber(){
                if(!this.pagination.to) return [];

                let from = this.pagination.current_page - this.offset;
                if(from < 1) from  = 1;

                let to = from + (this.offset * 2);

                if(to >= this.pagination.current_page) to = this.pagination.current_page;

                let pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from ++;
                }
                return pagesArray;
            }
        },
        methods: {
            async listarProducto(page,buscar,criterio) {
                let url = `producto?page=${page}&buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.productos = response.data.productos.data;
                this.pagination = response.data.pagination;
            },
            cambiarPagina(page, buscar, criterio){
                //actualiza la pagina actual
                this.pagination.current_page = page;
                //envia la peticion para visualizar la data de la pagina
                this.listarProducto(page, buscar, criterio)
            },
            async obtenerCategoria(){
                const response = await axios.get('categorias');
                this.categorias = response.data;
            },
            async agregarProducto(){
                const response = await axios.post('producto/agregar', this.producto);
                this.listarProducto(1,'','nombre');
                this.cerrarModal();                    
            },
            async actualizarProducto(){
                const response = await axios.put('producto/actualizar', this.producto);
                console.log(response.data);
                this.listarProducto(1,'','nombre');
                this.cerrarModal();
            },
            async activarProducto(id){
                Swal.fire({
                    title: 'Esta seguro de activar este producto?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('producto/activar', {
                            'id' : id
                        })
                        .then( response => {
                            this.listarProducto(1,'','nombre');
                            Swal.fire(
                            'Activado!',
                            'El producto ha sido activado con exito',
                            'success'
                            )
                        })
                    }
                })
            },
            desactivarProducto(id){
                Swal.fire({
                    title: 'Esta seguro de desactivar esta categoria?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('producto/desactivar', {
                            'id' : id
                        })
                        .then( response => {
                            this.listarProducto(1,'','nombre');
                            Swal.fire(
                            'Eliminado!',
                            'El producto ha sido desactivado con exito',
                            'success'
                            )
                        })
                    }
                })
            },
            validarProducto(){               
                if(!this.producto.nombre) this.errorMostrarMensajeProducto.push("El nombre del producto no debe estar vacio");
                if(!this.producto.precio_venta) this.errorMostrarMensajeProducto.push("El precio de la venta no debe estar vacio");
                if(!this.producto.stock) this.errorMostrarMensajeProducto.push("El stock del producto no debe estar vacio");
                if(this.errorMostrarMensajeProducto.length) this.errorProducto = 1;
                return this.errorProducto;
            },
            abrirModal(modelo,accion,data = []){
                switch(modelo){
                    case 'producto': {
                        switch(accion){
                            case 'registar': {
                                this.modal = 1;
                                this.tituloModal = 'Registar Producto';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar': {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Categoria';
                                this.tipoAccion = 2;
                                this.producto.id = data['id'];
                                this.producto.category_id = data['category_id'];
                                this.producto.codigo = data['codigo'];
                                this.producto.nombre = data['nombre'];
                                this.producto.precio_venta = data['precio_venta'];
                                this.producto.stock = data['stock'];
                                this.producto.descripcion = data['descripcion'];
                                break;
                            }
                        }
                    }
                }
                this.obtenerCategoria();
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.producto.category_id = 0
                this.producto.codigo = '';
                this.producto.nombre = '';
                this.producto.descripcion = '';
                this.producto.stock = 0;
                this.producto.precio_venta = 0.0;
            },
            cargarPDF(){
                window.open('producto/listarPDF','_blank');
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
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>