@extends('principal')
@section('contenido')
    <template v-if="menu == 0">
        <dashboard-component />
    </template>
    <template v-if="menu == 1">
        <category-component/>
    </template>
    <template v-if="menu == 2">
        <product-component />
    </template>
    <template v-if="menu == 3">
        <ingreso-component />
    </template>
    <template v-if="menu == 4">
        <provider-component />
    </template>
    <template v-if="menu == 5">
        <venta-component />
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
        <consulta-ingreso-component />
    </template>
    <template v-if="menu == 10">
        <consulta-venta-component />
    </template>
    <template v-if="menu == 11">
        <h1>Ayuda</h1>
    </template>
@endsection