<template>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <card>
            <template slot="header">
              <!--<button type="button" class="btn btn-outline-primary pull-right" @click="onExternalDialog()">External Calendar</button>-->
              <drop-down title="External Calendar" style="list-style: none; max-width: 180px">
                <a class="dropdown-item" @click="onExternalDialog()">Import a calendar</a>
                <a class="dropdown-item" @click="onExternalSet()">Setting</a>
              </drop-down>
            </template>
            <div class=''>
              <!--<b-btn variant="success" @click="toggleWeekends" size="sm">toggle weekends</b-btn>-->
              <!--<button @click="">the past</button>-->
              <FullCalendar
                  class='book-calendar'
                  ref="fullCalendar"
                  defaultView="dayGridMonth"
                  themeSystem="bootstrap"
                  :header="{
                  left: 'prev,next today',
                  center: 'title',
                  right: 'dayGridYear,dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                }"
                  :plugins="calendarPlugins"
                  :weekends="calendarWeekends"
                  :editable="true"
                  :height="600"
                  :events="events"
                  @dateClick="handleDateClick"
                  @eventResize="handleResize"
                  @eventDrop="handleDrop"
                  @eventClick="handleEventClick"
                  @eventRender="eventRender"
              />
            </div>
          </card>
          <modal name="external-calendar" transition="pop-out" :width="400" height="auto">
            <div class="container-fluid">
              <h5>
                <span>Import External Calendar</span>
              </h5>
              <form @submit.stop.prevent="onExternalSubmit">
                <div class="col-12 form-inline">
                  <label class="col-3">Name:</label>
                  <input class="col-9" type="text" v-model="externalform.name" placeholder="Enter a name">
                  <span class="col-9 offset-3" style="color: red" v-if="$v.externalform.name.$error">Name is required</span>
                </div>
                <div class="col-12 form-inline">
                  <label class="col-3">URL:</label>
                  <input class="col-9" type="text" v-model="externalform.url" placeholder="URL of Calendar">
                  <span class="col-9 offset-3" style="color: red" v-if="$v.externalform.url.$error">URL is required</span>
                </div>
                <div class="col-12 form-inline">
                  <label class="col-3">Color:</label>
                  <sketch-picker
                      @input="updateValue"
                      :value="externalform.color"
                      :presetColors="[
    '#f00', '#00ff00', '#00ff0055', 'rgb(201, 76, 76)', 'rgba(0,0,255,1)', 'hsl(89, 43%, 51%)', 'hsla(89, 43%, 51%, 0.6)'
  ]"
                  ></sketch-picker>
                  <!--<chrome-picker :value="externalform.color" @input="updateValue"></chrome-picker>-->
                </div>

                <div class="btnGroup">
                  <button type="button" class="btn btn-outline-secondary pull-right" @click="onCancel()">Cancel</button>
                  <button type="submit" class="btn btn-outline-primary pull-right">Import</button>
                </div>
              </form>
            </div>
          </modal>

          <modal name="external-setting" transition="pop-out" :width="500" height="auto">
            <div class="container-fluid">
              <h5>
                <span>Manage External Calendar</span>
              </h5>
              <div class="table-responsive" style="max-height: 500px; overflow: auto">
                <table class="table">
                  <thead>
                  <slot name="columns">
                    <th style="min-width: 150px">Name</th>
                    <th>Color</th>
                    <th colspan="1">Show/Hide</th>
                  </slot>
                  </thead>
                  <tbody>
                  <tr v-for="item in externals">
                    <slot :row="item">
                      <td style="min-width: 150px">{{item.name}}</td>
                      <td><div style="display: flex"><span  v-bind:style="style(item)">{{item.color}}</span></div></td>
                      <td>
                        <label class="switch">
                          <input type="checkbox" v-model="item.show" v-bind:checked="item.show" @change="onShowChange(item)">
                          <span class="slider"></span>
                        </label>
                        <!--<div>-->
                          <!--<a @click="onEdit(item)"><font-awesome-icon icon="pen-alt" /></a>-->
                          <!--<a @click="ondelete(item)"><font-awesome-icon icon="trash-alt" /></a>-->
                        <!--</div>-->
                      </td>
                    </slot>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </modal>

          <modal name="event-modal" transition="pop-out" :width="400" height="auto">
              <form @submit.prevent="onSubmit">
                <div class="box box-part">
                    <label class="event-title">Add an event</label>
                    <input type="text" v-model="form.title" @blur="$v.form.title.$touch()" style="width: 100%">
                  <span style="color: red" v-if="$v.form.title.$error">Title is required</span>
                </div>
                <div class="box box-content">
                  <label class="event-title">Project: </label>
                  <select v-model="form.project" class="select" data-dropup-auto="false"
                          data-size="10">
                    <option v-for="project in projects" v-bind:value="project.id">{{project.name}}</option>
                  </select>
                  <span style="color: red" v-if="$v.form.project.$error">Project is required</span>
                </div>
                <div class="box" v-if="!form.multi_day">
                  <div class="box-content">
                    <span>When?</span>
                    <div class="my-flex">
                      <div style="width: 60%">
                        <input type="date" class="dateinput"
                               v-on:keyup="preventkey" v-on:keypress="preventkey" v-model="form.start_date" style="width: 80%">
                        <span style="width: 10%;margin-left: 10px">
                        at
                      </span>
                      </div>
                      <div  style="width: 30%; flex: 1">
                        <input type="text" class="dateinput" v-model="form.start_time" style="width: 100%" placeholder="any time">
                        <label style="color: #5e5e5e">ex: 9: 30 am</label>
                      </div>

                    </div>
                    <span style="color: red" v-if="$v.form.start_date.$error">Start date is required</span>
                  </div>
                  <div>
                    <a class="multi-event" @click="onMulti(true)"><span>Make this a multi-day event</span></a>
                  </div>
                </div>

                <div class="box" v-if="form.multi_day">
                  <div class="flexdiv">
                    <span>Starts</span>
                    <div>
                      <input type="date" class="dateinput" v-model="form.start_date"
                             v-on:keyup="preventkey" v-on:keypress="preventkey" style="width: 100%">
                      <span style="color: red" v-if="$v.form.start_date.$error">Start date is required</span>
                    </div>
                  </div>
                  <div class="flexdiv">
                    <span>Ends</span>
                    <div>
                      <input type="date" class="dateinput" v-model="form.end_date"
                             v-on:keyup="preventkey" v-on:keypress="preventkey" style="width: 100%">
                      <span style="color: red" v-if="$v.form.end_date.$error">End date is required</span>
                    </div>
                  </div>
                  <div class="flexdiv">
                    <span></span>
                    <a class="multi-event" @click="onMulti(false)"><span>Make this a one-day event</span></a>
                  </div>
                </div>

                <div class="box" style="padding-bottom: 0">
                    <div class="flex-column">
                      <span>This is </span>
                      <label class="radio-inline"><input type="radio" name="optradio" v-model="form.type" value="event" @click="onEventType(true)"> an event</label>
                      <label class="radio-inline"><input type="radio" name="optradio" v-model="form.type" value="milestone" @click="onEventType(false)"> a milestone</label>
                    </div>
                    <span style="font-size: 12px">Milestones have checkboxes that let you mark them as done. They can be late, too.</span>
                </div>

                <div class="box" v-if="!eventType" style="padding-bottom: 0">
                  <div style="display: grid">
                    <span>Who's responsible?</span>
                    <select v-model="form.user" class="select" data-dropup-auto="false"
                            data-size="10" style="width: 30%;">
                      <option v-for="user in users" v-bind:value="user.id">{{user.name}}</option>
                    </select>
                  </div>
                  <!--<span style="font-size: 12px">It will send an email reminder 48 hours before the milestone is due.</span>-->
                </div>

                <div style="padding: 2px 20px">
                  <hr style="border-top: dotted 1px;" />
                </div>
                <div class="box" style="padding-top: 0; display: flex; justify-content: space-between">
                  <div class="flexdiv">
                    <button class="addbtn" type="submit" >
                      <span v-if="!editEvent">Add this event</span>
                      <span v-if="editEvent">Save changes</span>
                    </button>
                    <span style="padding: 0 10px; width: 30px;">
                        or
                      </span>
                      <a class="cancelbtn" @click="onCancel()"><span>Cancel</span></a>
                  </div>
                  <div class="flexdiv">
                    <a v-if="editEvent" class="pull-right" @click="ondelete()"><span><font-awesome-icon icon="trash-alt" /></span></a>
                  </div>
                </div>
              </form>
          </modal>
        </div>

      </div>
    </div>
  </div>
