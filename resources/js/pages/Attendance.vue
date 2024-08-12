<template>
  <v-container>
    <v-btn color="primary" class="mb-8" elevation="2" @click="availibilityModal = true">Add Student</v-btn>
    <v-data-table :headers="headers" :items="students" :items-per-page="10" class="elevation-1"></v-data-table>
    <v-dialog v-model="availibilityModal" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Create Student</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="student_form" v-model="validForm">
            <v-row>
              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="form.first_name" label="First name*" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="form.middle_name" label="Middle name"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="form.last_name" label="Last name*" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field v-model="form.email" type="email" label="Email*" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field type="date" v-model="form.date_of_birth" label="Date of birth" required></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="availibilityModal = false">
            Close
          </v-btn>
          <v-btn color="blue darken-1" @click="saveStudent" :loading="loading" :disabled="loading" text>
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
    form: {
      sunday: false,
    },
    emailRules: [
      v => !!v || 'Email is required',
      v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
    ],
    textRules: [
      v => !!v || 'Field is required',
    ],
    validForm: false,
    availibilityModal: false,
    loading: false,

  }),
  methods: {
    ...mapActions('student', [
      'availibility',
      'getStudents'
    ]),
    async saveStudent() {
      if (this.$refs.student_form.validate()) {
        this.loading = true
        const res = await this.availibility(this.form)
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