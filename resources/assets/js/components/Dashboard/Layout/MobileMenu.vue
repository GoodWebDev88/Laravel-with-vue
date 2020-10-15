<template>
  <ul class="nav nav-mobile-menu">
    <!--<drop-down>-->
      <!--<template slot="title">-->
        <!--<i class="fa fa-globe"></i>-->
        <!--<b class="caret"></b>-->
        <!--<span class="notification">5 Notifications</span>-->
      <!--</template>-->
      <!--<a class="dropdown-item" href="#">Notification 1</a>-->
      <!--<a class="dropdown-item" href="#">Notification 2</a>-->
      <!--<a class="dropdown-item" href="#">Notification 3</a>-->
      <!--<a class="dropdown-item" href="#">Notification 4</a>-->
      <!--<a class="dropdown-item" href="#">Another notification</a>-->
    <!--</drop-down>-->
    <!--<li class="nav-item">-->
      <!--<a href="#" class="nav-link">-->
        <!--<i class="nc-icon nc-zoom-split hidden-lg-up"></i>-->
        <!--<span class="d-lg-none">Search</span>-->
      <!--</a>-->
    <!--</li>-->
    <!--<drop-down title="Action">-->
      <!--<a class="dropdown-item" href="#">Action</a>-->
      <!--<a class="dropdown-item" href="#">Another action</a>-->
      <!--<a class="dropdown-item" href="#">Something</a>-->
      <!--<a class="dropdown-item" href="#">Something else here</a>-->
      <!--<div class="divider"></div>-->
      <!--<a class="dropdown-item" href="#">Log out</a>-->
    <!--</drop-down>-->

    <li class="nav-item">
      <a class="nav-link" href="#pablo">
        <span @click="onLogout()" class="no-icon">Log out</span>
      </a>
    </li>
  </ul>
</template>
<script>
  import DropDown from '../../../components/UIComponents/Dropdown.vue'

  export default {
    components: {
      DropDown
    },
    methods: {
      onLogout() {
        this.$store
            .dispatch("logout")
            .then(msg => {
              this.$router.push({ name: "login" });
            })
            .catch(e => {
              console.error(e);
            });
      }
    }
  }
</script>
<style>
  a:hover {
    cursor: pointer;
  }
</style>
