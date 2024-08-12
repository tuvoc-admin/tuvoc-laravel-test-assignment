<template>
  <v-container>
    <v-btn color="primary" class="mb-8" elevation="2" @click="availibilityModal = true">Add Student</v-btn>
    <v-data-table :headers="headers" :items="students" :items-per-page="10" class="elevation-1"></v-data-table>
    
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { dcdLrvlValdtnErr, isEmpty } from "@/helpers"

export default {
  name: "Students",
  data: () => ({
    headers: [
      {
        text: 'First Name',
        align: 'start',
        sortable: false,
        value: 'first_name',
      },
      { text: 'Middel Name', value: 'middle_name' },
      { text: 'Last Name', value: 'last_name' },
      { text: 'Email', value: 'email' },
      { text: 'Date fo birth', value: 'date_of_birth' },
    ],
    day: {
      monday: false,
      tuesday: false,
      wednesday: false,
      thursday: false,
      friday: false,
      saturday: false,
      sunday: false,
    },
    availibilityModal: false,
    loading: false,

  }),
  methods: {
    ...mapActions('student', [
      'setAvailibility',
    ]),
    async saveStudent() {
      if (this.$refs.student_form.validate()) {
        this.loading = true
        const res = await this.setAvailibility(this.form)
        this.loading = false
      }
    }
  },
  computed: {
    ...mapGetters('student', ['students']),
  },
  mounted() {
    this.getStudents()
  }
}
</script>