<template>
    <v-container>
        <v-row>
            <v-col cols="6" class="mx-auto mt-15">
                <v-form ref="form" @submit.prevent="uploadDocx" class="mb-9">
                    <v-select :items="students" v-model="student_id" label="Select Student" item-value="id"
                        item-text="first_name" />
                    <v-file-input truncate-length="15" v-model="doc" :loading="loading"
                        placeholder="Select docx file" />
                    <v-btn type="submit" color="blue darken-1">
                        submit
                    </v-btn>
                </v-form>
                <template v-if="!isEmpty(error)">
                    <v-alert color="red" type="error" :icon="false">
                        <div v-for="(errMsg, ek) in error" :key="`error_${ek}`">
                            {{ errMsg }}
                        </div>
                    </v-alert>
                </template>
                <template v-if="!isEmpty(success)">
                    <v-alert color="green" type="success" :icon="false">
                        <div>
                            {{ success }}
                        </div>
                    </v-alert>
                </template>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { dcdLrvlValdtnErr, isEmpty } from "@/helpers"

export default {
    name: "UploadSession",

    data: () => ({
        student_id: null,
        doc: null,
        loading: false,
        error: [],
        success: null
    }),

    methods: {
        isEmpty,
        ...mapActions('student', [
            'getStudents',
        ]),
        ...mapActions('uploadSession', [
            'uploadToParceDocx',
        ]),
        async uploadDocx() {
            this.error = []
            const form = new FormData()
            form.append('student_id', this.student_id)
            form.append('docx_file', this.doc)
            this.loading = true
            const res = await this.uploadToParceDocx(form)
            if (!res?.data?.success && res?.status === 400) {
                this.error = [res.data.error]
            } else if (!res?.data?.success && res?.status === 200 && res?.data?.message) {
                this.error = dcdLrvlValdtnErr(res.data.message)
            } else if (res?.data?.success) {
                this.success = res?.data?.message
                setTimeout(() => {
                    this.success = null;
                }, 5000);
            }
            this.loading = false
        }
    },

    computed: {
        ...mapGetters('student', ['students']),
    },
    beforeMount() {
        this.getStudents()
    }
}
</script>