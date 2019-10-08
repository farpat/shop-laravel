<template>
    <li :class="{'d-none':address.isDeleted}" class="list-group-item">
        <div class="row align-items-center">
            <div class="col">
                <!--                <input :name="getName('id')" type="hidden" v-model="address.id">-->
                <!--                <input :name="getName('line1')" type="hidden" v-model="address.line1">-->
                <!--                <input :name="getName('postal_code')" type="hidden" v-model="address.postalCode">-->
                <!--                <input :name="getName('city')" type="hidden" v-model="address.city">-->
                <!--                <input :name="getName('country')" type="hidden" v-model="address.country">-->
                <!--                <input :name="getName('longitude')" type="hidden" v-model="address.longitude">-->
                <!--                <input :name="getName('latitude')" type="hidden" v-model="address.latitude">-->
                <!--                <input :name="getName('is_deleted')" type="hidden" v-model="address.isDeleted">-->

                <InputComponent :data-attributes="{'algolia-input':true}" :name="getName('text')"
                                :placeholder="__('Type complete address')" @change="changeAddress($event)"
                                type="search"></InputComponent>

                <InputComponent :name="getName('line2')"></InputComponent>
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

    export default {
        components: {InputComponent},
        mixins:     [TranslationMixin],
        data:       function () {
            return {
                address: this.initialAddress,
            }
        },
        props:      {
            appId:          {type: String, required: true},
            apiKey:         {type: String, required: true},
            lang:           {type: String, required: true},
            initialAddress: {type: Object, required: true},
        },
        mounted:    function () {
            let placesAutocomplete = places({
                appId:     this.appId,
                apiKey:    this.apiKey,
                container: this.$el.querySelector('[data-algolia-input]')
            })
                .configure({language: this.lang});

            placesAutocomplete.setVal(this.initialAddress.text);

            placesAutocomplete.on('change', e => {
                this.address = {
                    ...this.address,
                    line1:      e.suggestion.name,
                    postalCode: e.suggestion.postcode,
                    city:       e.suggestion.city,
                    country:    e.suggestion.country,
                    longitude:  e.suggestion.latlng.lng,
                    latitude:   e.suggestion.latlng.lat,
                };
            });
        },
        methods:    {
            changeAddress(event) {
            },
            getName:       function (key) {
                return `addresses[${this.address.index}][${key}]`;
            },
            deleteAddress: function () {
                this.$set(this.address, 'isDeleted', 1);
            }
        }
    }
</script>