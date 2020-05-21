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
    </main>
</template>

<script>
    export default {
        mounted () {
            this.listarIngreso(1,'','num_comprobante');
        },
        data() {
            return {
                ingresos : [],
                detalles: [],
                totalParcial: 0.0,
                totalImpuesto: 0.0,
                total : 0.0,
                listado: 1,
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
            mostrarDetalle(){
                this.listado = 0;
            },
            ocultarDetalle(){
                this.listado = 1;
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
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnAgregar{
            margin-top: 2rem;
        }
    }
    .quitarPading{
        padding-top: 0;
    }
</style>
