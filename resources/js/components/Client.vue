<template>
    <main>
        <!-- Breadcrumb -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Clientes</li>
            </ol>
        </div>
        <div>
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Clientes
                    <button type="button" class="btn btn-primary" @click="abrirModal('cliente','registar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                  <option value="nombre">Nombre</option>
                                  <option value="num_documento">CI</option>
                                </select>
                                <input type="text" 
                                    class="form-control" 
                                    placeholder="Texto a buscar" 
                                    v-model="buscar"
                                    @keyup.enter="listarCliente(1, buscar, criterio)"
                                >
                                <button type="submit" class="btn btn-primary" @click="listarCliente(1, buscar, criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-sm">
                        <thead class="text-center">
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Tipo documento</th>
                                <th>Num documento</th>
                                <th>Direccion</th>
                                <th>Telefono</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="cliente in clientes" :key="cliente.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('cliente','actualizar', cliente)">
                                      <i class="icon-pencil"></i>
                                    </button>
                                </td>
                                <td>{{ cliente.nombre }}</td>
                                <td>{{ cliente.tipo_documento }}</td>
                                <td>{{ cliente.num_documento }}</td>
                                <td>{{ cliente.direccion }}</td>
                                <td>{{ cliente.telefono }}</td>
                                <td>{{ cliente.email }}</td>
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
                        <div class="container">
                            <form class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="nombre">Nombre</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="nombre" v-model="cliente.nombre" class="form-control" placeholder="Ingrese su nombre" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="tipo_documento">Tipo de Documento</label>
                                    <div class="col-sm-9">
                                        <select id="tipo_documento" v-model="cliente.tipo_documento" class="custom-select" required>
                                            <option value="">Seleccione su tipo de documento</option>
                                            <option value="ci">CI</option>
                                            <option value="dni">DNI</option>
                                            <option value="run">RUN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="num_documento">Numero de documento</label>
                                    <div class="col-sm-9">
                                        <input type="number" id="num_documento" v-model="cliente.num_documento" class="form-control" placeholder="Ingrese su numero de documento" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="direccion">Direccion</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="direccion" v-model="cliente.direccion" class="form-control" placeholder="Ingrese su direccion" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="telefono">Telefono</label>
                                    <div class="col-sm-9">
                                        <input type="number" id="telefono" v-model="cliente.telefono" class="form-control" placeholder="Ingrese su numero de telefono" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="email">Correo electronico</label>
                                    <div class="col-sm-9">
                                        <input type="email" id="email" v-model="cliente.email" class="form-control" placeholder="Ingrese su correo electronico" required>
                                    </div>
                                </div>
                                <div class="form-group div-error" v-show="errorCliente">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMensajeCliente" :key="error">
                                            {{ error }}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="agregarCliente()" v-if="tipoAccion == 1">Guardar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarCliente()" v-if="tipoAccion == 2">Actualizar</button>
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
    export default {
        mounted () {
            this.listarCliente(1,'','nombre');
        },
        data() {
            return {
                cliente : {
                    nombre : '',
                    tipo_documento: '',
                    num_documento:'',
                    direccion: '',
                    telefono: '',
                    email: ''
                },
                clientes : [],
                modal : 0,
                tituloModal: '',
                tipoAccion : 0,
                errorCliente : 0,
                errorMostrarMensajeCliente: [],
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
            }

        },
        methods: {
            async listarCliente(page,buscar,criterio) {
                let url = `clientes?page=${page}&buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.clientes = response.data.clientes.data;
                this.pagination = response.data.pagination;
            },
            cambiarPagina(page, buscar, criterio){
                //actualiza la pagina actual
                this.pagination.current_page = page;
                //envia la peticion para visualizar la data de la pagina
                this.listarCliente(page, buscar, criterio)
            },
            async agregarCliente(){
                if(this.validarCliente()){
                    const response = await axios.post('clientes/agregar', this.cliente);
                    this.listarCliente(1,'','nombre');
                    this.cerrarModal();                    
                }else{
                    return;
                }
            },
            async actualizarCliente(){
                if(this.validarCliente()) return;
                const response = await axios.put('clientes/actualizar', this.cliente);
                this.listarCliente(1,'','nombre');
                this.cerrarModal();
            },
            validarCliente(){               
                if(!this.cliente.nombre) {
                    this.errorMostrarMensajeCliente.push("El nombre del cliente no debe estar vacio");
                } else if(!this.cliente.num_documento) {
                    this.errorMostrarMensajeCliente.push("El numero de documento no debe estar vacio");
                }
                if(this.errorMostrarMensajeCliente.length) this.errorCliente = 1;
                return this.errorCliente;
            },
            abrirModal(modelo,accion,data = []){
                switch(modelo){
                    case 'cliente': {
                        switch(accion){
                            case 'registar': {
                                this.modal = 1;
                                this.tituloModal = 'Registar Cliente';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar': {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Cliente';
                                this.tipoAccion = 2;
                                this.cliente.nombre = data['nombre'];
                                this.cliente.tipo_documento = data['tipo_documento'];
                                this.cliente.num_documento = data['num_documento'];
                                this.cliente.direccion = data['direccion'];
                                this.cliente.telefono = data['telefono'];
                                this.cliente.email = data['email'];
                                this.cliente.id = data['id'];
                                break;
                            }
                        }
                    }
                }
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.cliente.nombre = ' ';
                this.cliente.tipo_documento = '';
                this.cliente.num_documento = '';
                this.cliente.direccion = '';
                this.cliente.telefono = '';
                this.cliente.email = '';
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