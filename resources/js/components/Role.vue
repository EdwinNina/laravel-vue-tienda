<template>
    <main>
        <!-- Breadcrumb -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Roles</li>
            </ol>
        </div>
        <div>
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Roles
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
                                    @keyup.enter="listarRole(1, buscar, criterio)"
                                >
                                <button type="submit" class="btn btn-primary" @click="listarRole(1, buscar, criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-sm">
                        <thead class="text-center">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="role in roles" :key="role.id">
                                <td>{{ role.nombre }}</td>
                                <td>{{ role.descripcion }}</td>
                                <td>
                                    <span class="badge badge-success" v-if="role.condicion == '1'">activo</span>
                                    <span class="badge badge-warning" v-if="role.condicion == '0'">desactivado</span>
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
    </main>
</template>

<script>
    export default {
        mounted () {
            this.listarRole(1,'','nombre');
        },
        data() {
            return {
                role : {
                    nombre : '',
                    descripcion :'',
                },
                roles : [],
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
            async listarRole(page,buscar,criterio) {
                let url = `roles?page=${page}&buscar=${buscar}&criterio=${criterio}`;
                const response = await axios.get(url);
                this.roles = response.data.roles.data;
                this.pagination = response.data.pagination;
            },
            cambiarPagina(page, buscar, criterio){
                //actualiza la pagina actual
                this.pagination.current_page = page;
                //envia la peticion para visualizar la data de la pagina
                this.listarRole(page, buscar, criterio)
            }
        },
    }
</script>
