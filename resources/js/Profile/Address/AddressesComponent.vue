<template>
    <section>
        <ul class="list-group">
            <AddressComponent :api-key="apiKey" :app-id="appId" :index="address.index"
                              :key="address.index"
                              :lang="lang"
                              v-for="address in getAddresses"></AddressComponent>
        </ul>

        <button @click="addAddress" class="btn btn-link text-success" type="button">{{ __('Add address')}}</button>
    </section>
</template>

<script>
    import AddressComponent from "./AddressComponent";
    import {InputComponent} from "../../src/Bootstrap";
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import FormMixin from "../../src/Bootstrap/FormMixin";
    import Store from "../../src/Bootstrap/Store";

    export default {
        components: {InputComponent, AddressComponent},
        mixins:     [TranslationMixin, FormMixin],
        props:      {
            appId:  {type: String, required: true},
            apiKey: {type: String, required: true},
            lang:   {type: String, default: 'en'}
        },
        computed:   {
            getAddresses:   function () {
                return Store.getData('addresses');
            },
            getLastAddress: function () {
                const keys = Object.keys(this.getAddresses);
                if (keys.length === 0) {
                    return null;
                }

                return this.getAddresses[keys[keys.length - 1]];
            }
        },
        methods:    {
            addAddress: function () {
                const lastAddress = this.getLastAddress;
                const index = lastAddress ? lastAddress.index + 1 : 0;

                Store.set(Store.getData('addresses'), index, {
                    city:        null,
                    country:     null,
                    id:          null,
                    index,
                    latitude:    null,
                    longitude:   null,
                    postal_code: null,
                    text:        null,
                });
            }
        }
    }
</script>