</template>
<script>

  import { mapGetters } from "vuex";
  import FullCalendar from '@fullcalendar/vue'
  import dayGridPlugin from '@fullcalendar/daygrid'
  import timeGridPlugin from '@fullcalendar/timegrid'
  import interactionPlugin from '@fullcalendar/interaction'
  import bootstrapPlugin from '@fullcalendar/bootstrap'
  import Card from '../../../components/UIComponents/Cards/Card.vue'
  import PRadio from "../../UIComponents/Inputs/Radio"
  import { required } from 'vuelidate/lib/validators'
  import ical from 'ical'
  import { Chrome, Sketch } from 'vue-color'

  export default {
    components: {
      PRadio,
      FullCalendar,
      Card,
      // 'photoshop-picker': Photoshop,
      'chrome-picker': Chrome,
      'sketch-picker': Sketch,
    },
    computed: {
      ...mapGetters({
        projects: "projectStore/projects",
        users: "userStore/users",
        events: "eventStore/events",
        externals: "eventStore/externals",
        userID: "userID"
      })
    },
    validations: {
      form: {
        title: {
          required
        },
        start_date: {
          required
        },
        end_date: {
          required
        },
        project: {
          required
        }
      },
      externalform: {
        name: {
          required
        },
        url: {
          required
        }
      }
    },
    data: function() {
      return {
        calendarPlugins: [
          dayGridPlugin,
          timeGridPlugin,
          bootstrapPlugin,
          interactionPlugin// needed for dateClick
        ],
        calendarWeekends: true,
        editEvent: null,
        eventType: false,
        externalform: {
          name: null,
          url: null,
          color: '#36E841'
        },
        form: {
          title: null,
          start_date: "",
          end_date: null,
          start_time: "",
          end_time: "",
          project: null,
          user: null,
          multi_day: false,
          type: "event"
        },
      }
    },
    methods: {
      style(e) {
        return 'background-color: ' + e.color;
      },
      toggleWeekends() {
        this.calendarWeekends = !this.calendarWeekends // update a property
      },
      preventkey(e) {
        e.preventDefault();
      },
      gotoEvent(event) {
        const date_time = new Date(event.start);
        const date = date_time.getFullYear() + '-' + ("0" + (date_time.getMonth() + 1)).slice(-2) + '-' + ("0" + date_time.getDate()).slice(-2);
        let calendarApi = this.$refs.fullCalendar.getApi();
        calendarApi.gotoDate(date) // goto map event month
      },
      onExternalDialog() {
        this.externalform.name = null;
        this.externalform.url = null;
        this.$modal.show('external-calendar');
      },
      onExternalSet() {
        this.$modal.show('external-setting');
      },
      onMulti(flag) {
        this.form.multi_day = flag;
      },
      onCancel() {
        this.$modal.hide('event-modal');
        this.$modal.hide('external-calendar');
        this.$modal.hide('external-setting');
      },
      onEventType(type) {
        this.eventType = type;
        if (this.eventType) {
          this.form.type = "event";
        } else {
          this.form.type = "milestone";
        }
      },
      ondelete() {
        if (confirm("Are you sure you want to delete this event?")) {
          this.$store
              .dispatch("eventStore/removeEvent", this.editEvent.id)
              .then(msg => {
                console.log(msg);
                this.$modal.hide('event-modal');
              })
              .catch(e => {
                this.$modal.hide('event-modal');
              });
        }
      },

      convertTime(isoDate) {
        const date_time = new Date(isoDate);
        const date = date_time.getFullYear() + '-' + ("0" + (date_time.getMonth() + 1)).slice(-2) + '-' + ("0" + date_time.getDate()).slice(-2);
        return {date: date}
      },
      handleDateClick(arg) {
        this.editEvent = null;
        this.eventType = true;
        this.form.start_date = arg.dateStr;
        this.form.end_date = arg.dateStr;
        this.form.start_time = "";
        this.form.end_time = "";
        this.form.user = this.userID;
        this.form.title = null;
        this.form.type = "event";
        this.form.project = null;
        this.form.multi_day = false;
        this.$modal.show('event-modal');
      },
      handleEventClick(arg) {

        const editEvent = this.events.find(e => e.id === parseInt(arg.event.id));
        this.editEvent = Object.assign({}, editEvent);
        if (arg.jsEvent.srcElement.type === "checkbox") {
          this.editEvent.complete = arg.jsEvent.target.checked;
          this.$store
              .dispatch("eventStore/updateEvent", this.editEvent)
              .then(msg => {
                this.$modal.hide('event-modal');
              })
              .catch(e => {
                this.$modal.hide('event-modal');
              });
          return;
        }

        const start_date = this.convertTime(arg.event.start).date;
        let end_date = this.convertTime(arg.event.start).date;
        if (arg.event.end) {
          end_date = this.convertTime(arg.event.end).date;
        }

        this.form.start_date = start_date;
        this.form.start_time = editEvent.start_time;
        this.form.end_date = end_date;
        this.form.end_time = editEvent.end_time;
        this.form.title = editEvent.title;
        this.form.type = editEvent.type;
        this.form.user = editEvent.user_id;
        this.form.project = editEvent.project_id;
        this.eventType = true;
        if (editEvent.type === "milestone") {
          this.eventType = false;
        }
        if (end_date) {
          this.form.multi_day = true;
        } else {
          this.form.multi_day = false;
        }
        this.$modal.show('event-modal');
      },
      handleResize(arg) {
        console.log('resize', arg)
      },
      handleDrop(arg) {
        let editEvent = this.events.find(e => e.id === parseInt(arg.event.id));
        this.editEvent = Object.assign({}, editEvent);
        this.editEvent.start = arg.event.start;
        this.editEvent.end = arg.event.end;
        this.$store
            .dispatch("eventStore/updateEvent", this.editEvent)
            .then(msg => {
              console.log(msg);
              this.$modal.hide('event-modal');
            })
            .catch(e => {
              console.error('ddd', e);
              this.$modal.hide('event-modal');
            });
      },
      eventRender(event, element, view ) {
        let checkEvent = this.events.find(e=> e.id === parseInt(event.event.id));
        if (checkEvent) {
          if (checkEvent.type === "milestone") {
            let answer = document.createElement('input');
            answer.setAttribute('type', 'checkbox');
            answer.setAttribute('id',   'answer');
            if (checkEvent.complete === 1) {
              answer.setAttribute('checked',   'true');
            }

            event.el.getElementsByClassName("fc-content")[0].insertBefore(answer, event.el.getElementsByClassName("fc-content")[0].firstChild);
            if (checkEvent.complete === 1) {
              event.el.getElementsByClassName("fc-content")[0].classList.add("completed");
            }
          } else if (checkEvent.type === "event") {
            let li = document.createElement('li');
            event.el.getElementsByClassName("fc-content")[0].insertBefore(li, event.el.getElementsByClassName("fc-content")[0].firstChild);
          } else {
            const external = this.externals.find(c => c.id === checkEvent.external_id);
            // if (external.show === 1) {
            //   checkEvent.backgroundColor = external.color;
            //   checkEvent.borderColor = external.color;
            //   checkEvent.textColor = '#000';
            // } else {
            //   checkEvent.backgroundColor = 'transparent';
            //   checkEvent.borderColor = 'transparent';
            //   checkEvent.textColor = 'transparent';
            // }
          }
        }
      },
      onSubmit() {
        this.$v.form.$touch();
        if(this.$v.form.$error) return
        let start = new Date(this.form.start_date).toISOString();
        // console.log('dddd', new Date('2019-09-19 20:00'))
        let end = null;
        if (this.form.multi_day) {
          end = new Date(this.form.end_date).toISOString();
        }

        let event_data = {
          title: this.form.title,
          type: this.form.type,
          start: start,
          end: end,
          start_time: this.form.start_time,
          end_time: this.form.end_time,
          user_id: this.form.user,
          project_id: this.form.project,
          multi_day: this.form.multi_day
        };
        if (!this.editEvent) { // create
          this.$store
              .dispatch("eventStore/createEvent", event_data)
              .then(msg => {
                console.log(msg);
                this.$modal.hide('event-modal');
              })
              .catch(e => {
                console.error('ddd', e);
                this.$modal.hide('event-modal');
              });
        } else { // update
          event_data.id = this.editEvent.id;
          this.$store
              .dispatch("eventStore/updateEvent", event_data)
              .then(msg => {
                console.log(msg);
                this.$modal.hide('event-modal');
              })
              .catch(e => {
                console.error('ddd', e);
                this.$modal.hide('event-modal');
              });
        }

      },
      onExternalSubmit() {
        this.$v.externalform.$touch();
        if(this.$v.externalform.$error) return;

        ical.fromURL(this.externalform.url, {}, (err, data) => {
          if (err) {
            window.alert('import error');
            return;
          }
          let externals = [];
          for (let k in data) {
            if (data.hasOwnProperty(k)) {
              let ev = data[k];
              if (data[k].type === 'VEVENT') {
                ev.title = ev.summary;
                ev.start = ev.start.toISOString();
                if (ev.end) {
                  ev.end = ev.end.toISOString();
                }
                externals.push(ev);
                // console.log('ddddd', data[k], ev.start)
                // console.log(`${ev.summary} is in ${ev.location} on the ${ev.start.getDate()} of ${months[ev.start.getMonth()]} at ${ev.start.toLocaleTimeString('en-GB')}`);

              }
            }
          }
          let event_data = {
            name: this.externalform.name,
            color: this.externalform.color,
            events: externals
          };
          this.$store
              .dispatch("eventStore/importExternalEvent", event_data)
              .then(msg => {
                this.$modal.hide('external-calendar');
              })
              .catch(e => console.log(e));
        });
      },
      updateValue(val) {
          this.externalform.color = val.hex;
      },
      onShowChange(val) {
        let event_data = {
          id: val.id,
          name: val.name,
          color: val.color,
          show: val.show
        };
        this.$store
            .dispatch("eventStore/updateExternal", event_data)
            .then(msg => {

            })
            .catch(e => console.log(e));
      }
    },
    created () {
      this.$store
          .dispatch("projectStore/getProjectsFromApi")
          .then(msg => {

          })
          .catch(e => console.log(e));
      this.$store
          .dispatch("eventStore/getEventsFromApi")
          .then(msg => {

          })
          .catch(e => console.log(e));
      this.$store
          .dispatch("userStore/getUsersFromApi")
          .then(msg => {
          })
          .catch(e => console.log(e));
    }
  }
