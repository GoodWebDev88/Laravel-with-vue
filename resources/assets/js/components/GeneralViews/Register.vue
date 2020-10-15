<template>
  <div class="content" style="background-color: #f3f3f3; height: 100Vh">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 wraper">
          <card>
            <form @submit.prevent="onSubmit">
                <div class="form-group">
                  <label :class="{ 'hasError': $v.form.username.$error }">User Name</label>
                  <input type="text" class="form-control" v-on:keyup="reset()"
                         v-model="form.username" placeholder="User Name">
                </div>
                <div class="form-group">
                  <label :class="{ 'hasError': $v.form.email.$error }">Email address</label>
                  <input type="email" class="form-control" v-model="form.email" v-on:keyup="reset()"
                         placeholder="Enter email">
                </div>

              <div class="form-group">
                <label :class="{ 'hasError': $v.form.password.$error }">Password</label>
                <input type="password" class="form-control" v-model="form.password"
                       v-on:keyup="checkpass()" placeholder="Password">
              </div>
              <div class="form-group">
                <label :class="{ 'hasError': $v.form.repassword.$error }">Password Confirm</label>
                <input type="password" class="form-control" v-on:keyup="checkpass()"
                       v-model="form.repassword" placeholder="Confirm Password">
                <span v-if="confirmpass" style="color: red">invalid password</span>
              </div>
              <div class="form-group">
                <span v-if="posterr" style="color: red">This user already exist</span>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Register</button> or <a @click="onLogin()"><span>Log in</span></a>
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
  import { required, email } from 'vuelidate/lib/validators'

export default {
  name: "Register",
  components: {
    Card
  },
  data: () => ({
    form: {
      username: "",
      email: "",
      password: "",
      repassword: "",
    },
    confirmpass: false,
    posterr: false,
  }),
  validations: {
    form: {
      username: {
        required
      },
      email: {
        required, email
      },
      password: {
        required
      },
      repassword: {
        required
      }
    }
  },
  methods: {
    onLogin() {
      this.$router.push({ name: "login" });
    },
    checkpass() {
      if (this.form.password !== this.form.repassword) {
        this.confirmpass = true;
      } else {
        this.confirmpass = false;
      }
    },
    reset() {
      this.posterr = false;
    },
    onSubmit() {
      this.$v.form.$touch();
      if(this.$v.form.$error) return
      if (this.form.password !== this.form.repassword) {
        this.confirmpass = true; return
      }
      this.$store
        .dispatch("userStore/createUser", {
          username: this.form.username,
          email: this.form.email,
          password: this.form.password
        })
        .then(msg => {
          console.log(msg);
          this.$router.push({ name: "login" });
        })
        .catch(e => {
          console.error('ddd', e);
          this.posterr = true;
        });
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
  .hasError {
    color: red;
  }
</style>
