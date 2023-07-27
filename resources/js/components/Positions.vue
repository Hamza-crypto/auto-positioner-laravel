<template>
    <div class="row">
        <div class="col-3">
            <div class="card mb-3">
                <div class="card-body" id="positions">
                    <a href="#" class="list-group-item d-flex align-items-center disabled fixed-item">
                        <div>
                            <p class="mb-1"> Fryer </p>
                        </div>
                    </a>




                </div>
            </div>

            <div class="card">
                <div class="card-body" id="positions2">
                    <a href="#" class="list-group-item d-flex align-items-center disabled">
                        <div>
                            <p class="mb-1"> Fryer </p>
                        </div>
                    </a>


                </div>
            </div>
        </div>
        <!-- <div class="col-6">
        <table>
            <thead>
                <tr>
                    <th></th>
                    @foreach ($positions as $position)
                    <th>{{ $position->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="table-body">
            </tbody>
        </table>
    </div> -->

        <!-- <div class="col-3">

        <div class="chat-block">

            <div class="chat-sidebar">


                <div tabindex="1" class="chat-sidebar-content" style="overflow: hidden; outline: none;">

                    <div id="pills-tabContent" class="tab-content">
                        <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                            class="tab-pane fade active show">
                            <div class="list-group list-group-flush" id="present-employees"
                                style="border: solid #eb2f516b; min-height:100px;">

                                <a href="#" class="list-group-item d-flex align-items-center disabled"
                                    id="drop-employees">
                                    <div>
                                        <p class="mb-1"> Present Employees </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

        <div class="col-6">

            <div class="chat-block">

                <div class="chat-sidebar" style="width: 100%;">

                    <div tabindex="1" class="chat-sidebar-content" style="overflow: hidden; outline: none;">

                        <div id="pills-tabContent" class="tab-content">
                            <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                                class="tab-pane fade active show">
                                <div class="list-group list-group-flush" id="all-employees"
                                    style="border: solid #eb2f516b; min-height:100px;">

                                    <a href="#" class="list-group-item d-flex align-items-center disabled"
                                        id="drop-employees">
                                        <div>
                                            <p class="mb-1"> All Employees </p>
                                        </div>
                                    </a>


                                    <a href="#" class="list-group-item d-flex align-items-center" v-for="employee in employees" :key="employee.id">
                                        <div class="pe-3">
                                            <div class="avatar avatar-info avatar-state-secondary">
                                                <span class="avatar-text rounded-circle"> {{ employee.id }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>

                                            <p class="mb-1">{{ employee.name }}</p>
                                            <div class="text-muted d-flex align-items-center">
                                                <input type="text" class="form-control clockpicker-example" name="time_in"
                                                    placeholder="Start Time">
                                                <input type="text" class="form-control clockpicker-example" name="time_out"
                                                    placeholder="End Time">
                                                <!-- hamza -->
                                            </div>
                                        </div>
                                        <div class="text-end ms-auto" v-for="position in employee.positions">{{position}} 
                                        </div>
                                    </a>

                                


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {

    props: [''],
    data: function () {
        return {
            employees: []
        }
    },

    mounted() {
        this.get_employees();

            $('.clockpicker-example').clockpicker({
            donetext: 'Done',
            afterDone: function () {
                console.log("after done");
            }

        });
    },

    methods: {

        get_employees: function () {
            const URL = '/api/v1/employees';

            axios.get(URL)
                .then((response) => {

                    this.employees = response.data;
                    console.log(this.employees);


                })
                .catch(error => {
                    console.log(error);

                });

            console.log('Getting Messages BG...');
        }

    }
}
</script>
