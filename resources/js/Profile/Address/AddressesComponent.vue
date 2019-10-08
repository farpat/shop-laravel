<template>
    <section>
        <ul class="list-group">
            <AddressComponent :api-key="apiKey" :app-id="appId" :initial-address="address"
                              :key="address.index"
                              :lang="lang"
                              v-for="address in addresses"></AddressComponent>
        </ul>

        <button @click="addAddress" class="btn btn-link text-success" type="button">{{ __('Add address')}}</button>
    </section>
</template>

<script>
    import AddressComponent from "./AddressComponent";
    import {InputComponent} from "../../src/Bootstrap";
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import FormMixin from "../../src/Bootstrap/FormMixin";

    export default {
        components: {InputComponent, AddressComponent},
        mixins:     [TranslationMixin, FormMixin],
        data:       function () {
            return {
                addresses: this.initialAddresses
            };
        },
        props:      {
            initialAddresses: {
                type: Array, default: function () {
                    return [];
                }
            },
            appId:            {type: String, required: true},
            apiKey:           {type: String, required: true},
            lang:             {type: String, default: 'en'}
        },
        methods:    {
            addAddress: function () {
                const index = this.addresses.length === 0 ?
                    0 :
                    this.addresses[this.addresses.length - 1].index + 1;

                this.addresses.push({
                    index,
                    id:        null,
                    isDeleted: 0,
                });
            }
        }
    }
</script>