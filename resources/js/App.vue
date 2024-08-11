<template>
  <v-app>
    
    <v-navigation-drawer app>
      <v-list-item link title="My Application" subtitle="Vuetify" :to="{ name: 'dashboard' }">Student App</v-list-item>
      <v-divider></v-divider>
      <v-list-item :to="{ name: 'users' }">
        <v-list-item-content>
          <v-list-item-title>Users</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-navigation-drawer>

    <v-app-bar app>
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
export default {
  name: "App",
};
</script>
  