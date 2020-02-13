<template>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4>Ingresos</h4>
                    </div>
                    <div class="card-content">
                        <div class="ct-chart">
                            <canvas id="ingresos"></canvas>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p>Compras de los ultimos meses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4>Ventas</h4>
                    </div>
                    <div class="card-content">
                        <div class="ct-chart">
                            <canvas id="ventas"></canvas>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p>Ventas de los ultimos meses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted(){
            this.cargarDashboards();
        },
        data() {
            return {
                ingresoCanva: null,
                charIngreso: null,
                ingresos: [],
                totalIngreso:[],
                mesIngreso:[],
                ventaCanva: null,
                charVenta: null,
                ventas: [],
                totalVenta:[],
                mesVenta:[]
            }
        },
        methods: {
            async cargarDashboards(){
                const response = await axios.get('dashboard');
                this.ingresos = response.data.ingresos;
                this.ventas = response.data.ventas;
                this.loadIngresos();
                this.loadVentas();
            },
            loadIngresos(){
                this.ingresos.map(ingreso => {
                    switch (ingreso.mes) {
                        case 1:
                            this.mesIngreso.push('Enero');
                        break;
                        case 2:
                            this.mesIngreso.push('Febrero');
                        break;
                        case 3:
                            this.mesIngreso.push('Marzo');
                        break;
                        case 4:
                            this.mesIngreso.push('Abril');
                        break;
                        case 5:
                            this.mesIngreso.push('Mayo');
                        break;
                        case 6:
                            this.mesIngreso.push('Junio');
                        break;
                        case 7:
                            this.mesIngreso.push('Julio');
                        break;
                        case 8:
                            this.mesIngreso.push('Agosto');
                        break;
                        case 9:
                            this.mesIngreso.push('Septiembre');
                        break;
                        case 10:
                            this.mesIngreso.push('Octubre');
                        break;
                        case 11:
                            this.mesIngreso.push('Noviembre');
                        break;
                        case 12:
                            this.mesIngreso.push('Diciembre');
                        break;                    
                    }
                    this.totalIngreso.push(ingreso.total);
                })
                this.ingresoCanva = document.getElementById('ingresos').getContext('2d');
                this.charIngreso = new Chart(this.ingresoCanva, {
                    type: 'bar',
                    data: {
                        labels: this.mesIngreso,
                        datasets: [{
                            label: 'Ingresos',
                            data: this.totalIngreso,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            },
            loadVentas(){
                this.ventas.map(venta => {
                    switch (venta.mes) {
                        case 1:
                            this.mesVenta.push('Enero');
                        break;
                        case 2:
                            this.mesVenta.push('Febrero');
                        break;
                        case 3:
                            this.mesVenta.push('Marzo');
                        break;
                        case 4:
                            this.mesVenta.push('Abril');
                        break;
                        case 5:
                            this.mesVenta.push('Mayo');
                        break;
                        case 6:
                            this.mesVenta.push('Junio');
                        break;
                        case 7:
                            this.mesVenta.push('Julio');
                        break;
                        case 8:
                            this.mesVenta.push('Agosto');
                        break;
                        case 9:
                            this.mesVenta.push('Septiembre');
                        break;
                        case 10:
                            this.mesVenta.push('Octubre');
                        break;
                        case 11:
                            this.mesVenta.push('Noviembre');
                        break;
                        case 12:
                            this.mesVenta.push('Diciembre');
                        break;                    
                    }
                    this.totalVenta.push(venta.total);
                })
                this.ventaCanva = document.getElementById('ventas').getContext('2d');
                this.charVenta = new Chart(this.ventaCanva, {
                    type: 'bar',
                    data: {
                        labels: this.mesVenta,
                        datasets: [{
                            label: 'Ventas',
                            data: this.totalVenta,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>