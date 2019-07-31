import CategoryStore from "../CategoryStore";

export default {
    props: {
        filter: {type: Object, required: true},
        size: {type: Number, default: 3}
    },
    methods: {
        setFilterValue: function (filterId, value) {
            CategoryStore.setFilterValue(filterId, value);
        },
        getFilterValue: function (filterId) {
            return CategoryStore.getFilterValue(filterId);
        }
    }
}
