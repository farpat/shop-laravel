<template>
    <li :class="{'d-none':getCurrentAddress.is_deleted}" class="list-group-item">
        <div class="row align-items-center">
            <div class="col">
                <input :name="getName('id')" type="hidden" v-model="getCurrentAddress.id">
                <input :name="getName('index')" type="hidden" v-model="getCurrentAddress.index">
                <input :name="getName('line1')" type="hidden" v-model="getCurrentAddress.line1">
                <input :name="getName('postal_code')" type="hidden" v-model="getCurrentAddress.postal_code">
                <input :name="getName('city')" type="hidden" v-model="getCurrentAddress.city">
                <input :name="getName('country')" type="hidden" v-model="getCurrentAddress.country">
                <input :name="getName('longitude')" type="hidden" v-model="getCurrentAddress.longitude">
                <input :name="getName('latitude')" type="hidden" v-model="getCurrentAddress.latitude">
                <input :name="getName('is_deleted')" type="hidden" v-model="getCurrentAddress.is_deleted ? 1 : 0">

                <InputComponent :data-attributes="{'algolia-input':true}" :name="getName('text')"
                                :placeholder="__('Street address, P.O., box, company name')" rules="required|min:10"
                                type="search"></InputComponent>

                <InputComponent :name="getName('line2')"
                                :placeholder="__('Apartment, suite, unit, building, floor, etc.')"></InputComponent>
            </div>
            <div class="col-auto">
                <button @click="deleteAddress" class="btn btn-link" style="font-size: 1.5rem;" type="button">&times;
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
            appId:  {type: String, required: true},
            apiKey: {type: String, required: true},
            lang:   {type: String, required: true},
            index:  {type: Number, required: true},
        },
        computed:   {
            getCurrentAddress: function () {
                return Store.getData(`addresses[${this.index}]`);
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

            placesAutocomplete.setVal(this.getCurrentAddress.text);

            placesAutocomplete.on('change', event => {
                Store.set(Store.getData('addresses'), this.index, {
                    city:        event.suggestion.city,
                    country:     event.suggestion.country,
                    index:       this.index,
                    line1:       event.suggestion.name,
                    latitude:    event.suggestion.latlng.lat,
                    longitude:   event.suggestion.latlng.lng,
                    postal_code: event.suggestion.postcode,
                    text:        event.suggestion.value,
                    is_deleted:  false
                });

                Store.checkData(`addresses[${this.index}][text]`, event.suggestion.value, this.$rules);
            });

            placesAutocomplete.on('clear', () => {
                Store.set(Store.getData('addresses'), this.index, {
                    city:        null,
                    country:     null,
                    index:       this.index,
                    line1:       null,
                    latitude:    null,
                    longitude:   null,
                    postal_code: null,
                    text:        null,
                    is_deleted:  false
                });

                Store.checkData(`addresses[${this.index}][text]`, null, this.$rules);
            })
        },
        methods:    {
            getName:       function (key) {
                return `addresses[${this.index}][${key}]`;
            },
            deleteAddress: function () {
                this.$input.removeAttribute('required');

                Store.set(Store.getData('addresses'), this.index, {
                    ...this.getCurrentAddress,
                    is_deleted: true
                });

                if (Store.getError(`addresses[${this.index}]`)) {
                    Store.set(Store.getError('addresses'), this.index, {text: undefined});
                }
            },
        }
    }
</script>