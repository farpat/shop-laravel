import CategoryStore from "../CategoryStore";
import {throttle} from "lodash";

const wait = 1000; //seconds

export default {
    props: {
        filter: {type: Object, required: true},
        size: {type: Number, default: 3}
    },
    methods: {
        setFilterValue: throttle(function (filterId, value) {
            CategoryStore.setFilterValue(filterId, value);
        }, wait),
        getFilterValue: function (filterId) {
            return CategoryStore.getFilterValue(filterId);
        }
    }
}
