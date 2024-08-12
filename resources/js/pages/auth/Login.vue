<template>
  <v-container class="d-flex align-center justify-center" style="height: 100vh;">
    <v-card class="pa-5" width="500">
      <v-card-title class="headline text-center">Login</v-card-title>
      <v-card-text>
        <v-form ref="form" v-model="valid">
          <v-text-field v-model="email" label="Email" :rules="emailRules" required></v-text-field>
          <v-text-field v-model="password" label="Password" :rules="passwordRules" type="password"
            required></v-text-field>
        </v-form>
      </v-card-text>
      <v-alert v-if="errMsg" border="left" color="red" :icon="$mdiAccount" type="error">{{ errMsg }}</v-alert>
      <v-card-actions class="d-flex justify-center">
        <v-btn color="primary" @click="attemptLogin" :loading="loading" :disabled="!valid || loading">Login</v-btn>
      </v-card-actions>
    </v-card>
  </v-container>
</template>

<script>
import { mapActions } from 'vuex'
import { dcdLrvlValdtnErr, isEmpty } from "@/helpers"

export default {
  data() {
    return {
      email: '',
      password: '',
      valid: false,
      emailRules: [
        v => !!v || 'Email is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      passwordRules: [
        v => !!v || 'Password is required',
        v => v.length >= 6 || 'Password must be at least 6 characters',
      ],
      loading: false,
      errMsg: null,
    };
  },
  methods: {
    ...mapActions('auth', [
      'login'
    ]),
    async attemptLogin() {
      this.errMsg = null;
      if (this.$refs.form.validate()) {
        this.loading = true
        const res = await this.login({
          email: this.email,
          password: this.password,
        })
        if(res?.status === 200){
          this.$router.push({name: 'dashboard'})
        }else if (res?.status !== 200 && res?.message){
          this.errMsg = res?.message
        }
        this.loading = false
      }
    },
  },
};
</script>

<style>
.v-container {
  background-color: #f5f5f5;
}
</style>