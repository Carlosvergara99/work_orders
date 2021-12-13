<template>
    <v-toolbar color="purple" dark>
        <v-toolbar-title>Title</v-toolbar-title>

        <v-divider class="mx-4" vertical></v-divider>

        <v-spacer></v-spacer>

        <v-toolbar-items class="hidden-sm-and-down">
            <v-btn text to="/"> Ordenes </v-btn>

            <v-divider vertical></v-divider>

            <v-btn text to="/operator"> operadores </v-btn>

            <v-divider vertical></v-divider>
        </v-toolbar-items>
    </v-toolbar>
</template>

<script>
export default {
    mounted() {},
};
</script>
