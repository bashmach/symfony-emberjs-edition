import Ember from 'ember';

export default Ember.Component.extend({

  product: null,

  isEditing: false,

  actions: {
    edit: function () {
      this.set('isEditing', true);
    },
    doneEditing: function () {
      this.set('isEditing', false);

      this.get('product').save({
        name: 'ololoo'
      });
    }
  }

});
