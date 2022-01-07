<template>
    <p v-for="management in managements" :key="management.id">
        {{management.email}}
        <button @click="destroy(management.id)" class="btn btn-danger"><i class="bi bi-trash"></i></button>
    </p>
</template>


<script>
import { defineComponent } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    props: [
        'managements',
        'timetableId'
    ],
    data() {

    },
    methods:{
        destroy(id){
            axios.delete(route("managements.destroy", id));
            setTimeout(() => this.$parent.updateManagementsList(this.timetableId), 500);
        }
    }
})
</script>
