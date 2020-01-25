@extends('principal')
@section('contenido')
    <template v-if="menu == 0">
        <h1>Contenido menu 0</h1>
    </template>
    <template v-if="menu == 1">
        <category-component/>
    </template>
    <template v-if="menu == 2">
        <product-component />
    </template>
    <template v-if="menu == 3">
        <h1>Contenido menu 3</h1>
    </template>
    <template v-if="menu == 4">
        <provider-component />
    </template>
    <template v-if="menu == 5">
        <h1>Contenido menu 5</h1>
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
        <h1>Contenido menu 9</h1>
    </template>
    <template v-if="menu == 10">
        <h1>Contenido menu 10</h1>
    </template>
    <template v-if="menu == 11">
        <h1>Contenido menu 11</h1>
    </template>
    <template v-if="menu == 12">
        <h1>Contenido menu 12</h1>
    </template>
@endsection