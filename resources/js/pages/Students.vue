<template>
  <v-container>
    <v-btn color="primary" class="mb-8" elevation="2" @click="createStudentModal = true">Add Student</v-btn>
    <v-data-table :headers="headers" :items="students" :items-per-page="10" class="elevation-1">
      <template v-slot:item.actions="{ item }">
        <v-btn color="primary" elevation="1" small @click="openAvailibilityModal(item)">Avaibility</v-btn>
      </template>
    </v-data-table>
    <v-dialog v-model="createStudentModal" persistent max-width="600px">
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
          <template v-if="!isEmpty(errors)">
            <v-alert v-for="(errMsg, ek) in errors" border="left" color="red" type="error" :key="`error_${ek}`">{{
              errMsg }}</v-alert>
          </template>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="createStudentModal = false">
            Close
          </v-btn>
          <v-btn color="blue darken-1" @click="saveStudent" :loading="loading" :disabled="loading" text>
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="availibilityModal" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Weekday Avaibility</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="student_form" v-model="validForm">
            <v-row>
              <v-col cols="12">
                <v-checkbox v-model="day.monday" :label="`Monday`"></v-checkbox>
              </v-col>
              <v-col cols="12">
                <v-checkbox v-model="day.tuesday" :label="`Tuesday`"></v-checkbox>
              </v-col>
              <v-col cols="12">
                <v-checkbox v-model="day.wednesday" :label="`Wednesday`"></v-checkbox>
              </v-col>
              <v-col cols="12">
                <v-checkbox v-model="day.thursday" :label="`Thursday`"></v-checkbox>
              </v-col>
              <v-col cols="12">
                <v-checkbox v-model="day.friday" :label="`Friday`"></v-checkbox>
              </v-col>
              <v-col cols="12">
                <v-checkbox v-model="day.saturday" :label="`Saturday`"></v-checkbox>
              </v-col>
              <v-col cols="12">
                <v-checkbox v-model="day.sunday" :label="`Suday`"></v-checkbox>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="availibilityModal = false">
            Close
          </v-btn>
          <v-btn color="blue darken-1" @click="saveAvaibility" :loading="loading" :disabled="loading" text>
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
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    form: {
      first_name: null,
      middle_name: null,
      last_name: null,
      email: null,
      date_of_birth: null,
    },
    emailRules: [
      v => !!v || 'Email is required',
      v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
    ],
    textRules: [
      v => !!v || 'Field is required',
    ],
    validForm: false,
    createStudentModal: false,
    availibilityModal: false,
    day: {
      monday: false,
      tuesday: false,
      wednesday: false,
      thursday: false,
      friday: false,
      saturday: false,
      sunday: false,
    },
    loading: false,
    errors: [],
    currentStudent: {}
  }),
  methods: {
    isEmpty,
    ...mapActions('student', [
      'createStudent',
      'getStudents',
      'updateAvailability',
    ]),
    async saveStudent() {
      this.errors = []
      if (this.$refs.student_form.validate()) {
        this.loading = true
        const res = await this.createStudent(this.form)
        if (!res?.data?.success && res?.data?.message) {
          const errors = dcdLrvlValdtnErr(res.data.message)
          if (!isEmpty(errors)) {
            this.errors = errors
          }
        } else if (res?.data?.success) [
          this.createStudentModal = false
        ]
        this.loading = false
      }
    },
    openAvailibilityModal(item){
      this.currentStudent = item
      this.availibilityModal = true
    },
    async saveAvaibility(){
      this.loading = true
      const res = this.updateAvailability({
        ...this.day,
        student_id: this.currentStudent.id
      })
      this.loading = false
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