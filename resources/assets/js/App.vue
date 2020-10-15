<template>
  <div :class="{'nav-open': $sidebar.showSidebar}">
    <notifications></notifications>
    <router-view></router-view>
  </div>
</template>

<script>
  export default {
    name: "app",

    created() {
      const authToken = localStorage.getItem("token");
      const userID = parseInt(localStorage.getItem("userID"));
      const currentProject = JSON.parse(localStorage.getItem("currentProject"));
      if (authToken !== null) {
        this.$store.commit("setAuthToken", authToken);
        this.$store.commit("setUser", userID);
        this.$store.commit("setCurrentProject", currentProject);
      }
    }
  };
</script>
<style lang="scss">
  // Fullcalendar Css
  @import '~@fullcalendar/core/main.css';
  @import '~@fullcalendar/bootstrap/main.css';
  @import '~@fullcalendar/daygrid/main.css';
  @import '~@fullcalendar/timegrid/main.css';
  .wrapper {
    font-family: "Lucida Grande", sans-serif;
  }
  .vue-notifyjs.notifications{
    .list-move {
      transition: transform 0.3s, opacity 0.4s;
    }
    .list-item {
      display: inline-block;
      margin-right: 10px;

    }
    .list-enter-active {
      transition: transform 0.2s ease-in, opacity 0.4s ease-in;
    }
    .list-leave-active {
      transition: transform 1s ease-out, opacity 0.4s ease-out;
    }

    .list-enter {
      opacity: 0;
      transform: scale(1.1);

    }
    .list-leave-to {
      opacity: 0;
      transform: scale(1.2, 0.7);
    }
  }
  .container-fluid {
    padding: 20px;
  }
  .btnGroup {
    padding: 10px;
    button {
      margin-left: 3px;
      margin-bottom: 10px;
    }
  }
</style>
