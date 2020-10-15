<template>
  <div class="content" style="background-color: #f3f3f3; height: 100Vh">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 wraper">
          <card>
            <form @submit.prevent="onSubmit">
              <div class="form-group">
                <label>Email address</label>
                <input type="email" class="form-control" v-model="email" v-on:keyup="reset()" placeholder="Enter email">

              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" v-model="password" v-on:keyup="reset()" placeholder="Password">
              </div>
              <div v-if="error" class="form-group">
                <span style="color: red">Unkown user!</span>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Log in</button> <label>or</label> <a @click="onRegister()"><span>Register</span></a>
              </div>
            </form>
          </card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import Card from '../../components/UIComponents/Cards/Card.vue'
export default {
  name: "Login",
  components: {
    Card
  },
  data: () => ({
    email: null,
    password: null,
    error: false,
    submitting: false
  }),

  methods: {
    reset() {
      this.error = false;
    },
    onSubmit() {
      this.submitting = true;
      this.$store
        .dispatch("login", {
          email: this.email,
          password: this.password
        })
        .then(msg => {
          this.$router.push({ name: "Events" });
        })
        .catch(e => {
          this.error = true;
          this.submitting = false;
          console.error(e);
        });
    },
    onRegister() {
      this.$router.push({ name: "Register" });
    }
  }
};
</script>

<style lang='scss'>
  .wraper{
    margin-right: auto; /* 1 */
    margin-left:  auto; /* 1 */

    max-width: 500px; /* 2 */

    padding-top: 200px; /* 3 */
  }

  .input-group .form-control {
    border-top-right-radius: 0.25rem !important;
    border-bottom-right-radius: 0.25rem !important;
  }
  a:hover {
    cursor: pointer;
  }
</style>
