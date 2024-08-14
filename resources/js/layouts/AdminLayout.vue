<template>
  <v-app>
    <v-navigation-drawer app>
      <v-list-item
        link
        title="My Application"
        subtitle="Vuetify"
        :to="{ name: 'dashboard' }"
        >Student App</v-list-item
      >
      <v-list-item :to="{ name: 'students' }">
        <v-list-item-content>
          <v-list-item-title>Students</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item :to="{ name: 'upload-session' }">
        <v-list-item-content>
          <v-list-item-title>Upload Session</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider></v-divider>
      <v-list-item @click="logOut">
        <v-list-item-content>
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-navigation-drawer>

    <v-app-bar app>
      <h2>{{ pageMeta.title }}</h2>
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>

    <v-footer app> </v-footer>
  </v-app>
</template>
    
  <script>
import { mapActions } from 'vuex';
export default {
  name: "AdminLayout",
  computed: {
    pageMeta: {
      get() {
        return this.$route.meta;
      },
    },
  },
  methods: {
    ...mapActions('auth', ['logout']),
    async logOut(){
      const res = await this.logout()
      if(res.status === 200){
        this.$router.push({name: 'login'})
      }
    }
  }
};
</script>
    