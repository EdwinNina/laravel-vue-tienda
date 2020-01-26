@extends('principal')
@section('contenido')
    <template v-if="menu == 0">
        <h1>Escritorio</h1>
    </template>
    <template v-if="menu == 1">
        <category-component/>
    </template>
    <template v-if="menu == 2">
        <product-component />
    </template>
    <template v-if="menu == 3">
        <h1>Ingresos</h1>
    </template>
    <template v-if="menu == 4">
        <provider-component />
    </template>
    <template v-if="menu == 5">
        <h1>Ventas</h1>
    </template>
    <template v-if="menu == 6">
        <client-component />
    </template>
    <template v-if="menu == 7">
        <user-component />
    </template>
    <template v-if="menu == 8">
        <role-component />
    </template>
    <template v-if="menu == 9">
        <h1>Reporte Ingresos</h1>
    </template>
    <template v-if="menu == 10">
        <h1>Reportes Egresos</h1>
    </template>
    <template v-if="menu == 11">
        <h1>Ayuda</h1>
    </template>
@endsection