import DS from 'ember-data';

const { Model, attr, belongsTo } = DS;

export default Model.extend({
  name: attr('string'),
  description: attr('string'),
  price: attr('number'),
  category: belongsTo('Category', {async: true})
});
