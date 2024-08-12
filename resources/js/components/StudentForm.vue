<template>
    <v-row justify="center">
        
    </v-row>
</template>
<script>
import { mapActions } from 'vuex'

export default {
    data: () => ({
        dialog: false,
    }),
    methods: {
        ...mapActions('student', [
            'createStudent',
        ])
    },
}
</script>