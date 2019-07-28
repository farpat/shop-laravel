import categoryStore from "../categoryStore";

export default {
    props: {
        filter: {type: Object, required: true},
        size: {type: Number, default: 3}
    },
    methods: {
        setFilterValue: function (filterId, value) {
            categoryStore.setFilterValue(filterId, value);
        },
        getFilterValue: function (filterId) {
            return categoryStore.getFilterValue(filterId);
        }
    }
}
