<template>
    <main>
        <!-- Breadcrumb -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Categorias</li>
            </ol>
        </div>
        <div>
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Categorías
                    <button type="button" class="btn btn-primary" @click="abrirModal('categoria','registar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
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
                                    @keyup.enter="listarCategoria(1, buscar, criterio)"
                                >
                                <button type="submit" class="btn btn-primary" @click="listarCategoria(1, buscar, criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-sm">
                        <thead class="text-center">
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="categoria in categorias" :key="categoria.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('categoria','actualizar', categoria)">
                                      <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="categoria.condicion">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activarCategoria(categoria.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td>{{ categoria.nombre }}</td>
                                <td>{{ categoria.descripcion }}</td>
                                <td>
                                    <span class="badge badge-success" v-if="categoria.condicion == '1'">activo</span>
                                    <span class="badge badge-warning" v-if="categoria.condicion == '0'">desactivado</span>
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
                                <div class="form-group">
                                    <label class="form-control-label" for="nombre">Nombre</label>
                                    <input type="text" id="nombre" pattern="[A-Za-z]" v-model="categoria.nombre" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="descripcion">Descripción</label>
                                    <input type="text" id="descripcion" pattern="[A-Za-z]" v-model="categoria.descripcion" class="form-control" required>
                                </div>
                                <div class="form-group div-error" v-show="errorCategoria">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMensajeCategoria" :key="error">
                                            {{ error }}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="agregarCategoria()" v-if="tipoAccion == 1">Guardar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarCategoria()" v-if="tipoAccion == 2">Actualizar</button>
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
            this.listarCategoria(1,'','nombre');
        },
        data() {
            return {
                categoria : {
                    nombre : '',
                    descripcion :'',
                },
                categorias : [],
                modal : 0,
                tituloModal: '',
                tipoAccion : 0,
                errorCategoria : 0,
                errorMostrarMensajeCategoria: [],
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
            async listarCategoria(page,buscar,criterio) {
                let url = `categoria?page=${page}&buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.categorias = response.data.categories.data;
                this.pagination = response.data.pagination;
            },
            cambiarPagina(page, buscar, criterio){
                //actualiza la pagina actual
                this.pagination.current_page = page;
                //envia la peticion para visualizar la data de la pagina
                this.listarCategoria(page, buscar, criterio)
            },
            async agregarCategoria(){
                if(this.validarCategoria()){
                    const response = await axios.post('categoria/agregar', this.categoria);
                    this.listarCategoria(1,'','nombre');
                    this.cerrarModal();                    
                }else{
                    return;
                }
            },
            async actualizarCategoria(){
                if(this.validarCategoria()){
                    const response = await axios.put('categoria/actualizar', this.categoria);
                    this.listarCategoria(1,'','nombre');
                    this.cerrarModal();
                }else{
                    return;
                } 
            },
            async activarCategoria(id){
                Swal.fire({
                    title: 'Esta seguro de activar esta categoria?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('categoria/activar', {
                            'id' : id
                        })
                        .then( response => {
                            this.listarCategoria(1,'','nombre');
                            Swal.fire(
                            'Activado!',
                            'la categoria ha sido activado con exito',
                            'success'
                            )
                        })
                    }
                })
            },
            desactivarCategoria(id){
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
                        axios.put('categoria/desactivar', {
                            'id' : id
                        })
                        .then( response => {
                            this.listarCategoria(1,'','nombre');
                            Swal.fire(
                            'Eliminado!',
                            'la categoria ha sido desactivado con exito',
                            'success'
                            )
                        })
                    }
                })
            },
            validarCategoria(){               
                if(!this.categoria.nombre) this.errorMostrarMensajeCategoria.push("El nombre de la categoria no debe estar vacio");
                if(this.errorMostrarMensajeCategoria.length) this.errorCategoria = 1;
                return this.errorCategoria;
            },
            abrirModal(modelo,accion,data = []){
                switch(modelo){
                    case 'categoria': {
                        switch(accion){
                            case 'registar': {
                                this.modal = 1;
                                this.tituloModal = 'Registar Categoria';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar': {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Categoria';
                                this.tipoAccion = 2;
                                this.categoria.nombre = data['nombre'];
                                this.categoria.descripcion = data['descripcion'];
                                this.categoria.id = data['id'];
                                break;
                            }
                        }
                    }
                }
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.categoria.nombre = '';
                this.categoria.descripcion = '';
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