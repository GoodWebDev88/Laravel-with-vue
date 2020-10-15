<template>
  <!--<cube-spin></cube-spin>-->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <card class="card-plain">
            <template slot="header">
              <h4 class="card-title">Projects</h4>
              <button type="button" class="btn btn-outline-primary magin-top" @click="onNewDialog()">Add New Project</button>
            </template>
            <div class="table-responsive">
              <!--<l-table class="table-hover"-->
                       <!--:columns="tableColumns"-->
                       <!--:data="projects">-->
              <!--</l-table>-->
              <table class="table">
                <thead>
                <slot name="columns">
                  <th v-for="column in tableColumns">{{column}}</th>
                  <th colspan="1">Actions</th>
                </slot>
                </thead>
                <tbody>
                <tr v-for="item in projects">
                  <slot :row="item">
                    <td v-for="column in tableColumns" v-if="hasValue(item, column)">{{itemValue(item, column)}}</td>
                    <td>
                      <div>
                        <a @click="onEdit(item)"><font-awesome-icon icon="pen-alt" /></a>
                        <a @click="ondelete(item)"><font-awesome-icon icon="trash-alt" /></a>
                      </div>
                    </td>
                  </slot>
                </tr>
                </tbody>
              </table>
            </div>
          </card>
        </div>

      </div>
    </div>
    <modal name="project-modal" transition="pop-out" :width="400" height="auto">
      <div class="container-fluid">
        <h5>
          <span v-if="!editID">Add New Project</span>
          <span v-if="editID">Update Project</span>
        </h5>
        <form @submit.stop.prevent="onSubmit">
          <div class="">
            <label :class="{ 'hasError': $v.form.name.$error }">name:</label>
            <input type="text" v-model="form.name" style="width: 100%">
          </div>
          <div class="btnGroup">
            <button type="button" class="btn btn-outline-secondary pull-right" @click="onCancel()">Cancel</button>
            <button type="submit" class="btn btn-outline-primary pull-right">Submit</button>
          </div>
        </form>
      </div>
    </modal>
  </div>
</template>
<script>

  import { mapGetters } from "vuex";
  import { required } from 'vuelidate/lib/validators'
  import LTable from '../../../components/UIComponents/Table.vue'
  import Card from '../../../components/UIComponents/Cards/Card.vue'
  // import CubeSpin from 'vue-loading-spinner/components/Cube'

  export default {
    components: {
      LTable,
      Card
    },
    computed: {
      ...mapGetters({
        projects: "projectStore/projects",
        userID: "userID"
      })
    },
    data () {
      return {
        form: {
          name: null,
        },
        tableColumns: ['Id', 'Name'],
        editID: null
      }
    },
    validations: {
      form: {
        name: {
          required
        }
      }
    },
    methods: {
      onNewDialog() {
        this.editID = null;
        this.form.name = null;
        this.$modal.show('project-modal');
        },
      onCancel() {
        this.$modal.hide('project-modal');
      },
      onEdit(project) {
        this.editID = project.id;
        this.form.name = project.name;
        this.$modal.show('project-modal');
      },
      ondelete(project) {
        if (confirm("Are you sure you want to delete this project?")) {
          this.$store
              .dispatch("projectStore/removeProject", project.id)
              .then(msg => {
                console.log(msg);
              })
              .catch(e => {
              });
        }
      },
      onSubmit() {
        this.$v.form.$touch();
        if(this.$v.form.$error) return;

        if (!this.editID) { // create
          this.$store
              .dispatch("projectStore/createProject", {
                name: this.form.name
              })
              .then(msg => {
                this.$modal.hide('project-modal');
              })
              .catch(e => console.log(e));
        } else { // update
          console.log('dddd', this.editID)
          this.$store
              .dispatch("projectStore/updateProject", {
                id: this.editID,
                name: this.form.name,
                user_id: this.userID
              })
              .then(msg => {
                this.$modal.hide('project-modal');
              })
              .catch(e => console.log(e));
        }
      },
      hasValue (item, column) {
        return item[column.toLowerCase()] !== 'undefined'
      },
      itemValue (item, column) {
        return item[column.toLowerCase()]
      }
    },
    created() {
      this.$store
          .dispatch("projectStore/getProjectsFromApi")
          .then(msg => {
          })
          .catch(e => console.log(e));
    }
  }
</script>
<style lang='scss'>
  .magin-top {
    margin-top: 10px;
  }
  .hasError {
    color: red;
  }
  td {
    div {
      a {
        margin: 0 10px;
      }
    }
  }
</style>
