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
    </main>
</template>

<script>
    export default {
        mounted () {
            this.listarVenta(1,'','num_comprobante');
        },
        data() {
            return {
                ventas : [],
                detalles: [],
                totalParcial: 0.0,
                totalImpuesto: 0.0,
                total : 0.0,
                descuento: 0,
                cliente : '',
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
            mostrarDetalle(){
                this.listado = 0;
            },
            ocultarDetalle(){
                this.listado = 1;
                this.detalles = [];
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
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
