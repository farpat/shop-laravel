<template>
    <div>
        <input name="address_latitude" type="hidden">
        <input name="address_longitude" type="hidden">

        <ul class="list-group">
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col">
                        <input :placeholder="__('Type complete address')" id="address-input" type="search"/>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-secondary" type="button">&times;</button>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    import {InputComponent} from "../src/Bootstrap";
    import TranslationMixin from "../src/Translation/TranslationMixin";
    import places from 'places.js';

    export default {
        components: {InputComponent},
        mixins:     [TranslationMixin],
        mounted:    function () {
            const lang = document.querySelector('html').getAttribute('lang');

            let placesAutocomplete = places({
                appId:     'pl9V2DE5GILO',
                apiKey:    '01daf404b179e00b0d49f944fba53341',
                container: document.querySelector('#address-input')
            })
                .configure({language: lang});

            placesAutocomplete.on('change', e => console.log(e.suggestion));
        }
    }
</script>