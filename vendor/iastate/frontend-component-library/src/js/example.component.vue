<template>
    <div class="example-component">
        <p>This is an example Vue App</p>
    </div>
</template>

<script lang="ts">
export default {
    data: function() {
        return {
            foo: "bar",
        };
    },
};
</script>

<style lang="scss">
.example-component {
    padding: 1rem;
    background: #f1f1f1;
}
</style>
