<template>
    <main>
        <!-- Breadcrumb -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
            </ol>
        </div>
        <div>
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Usuarios
                    <button type="button" class="btn btn-primary" @click="abrirModal('usuario','registar')">
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
                                    @keyup.enter="listarUsuario(1, buscar, criterio)"
                                >
                                <button type="submit" class="btn btn-primary" @click="listarUsuario(1, buscar, criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-sm">
                        <thead class="text-center">
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Tipo documento</th>
                                <th>Direccion</th>
                                <th>Telefono</th>
                                <th>Email</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="persona in usuarios" :key="persona.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('usuario','actualizar', persona)">
                                    <i class="icon-pencil"></i>
                                    </button>
                                    <template v-if="persona.condicion">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(persona.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activarUsuario(persona.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td>{{ persona.nombre }}</td>
                                <td>{{ persona.tipo_documento }} {{ persona.num_documento }}</td>
                                <td>{{ persona.direccion }}</td>
                                <td>{{ persona.telefono }}</td>
                                <td>{{ persona.email }}</td>
                                <td>{{ persona.name }}</td>
                                <td>{{ persona.role }}</td>
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
                                        <input type="text" id="nombre" v-model="usuario.nombre" class="form-control" placeholder="Ingrese su nombre" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="tipo_documento">Tipo de Documento</label>
                                    <div class="col-sm-9">
                                        <select id="tipo_documento" v-model="usuario.tipo_documento" class="custom-select" required>
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
                                        <input type="number" id="num_documento" v-model="usuario.num_documento" class="form-control" placeholder="Ingrese su numero de documento" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="direccion">Direccion</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="direccion" v-model="usuario.direccion" class="form-control" placeholder="Ingrese su direccion" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="telefono">Telefono</label>
                                    <div class="col-sm-9">
                                        <input type="number" id="telefono" v-model="usuario.telefono" class="form-control" placeholder="Ingrese su numero de telefono" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label" for="email">Correo electronico</label>
                                    <div class="col-sm-9">
                                        <input type="email" id="email" v-model="usuario.email" class="form-control" placeholder="Ingrese su correo electronico" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <input type="text" id="usuario" v-model="usuario.name" class="form-control" placeholder="Ingrese su nick del usuario" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <input type="password" id="password" v-model="usuario.password" class="form-control" placeholder="Ingrese el password del usuario" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="rol">Rol de Usuario</label>
                                    <div class="col-sm-9">
                                        <select id="rol" v-model="usuario.role_id" class="custom-select" required>
                                            <option value="">Seleccione un rol</option>
                                            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.nombre }}</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group div-error" v-show="errorUsuario">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMensajeUsuario" :key="error">
                                            {{ error }}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="agregarUsuario()" v-if="tipoAccion == 1">Guardar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarUsuario()" v-if="tipoAccion == 2">Actualizar</button>
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
            this.listarUsuario(1,'','nombre');
        },
        data() {
            return {
                usuario : {
                    nombre : '',
                    tipo_documento: '',
                    num_documento:'',
                    direccion: '',
                    telefono: '',
                    email: '',
                    name: '',
                    password: '',
                    role_id:''
                },
                usuarios : [],
                roles : [],
                modal : 0,
                tituloModal: '',
                tipoAccion : 0,
                errorUsuario : 0,
                errorMostrarMensajeUsuario: [],
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
            async listarUsuario(page,buscar,criterio) {
                let url = `usuario?page=${page}&buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.usuarios = response.data.usuarios.data;
                this.pagination = response.data.pagination;
            },
            cambiarPagina(page, buscar, criterio){
                //actualiza la pagina actual
                this.pagination.current_page = page;
                //envia la peticion para visualizar la data de la pagina
                this.listarUsuario(page, buscar, criterio)
            },
            async obtenerRoles(){
                const response = await axios.get('obtenerRoles');
                this.roles = response.data;
            },
            async agregarUsuario(){
                const response = await axios.post('usuario/agregar', this.usuario);
                this.listarUsuario(1,'','nombre');
                this.cerrarModal();                    
            },
            async actualizarUsuario(){
                const response = await axios.put('usuario/actualizar', this.usuario);
                this.listarUsuario(1,'','nombre');
                this.cerrarModal();
            },
            validarProveedor(){               
                if(!this.usuario.nombre) {
                    this.errorMostrarMensajeUsuario.push("El nombre del proveedor no debe estar vacio");
                } else if(!this.usuario.num_documento) {
                    this.errorMostrarMensajeUsuario.push("El numero de documento no debe estar vacio");
                }
                if(this.errorMostrarMensajeUsuario.length) this.errorUsuario = 1;
                return this.errorUsuario;
            },
            abrirModal(modelo,accion,data = []){
                switch(modelo){
                    case 'usuario': {
                        switch(accion){
                            case 'registar': {
                                this.modal = 1;
                                this.tituloModal = 'Registar Usuario';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar': {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Usuario';
                                this.tipoAccion = 2;
                                this.usuario.nombre = data['nombre'];
                                this.usuario.tipo_documento = data['tipo_documento'];
                                this.usuario.num_documento = data['num_documento'];
                                this.usuario.direccion = data['direccion'];
                                this.usuario.telefono = data['telefono'];
                                this.usuario.email = data['email'];
                                this.usuario.name = data['name'];
                                this.usuario.password = data['password'];
                                this.usuario.role_id = data['role_id'];
                                this.usuario.id = data['person_id'];
                                break;
                            }
                        }
                    }
                }
                this.obtenerRoles();
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.usuario.nombre = ' ';
                this.usuario.tipo_documento = '';
                this.usuario.num_documento = '';
                this.usuario.direccion = '';
                this.usuario.telefono = '';
                this.usuario.email = '';
            },
            activarUsuario(id){
                Swal.fire({
                    title: 'Esta seguro de activar este usuario?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('usuario/activar', {
                            'id' : id
                        })
                        .then( response => {
                            this.listarUsuario(1,'','nombre');
                            Swal.fire(
                            'Activado!',
                            'El usuario ha sido activado con exito',
                            'success'
                            )
                        })
                    }
                })
            },
            desactivarUsuario(id){
                Swal.fire({
                    title: 'Esta seguro de desactivar este usuario?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('usuario/desactivar', {
                            'id' : id
                        })
                        .then( response => {
                            this.listarUsuario(1,'','nombre');
                            Swal.fire(
                            'Eliminado!',
                            'El usuario ha sido desactivado con exito',
                            'success'
                            )
                        })
                    }
                })
            },
        },
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
        margin-top: 2em;
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
