<template>
  <table class="table">
    <thead>
      <slot name="columns">
        <th v-for="column in columns">{{column}}</th>
        <th colspan="1">Actions</th>
      </slot>
    </thead>
    <tbody>
    <tr v-for="item in data">
      <slot :row="item">
        <td v-for="column in columns" v-if="hasValue(item, column)" @click="gotoProject(item)">{{itemValue(item, column)}}</td>
        <td>
          <div>
            <a><font-awesome-icon icon="pen-alt" /></a>
            <a><font-awesome-icon icon="trash-alt" /></a>
          </div>
        </td>
      </slot>
    </tr>
    </tbody>
  </table>
</template>
<script>
  export default {
    name: 'l-table',
    props: {
      columns: Array,
      data: Array
    },
    methods: {
      gotoProject(project) {
        this.$store.commit("setCurrentProject", project);
        this.$router.push({ name: "Events" });
      },
      hasValue (item, column) {
        return item[column.toLowerCase()] !== 'undefined'
      },
      itemValue (item, column) {
        return item[column.toLowerCase()]
      }
    }
  }
</script>
<style lang="scss">
  td {
    div {
      a {
        margin: 0 10px;
      }
    }
  }
</style>
