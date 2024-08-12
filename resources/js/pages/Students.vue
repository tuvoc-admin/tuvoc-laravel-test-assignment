<template>
  <v-container>
    <v-btn color="primary" class="mb-8" elevation="2" @click="createStudentModal = true">Add Student</v-btn>
    <v-data-table :headers="headers" :items="items" :items-per-page="10" class="elevation-1">
      <template v-slot:item.availability="{ item }">
        <template v-if="!isEmpty(item.availability)">
          <span
            v-for="(d, dk) in item.aval_days"
            :key="`day_${dk}`"
            :style="{backgroundColor: d.color, marginRight: '2px', borderRadius: '50%', padding: '2px', color: '#fff'}"
          >
            {{ d.day }}
          </span>
        </template>
        <template v-else>
          --
        </template>
      </template>
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
            <v-alert color="red" type="error" :icon="false">
              <div v-for="(errMsg, ek) in errors" :key="`error_${ek}`">
                {{ ek + 1 }}). {{ errMsg }}
              </div>
            </v-alert>
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
                <div>
                  <label>
                    <span class="mr-1">Monday</span>
                    <input type="checkbox" v-model="day.monday" :label="`Monday`">
                  </label>
                </div>
                <div>
                  <label>
                    <span class="mr-1">Tuesday</span>
                    <input type="checkbox" v-model="day.tuesday" :label="`tuesday`">
                  </label>
                </div>
                <div>
                  <label>
                    <span class="mr-1">Wednesday</span>
                    <input type="checkbox" v-model="day.wednesday" :label="`Monday`">
                  </label>
                </div>
                <div>
                  <label>
                    <span class="mr-1">Thursday</span>
                    <input type="checkbox" v-model="day.thursday" :label="`Monday`">
                  </label>
                </div>
                <div>
                  <label>
                    <span class="mr-1">Friday</span>
                    <input type="checkbox" v-model="day.friday" :label="`Monday`">
                  </label>
                </div>
                <div>
                  <label>
                    <span class="mr-1">Saturday</span>
                    <input type="checkbox" v-model="day.saturday" :label="`Monday`">
                  </label>
                </div>
                <div>
                  <label>
                    <span class="mr-1">Sunday</span>
                    <input type="checkbox" v-model="day.sunday" :label="`Monday`">
                  </label>
                </div>
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
            Save Avaibility
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { dcdLrvlValdtnErr, isEmpty } from "@/helpers"
const days = [
  'monday',
  'tuesday',
  'wednesday',
  'thursday',
  'friday',
  'saturday',
  'sunday',
]
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
      { text: 'Availability', value: 'availability', sortable: false },
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
    openAvailibilityModal(item) {
      this.resetDays()
      this.currentStudent = item
      if(!isEmpty(item?.availability)){
        const availability = item.availability
        days.forEach(day => {
          if(availability[day]){
            this.day[day] = true
          }
        })
      }
      this.availibilityModal = true
    },
    async saveAvaibility() {
      this.loading = true
      const form = new FormData()
      days.forEach(day => {
        form.append(day, this.day[day] ? 1 : 0)
      })
      form.append('student_id', this.currentStudent.id)
      const res = await this.updateAvailability(form)
      if(res?.status === 200 && res?.data?.success){
        this.currentStudent = {}
        this.availibilityModal = false
      }
      this.loading = false
    },
    resetDays(){
      days.forEach(day => {
        this.day[day] = false
      })
    }
  },
  computed: {
    ...mapGetters('student', ['students']),
    items: {
      get(){
        return this.students.map(i => {
          const aval_days = days.map(d => {
            const d_name = d.substring(0, 1).toUpperCase()
            let isAvail = false
            if(!isEmpty(i?.availability)){
              isAvail = i.availability[d] ? true : false
            }
            return {
              day: d_name,
              isAvail,
              color: isAvail ? 'green' : 'red'
            }
          })
          return {
            ...i,
            aval_days
          }
        })
      }
    }
  },
  mounted() {
    this.resetDays()
    this.getStudents()
  }
}
</script>