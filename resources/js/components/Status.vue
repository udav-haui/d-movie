<template>
    <select class="bs-select-hidden form-control">
        <slot></slot>
    </select>
</template>

<script>
export default {
    name: "Status",
    props: ["options", "value"],
    mounted: function () {
        console.log('mount select');
        var vm = this;
        $(this.$el)
            .val(this.value)
            // init select2
            .select2({
                data: vm.options, width: '100%', containerCssClass: ' dmovie-border', minimumResultsForSearch: -1
            })
            .val(this.value)
            .trigger("change")
            // emit event on change.
            .on("change", function () {
                vm.$emit("input", this.value);
            });
    },
    watch: {
        value: function (value) {
            // update value
            console.log('change data after aixos ' + value);
            console.log($(this.$el).val());
            $(this.$el).val(value).trigger("change");
            console.log($(this.$el).trigger('change'));
        },
        options: function (options) {
            // update options
            $(this.$el).empty().select2({ data: options });
        }
    },
    destroyed: function () {
        $(this.$el).off().select2("destroy");
    }
}
</script>

<style scoped>

</style>