</script>
<style lang="scss">
  .box {
    padding: 0 20px;
    padding-top: 10px;
    padding-bottom: 10px;
    .dateinput {
      height: 30px;
      -webkit-appearance: push-button !important;
    }
    select {
      height: 30px;
    }
  }
  .box-part {
    background-color: #f3f3f3;
    padding: 20px;
  }
  .event-title {
    font-weight: 500;
  }
  .box-content {
    display: grid;
  }
  .multi-event {
    span {
      color: #0e0be8;
      font-size: 12px;
      text-decoration: underline;
    }
  }
  .multi-event:hover {
    cursor: pointer;
    background-color: #0e0be8;
    span {
      color: #fff;
    }
  }
  .cancelbtn {
    height: 20px;
    padding: 0 0;
    span {
      color: #ff1826;
      font-size: 14px;
      text-decoration: underline;
    }
  }
  .cancelbtn:hover {
    cursor: pointer;
    background-color: #ff1826;
    span {
      color: #fff;
    }
  }
  .flexdiv {
    display: flex;
    padding-bottom: 5px;
    span {
      width: 30%;
    }
  }
  .radio-inline {
    margin-left: 8px;
  }
  .addbtn {
    font-size: 14px;
    height: 30px;
  }
  .hasError {
    border-color: red;
  }
  .completed {
    text-decoration: line-through;
    color: #0e0e0e;
  }
  .my-flex {
    justify-content: space-between;
    display: inline-flex;
  }
  input[type="date"]::-ms-clear {
    display: none;
  }
  input[type="date"]::-webkit-clear-button {
    display: none;
  }
  form {
    font-family: "Lucida Grande", sans-serif;
  }
  .form-inline {
    margin-top: 10px;
  }
  td {
    div {
      a {
        margin: 0 10px;
      }
    }
  }
  .fc-sun { background-color: #f7f7f7; }
  .fc-sat { background-color: #f7f7f7;  }
  .fc-today { background-color: #ffc;  }
  .fc-day-number {color: #5e5e5e}
  .fc-content {
    display: flex;
    input {
      margin-top: 3px;
    }
    li {
      width: 10px;
      margin-left: 5px;
    }
    span {
      margin-left: 3px;
    }
  }

  // show/hide slider css
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>
