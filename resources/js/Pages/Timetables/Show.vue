<template>
    <h2>Show Timetable</h2>
    <p>{{timetable.title}}</p>
</template>


<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: [
        "timetable"
    ],
    data() {

    }
})
</script>
