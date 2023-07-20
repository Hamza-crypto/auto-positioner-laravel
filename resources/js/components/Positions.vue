<template>
    <div>
        <h1>I am from Vue component {{  this.name  }} </h1>
    </div>
</template>

<script>

export default {

    props: [''],
    data: function () {
        return {
            name: 'Helo hamza'
        }
    },

    mounted() {
        console.log('Message BG  Component mounted.');
        this.myMethod()
    },

    methods: {

        myMethod: function () {
            console.log('Getting Messages BG...');
        }

    }
}
</script>
