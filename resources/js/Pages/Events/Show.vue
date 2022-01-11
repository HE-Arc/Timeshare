<template>
    <h2>{{event.title}} {{ event.start && event.start.format('DD/MM/YYYY') }}</h2>

    <strong>Event details:</strong>
    <ul>
        <li>Event starts at : {{ event.start && event.start.formatTime() }}</li>
        <li>Event ends at : {{ event.end && event.end.formatTime() }}</li>
        <li>Event author : <strong>{{ event.name }}</strong> <a :href="'mailto:'+event.email">{{event.email}}</a></li>
    </ul>

    <button class="btn btn-danger" v-if="event.validated && hasRights" @click="deleteEvent">Delete</button>
    <template v-else-if="hasRights">
        <button class="btn btn-danger" @click="deleteEvent">Refuse</button>
        <button class="btn btn-success" @click="validateEvent">Validate</button>
    </template>

</template>


<script>
import { defineComponent } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import Button from '@/Components/Button.vue'

export default defineComponent({
  components: { Button },
    props: [
        'event',
        'hasRights'
    ],
    data() {

    },
    methods:{
        deleteEvent(){
            axios.delete(route("events.destroy", this.event.id));
            setTimeout(() => this.$parent.updateTable(), 500);
        },
        validateEvent(){
            axios.put(route("events.update", {event:this.event.id,
                                              validated:true}));
            setTimeout(() => this.$parent.updateTable(), 500);
        }
    }
})
</script>
