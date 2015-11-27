import Ember from 'ember';
import config from './config/environment';

const Router = Ember.Router.extend({
  location: config.locationType
});

Router.map(function() {
  this.resource('categories', function() {
    this.resource('category', { path: ':id' });
  });
  this.resource('products', function() {
    this.resource('product', { path: ':id' });
  });
});

export default Router;
