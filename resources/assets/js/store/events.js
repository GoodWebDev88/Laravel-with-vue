import invoiceApi from '../services/api'
import _ from 'lodash';

export default {
  namespaced: true,

  state: {
    // This state contains all the users after fetching them from API
    events: [],
    allevents: [],
    externals: [],
    userLocation: {}
  },

  getters: {
    events: ({ events }) => events,
    externals: ({ externals }) => externals,
    getEvent: ({ events }) => id => events.find(event => event.id === id)
  },
  actions: {

    getEventsFromApi({ commit, rootState }) {
      return new Promise((resolve, reject) => {
        invoiceApi
            .get('/events', {
            })
            .then(res => {
              const externals = _.cloneDeep(res.data['externals']);
              res.data['events'].forEach(e => {
                if (e.type === 'VEVENT') {
                  const external = externals.find(c => c.id === e.external_id);
                    e.backgroundColor = external.color;
                    e.borderColor = external.color;
                    e.textColor = '#000';
                } else {
                  e.backgroundColor = 'transparent';
                  e.borderColor = 'transparent';
                  e.textColor = '#000';
                  e.borderColor = 'transparent';

                  if (e.multi_day === 1) {
                    e.backgroundColor = "#3788d8";
                    e.borderColor = "#3788d8";
                    e.textColor = '#fff';
                  }
                }

              });
              commit('setExternalsList', externals);
              commit('setEventsList', res.data['events']);
              resolve('success')
            })
            .catch(error => reject(error))
      })
    },

    createEvent({ commit, rootState }, formData) {
      return new Promise((resolve, reject) => {
        invoiceApi
            .post('/addevent', formData)
            .then(response => {
              if (response.status === 200) {
                commit('addEvent', response.data.event)
                resolve("created");
              }
            })
            .catch(e => {reject(e)})
      })
    },

    updateEvent({ commit, rootState }, formData) {
      return new Promise((resolve, reject) => {
        invoiceApi
          .post(`/editevent`, formData)
          .then(response => {
            if (response.status === 200) {
              commit('updateEvent', response.data.event);
              resolve('updated')
            }
          })
          .catch(e => reject(e))
      })
    },

    removeEvent({ commit, rootState }, id) {
      new Promise((resolve, reject) => {
        invoiceApi
          .delete(`/events/${id}`)
          .then(response => {
            if (response.status >= 200 && response.status <= 204) {
              commit('removeEvent', id);
              resolve('removed')
            } else reject('error')
          })
          .catch(e => reject(e))
      })
    },

    importExternalEvent({ commit, rootState }, formData) {
      return new Promise((resolve, reject) => {
        invoiceApi
            .post('/externalEvent', formData)
            .then(response => {
              if (response.status === 200) {
                commit('importExternalEvent', {events: formData.events, external: response.data['external']})
                resolve("created");
              }
            })
            .catch(e => {reject(e)})
      })
    },

    updateExternal({ commit, rootState }, formData) {
      return new Promise((resolve, reject) => {
        invoiceApi
            .post(`/editexternal`, formData)
            .then(response => {
              if (response.status === 200) {
                commit('updateExternal', response.data.external);
                resolve('updated')
              }
            })
            .catch(e => reject(e))
      })
    },
  },

  mutations: {
    setEventsList: (state, eventsList) => {
      state.allevents = _.cloneDeep(eventsList);
      state.events = _.cloneDeep(eventsList);
      state.externals.forEach(ex => {
        if (ex.show !== 1) {
          state.events = state.events.filter(event => {
            if (event.type === 'VEVENT') {
                return event.external_id !== ex.id;
            } else {
              return true;
            }
          });
        }
      })
      },
    setExternalsList: (state, externalssList) => (state.externals = externalssList),
    addEvent: (state, eventData) => {
        eventData.backgroundColor = 'transparent';
        eventData.borderColor = 'transparent';
        eventData.textColor = '#000';
        if (eventData.multi_day === 1) {
          eventData.backgroundColor = "#3788d8";
          eventData.textColor = '#fff';
        }
      state.allevents.push(eventData);
      state.events.push(eventData);
      },
    updateEvent: (state, data) => {
      const index = state.events.findIndex(c => c.id === data.id);
      if (data.type !== 'VEVENT') {
        data.backgroundColor = 'transparent';
        data.borderColor = 'transparent';
        data.textColor = '#000';
        if (data.multi_day === 1) {
          data.backgroundColor = "#3788d8";
          data.textColor = '#fff';
        }
      }
      Object.assign(state.events[index], data);
      Object.assign(state.allevents[index], data);
    },
    importExternalEvent: (state, data) => {
      state.externals.push(data.external);
      data.events.forEach(eventData => {
        eventData.backgroundColor = data.external.color;
        eventData.borderColor = data.external.color;
        eventData.textColor = '#000';
        eventData.external_id = data.external.id;
        state.events.push(eventData);
        state.allevents.push(eventData);
      })
    },
    updateExternal: (state, data) => {
      const index = state.externals.findIndex(c => c.id === data.id);
      Object.assign(state.externals[index], data);
      state.events = _.cloneDeep(state.allevents);
      state.externals.forEach(ex => {
        if (ex.show !== 1) {
          state.events = state.events.filter(event => {
            if (event.type === 'VEVENT') {
              return event.external_id !== ex.id;
            } else {
              return true;
            }
          });
        }
      })
    },
    removeEvent: (state, id) =>{
      state.events = state.events.filter(event => event.id !== id);
      state.events = state.allevents.filter(event => event.id !== id)
    }

  }
}
