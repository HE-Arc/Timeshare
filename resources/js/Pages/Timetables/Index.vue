<template>

<Head title="Dashboard" />

<breeze-authenticated-layout>
    <template #header>
        <h2 class="h4 font-weight-bold">
        Livrs
        </h2>
    </template>
</breeze-authenticated-layout>

<!--
    A FAIRE :
        ajouter un petit engrenage sur les bouton "myTime..."
            qui montrent la fenêtre de manage
    -->

<div class="container h-100">

    <div class="row">
        <div class="col-sm-4">
            <div class="container p-3 my-3 bg-dark text-white">

                <div class="form-group">
                    <label>Manage : </label>
                    <select class="form-control" @change="updateManagementsList" v-model="currentManageOn">
                        <option value="-1">Select</option>
                        <option v-for="timetable in myTimetables" :key="timetable.id" :value="timetable.id">{{timetable.title}}</option>
                    </select>
                </div>

                <template v-if="currentManageOn != -1">
                    <show-managements :managements="currentManagers"></show-managements>
                    <create-management :timetableId="currentManageOn"></create-management>

                </template>

            </div>
        </div>

        <div class="col-sm-8">

            <div class="container p-3 my-3 bg-dark text-white">
                <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <template v-for="timetable in myTimetables" :key="timetable.id" >
                    <input @change="updateTable" v-model="selected[timetable.id]" type="checkbox" class="btn-check" :id="timetable.id" autocomplete="off"/>
                    <label :for="timetable.id" class="btn btn-outline-primary" >{{timetable.title}}
                        <button class="btn btn-primary" type="button"><i class="bi bi-pencil"></i></button>
                    </label>
                    </template>

                    <template v-for="timetable in manageTimetables" :key="timetable.id" >
                    <input @change="updateTable" v-model="selected[timetable.id]" type="checkbox" class="btn-check" :id="timetable.id" autocomplete="off"/>
                    <label :for="timetable.id" class="btn btn-outline-primary" >{{timetable.title}}</label>
                    </template>

                    <button @click="showForm" class="btn btn-primary" type="button">+</button>
                </div>

                <create-timetable v-if="formVisible"></create-timetable>
                <template v-else>
                    <show :timetable="currentTimetable">test</show>
                    oue
                </template>
            </div>

        </div>
    </div>



</div>


</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import CreateTimetable from '@/Pages/Timetables/Create.vue'
import Show from '@/Pages/Timetables/Show.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import Button from '@/Components/Button.vue'
import CreateManagement from '@/Pages/Managements/Create.vue'
import ShowManagements from '@/Pages/Managements/Show.vue'
import Label from '@/Components/Label.vue'

export default {

    components: {
        BreezeAuthenticatedLayout,
        CreateTimetable,
        Show,
        Head,
        Link,
        Button,
        CreateManagement,
        ShowManagements,
        Label
    },
    data() {
        return {
            formVisible: false,
            currentTimetable: null,
            selected: {},
            currentManageOn: -1,
            currentManagers: null
        }
    },
    props: [
        "myTimetables",
        "manageTimetables"
    ],
    mounted(){
        this.myTimetables.forEach(timetable => {
            this.selected[timetable.id] = false;
        });
        this.manageTimetables.forEach(timetable => {
            this.selected[timetable.id] = false;
        });
    },
    methods:{
        updateTable(){
            this.formVisible = false;
        },

        updateManagementsList(){
            axios.get(route('managements.timetablesManagers', {timetableId:this.currentManageOn})).then((response) => {

            this.currentManagers = response.data;
            console.log(response.data);
            });
        },

        showForm(){
            this.formVisible=true;
            Object.keys(this.selected).forEach(v => this.selected[v] = false)
        },
    }
}
</script>
