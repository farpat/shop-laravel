import CategoryStore from "../CategoryStore";
import debounce from "lodash/debounce";

const wait = 500; //seconds

export default {
    props: {
        filter: {type: Object, required: true},
    },
    methods: {
        setFilterValue: debounce(function (filterKey, value) {
            CategoryStore.setFilterValue(filterKey, value);
        }, wait),
        getFilterValue: function (filterKey) {
            return CategoryStore.getFilterValue(filterKey);
        }
    }
}
