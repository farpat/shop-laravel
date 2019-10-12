import FormStore from "./Store";

export default {
    data:    function () {
        return {
            sharedState: FormStore.state
        }
    },
    mounted: function () {
        this.$store = FormStore;
    }
};
