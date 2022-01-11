<template>

<Head title="Timetable" />

<breeze-authenticated-layout>

</breeze-authenticated-layout>

<!--
    A FAIRE :
        ajouter un petit engrenage sur les bouton "myTime..."
            qui montrent la fenêtre de management
    -->

<div class="container h-100">

    <div class="row">
        <div v-if="editMode != 0 && editMode != 4"
             class="col-sm-4 container p-3 my-3 bg-dark text-white">

            <button @click="editMode = 0" style="float:right">x</button>

            <template v-if="editMode == 1">
                <show-managements :managements="currentManagers" :timetableId="currentEditOn"></show-managements>
                <create-management :timetableId="currentEditOn"></create-management>
            </template>

            <template v-else-if="editMode == 2">
                <create-event :clickedDateTime="clickedDateTime" :timetableId="currentEditOn"/>
            </template>

            <template v-else-if="editMode == 3">
                <show-event :hasRights="hasRightsOnEvent" :event="selectedEvent"></show-event>
            </template>

        </div>

        <div class="col">

            <div>     <!--  container p-3 my-3 bg-dark text-white -->
                <div v-if="editMode != 3" class="btn-group mb-3" role="group" aria-label="Basic checkbox toggle button group">
                    <template v-for="timetable in myTimetables" :key="timetable.id" >
                    <input @change="updateTable" v-model="selected[timetable.id]" type="checkbox" class="btn-check" :id="timetable.id" autocomplete="off"/>
                    <label :for="timetable.id" class="btn btn-outline-primary" >{{timetable.title}}
                        <button @click="updateManagementsList(timetable.id)" class="btn btn-primary mx-2" type="button">
                            <edit-icon></edit-icon>
                        </button>
                        <button v-if="timetable.isPublic" @click="copyShareToClipBoard(timetable.id)" class="btn btn-primary" type="button">
                            <share-icon></share-icon>
                        </button>
                    </label>
                    </template>

                    <template v-for="timetable in manageTimetables" :key="timetable.id" >
                    <input @change="updateTable" v-model="selected[timetable.id]" type="checkbox" class="btn-check" :id="timetable.id" autocomplete="off"/>
                    <label :for="timetable.id" class="btn btn-outline-primary" >{{timetable.title}}</label>
                    </template>

                    <template v-if="sharedTimetable != null">
                    <input @change="updateTable" v-model="selected[sharedTimetable.id]" type="checkbox" class="btn-check" :id="sharedTimetable.id" autocomplete="off"/>
                    <label :for="sharedTimetable.id" class="btn btn-outline-primary" >{{sharedTimetable.title}}</label>
                    </template>

                    <button @click="showForm" class="btn btn-primary" type="button">+</button>
                </div>

                <create-timetable v-if="editMode == 4"></create-timetable>
                <template v-else>

                    <vue-cal
                    style="height: 600px"
                    :events="events"
                    :editable-events="{ title: false, drag: false, resize: false, delete: true, create: false }"
                    :cell-click-hold="false"
                    :drag-to-create-event="false"
                    :split-days="split"
                    @cell-dblclick="showEventCreation"
                    @event-delete="deleteEvent"
                    :on-event-click="onEventClick"
                    />
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
import CreateEvent from '@/Pages/Events/Create.vue'
import ShowEvent from '@/Pages/Events/Show.vue'
import Label from '@/Components/Label.vue'
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'

import EditIcon from '@/Components/EditIcon.vue'
import ShareIcon from '@/Components/ShareIcon.vue'

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
        Label,
        VueCal,
        CreateEvent,
        EditIcon,
        ShareIcon,
        ShowEvent
    },
    data() {
        return {
            editMode: 0,
            currentTimetable: null,
            selected: {},
            currentEditOn: -1,
            currentManagers: null,

            events: [],
            clickedDateTime: Date.now(),
            split: [],
            selectedEvent: {},
            showCreateTimetable: false,
            hasRightsOnEvent: false
        }
    },
    props: [
        "myTimetables",
        "manageTimetables",
        "sharedTimetable"
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
        getTheOnlySelected(){
            let keys = Object.keys(this.selected);
            let s = this.selected;
            let selectedTrue = keys.filter(function(k){
                return s[k];
            });

            if(selectedTrue.length != 1)
                return false
            return selectedTrue[0];
        },

        updateTable(){
            this.editMode = 0;
            this.events = [];

            this.split = [];
            (Object.keys(this.selected)).filter(k=>this.selected[k])
                                      .forEach(k=>{
                this.split.push({
                    id: k
                });

                this.loadTimetableEvents(k);
            });

            console.log(this.events);
        },

        loadTimetableEvents(timetableId){
            axios.get(route('events.timetablesEvents', {timetable:timetableId})).then((response) => {

                let eventObjects = response.data;
                eventObjects.forEach(e => {
                    let vcEvent = this.eventObjectToVueCalEvent(e);
                    vcEvent["split"] = timetableId;
                    //vcEvent["deletable"] =
                    this.events.push(vcEvent);
                });
            });
        },

        eventObjectToVueCalEvent(e){
            e["start"] = e.begin;
            e["title"] = e.description;
            e["content"] = e.name;

            if(e.validated == true)
                e["class"] = "valid";
            else
                e["class"] = "invalid";

            return e;
        },

        updateManagementsList(id){
            this.editMode = 1;
            this.currentEditOn = id;

            axios.get(route('managements.timetablesManagers', {timetable:this.currentEditOn})).then((response) => {
                this.currentManagers = response.data;
            });
        },

        showForm(){
            this.editMode = 4;
            Object.keys(this.selected).forEach(v => this.selected[v] = false)
        },

        showEventCreation(d){
            let dateTime = d["date"];
            this.currentEditOn = d["split"];
            this.editMode = 2;
            this.clickedDateTime = dateTime;
        },

        deleteEvent(e){
            axios.delete(route("events.destroy", e.id));
        },

        onEventClick(event, e){

            if(this.sharedTimetable == null)
                this.hasRightsOnEvent = true;
            else
                // if the only timetable where I do not have the rights (sharedTimetable) is the one where sits the event
                this.hasRightsOnEvent = this.sharedTimetable.id != event.timetable;


            this.selectedEvent = event;
            this.editMode = 3;

            e.stopPropagation();
        },

        copyShareToClipBoard(id){
            navigator.clipboard.writeText(route('timetables.show', id));
        }
    }
}
</script>
