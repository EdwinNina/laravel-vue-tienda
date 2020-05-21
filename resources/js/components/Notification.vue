<template>
    <li class="nav-item no-arrow dropdown mx-1">
        <a
            class="nav-link dropdown-toggle"
            href="#"
            id="alertsDropdown"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <i class="icon-bag icono"></i>
            <span class="badge badge-danger badge-counter">{{ notifications.length}}</span>
        </a>
        <div
            class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="alertsDropdown"
        >
            <h6 class="dropdown-header">Notificaciones</h6>
            <div v-if="notifications.length">
                <li v-for="notification in listar" :key="notification.id">
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="#">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-success">
                                <i class="icon-wallet text-white"></i>
                            </div>
                            <span class="ml-3 small text-gray-700">{{ notification.ingresos.msj}}</span> 
                        </div>
                        <div>
                            <span class="badge badge-primary">{{ notification.ingresos.numero}}</span>
                        </div>
                    </a>
                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="#">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-success">
                                <i class="icon-basket text-white"></i>
                            </div>
                            <span class="ml-3 small text-gray-700">{{ notification.ventas.msj}}</span> 
                        </div>
                        <div>
                            <span class="badge badge-success">{{ notification.ventas.numero}}</span>
                        </div>
                    </a>
                </li>
            </div>
            <div v-else>
                <p class="mt-2 text-gray-700 text-center">No tiene notificaciones</p>
            </div>
        </div>
    </li>
</template>

<script>
export default {
    props: ['notifications'],
    data() {
        return {
            arrayNotifications : []
        }
    },
    computed: {
        listar() {
            if(this.notifications == ''){
                return this.arrayNotifications = [] ;
            }else{
                //capturo la ultima notificacion agregada
                this.arrayNotifications = Object.values(this.notifications[0]);
                //validacion por el indice fuera de rango
                if(this.arrayNotifications.length > 3){
                    //si el tamaño es > 3 Es cuando las notificaciones son obtenidas desde el mismo servidor
                    return Object.values(this.arrayNotifications[4]);
                }else{
                    //si el tamaño es < 3 Es cuando las notificaciones son obtenidas desde el canal privado
                    return Object.values(this.arrayNotifications[0]);
                }
            }
        }
    },
};
</script>

<style>
    .icono{
        font-size: 2rem;
        font-weight: bold;
    }
</style>
