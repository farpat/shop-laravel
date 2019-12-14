import CategoryStore from "../CategoryStore";
import throttle from "lodash/throttle";

const wait = 1000; //seconds

export default {
    props: {
        filter: {type: Object, required: true},
    },
    methods: {
        setFilterValue: throttle(function (filterKey, value) {
            CategoryStore.setFilterValue(filterKey, value);
        }, wait),
        getFilterValue: function (filterKey) {
            return CategoryStore.getFilterValue(filterKey);
        }
    }
}
