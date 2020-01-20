<template>
    <main>
        <!-- Breadcrumb -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Proveedores</li>
            </ol>
        </div>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Proveedores
                    <button type="button" class="btn btn-primary" @click="abrirModal('proveedor','registar')">
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
                                    @keyup.enter="listarProveedor(1, buscar, criterio)"
                                >
                                <button type="submit" class="btn btn-primary" @click="listarProveedor(1, buscar, criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="text-center">
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo documento</th>
                                    <th>Numero</th>
                                    <th>Direccion</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    <th>Contacto</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="persona in proveedores" :key="persona.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('proveedor','actualizar', persona)">
                                        <i class="icon-pencil"></i>
                                        </button>
                                    </td>
                                    <td>{{ persona.nombre }}</td>
                                    <td>{{ persona.tipo_documento }}</td>
                                    <td>{{ persona.num_documento }}</td>
                                    <td>{{ persona.direccion }}</td>
                                    <td>{{ persona.telefono }}</td>
                                    <td>{{ persona.email }}</td>
                                    <td>{{ persona.proveedor.contacto }}</td>
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
                                        <input type="text" id="nombre" v-model="proveedor.nombre" class="form-control" placeholder="Ingrese su nombre" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="tipo_documento">Tipo de Documento</label>
                                    <div class="col-sm-9">
                                        <select id="tipo_documento" v-model="proveedor.tipo_documento" class="custom-select" required>
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
                                        <input type="number" id="num_documento" v-model="proveedor.num_documento" class="form-control" placeholder="Ingrese su numero de documento" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="direccion">Direccion</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="direccion" v-model="proveedor.direccion" class="form-control" placeholder="Ingrese su direccion" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="telefono">Telefono</label>
                                    <div class="col-sm-9">
                                        <input type="number" id="telefono" v-model="proveedor.telefono" class="form-control" placeholder="Ingrese su numero de telefono" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="email">Correo electronico</label>
                                    <div class="col-sm-9">
                                        <input type="email" id="email" v-model="proveedor.email" class="form-control" placeholder="Ingrese su correo electronico" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="contacto">Contacto</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="contacto" v-model="proveedor.contacto" class="form-control" placeholder="Ingrese el contacto del proveedor" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="telefono_contacto">Telefono de contacto</label>
                                    <div class="col-sm-9">
                                        <input type="number" id="telefono_contacto" v-model="proveedor.telefono_contacto" class="form-control" placeholder="Ingrese el telefono de contacto" required>
                                    </div>
                                </div>
                                <div class="form-group div-error" v-show="errorProveedor">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMensajeProveedor" :key="error">
                                            {{ error }}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="agregarProveedor()" v-if="tipoAccion == 1">Guardar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarProveedor()" v-if="tipoAccion == 2">Actualizar</button>
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
            this.listarProveedor(1,'','nombre');
        },
        data() {
            return {
                proveedor : {
                    nombre : '',
                    tipo_documento: '',
                    num_documento:'',
                    direccion: '',
                    telefono: '',
                    email: '',
                    contacto: '',
                    telefono_contacto: ''
                },
                proveedores : [],
                modal : 0,
                tituloModal: '',
                tipoAccion : 0,
                errorProveedor : 0,
                errorMostrarMensajeProveedor: [],
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
            async listarProveedor(page,buscar,criterio) {
                let url = `proveedores?page=${page}&buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.proveedores = response.data.proveedores.data;
                this.pagination = response.data.pagination;
            },
            cambiarPagina(page, buscar, criterio){
                //actualiza la pagina actual
                this.pagination.current_page = page;
                //envia la peticion para visualizar la data de la pagina
                this.listarProveedor(page, buscar, criterio)
            },
            async agregarProveedor(){
                const response = await axios.post('proveedores/agregar', this.proveedor);
                this.listarProveedor(1,'','nombre');
                this.cerrarModal();                    
            },
            async actualizarProveedor(){
                if(this.validarProveedor()) return;
                const response = await axios.put('proveedores/actualizar', this.proveedor);
                this.listarProveedor(1,'','nombre');
                this.cerrarModal();
            },
            validarProveedor(){               
                if(!this.proveedor.nombre) {
                    this.errorMostrarMensajeProveedor.push("El nombre del proveedor no debe estar vacio");
                } else if(!this.proveedor.num_documento) {
                    this.errorMostrarMensajeProveedor.push("El numero de documento no debe estar vacio");
                }
                if(this.errorMostrarMensajeProveedor.length) this.errorProveedor = 1;
                return this.errorProveedor;
            },
            abrirModal(modelo,accion,data = []){
                switch(modelo){
                    case 'proveedor': {
                        switch(accion){
                            case 'registar': {
                                this.modal = 1;
                                this.tituloModal = 'Registar Proveedor';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar': {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Proveedor';
                                this.tipoAccion = 2;
                                this.proveedor.nombre = data['nombre'];
                                this.proveedor.tipo_documento = data['tipo_documento'];
                                this.proveedor.num_documento = data['num_documento'];
                                this.proveedor.direccion = data['direccion'];
                                this.proveedor.telefono = data['telefono'];
                                this.proveedor.email = data['email'];
                                this.proveedor.contacto = data['proveedor']['contacto'];
                                this.proveedor.telefono_contacto = data['proveedor']['telefono_contacto'];
                                this.proveedor.id = data['id'];
                                break;
                            }
                        }
                    }
                }
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.proveedor.nombre = ' ';
                this.proveedor.tipo_documento = '';
                this.proveedor.num_documento = '';
                this.proveedor.direccion = '';
                this.proveedor.telefono = '';
                this.proveedor.email = '';
            }
        },
    }
</script>
<style>