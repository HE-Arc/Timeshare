<template>
    <div class="mb-3">
        <h2>Timeholders list</h2>
    </div>
    <div class="mb-2">
        <p v-for="management in managements" :key="management.id">
            <button @click="destroy(management.id)" class="btn btn-outline-danger mx-3">
                Remove
            </button>
            {{management.email}}
        </p>
    </div>
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
