import CategoryStore from "../CategoryStore";
import throttle from "lodash/throttle";

const wait = 1000; //seconds

export default {
    props: {
        filter: {type: Object, required: true},
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
