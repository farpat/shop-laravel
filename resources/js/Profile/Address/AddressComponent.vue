<template>
    <li :class="getLiClass">
        <div class="row align-items-center">
            <div class="col">
                <input :name="getName('id')" type="hidden" v-model="address.id">
                <input :name="getName('index')" type="hidden" v-model="address.index">
                <input :name="getName('line1')" type="hidden" v-model="address.line1">
                <input :name="getName('postal_code')" type="hidden" v-model="address.postal_code">
                <input :name="getName('city')" type="hidden" v-model="address.city">
                <input :name="getName('country')" type="hidden" v-model="address.country">
                <input :name="getName('longitude')" type="hidden" v-model="address.longitude">
                <input :name="getName('latitude')" type="hidden" v-model="address.latitude">
                <input :name="getName('is_deleted')" type="hidden" v-model="address.is_deleted ? 1 : 0">

                <InputComponent :data-attributes="{'algolia-input':true}" :name="getName('text')"
                                :placeholder="__('Street address, P.O., box, company name')" rules="required|min:10"
                                type="search"/>

                <InputComponent :name="getName('line2')"
                                :placeholder="__('Apartment, suite, unit, building, floor, etc.')"></InputComponent>
            </div>
            <div class="col-auto">
                <button @click="deleteAddress" class="btn btn-link" style="font-size: 1.5rem;" type="button">
                    &times;
                </button>
            </div>
        </div>
    </li>
</template>

<script>
    import places from "places.js";
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import {InputComponent} from "../../src/Bootstrap";
    import Store from "../../src/Bootstrap/Store";

    export default {
        components: {InputComponent},
        mixins:     [TranslationMixin],
        data:       function () {
            return {
                sharedState: Store.state
            }
        },
        props:      {
            appId:   {type: String, required: true},
            apiKey:  {type: String, required: true},
            lang:    {type: String, required: true},
            address: {type: Object, required: true},
        },
        computed:   {
            getLiClass: function () {
                return this.address.is_deleted ? 'd-none' : 'list-group-item';
            }
        },
        mounted:    function () {
            this.$input = this.$children[0].$el.querySelector('input');
            this.$rules = this.$children[0].$rules;

            let placesAutocomplete = places({
                appId:     this.appId,
                apiKey:    this.apiKey,
                container: this.$el.querySelector('[data-algolia-input]')
            })
                .configure({language: this.lang, type: 'address'});

            placesAutocomplete.setVal(this.address.text);

            placesAutocomplete.on('change', event => {
                Store.setData(`addresses[${this.address.index}]`, {
                    id:          this.address.id,
                    city:        event.suggestion.city,
                    country:     event.suggestion.country,
                    index:       this.address.index,
                    line1:       event.suggestion.name,
                    latitude:    event.suggestion.latlng.lat,
                    longitude:   event.suggestion.latlng.lng,
                    postal_code: event.suggestion.postcode,
                    text:        event.suggestion.value,
                    is_deleted:  false
                });

                Store.checkData(`addresses[${this.address.index}][text]`, event.suggestion.value, this.$rules);
            });

            placesAutocomplete.on('clear', () => {
                Store.setData(`addresses[${this.address.index}]`, {
                    id:          this.address.id,
                    city:        null,
                    country:     null,
                    index:       this.address.index,
                    line1:       null,
                    latitude:    null,
                    longitude:   null,
                    postal_code: null,
                    text:        null,
                    is_deleted:  false
                });

                Store.checkData(`addresses[${this.address.index}][text]`, null, this.$rules);
            })
        },
        methods:    {
            getName:       function (key) {
                return `addresses[${this.address.index}][${key}]`;
            },
            deleteAddress: function () {
                this.$input.removeAttribute('required');

                Store.setData(`addresses[${this.address.index}]`, {
                    ...this.address,
                    is_deleted: true
                });

                if (Store.getError(`addresses[${this.address.index}]`)) {
                    Store.setError(`addresses[${this.address.index}]`, undefined);
                }
            },
        }
    }
</script>